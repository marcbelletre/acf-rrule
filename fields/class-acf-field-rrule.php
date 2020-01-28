<?php

use \Recurr\Rule;

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_field_rrule') ) :


class acf_field_rrule extends acf_field {

	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function __construct( $settings ) {

		/*
		*  name (string) Single word, no spaces. Underscores allowed
		*/

		$this->name = 'rrule';


		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/

		$this->label = __('RRule', 'acf-rrule');


		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/

		$this->category = 'jquery';


		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/

		$this->defaults = array(
			'date_display_format' => 'j F Y',
			'date_return_format' => 'Y-m-d',
		);


		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('rrule', 'error');
		*/

		$this->l10n = array(
			'error'	=> __('Error! Please enter a higher value', 'acf-rrule'),
		);

		/*
		*  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
		*/

		$this->settings = $settings;

		parent::__construct();

	}


	/*
	*  render_field_settings()
	*
	*  Create extra settings for your field. These are visible when editing a field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

	function render_field_settings( $field ) {

		/*
		*  acf_render_field_setting
		*
		*  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
		*  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
		*
		*  More than one setting can be added by copy/paste the above code.
		*  Please note that you must also have a matching $defaults value for the field name (font_size)
		*/

		// acf_render_field_setting( $field, array(
		// 	'label'			=> __('Font Size','acf-rrule'),
		// 	'instructions'	=> __('Customise the input font size','acf-rrule'),
		// 	'type'			=> 'number',
		// 	'name'			=> 'font_size',
		// 	'prepend'		=> 'px',
		// ));

	}



	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

	function render_field( $field ) {

		echo "<pre>";
		var_dump($field);
		echo "</pre>";

		// Datepicker options
		$datepicker_options = array(
			'class' => 'acf-date-picker acf-input-wrap',
			'data-date_format' => acf_convert_date_to_js($field['date_display_format']),
			'data-first_day' => $field['first_day'],
		);

		// HTML
		?>
		<div class="acf-field-rrule-sub-fields">
			<div class="acf-field acf-field-date-picker is-required" data-type="date_picker">
				<div <?php acf_esc_attr_e( $datepicker_options ); ?>>
					<?php
					$start_date_hidden = '';
					$start_date_display = '';

					// Format values
					if( $field['value'] ) {
						$start_date_hidden = acf_format_date( $field['value']['start_date'], 'Ymd' );
						$start_date_display = acf_format_date( $field['value']['start_date'], $field['date_display_format'] );
					}
					?>

					<div class="acf-label">
						<label for="<?=$field['id']?>-start-date">
							Date de début <span class="acf-required">*</span>
						</label>
					</div>

					<?php acf_hidden_input( array (
						'name' => $field['name'] . '[start_date]',
						'value'	=> $start_date_hidden,
					) ); ?>
					<?php acf_text_input( array(
						'id' => $field['id'] . '-start-date',
						'class' => 'input',
						'value'	=> $start_date_display,
					) ); ?>
				</div>
			</div>

			<div class="acf-field">
				<?php
				$frequency = array(
					'id' => $field['id'] . '-frequency',
					'name' => $field['name'] . '[frequency]',
					'value' => $field['value']['frequency'],
					'choices' => array(
						'DAILY' => 'Quotidien',
						'WEEKLY' => 'Hebdomadaire',
						'MONTHLY' => 'Mensuel',
						'YEARLY' => 'Annuel',
					),
				);
				?>

				<div class="acf-label">
					<label for="<?=$frequency['id']?>">
						Fréquence
					</label>
				</div>

				<div class="acf-input">
					<?php acf_select_input( $frequency ); ?>
				</div>
			</div>

			<div class="acf-field">
				<?php
				$interval = array(
					'id' => $field['id'] . '-interval',
					'name' => $field['name'] . '[interval]',
					'type' => 'number',
					'class' => 'acf-is-prepended acf-is-appended',
					'value'	=> $field['value']['interval'],
					'min' => 1,
					'step' => 1,
				);
				?>

				<div class="acf-input">
					<div class="acf-input-prepend">Tous les</div>
					<div class="acf-input-append">
						<span class="freq-suffix" data-frequency="DAILY">jour(s)</span>
						<span class="freq-suffix" data-frequency="WEEKLY">semaine(s)</span>
						<span class="freq-suffix" data-frequency="MONTHLY">mois</span>
						<span class="freq-suffix" data-frequency="YEARLY">an(s)</span>
					</div>
					<div class="acf-input-wrap">
						<?php acf_text_input( $interval ); ?>
					</div>
				</div>
			</div>

			<div class="acf-field acf-field-button-group" data-type="button_group_multiple" data-frequency="WEEKLY">
				<?php
				$weekdays = array(
					'MO' => 'Lundi',
					'TU' => 'Mardi',
					'WE' => 'Mercredi',
					'TH' => 'Jeudi',
					'FR' => 'Vendredi',
					'SA' => 'Samedi',
					'SU' => 'Dimanche',
				);
				?>

				<div class="acf-label">
					<label>
						Jour(s) de la semaine
					</label>
				</div>

				<div class="acf-input">
					<div class="acf-button-group">
						<?php foreach ($weekdays as $key => $value) : ?>
							<label<?=in_array($key, $field['value']['weekdays']) ? ' class="selected"' : ''?>>
								<input type="checkbox" name="<?=$field['name']?>[weekdays][]" value="<?=$key?>"<?=in_array($key, $field['value']['weekdays']) ? ' checked' : ''?>> <?=$value?>
							</label>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

			<div class="acf-field" data-frequency="MONTHLY">
				<div class="acf-columns">
					<div class="acf-column">
						<div class="acf-label is-inline">
							<input id="acf-<?=$field['name']?>-bymonthdays" class="acf-<?=$field['name']?>-monthly-by" type="radio" name="<?=$field['name']?>[monthly_by]" value="monthdays">
							<label for="acf-<?=$field['name']?>-bymonthdays">
								Jour(s) du mois
							</label>
						</div>

						<?php $months = range(1, 31); ?>

						<div class="acf-input is-disabled">
							<table class="acf-rrule-monthdays">
								<?php foreach (array_chunk($months, 7) as $week) : ?>
									<tr>
										<?php foreach ($week as $day) : ?>
											<td>
												<input id="acf-<?=$field['name']?>-monthdays-<?=$day?>" type="checkbox" name="<?=$field['name']?>[monthdays][]" value="<?=$day?>"<?=in_array($day, $field['value']['monthdays']) ? ' checked' : ''?>>
												<label for="acf-<?=$field['name']?>-monthdays-<?=$day?>"><?=$day?></label>
											</td>
										<?php endforeach; ?>
									</tr>
								<?php endforeach; ?>
							</table>
						</div>
					</div>

					<div class="acf-column">
						<div class="acf-label is-inline">
							<input id="acf-<?=$field['name']?>-bysetpos" class="acf-<?=$field['name']?>-monthly-by" type="radio" name="<?=$field['name']?>[monthly_by]" value="setpos">
							<label for="acf-<?=$field['name']?>-bysetpos">
								Jour spécifique
							</label>
						</div>

						<?php
						$setpos = array(
							'id' => $field['id'] . '-setpos',
							'name' => $field['name'] . '[setpos]',
							'value' => $field['value']['setpos'],
							'choices' => array(
								'1' => 'Premier',
								'2' => 'Deuxième',
								'3' => 'Troisième',
								'4' => 'Quatrième',
								'-1' => 'Dernier',
							),
						);
						$setpos_options = array(
							'id' => $field['id'] . '-setpos-options',
							'name' => $field['name'] . '[setpos_options]',
							'value' => $field['value']['setpos_options'],
							'choices' => $weekdays,
						);
						?>

						<div class="acf-input is-disabled">
							<div class="acf-columns">
								<div class="acf-column">
									<?php acf_select_input( $setpos ); ?>
								</div>
								<div class="acf-column">
									<?php acf_select_input( $setpos_options ); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="acf-field">
				<div class="acf-label">
					<label for="<?=$field['id']?>-end-type">
						Fin de la récurrence
					</label>
				</div>
				<div class="acf-input">
					<?php acf_select_input( array(
						'id' => $field['id'] . '-end-type',
						'name' => $field['name'] . '[end_type]',
						'value' => $field['value']['end_type'],
						'choices' => array(
							'date' => "À une date spécifique",
							'count' => "Après un nombre d'occurences",
						),
					) ); ?>
				</div>
			</div>

			<div class="acf-field" data-end-type="count">
				<?php
				$occurence_count = array(
					'id' => $field['id'] . '-occurence-count',
					'name' => $field['name'] . '[occurence_count]',
					'type' => 'number',
					'class' => 'acf-is-prepended acf-is-appended',
					'value'	=> $field['value']['occurence_count'],
					'min' => 1,
					'step' => 1,
				);
				?>

				<div class="acf-input">
					<div class="acf-input-prepend">Après</div>
					<div class="acf-input-append">occurence(s)</div>
					<div class="acf-input-wrap">
						<?php acf_text_input( $occurence_count ); ?>
					</div>
				</div>
			</div>

			<div class="acf-field acf-field-date-picker" data-type="date_picker" data-end-type="date">
				<div <?php acf_esc_attr_e( $datepicker_options ); ?>>
					<?php
					$end_date_hidden = '';
					$end_date_display = '';

					// Format values
					if( $field['value'] ) {
						$end_date_hidden = acf_format_date( $field['value']['end_date'], 'Ymd' );
						$end_date_display = acf_format_date( $field['value']['end_date'], $field['date_display_format'] );
					}
					?>

					<div class="acf-input">
						<div class="acf-input-prepend">Jusqu'au</div>
						<div class="acf-input-wrap">
							<?php acf_hidden_input( array (
								'name' => $field['name'] . '[end_date]',
								'value'	=> $end_date_hidden,
							) ); ?>
							<?php acf_text_input( array(
								'id' => $field['id'] . '-end-date',
								'class' => 'acf-is-prepended',
								'value'	=> $end_date_display,
							) ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}


	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function input_admin_enqueue_scripts() {

		// bail early if no enqueue
	   	if( !acf_get_setting('enqueue_datepicker') ) {
		   	return;
	   	}

	   	// localize
	   	global $wp_locale;
	   	acf_localize_data(array(
		   	'datePickerL10n'	=> array(
				'closeText'			=> _x('Done',	'Date Picker JS closeText',		'acf'),
				'currentText'		=> _x('Today',	'Date Picker JS currentText',	'acf'),
				'nextText'			=> _x('Next',	'Date Picker JS nextText',		'acf'),
				'prevText'			=> _x('Prev',	'Date Picker JS prevText',		'acf'),
				'weekHeader'		=> _x('Wk',		'Date Picker JS weekHeader',	'acf'),
				'monthNames'        => array_values( $wp_locale->month ),
				'monthNamesShort'   => array_values( $wp_locale->month_abbrev ),
				'dayNames'          => array_values( $wp_locale->weekday ),
				'dayNamesMin'       => array_values( $wp_locale->weekday_initial ),
				'dayNamesShort'     => array_values( $wp_locale->weekday_abbrev )
			)
	   	));

		// script
		wp_enqueue_script('jquery-ui-datepicker');

		// style
		wp_enqueue_style('acf-datepicker', acf_get_url('assets/inc/datepicker/jquery-ui.min.css'), array(), '1.11.4' );

		// vars
		$url = $this->settings['url'];
		$version = $this->settings['version'];


		// register & include JS
		wp_enqueue_script('acf-rrule', "{$url}assets/js/input.js", array('acf-input'), $version);


		// register & include CSS
		wp_enqueue_style('acf-rrule', "{$url}assets/css/input.css", array('acf-input'), $version);

	}


	/*
	*  input_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function input_admin_head() {



	}

	*/


	/*
   	*  input_form_data()
   	*
   	*  This function is called once on the 'input' page between the head and footer
   	*  There are 2 situations where ACF did not load during the 'acf/input_admin_enqueue_scripts' and
   	*  'acf/input_admin_head' actions because ACF did not know it was going to be used. These situations are
   	*  seen on comments / user edit forms on the front end. This function will always be called, and includes
   	*  $args that related to the current screen such as $args['post_id']
   	*
   	*  @type	function
   	*  @date	6/03/2014
   	*  @since	5.0.0
   	*
   	*  @param	$args (array)
   	*  @return	n/a
   	*/

   	/*

   	function input_form_data( $args ) {



   	}

   	*/


	/*
	*  input_admin_footer()
	*
	*  This action is called in the admin_footer action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_footer)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function input_admin_footer() {



	}

	*/


	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add CSS + JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function field_group_admin_enqueue_scripts() {

	}

	*/


	/*
	*  field_group_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is edited.
	*  Use this action to add CSS and JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function field_group_admin_head() {

	}

	*/


	/*
	*  load_value()
	*
	*  This filter is applied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/

	function load_value( $value, $post_id, $field ) {

		$new_value = array(
			'rrule' => null,
			'start_date' => null,
			'frequency' => 'WEEKLY',
			'interval' => 1,
			'weekdays' => array(),
			'monthdays' => array(),
			'end_type' => null,
			'end_date' => null,
			'occurence_count' => null,
		);

		if ($value) {
			$rule = new Rule($value);

			$new_value['rrule'] = $value;
			$new_value['start_date'] = $rule->getStartDate()->format('Y-m-d');
			$new_value['frequency'] = $rule->getFreqAsText();
			$new_value['interval'] = $rule->getInterval();

			if ($new_value['frequency'] == 'WEEKLY') {
				$new_value['weekdays'] = $rule->getByDay() ?: array();
			} elseif ($new_value['frequency'] == 'MONTHLY') {
				$new_value['monthdays'] = $rule->getByMonthDay() ?: array();
			}

			if ($rule->getUntil()) {
				$new_value['end_type'] = 'date';
				$new_value['end_date'] =  $rule->getUntil()->format('Y-m-d');
			} else {
				$new_value['end_type'] = 'count';
				$new_value['occurence_count'] =  $rule->getCount();
			}
		}

		return $new_value;

	}


	/*
	*  update_value()
	*
	*  This filter is applied to the $value before it is saved in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/

	function update_value( $value, $post_id, $field ) {

		// echo "<pre>";
		// var_dump($value);
		// echo "</pre>"; die;

		if (is_array($value)) {
			$timezone = $value['timezone'] ?: 'Europe/Paris';
			$start_date = \DateTime::createFromFormat('Ymd', $value['start_date']);
			$start_date->setTime(0,0,0);

			$rule = new Rule;

			$rule->setTimezone($timezone)
				 ->setStartDate($start_date, true)
				 ->setFreq($value['frequency'])
				 ->setInterval($value['interval']);

			switch ($value['frequency']) {
				case 'WEEKLY':
					$rule->setByDay($value['weekdays']);
					break;
				case 'MONTHLY':
					$rule->setByMonthDay($value['monthdays']);
					break;
				case 'YEARLY':
				default: break;
			}

			switch ($value['end_type']) {
				case 'date':
					if ($value['end_date']) {
						$end_date = \DateTime::createFromFormat('Ymd', $value['end_date']);
						$end_date->setTime(0,0,0);

						$rule->setUntil($end_date);
					}

					break;
				case 'count':
					$rule->setCount($value['occurence_count']);
					break;
				default: break;
			}

			$new_value = $rule->getString();

			acf_update_value( $new_value, $post_id, $field );

			return $new_value;
		}
	}


	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value which was loaded from the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*
	*  @return	$value (mixed) the modified value
	*/

	/*

	function format_value( $value, $post_id, $field ) {

		// bail early if no value
		if( empty($value) ) {

			return $value;

		}


		// apply setting
		if( $field['font_size'] > 12 ) {

			// format the value
			// $value = 'something';

		}


		// return
		return $value;
	}

	*/


	/*
	*  validate_value()
	*
	*  This filter is used to perform validation on the value prior to saving.
	*  All values are validated regardless of the field's required setting. This allows you to validate and return
	*  messages to the user if the value is not correct
	*
	*  @type	filter
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$valid (boolean) validation status based on the value and the field's required setting
	*  @param	$value (mixed) the $_POST value
	*  @param	$field (array) the field array holding all the field options
	*  @param	$input (string) the corresponding input name for $_POST value
	*  @return	$valid
	*/

	/*

	function validate_value( $valid, $value, $field, $input ){

		// Basic usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = false;
		}


		// Advanced usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = __('The value is too little!','acf-rrule'),
		}


		// return
		return $valid;

	}

	*/


	/*
	*  delete_value()
	*
	*  This action is fired after a value has been deleted from the db.
	*  Please note that saving a blank value is treated as an update, not a delete
	*
	*  @type	action
	*  @date	6/03/2014
	*  @since	5.0.0
	*
	*  @param	$post_id (mixed) the $post_id from which the value was deleted
	*  @param	$key (string) the $meta_key which the value was deleted
	*  @return	n/a
	*/

	/*

	function delete_value( $post_id, $key ) {



	}

	*/


	/*
	*  load_field()
	*
	*  This filter is applied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/

	// function load_field( $field ) {
	//
	// 	return $field;
	//
	// }


	/*
	*  update_field()
	*
	*  This filter is applied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/

	/*

	function update_field( $field ) {

		return $field;

	}

	*/


	/*
	*  delete_field()
	*
	*  This action is fired after a field is deleted from the database
	*
	*  @type	action
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	n/a
	*/

	/*

	function delete_field( $field ) {



	}

	*/


}

// initialize
new acf_field_rrule( $this->settings );

// class_exists check
endif;

?>
