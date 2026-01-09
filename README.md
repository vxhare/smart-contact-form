# Smart Contact Form for Divi

A lightweight Divi Builder module for contact forms with conditional email routing based on department selection.

## [Download Latest Release](https://github.com/vxhare/smart-contact-form/archive/refs/heads/master.zip)

---

## Features

- **Conditional Email Routing** - Route emails to different addresses based on department selection
- **Full Visual Builder Support** - Live preview in Divi's Visual Builder
- **AJAX Submission** - No page reload, instant feedback
- **Honeypot Spam Protection** - Invisible spam prevention without CAPTCHAs
- **Divi-Styled** - Matches Divi's default form aesthetic
- **Lightweight** - No database storage, no bloat (~56 KB)
- **Secure** - Nonce verification, sanitized inputs

## Installation

1. [Download the plugin](https://github.com/vxhare/smart-contact-form/archive/refs/heads/master.zip)
2. In WordPress, go to **Plugins → Add New → Upload Plugin**
3. Upload the zip file and click **Install Now**
4. Activate the plugin

## Usage

1. Edit any page with **Divi Builder**
2. Add a new module and search for **"Smart Contact Form"**
3. Configure your department routing:

```
Sales|sales@yoursite.com
Support|support@yoursite.com
General Inquiry|info@yoursite.com
```

4. Customize labels, button text, and styling as needed
5. Save and publish

## Module Settings

### Form Fields
- Name, Email, Subject, Message field labels
- Submit button text
- Show/hide department dropdown

### Departments & Routing
- Define departments with email mapping (one per line)
- Format: `Department Name|email@example.com`
- Fallback email for unmatched submissions

### Messages
- Custom success message
- Custom error message

### Styling (Advanced Tab)
- Field background colors
- Label fonts
- Field fonts and borders
- Button styles
- Spacing and layout

## Requirements

- WordPress 5.0+
- Divi Theme or Divi Builder Plugin
- PHP 7.4+

## License

GPL-2.0+

## Credits

Built by [syddakid32](https://www.reddit.com/user/syddakid32/)
