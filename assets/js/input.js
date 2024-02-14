(function ($) {

    /**
     * Initialize the field
     */
    function initializeField(field) {
        const selectFrequency = function ($input) {
            var freq = $input.val();

            field.$el.find('.acf-field[data-frequency], .freq-suffix[data-frequency]').each(function () {
                if ($(this).data('frequency') != freq) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        }

        const selectEndType = function ($input) {
            var type = $input.val();

            field.$el.find('.acf-field[data-end-type]').each(function () {
                if ($(this).data('end-type') != type) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        }

        const selectMonthlyBy = function ($input) {
            var parent = $input.closest('.acf-field');
            var value = $input.val() ? $input.val() : "''";

            parent.find('.acf-input').addClass('is-disabled');
            parent.find('.acf-input[data-monthly-by=' + value + ']').removeClass('is-disabled');
        }

        field.$el.find('.frequency-select').on('change', function () {
            selectFrequency($(this));
        });

        field.$el.find('.end-type-select').on('change', function () {
            selectEndType($(this));
        });

        field.$el.find('.monthly-by-options').on('change', 'input[type=radio]', function () {
            selectMonthlyBy($(this));
        });

        selectFrequency(field.$el.find('.frequency-select'));
        selectEndType(field.$el.find('.end-type-select'));
        selectMonthlyBy(field.$el.find('.monthly-by-options input[type=radio]:checked'));
    }

    if (typeof acf.addAction !== 'undefined') {
        acf.addAction('ready_field/type=rrule', initializeField);
        acf.addAction('append_field/type=rrule', initializeField);
    }

    var Field = acf.Field.extend({
        type: 'button_group_multiple',
        events: {
            'click input[type="checkbox"]': 'onClick'
        },
        $control: function () {
            return this.$('.acf-button-group');
        },
        $input: function () {
            return this.$('input:checked');
        },
        setValue: function (val) {
            this.$('input[value="' + val + '"]').prop('checked', true).trigger('change');
        },
        onClick: function (e, $el) {
            var $label = $el.parent('label');

            // Toggle active class
            if ($el.prop('checked')) {
                $label.addClass('selected');
            } else {
                $label.removeClass('selected');
            }
        }
    });

    acf.registerFieldType(Field);

})(jQuery);
