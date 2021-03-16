(function($){


	/**
	*  initialize_field
	*
	*  This function will initialize the $field.
	*
	*  @date	30/11/17
	*  @since	5.6.5
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function initialize_field( $field ) {

		$field.find('.frequency-select').on('change', function () {
			var freq = $(this).val();

			$field.find('.acf-field[data-frequency], .freq-suffix[data-frequency]').each(function () {
				if ($(this).data('frequency') != freq) {
					$(this).hide();
				} else {
					$(this).show();
				}
			});
		}).trigger('change');

		$field.find('.end-type-select').on('change', function () {
			var type = $(this).val();

			$field.find('.acf-field[data-end-type]').each(function () {
				if ($(this).data('end-type') != type) {
					$(this).hide();
				} else {
					$(this).show();
				}
			});
		}).trigger('change');

		$field.find('.monthly-by-options').on('change', 'input[type=radio]', function () {
			var parent = $(this).closest('.acf-field');

			parent.find('.acf-input').addClass('is-disabled');
			parent.find('.acf-input[data-monthly-by=' + $(this).val() + ']').removeClass('is-disabled');
		});

	}


	if( typeof acf.add_action !== 'undefined' ) {

		/*
		*  ready & append (ACF5)
		*
		*  These two events are called when a field element is ready for initizliation.
		*  - ready: on page load similar to $(document).ready()
		*  - append: on new DOM elements appended via repeater field or other AJAX calls
		*
		*  @param	n/a
		*  @return	n/a
		*/

		acf.add_action('ready_field/type=rrule', initialize_field);
		acf.add_action('append_field/type=rrule', initialize_field);


	} else {

		/*
		*  acf/setup_fields (ACF4)
		*
		*  This single event is called when a field element is ready for initialization.
		*
		*  @param	event		an event object. This can be ignored
		*  @param	element		An element which contains the new HTML
		*  @return	n/a
		*/

		$(document).on('acf/setup_fields', function(e, postbox){

			// find all relevant fields
			$(postbox).find('.field[data-field_type="rrule"]').each(function(){

				// initialize
				initialize_field( $(this) );

			});

		});

	}

})(jQuery);

(function($, undefined){

	var Field = acf.Field.extend({

		type: 'button_group_multiple',

		events: {
			'click input[type="checkbox"]': 'onClick'
		},

		$control: function(){
			return this.$('.acf-button-group');
		},

		$input: function(){
			return this.$('input:checked');
		},

		setValue: function( val ){
			this.$('input[value="' + val + '"]').prop('checked', true).trigger('change');
		},

		onClick: function( e, $el ){

			// vars
			var $label = $el.parent('label');

			// toggle active class
			if ($el.prop('checked')) {
				$label.addClass('selected');
			} else {
				$label.removeClass('selected');
			}
		}
	});

	acf.registerFieldType( Field );

})(jQuery);
