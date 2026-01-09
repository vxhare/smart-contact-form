import React, { Component } from 'react';

class SmartContactForm extends Component {
    static slug = 'scf_smart_contact_form';

    /**
     * Parse department routing string into options array
     */
    getDepartmentOptions() {
        const { department_routing = '', show_department = 'on' } = this.props;

        if (show_department !== 'on' || !department_routing) {
            return [];
        }

        return department_routing
            .split('\n')
            .filter(line => line.trim())
            .map(line => {
                const parts = line.split('|');
                return parts[0] ? parts[0].trim() : '';
            })
            .filter(Boolean);
    }

    /**
     * Render the module in Visual Builder
     */
    render() {
        const {
            name_label = 'Name',
            email_label = 'Email',
            subject_label = 'Subject',
            message_label = 'Message',
            department_label = 'Department',
            submit_text = 'Send Message',
            show_department = 'on',
            field_background = '#ffffff',
        } = this.props;

        const departmentOptions = this.getDepartmentOptions();

        const fieldStyle = {
            backgroundColor: field_background,
        };

        return (
            <div className="scf-smart-contact-form">
                <div className="scf-form">
                    {/* Name Field */}
                    <div className="scf-field scf-field-half">
                        <label className="scf-label">
                            {name_label} <span className="scf-required">*</span>
                        </label>
                        <input
                            type="text"
                            className="scf-input"
                            style={fieldStyle}
                            placeholder=""
                            disabled
                        />
                    </div>

                    {/* Email Field */}
                    <div className="scf-field scf-field-half">
                        <label className="scf-label">
                            {email_label} <span className="scf-required">*</span>
                        </label>
                        <input
                            type="email"
                            className="scf-input"
                            style={fieldStyle}
                            placeholder=""
                            disabled
                        />
                    </div>

                    {/* Department Dropdown */}
                    {show_department === 'on' && departmentOptions.length > 0 && (
                        <div className="scf-field">
                            <label className="scf-label">{department_label}</label>
                            <select className="scf-select" style={fieldStyle} disabled>
                                <option value="">Select Department</option>
                                {departmentOptions.map((dept, index) => (
                                    <option key={index} value={dept}>
                                        {dept}
                                    </option>
                                ))}
                            </select>
                        </div>
                    )}

                    {/* Subject Field */}
                    <div className="scf-field">
                        <label className="scf-label">
                            {subject_label} <span className="scf-required">*</span>
                        </label>
                        <input
                            type="text"
                            className="scf-input"
                            style={fieldStyle}
                            placeholder=""
                            disabled
                        />
                    </div>

                    {/* Message Field */}
                    <div className="scf-field">
                        <label className="scf-label">
                            {message_label} <span className="scf-required">*</span>
                        </label>
                        <textarea
                            className="scf-textarea"
                            style={fieldStyle}
                            rows="6"
                            disabled
                        />
                    </div>

                    {/* Submit Button */}
                    <div className="scf-submit-wrap">
                        <button type="button" className="scf-submit-btn et_pb_button" disabled>
                            <span className="scf-btn-text">{submit_text}</span>
                        </button>
                    </div>
                </div>
            </div>
        );
    }
}

export default SmartContactForm;
