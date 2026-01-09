<?php
/**
 * Plugin Name: Smart Contact Form for Divi
 * Plugin URI: https://indulta.org
 * Description: A lightweight Divi Builder module for contact forms with conditional email routing based on department selection.
 * Version: 1.0.0
 * Author: Indulta
 * Author URI: https://indulta.org
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: smart-contact-form
 * Requires at least: 5.0
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'SCF_VERSION', '1.0.0' );
define( 'SCF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SCF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Main Plugin Class
 */
final class Smart_Contact_Form {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Load module when Divi Builder is ready
        add_action( 'et_builder_ready', array( $this, 'load_module' ) );

        // AJAX handlers
        add_action( 'wp_ajax_scf_submit_form', array( $this, 'handle_form_submission' ) );
        add_action( 'wp_ajax_nopriv_scf_submit_form', array( $this, 'handle_form_submission' ) );

        // Enqueue assets
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );

        // Enqueue Visual Builder assets
        add_action( 'et_fb_enqueue_assets', array( $this, 'enqueue_builder_assets' ) );
    }

    /**
     * Load the Divi Builder module
     */
    public function load_module() {
        if ( class_exists( 'ET_Builder_Module' ) ) {
            require_once SCF_PLUGIN_DIR . 'includes/modules/SmartContactForm/SmartContactForm.php';
        }
    }

    /**
     * Enqueue frontend assets
     */
    public function enqueue_assets() {
        // Only load on frontend or when Divi Builder is active
        if ( is_admin() && ! ( function_exists( 'et_core_is_fb_enabled' ) && et_core_is_fb_enabled() ) ) {
            return;
        }

        wp_enqueue_style(
            'smart-contact-form',
            SCF_PLUGIN_URL . 'assets/css/smart-contact-form.css',
            array(),
            SCF_VERSION
        );

        wp_enqueue_script(
            'smart-contact-form',
            SCF_PLUGIN_URL . 'assets/js/smart-contact-form.js',
            array( 'jquery' ),
            SCF_VERSION,
            true
        );

        wp_localize_script( 'smart-contact-form', 'scf_ajax', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'scf_form_nonce' ),
        ) );
    }

    /**
     * Enqueue Visual Builder assets
     */
    public function enqueue_builder_assets() {
        $bundle_path = SCF_PLUGIN_DIR . 'assets/js/builder-frontend.bundle.js';

        if ( file_exists( $bundle_path ) ) {
            wp_enqueue_script(
                'smart-contact-form-builder',
                SCF_PLUGIN_URL . 'assets/js/builder-frontend.bundle.js',
                array( 'react', 'react-dom', 'jquery' ),
                SCF_VERSION,
                true
            );
        }

        wp_enqueue_style(
            'smart-contact-form',
            SCF_PLUGIN_URL . 'assets/css/smart-contact-form.css',
            array(),
            SCF_VERSION
        );
    }

    /**
     * Handle AJAX form submission
     */
    public function handle_form_submission() {
        // Verify nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'scf_form_nonce' ) ) {
            wp_send_json_error( array( 'message' => __( 'Security verification failed. Please refresh and try again.', 'smart-contact-form' ) ) );
        }

        // Honeypot check - if filled, it's a bot
        if ( ! empty( $_POST['scf_website'] ) ) {
            // Send fake success to confuse bots
            wp_send_json_success( array( 'message' => __( 'Thank you! Your message has been sent.', 'smart-contact-form' ) ) );
        }

        // Sanitize inputs
        $name       = isset( $_POST['scf_name'] ) ? sanitize_text_field( wp_unslash( $_POST['scf_name'] ) ) : '';
        $email      = isset( $_POST['scf_email'] ) ? sanitize_email( wp_unslash( $_POST['scf_email'] ) ) : '';
        $subject    = isset( $_POST['scf_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['scf_subject'] ) ) : '';
        $message    = isset( $_POST['scf_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['scf_message'] ) ) : '';
        $department = isset( $_POST['scf_department'] ) ? sanitize_text_field( wp_unslash( $_POST['scf_department'] ) ) : '';
        $routing    = isset( $_POST['scf_routing'] ) ? sanitize_textarea_field( wp_unslash( $_POST['scf_routing'] ) ) : '';

        // Validate required fields
        if ( empty( $name ) ) {
            wp_send_json_error( array( 'message' => __( 'Please enter your name.', 'smart-contact-form' ) ) );
        }

        if ( empty( $email ) || ! is_email( $email ) ) {
            wp_send_json_error( array( 'message' => __( 'Please enter a valid email address.', 'smart-contact-form' ) ) );
        }

        if ( empty( $subject ) ) {
            wp_send_json_error( array( 'message' => __( 'Please enter a subject.', 'smart-contact-form' ) ) );
        }

        if ( empty( $message ) ) {
            wp_send_json_error( array( 'message' => __( 'Please enter your message.', 'smart-contact-form' ) ) );
        }

        // Determine recipient based on department routing
        $recipient = get_option( 'admin_email' ); // Default fallback

        if ( ! empty( $routing ) && ! empty( $department ) ) {
            $routes = array_filter( array_map( 'trim', explode( "\n", $routing ) ) );
            foreach ( $routes as $route ) {
                $parts = array_map( 'trim', explode( '|', $route ) );
                if ( count( $parts ) === 2 && strtolower( $parts[0] ) === strtolower( $department ) ) {
                    if ( is_email( $parts[1] ) ) {
                        $recipient = sanitize_email( $parts[1] );
                    }
                    break;
                }
            }
        }

        // Build email content
        $site_name     = get_bloginfo( 'name' );
        $email_subject = sprintf( '[%s] %s', $site_name, $subject );

        $email_body = sprintf(
            "You have received a new contact form submission.\n\n" .
            "----------------------------------------\n\n" .
            "Name: %s\n" .
            "Email: %s\n" .
            "Department: %s\n" .
            "Subject: %s\n\n" .
            "Message:\n%s\n\n" .
            "----------------------------------------\n\n" .
            "This message was sent from: %s",
            $name,
            $email,
            $department ? $department : 'Not specified',
            $subject,
            $message,
            home_url()
        );

        $headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            sprintf( 'From: %s <%s>', $site_name, get_option( 'admin_email' ) ),
            sprintf( 'Reply-To: %s <%s>', $name, $email ),
        );

        // Send email
        $sent = wp_mail( $recipient, $email_subject, $email_body, $headers );

        if ( $sent ) {
            /**
             * Fires after a successful form submission
             *
             * @param array $submission Form submission data
             */
            do_action( 'scf_form_submitted', array(
                'name'       => $name,
                'email'      => $email,
                'subject'    => $subject,
                'message'    => $message,
                'department' => $department,
                'recipient'  => $recipient,
            ) );

            wp_send_json_success( array( 'message' => __( 'Thank you! Your message has been sent successfully.', 'smart-contact-form' ) ) );
        } else {
            wp_send_json_error( array( 'message' => __( 'Failed to send message. Please try again later.', 'smart-contact-form' ) ) );
        }
    }
}

// Initialize plugin
Smart_Contact_Form::get_instance();
