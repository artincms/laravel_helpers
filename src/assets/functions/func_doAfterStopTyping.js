function init_doAfterStopTyping(selector, function_name, function_params, waiting_time, get_full_element, exit_value, exit_operator) {
    exit_value = exit_value || false;
    exit_operator = exit_operator || '=';
    selector = selector || 'document';
    function_params = function_params || false;
    get_full_element = get_full_element || false;
    var waiting_time = waiting_time || 3000;
    //var $this = $(selector);
    //setup before functions
    var typingTimer; //timer identifier
    //on keyup, start the countdown
    $(document).on('keyup', selector, do_after_stop_typing);
    $(document).on('focusout', selector, do_after_stop_typing_on_focusout);

    function do_after_stop_typing() {
        var $this = $(this);
        if (exit_value) {
            switch (exit_operator) {
                case '=':
                    if (exit_value === $this.val())
                        do_function(function_name, function_params, get_full_element, $this, $this.val());
                    break;
                case '>':
                    if ($this.val() > exit_value)
                        do_function(function_name, function_params, get_full_element, $this, $this.val());
                    break;
                case '<':
                    if ($this.val() < exit_value)
                        do_function(function_name, function_params, get_full_element, $this, $this.val());
                    break;
                case '<=':
                    if ($this.val() <= exit_value)
                        do_function(function_name, function_params, get_full_element, $this, $this.val());
                    break;
                case '>=':
                    if ($this.val() >= exit_value)
                        do_function(function_name, function_params, get_full_element, $this, $this.val());
                    break;
                case '!=':
                    if (exit_value !== $this.val())
                        do_function(function_name, function_params, get_full_element, $this, $this.val());
                    break;
            }
        }
        clearTimeout(typingTimer);
        typingTimer = setTimeout(function () {
            do_function(function_name, function_params, get_full_element, $this, $this.val())
        }, waiting_time);
    }

    function do_after_stop_typing_on_focusout() {
        var $this = $(this);
        clearTimeout(typingTimer);
        do_function(function_name, function_params, get_full_element, $this, $this.val());
    }

    function do_function(function_name, function_params, get_full_element, v_this, this_value) {
        if (function_params) {
            if (get_full_element) {
                return function_name(v_this, function_params);
            }
            else {
                return function_name(this_value, function_params);
            }
        }
        else {
            if (get_full_element) {
                return function_name(v_this, function_params);
            }
            else {
                return function_name(this_value);
            }
        }
    }

    //on keydown, clear the countdown
    $(document).on('keydown', selector, function (objEvent) {
        /*if (objEvent.keyCode == 9) {  //tab pressed
            objEvent.preventDefault(); // stops its action
        }*/
        clearTimeout(typingTimer);
    });
}
