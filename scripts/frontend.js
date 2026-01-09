import SmartContactForm from '../includes/modules/SmartContactForm/SmartContactForm';

jQuery(window).on('et_builder_api_ready', (event, API) => {
    API.registerModules([SmartContactForm]);
});
