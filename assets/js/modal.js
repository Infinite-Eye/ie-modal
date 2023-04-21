(function ($, settings) {

    var init = true;
    var setup = false;

    var _init_modals = function () {

        if (setup) {
            return;
        }

        $.each(settings, function (i, modal) {

            // skip if manual display mode
            if (modal.mode === 'manual') {
                return true;
            }

            var $modal = $('#' + modal.id);

            var show_modal = true;
            var current_time = Date.now() / 1000;

            // if cookie is present
            if (show_modal && modal.cookie > 0 && Cookies.get('modal_' + modal.id + '_close') === modal.cookie_value) {
                show_modal = false;
            }

            // before schedule_from date
            if (show_modal && modal.schedule_from > 0 && current_time < modal.schedule_from) {
                show_modal = false;
            }

            // after schedule_to date
            if (show_modal && modal.schedule_to > 0 && current_time > modal.schedule_to) {
                show_modal = false;
            }

            if (modal.cookie > 0) {
                $modal.on($.modal.AFTER_CLOSE, function (event) {

                    Cookies.set('modal_' + modal.id + '_close', modal.cookie_value, { expires: modal.cookie, path: '/' })
                });
            }

            if (show_modal) {
                $modal.modal(modal.args);
            }
        });

        setup = true;
    }

    // Intergrate with age-gate plugin
    window.addEventListener('age_gate_shown', function () {

        init = false;

        window.addEventListener('age_gate_passed', _init_modals);
        window.addEventListener('age_gate_failed', _init_modals);

    });

    $(document).ready(function () {
        if (init) {
            _init_modals();
        }
    });

})(jQuery, modal_config);