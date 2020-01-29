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
			'time_display_format' => 'H:i',
			'timezone' => get_option('timezone_string'),
		);


		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('rrule', 'error');
		*/

		// $this->l10n = array(
		// 	'error'	=> __('Error! Please enter a higher value', 'acf-rrule'),
		// );

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

		// global
		global $wp_locale;


		// vars
		$d_m_Y = date_i18n('d/m/Y');
		$m_d_Y = date_i18n('m/d/Y');
		$F_j_Y = date_i18n('F j, Y');
		$Ymd = date_i18n('Ymd');


		// display_format
		acf_render_field_setting( $field, array(
			'label'			=> __('Date Display Format', 'acf-rrule'),
			'instructions'	=> __('The date format displayed when editing a post', 'acf-rrule'),
			'type'			=> 'radio',
			'name'			=> 'date_display_format',
			'other_choice'	=> 1,
			'choices'		=> array(
				'd/m/Y'			=> '<span>' . $d_m_Y . '</span><code>d/m/Y</code>',
				'm/d/Y'			=> '<span>' . $m_d_Y . '</span><code>m/d/Y</code>',
				'F j, Y'		=> '<span>' . $F_j_Y . '</span><code>F j, Y</code>',
				'other'			=> '<span>' . __('Custom:', 'acf') . '</span>'
			)
		));

		// return_format
		acf_render_field_setting( $field, array(
			'label'			=> __('Date Return Format', 'acf-rrule'),
			'instructions'	=> __('The date format returned via template functions', 'acf-rrule'),
			'type'			=> 'radio',
			'name'			=> 'return_format',
			'other_choice'	=> 1,
			'choices'		=> array(
				'd/m/Y'			=> '<span>' . $d_m_Y . '</span><code>d/m/Y</code>',
				'm/d/Y'			=> '<span>' . $m_d_Y . '</span><code>m/d/Y</code>',
				'F j, Y'		=> '<span>' . $F_j_Y . '</span><code>F j, Y</code>',
				'Ymd'			=> '<span>' . $Ymd . '</span><code>Ymd</code>',
				'other'			=> '<span>' . __('Custom:', 'acf') . '</span>'
			)
		));

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

		// Generate a unique ID for fields we don't want to be autocompleted
		$unique_id = $field['id'] . '-' . time();

		// echo "<pre>";
		// var_dump($field);
		// echo "</pre>";

		// Datepicker options
		$datepicker_options = array(
			'class' => 'acf-date-picker acf-input-wrap',
			'data-date_format' => acf_convert_date_to_js($field['date_display_format']),
		);
		$timepicker_options = array(
			'class' => 'acf-time-picker acf-input-wrap',
		);

		// HTML
		?>
		<div class="acf-field-rrule-sub-fields">
			<div class="acf-field">
				<div class="acf-columns">
					<div class="acf-column">
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
									<label for="<?=$unique_id?>-start-date">
										<?=__('Start date', 'acf-rrule')?> <span class="acf-required">*</span>
									</label>
								</div>

								<?php acf_hidden_input( array (
									'name' => $field['name'] . '[start_date]',
									'value'	=> $start_date_hidden,
								) ); ?>
								<?php acf_text_input( array(
									'id' => $unique_id . '-start-date',
									'class' => 'input',
									'value'	=> $start_date_display,
								) ); ?>
							</div>
						</div>
					</div>

					<div class="acf-column">
						<div class="acf-field acf-field-time-picker is-required" data-type="time_picker">
							<div <?php acf_esc_attr_e( $timepicker_options ); ?>>
								<?php
								$start_time = '';

								// Format values
								if( $field['value'] ) {
									$start_time = acf_format_date( $field['value']['start_time'], $field['time_display_format'] );
								}
								?>

								<div class="acf-label">
									<label for="<?=$unique_id?>-start-time">
										<?=__('Start time', 'acf-rrule')?> <span class="acf-required">*</span>
									</label>
								</div>

								<?php acf_hidden_input( array (
									'name' => $field['name'] . '[start_time]',
									'value'	=> $start_time,
								) ); ?>
								<?php acf_text_input( array(
									'id' => $unique_id . '-start-time',
									'class' => 'input',
									'value'	=> $start_time,
								) ); ?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="acf-field">
				<?php
				$frequency = array(
					'id' => $field['id'] . '-frequency',
					'name' => $field['name'] . '[frequency]',
					'value' => $field['value']['frequency'],
					'choices' => array(
						'DAILY' => __('Daily', 'acf-rrule'),
						'WEEKLY' => __('Weekly', 'acf-rrule'),
						'MONTHLY' => __('Monthly', 'acf-rrule'),
						'YEARLY' => __('Yearly', 'acf-rrule'),
					),
				);
				?>

				<div class="acf-label">
					<label for="<?=$frequency['id']?>">
						<?=__('Frequency', 'acf-rrule')?>
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
					<div class="acf-input-prepend"><?=_x('Every', 'RRule Interval', 'acf-rrule')?></div>
					<div class="acf-input-append">
						<span class="freq-suffix" data-frequency="DAILY"><?=_x('day', 'RRule Interval', 'acf-rrule')?></span>
						<span class="freq-suffix" data-frequency="WEEKLY"><?=_x('week', 'RRule Interval', 'acf-rrule')?></span>
						<span class="freq-suffix" data-frequency="MONTHLY"><?=_x('month', 'RRule Interval', 'acf-rrule')?></span>
						<span class="freq-suffix" data-frequency="YEARLY"><?=_x('year', 'RRule Interval', 'acf-rrule')?></span>
					</div>
					<div class="acf-input-wrap">
						<?php acf_text_input( $interval ); ?>
					</div>
				</div>
			</div>

			<div class="acf-field acf-field-button-group" data-type="button_group_multiple" data-frequency="WEEKLY">
				<?php
				$weekdays = array(
					'MO' => __('Monday', 'acf-rrule'),
					'TU' => __('Tuesday', 'acf-rrule'),
					'WE' => __('Wednesday', 'acf-rrule'),
					'TH' => __('Thursday', 'acf-rrule'),
					'FR' => __('Friday', 'acf-rrule'),
					'SA' => __('Saturday', 'acf-rrule'),
					'SU' => __('Sunday', 'acf-rrule'),
				);
				?>

				<div class="acf-label">
					<label>
						<?=__('Week days', 'acf-rrule')?>
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

			<div id="<?=$field['id']?>-monthly-by" class="acf-field" data-frequency="MONTHLY">
				<div class="acf-columns">
					<div class="acf-column">
						<div class="acf-label is-inline">
							<input id="acf-<?=$field['name']?>-bymonthdays" type="radio" name="<?=$field['name']?>[monthly_by]" value="monthdays"<?=$field['value']['monthly_by'] == 'monthdays' ? ' checked' : ''?>>
							<label for="acf-<?=$field['name']?>-bymonthdays">
								<?=__('Month days', 'acf-rrule')?>
							</label>
						</div>

						<?php $days = range(1, 31); ?>

						<div class="acf-input<?=$field['value']['monthly_by'] != 'monthdays' ? ' is-disabled' : ''?>" data-monthly-by="monthdays">
							<table class="acf-rrule-monthdays">
								<?php foreach (array_chunk($days, 7) as $week) : ?>
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
							<input id="acf-<?=$field['name']?>-bysetpos" type="radio" name="<?=$field['name']?>[monthly_by]" value="setpos"<?=$field['value']['monthly_by'] == 'setpos' ? ' checked' : ''?>>
							<label for="acf-<?=$field['name']?>-bysetpos">
								<?=__('Day of the week', 'acf-rrule')?>
							</label>
						</div>

						<?php
						$setpos = array(
							'id' => $field['id'] . '-setpos',
							'name' => $field['name'] . '[setpos]',
							'value' => $field['value']['setpos'],
							'choices' => array(
								'1' => __('First', 'acf-rrule'),
								'2' => __('Second', 'acf-rrule'),
								'3' => __('Third', 'acf-rrule'),
								'4' => __('Fourth', 'acf-rrule'),
								'-1' => __('Last', 'acf-rrule'),
							),
						);
						$setpos_options = array(
							'id' => $field['id'] . '-setpos-option',
							'name' => $field['name'] . '[setpos_option]',
							'value' => $field['value']['setpos_option'],
							'choices' => $weekdays,
						);
						?>

						<div class="acf-input<?=$field['value']['monthly_by'] != 'setpos' ? ' is-disabled' : ''?>" data-monthly-by="setpos">
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

			<div class="acf-field acf-field-checkbox" data-type="checkbox" data-key="<?=$field['key']?>" data-frequency="YEARLY">
				<div class="acf-label">
					<label><?=__('Month', 'acf-rrule')?></label>
				</div>
				<div class="acf-input">
					<?php $months = array(
						'1' => __('January', 'acf-rrule'),
						'2' => __('February', 'acf-rrule'),
						'3' => __('March', 'acf-rrule'),
						'4' => __('April', 'acf-rrule'),
						'5' => __('May', 'acf-rrule'),
						'6' => __('June', 'acf-rrule'),
						'7' => __('July', 'acf-rrule'),
						'8' => __('August', 'acf-rrule'),
						'9' => __('September', 'acf-rrule'),
						'10' => __('October', 'acf-rrule'),
						'11' => __('November', 'acf-rrule'),
						'12' => __('December', 'acf-rrule'),
					); ?>

					<input type="hidden" name="<?=$field['name']?>[months]">

					<ul class="acf-checkbox-list acf-hl">
						<?php foreach ($months as $key => $month) : ?>
							<li>
								<label<?=in_array($key, $field['value']['months']) ? ' class="selected"' : ''?>>
									<input type="checkbox" id="acf-<?=$field['name']?>-months-<?=$key?>" name="<?=$field['name']?>[months][]" value="<?=$key?>"<?=in_array($key, $field['value']['months']) ? ' checked' : ''?>>
									<?=$month?>
								</label>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>

			<div class="acf-field">
				<div class="acf-label">
					<label for="<?=$field['id']?>-end-type">
						<?=__('End date', 'acf-rrule')?>
					</label>
				</div>
				<div class="acf-input">
					<?php acf_select_input( array(
						'id' => $field['id'] . '-end-type',
						'name' => $field['name'] . '[end_type]',
						'value' => $field['value']['end_type'],
						'choices' => array(
							'date' => __('At a specific date', 'acf-rrule'),
							'count' =>  __('After a number of occurences', 'acf-rrule'),
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
					<div class="acf-input-prepend"><?=__('After', 'acf-rrule')?></div>
					<div class="acf-input-append"><?=__('occurence(s)', 'acf-rrule')?></div>
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
						<div class="acf-input-prepend"><?=__('Until', 'acf-rrule')?></div>
						<div class="acf-input-wrap">
							<?php acf_hidden_input( array (
								'name' => $field['name'] . '[end_date]',
								'value'	=> $end_date_hidden,
							) ); ?>
							<?php acf_text_input( array(
								'id' => $unique_id . '-end-date',
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
			'start_time' => null,
			'frequency' => 'WEEKLY',
			'interval' => 1,
			'weekdays' => array(),
			'monthdays' => array(),
			'months' => array(),
			'monthly_by' => 'monthdays',
			'setpos' => 1,
			'setpos_option' => 'MO',
			'end_type' => null,
			'end_date' => null,
			'occurence_count' => null,
		);

		if ($value) {
			try {
				$rule = new Rule($value);

				$start_date = $rule->getStartDate();

				$new_value['rrule'] = $value;
				$new_value['start_date'] = $start_date->format('Y-m-d');
				$new_value['start_time'] = $start_date->format('H:i:s');
				$new_value['frequency'] = $rule->getFreqAsText();
				$new_value['interval'] = $rule->getInterval();
				$new_value['weekdays'] = $rule->getByDay() ?: array();
				$new_value['monthdays'] = $rule->getByMonthDay() ?: array();
				$new_value['months'] = $rule->getByMonth() ?: array();

				if ($new_value['frequency'] == 'MONTHLY') {
					if (sizeof($new_value['weekdays']) > 0) {
						$new_value['monthly_by'] = 'setpos';
						$set_position = $rule->getBySetPosition();
						$new_value['setpos'] = $set_position[0];
						$new_value['setpos_option'] = $new_value['weekdays'][0];
					} else {
						$new_value['monthly_by'] = 'monthdays';
					}
				}

				if ($rule->getUntil()) {
					$new_value['end_type'] = 'date';
					$new_value['end_date'] =  $rule->getUntil()->format('Y-m-d');
				} else {
					$new_value['end_type'] = 'count';
					$new_value['occurence_count'] =  $rule->getCount();
				}
			} catch (\Exception $e) {
				//
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

		if (is_array($value)) {
			$start_date = \DateTime::createFromFormat('Ymd H:i', $value['start_date'] . ' ' . $value['start_time']);

			$rule = new Rule;

			$rule->setTimezone($field['timezone'])
				 ->setStartDate($start_date, true)
				 ->setFreq($value['frequency'])
				 ->setInterval($value['interval']);

			switch ($value['frequency']) {
				case 'WEEKLY':
					$rule->setByDay($value['weekdays']);

					break;

				case 'MONTHLY':
					if ($value['monthly_by'] == 'monthdays') {
						$rule->setByMonthDay($value['monthdays']);
					} else {
						$rule->setBySetPosition(array(intval($value['setpos'])));
						$rule->setByDay(array($value['setpos_option']));
					}

					break;

				case 'YEARLY':
					$rule->setByMonth($value['months']);

					break;

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
