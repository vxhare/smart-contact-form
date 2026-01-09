<?php
/**
 * Smart Contact Form Divi Module
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class SCF_Smart_Contact_Form extends ET_Builder_Module {

    public $slug       = 'scf_smart_contact_form';
    public $vb_support = 'on';

    protected $module_credits = array(
        'module_uri' => 'https://indulta.org',
        'author'     => 'Indulta',
        'author_uri' => 'https://indulta.org',
    );

    public function init() {
        $this->name = esc_html__( 'Smart Contact Form', 'smart-contact-form' );
        $this->icon = 'E';

        $this->settings_modal_toggles = array(
            'general'  => array(
                'toggles' => array(
                    'form_fields'   => esc_html__( 'Form Fields', 'smart-contact-form' ),
                    'departments'   => esc_html__( 'Departments & Routing', 'smart-contact-form' ),
                    'messages'      => esc_html__( 'Messages', 'smart-contact-form' ),
                ),
            ),
            'advanced' => array(
                'toggles' => array(
                    'form_styles'   => esc_html__( 'Form Styles', 'smart-contact-form' ),
                    'button_styles' => esc_html__( 'Button Styles', 'smart-contact-form' ),
                ),
            ),
        );

        $this->main_css_element = '%%order_class%%.scf-smart-contact-form';

        $this->advanced_fields = array(
            'fonts'          => array(
                'labels' => array(
                    'label'       => esc_html__( 'Labels', 'smart-contact-form' ),
                    'css'         => array(
                        'main' => "{$this->main_css_element} .scf-label",
                    ),
                    'toggle_slug' => 'form_styles',
                ),
                'fields' => array(
                    'label'       => esc_html__( 'Fields', 'smart-contact-form' ),
                    'css'         => array(
                        'main' => "{$this->main_css_element} .scf-input, {$this->main_css_element} .scf-textarea, {$this->main_css_element} .scf-select",
                    ),
                    'toggle_slug' => 'form_styles',
                ),
            ),
            'button'         => array(
                'submit' => array(
                    'label'       => esc_html__( 'Submit Button', 'smart-contact-form' ),
                    'css'         => array(
                        'main'      => "{$this->main_css_element} .scf-submit-btn",
                        'alignment' => "{$this->main_css_element} .scf-submit-wrap",
                    ),
                    'use_alignment' => true,
                    'box_shadow'    => array(
                        'css' => array(
                            'main' => "{$this->main_css_element} .scf-submit-btn",
                        ),
                    ),
                    'toggle_slug' => 'button_styles',
                ),
            ),
            'borders'        => array(
                'default' => array(
                    'css' => array(
                        'main' => array(
                            'border_radii'  => "{$this->main_css_element}",
                            'border_styles' => "{$this->main_css_element}",
                        ),
                    ),
                ),
                'fields' => array(
                    'label_prefix' => esc_html__( 'Fields', 'smart-contact-form' ),
                    'css'          => array(
                        'main' => array(
                            'border_radii'  => "{$this->main_css_element} .scf-input, {$this->main_css_element} .scf-textarea, {$this->main_css_element} .scf-select",
                            'border_styles' => "{$this->main_css_element} .scf-input, {$this->main_css_element} .scf-textarea, {$this->main_css_element} .scf-select",
                        ),
                    ),
                    'toggle_slug'  => 'form_styles',
                ),
            ),
            'box_shadow'     => array(
                'default' => array(),
            ),
            'margin_padding' => array(
                'css' => array(
                    'important' => 'all',
                ),
            ),
            'background'     => array(
                'css' => array(
                    'main' => "{$this->main_css_element}",
                ),
            ),
            'text'           => false,
            'link_options'   => false,
        );
    }

    public function get_fields() {
        return array(
            // Form Field Labels
            'name_label' => array(
                'label'       => esc_html__( 'Name Field Label', 'smart-contact-form' ),
                'type'        => 'text',
                'default'     => esc_html__( 'Name', 'smart-contact-form' ),
                'toggle_slug' => 'form_fields',
            ),
            'email_label' => array(
                'label'       => esc_html__( 'Email Field Label', 'smart-contact-form' ),
                'type'        => 'text',
                'default'     => esc_html__( 'Email', 'smart-contact-form' ),
                'toggle_slug' => 'form_fields',
            ),
            'subject_label' => array(
                'label'       => esc_html__( 'Subject Field Label', 'smart-contact-form' ),
                'type'        => 'text',
                'default'     => esc_html__( 'Subject', 'smart-contact-form' ),
                'toggle_slug' => 'form_fields',
            ),
            'message_label' => array(
                'label'       => esc_html__( 'Message Field Label', 'smart-contact-form' ),
                'type'        => 'text',
                'default'     => esc_html__( 'Message', 'smart-contact-form' ),
                'toggle_slug' => 'form_fields',
            ),
            'department_label' => array(
                'label'       => esc_html__( 'Department Field Label', 'smart-contact-form' ),
                'type'        => 'text',
                'default'     => esc_html__( 'Department', 'smart-contact-form' ),
                'toggle_slug' => 'form_fields',
            ),
            'submit_text' => array(
                'label'       => esc_html__( 'Submit Button Text', 'smart-contact-form' ),
                'type'        => 'text',
                'default'     => esc_html__( 'Send Message', 'smart-contact-form' ),
                'toggle_slug' => 'form_fields',
            ),
            'show_department' => array(
                'label'           => esc_html__( 'Show Department Dropdown', 'smart-contact-form' ),
                'type'            => 'yes_no_button',
                'options'         => array(
                    'on'  => esc_html__( 'Yes', 'smart-contact-form' ),
                    'off' => esc_html__( 'No', 'smart-contact-form' ),
                ),
                'default'         => 'on',
                'toggle_slug'     => 'departments',
                'description'     => esc_html__( 'Enable to show the department dropdown for email routing.', 'smart-contact-form' ),
            ),

            // Department Configuration
            'department_routing' => array(
                'label'           => esc_html__( 'Department Email Routing', 'smart-contact-form' ),
                'type'            => 'textarea',
                'toggle_slug'     => 'departments',
                'description'     => esc_html__( 'Enter one department per line in format: Department Name|email@example.com', 'smart-contact-form' ),
                'default'         => "Sales|sales@example.com\nSupport|support@example.com\nGeneral Inquiry|info@example.com",
                'show_if'         => array(
                    'show_department' => 'on',
                ),
            ),
            'fallback_email' => array(
                'label'       => esc_html__( 'Fallback Email', 'smart-contact-form' ),
                'type'        => 'text',
                'default'     => '',
                'toggle_slug' => 'departments',
                'description' => esc_html__( 'Email address to use if no department is selected or routing fails. Leave empty to use admin email.', 'smart-contact-form' ),
            ),

            // Messages
            'success_message' => array(
                'label'       => esc_html__( 'Success Message', 'smart-contact-form' ),
                'type'        => 'text',
                'default'     => esc_html__( 'Thank you! Your message has been sent successfully.', 'smart-contact-form' ),
                'toggle_slug' => 'messages',
            ),
            'error_message' => array(
                'label'       => esc_html__( 'Error Message', 'smart-contact-form' ),
                'type'        => 'text',
                'default'     => esc_html__( 'Something went wrong. Please try again.', 'smart-contact-form' ),
                'toggle_slug' => 'messages',
            ),

            // Advanced Styles
            'field_background' => array(
                'label'        => esc_html__( 'Field Background Color', 'smart-contact-form' ),
                'type'         => 'color-alpha',
                'custom_color' => true,
                'default'      => '#ffffff',
                'toggle_slug'  => 'form_styles',
                'tab_slug'     => 'advanced',
            ),
            'field_focus_background' => array(
                'label'        => esc_html__( 'Field Focus Background', 'smart-contact-form' ),
                'type'         => 'color-alpha',
                'custom_color' => true,
                'default'      => '#f7f7f7',
                'toggle_slug'  => 'form_styles',
                'tab_slug'     => 'advanced',
            ),
        );
    }

    public function render( $attrs, $content, $render_slug ) {
        $name_label        = $this->props['name_label'];
        $email_label       = $this->props['email_label'];
        $subject_label     = $this->props['subject_label'];
        $message_label     = $this->props['message_label'];
        $department_label  = $this->props['department_label'];
        $submit_text       = $this->props['submit_text'];
        $show_department   = $this->props['show_department'];
        $department_routing = $this->props['department_routing'];
        $success_message   = $this->props['success_message'];
        $error_message     = $this->props['error_message'];
        $field_background  = $this->props['field_background'];
        $field_focus_bg    = $this->props['field_focus_background'];

        // Generate unique form ID
        $form_id = 'scf-form-' . $this->render_count();

        // Build department options
        $department_options = '';
        if ( 'on' === $show_department && ! empty( $department_routing ) ) {
            $routes = array_filter( array_map( 'trim', explode( "\n", $department_routing ) ) );
            $department_options .= '<option value="">' . esc_html__( 'Select Department', 'smart-contact-form' ) . '</option>';
            foreach ( $routes as $route ) {
                $parts = array_map( 'trim', explode( '|', $route ) );
                if ( ! empty( $parts[0] ) ) {
                    $department_options .= sprintf(
                        '<option value="%s">%s</option>',
                        esc_attr( $parts[0] ),
                        esc_html( $parts[0] )
                    );
                }
            }
        }

        // Add custom CSS
        if ( ! empty( $field_background ) ) {
            ET_Builder_Element::set_style( $render_slug, array(
                'selector'    => '%%order_class%% .scf-input, %%order_class%% .scf-textarea, %%order_class%% .scf-select',
                'declaration' => sprintf( 'background-color: %s;', esc_attr( $field_background ) ),
            ) );
        }

        if ( ! empty( $field_focus_bg ) ) {
            ET_Builder_Element::set_style( $render_slug, array(
                'selector'    => '%%order_class%% .scf-input:focus, %%order_class%% .scf-textarea:focus, %%order_class%% .scf-select:focus',
                'declaration' => sprintf( 'background-color: %s;', esc_attr( $field_focus_bg ) ),
            ) );
        }

        // Build form HTML
        $output = sprintf(
            '<div class="scf-smart-contact-form" id="%s">
                <form class="scf-form" data-routing="%s" data-success="%s" data-error="%s">
                    <div class="scf-field scf-field-half">
                        <label class="scf-label" for="%s-name">%s <span class="scf-required">*</span></label>
                        <input type="text" id="%s-name" name="scf_name" class="scf-input" required>
                    </div>
                    <div class="scf-field scf-field-half">
                        <label class="scf-label" for="%s-email">%s <span class="scf-required">*</span></label>
                        <input type="email" id="%s-email" name="scf_email" class="scf-input" required>
                    </div>',
            esc_attr( $form_id ),
            esc_attr( base64_encode( $department_routing ) ),
            esc_attr( $success_message ),
            esc_attr( $error_message ),
            esc_attr( $form_id ),
            esc_html( $name_label ),
            esc_attr( $form_id ),
            esc_attr( $form_id ),
            esc_html( $email_label ),
            esc_attr( $form_id )
        );

        // Department dropdown
        if ( 'on' === $show_department && ! empty( $department_options ) ) {
            $output .= sprintf(
                '<div class="scf-field">
                    <label class="scf-label" for="%s-department">%s</label>
                    <select id="%s-department" name="scf_department" class="scf-select">
                        %s
                    </select>
                </div>',
                esc_attr( $form_id ),
                esc_html( $department_label ),
                esc_attr( $form_id ),
                $department_options
            );
        }

        // Subject and Message
        $output .= sprintf(
            '<div class="scf-field">
                <label class="scf-label" for="%s-subject">%s <span class="scf-required">*</span></label>
                <input type="text" id="%s-subject" name="scf_subject" class="scf-input" required>
            </div>
            <div class="scf-field">
                <label class="scf-label" for="%s-message">%s <span class="scf-required">*</span></label>
                <textarea id="%s-message" name="scf_message" class="scf-textarea" rows="6" required></textarea>
            </div>',
            esc_attr( $form_id ),
            esc_html( $subject_label ),
            esc_attr( $form_id ),
            esc_attr( $form_id ),
            esc_html( $message_label ),
            esc_attr( $form_id )
        );

        // Honeypot field (hidden from users, visible to bots)
        $output .= '<div class="scf-hp" aria-hidden="true">
            <label for="scf-website">Website</label>
            <input type="text" id="scf-website" name="scf_website" tabindex="-1" autocomplete="off">
        </div>';

        // Submit button and messages
        $output .= sprintf(
            '<div class="scf-submit-wrap">
                <button type="submit" class="scf-submit-btn et_pb_button">
                    <span class="scf-btn-text">%s</span>
                    <span class="scf-spinner"></span>
                </button>
            </div>
            <div class="scf-message scf-message-success" role="alert"></div>
            <div class="scf-message scf-message-error" role="alert"></div>
            </form>
        </div>',
            esc_html( $submit_text )
        );

        return $output;
    }
}

new SCF_Smart_Contact_Form();
