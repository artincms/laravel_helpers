function clear_form_elements(selector) {
    selector = selector || 'document';
    $(selector).find(':input').each(function () {
        switch (this.type) {
            case 'password':
            case 'select-multiple':
            case 'select-one':
            case 'text':
            case 'number':
            case 'textarea':
                $(this).val('').trigger('change');
                break;
            case 'checkbox':
            case 'radio':
                this.checked = false;
        }
    });
}