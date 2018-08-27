function clear_form_elements(selector) {
    selector = selector || 'document';
    $(selector).find(':input').each(function () {
        switch (this.type) {
            case 'password':
            case 'select-multiple':
                $(this).val('').trigger('change');
            case 'select-one':
                $(this).val('').trigger('change');
            case 'text':
                $(this).val('');
            case 'textarea':
                $(this).val('');
                break;
            case 'checkbox':
            case 'radio':
                this.checked = false;
        }
    });

}