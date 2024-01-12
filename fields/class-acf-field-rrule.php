<?php

use Recurr\Rule;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Check if class already exists
if (!class_exists('acf_field_rrule')) :

    class acf_field_rrule extends acf_field
    {
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

        public function __construct($settings)
        {
            /*
             *  name (string) Single word, no spaces. Underscores allowed
             */

            $this->name = 'rrule';

            /*
             *  label (string) Multiple words, can include spaces, visible when selecting a field type
             */

            $this->label = __('RRule', 'acf-rrule-field');

            /*
             *  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
             */

            $this->category = 'jquery';

            /*
             *  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
             */

            $this->defaults = [
                'date_display_format' => 'j F Y',
                'date_return_format' => 'Y-m-d',
                'allow_time' => false,
                'time_display_format' => 'H:i',
                'time_return_format' => 'H:i',
            ];

            /*
             *  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
             *  var message = acf._e('rrule', 'error');
             */

            // $this->l10n = array(
            // 	'date_error' => __('The end date should be after the start date.', 'acf-rrule-field'),
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

        public function render_field_settings($field)
        {
            /*
             *  acf_render_field_setting
             *
             *  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
             *  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
             *
             *  More than one setting can be added by copy/paste the above code.
             *  Please note that you must also have a matching $defaults value for the field name (font_size)
             */

            global $wp_locale;

            // Init vars
            $d_m_Y = date_i18n('d/m/Y');
            $m_d_Y = date_i18n('m/d/Y');
            $F_j_Y = date_i18n('F j, Y');
            $g_i_a = date_i18n('g:i a');
            $H_i = date_i18n('H:i');
            $Ymd = date_i18n('Ymd');

            echo '<div class="acf-field-settings-split">';

            // Date display format
            acf_render_field_setting($field, [
                'label' => __('Date Display Format', 'acf-rrule-field'),
                'instructions' => __('The date format displayed when editing a post', 'acf-rrule-field'),
                'type' => 'radio',
                'name' => 'date_display_format',
                'other_choice' => 1,
                'choices' => [
                    'd/m/Y' => '<span>' . $d_m_Y . '</span><code>d/m/Y</code>',
                    'm/d/Y' => '<span>' . $m_d_Y . '</span><code>m/d/Y</code>',
                    'F j, Y' => '<span>' . $F_j_Y . '</span><code>F j, Y</code>',
                    'other' => '<span>' . __('Custom:', 'acf-rrule-field') . '</span>',
                ],
            ]);

            // Date return format
            acf_render_field_setting($field, [
                'label' => __('Date Return Format', 'acf-rrule-field'),
                'instructions' => __('The date format returned via template functions', 'acf-rrule-field'),
                'type' => 'radio',
                'name' => 'date_return_format',
                'other_choice' => 1,
                'choices' => [
                    'd/m/Y' => '<span>' . $d_m_Y . '</span><code>d/m/Y</code>',
                    'm/d/Y' => '<span>' . $m_d_Y . '</span><code>m/d/Y</code>',
                    'F j, Y' => '<span>' . $F_j_Y . '</span><code>F j, Y</code>',
                    'Ymd' => '<span>' . $Ymd . '</span><code>Ymd</code>',
                    'other' => '<span>' . __('Custom:', 'acf-rrule-field') . '</span>',
                ],
            ]);

            echo '</div>';

            echo '<div class="acf-field-settings-split">';

            // Allow time selector
            acf_render_field_setting($field, [
                'label' => __('Time Selector', 'acf-rrule-field'),
                'instructions' => __('Allow time selection when creating the recurring rule', 'acf-rrule-field'),
                'name' => 'allow_time',
                'type' => 'true_false',
                'ui' => 1,
            ]);

            echo '</div>';

            echo '<div class="acf-field-settings-split">';

            // Time display format
            acf_render_field_setting($field, [
                'label' => __('Time Display Format', 'acf-rrule-field'),
                'instructions' => __('The time format displayed when editing a post', 'acf-rrule-field'),
                'type' => 'radio',
                'name' => 'time_display_format',
                'other_choice' => 1,
                'choices' => [
                    'H:i' => '<span>' . $H_i . '</span><code>H:i</code>',
                    'g:i a' => '<span>' . $g_i_a . '</span><code>g:i a</code>',
                    'other' => '<span>' . __('Custom:', 'acf-rrule-field') . '</span>',
                ],
                'conditions' => [
                    'field' => 'allow_time',
                    'operator' => '==',
                    'value' => 1,
                ],
            ]);

            // Time return format
            acf_render_field_setting($field, [
                'label' => __('Time Return Format', 'acf-rrule-field'),
                'instructions' => __('The time format returned via template functions', 'acf-rrule-field'),
                'type' => 'radio',
                'name' => 'time_return_format',
                'other_choice' => 1,
                'choices' => [
                    'H:i' => '<span>' . $H_i . '</span><code>H:i</code>',
                    'g:i a' => '<span>' . $g_i_a . '</span><code>g:i a</code>',
                    'other' => '<span>' . __('Custom:', 'acf-rrule-field') . '</span>',
                ],
                'conditions' => [
                    'field' => 'allow_time',
                    'operator' => '==',
                    'value' => 1,
                ],
            ]);

            echo '</div>';
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

        public function render_field($field)
        {
            // Generate a unique ID for fields we don't want to be autocompleted
            $unique_id = $field['id'] . '-' . time();

            // Datepicker options
            $datepicker_options = [
                'class' => 'acf-date-picker acf-input-wrap',
                'data-date_format' => acf_convert_date_to_js($field['date_display_format']),
            ];
            $timepicker_options = [
                'class' => 'acf-time-picker acf-input-wrap',
                'data-time_format' => acf_convert_time_to_js($field['time_display_format']),
            ];

            include __DIR__ . '/../include/render.php';
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

        public function input_admin_enqueue_scripts()
        {
            // Bail early if no enqueue
            if (!acf_get_setting('enqueue_datepicker')) {
                return;
            }

            // Localize data
            global $wp_locale;

            acf_localize_data([
                'datePickerL10n' => [
                    'closeText' => _x('Done', 'Date Picker JS closeText', 'acf-rrule-field'),
                    'currentText' => _x('Today', 'Date Picker JS currentText', 'acf-rrule-field'),
                    'nextText' => _x('Next', 'Date Picker JS nextText', 'acf-rrule-field'),
                    'prevText' => _x('Prev', 'Date Picker JS prevText', 'acf-rrule-field'),
                    'weekHeader' => _x('Wk', 'Date Picker JS weekHeader', 'acf-rrule-field'),
                    'monthNames' => array_values($wp_locale->month),
                    'monthNamesShort' => array_values($wp_locale->month_abbrev),
                    'dayNames' => array_values($wp_locale->weekday),
                    'dayNamesMin' => array_values($wp_locale->weekday_initial),
                    'dayNamesShort' => array_values($wp_locale->weekday_abbrev),
                ],
            ]);

            // Enqueue scripts
            wp_enqueue_script('jquery-ui-datepicker');

            // Enqueue style
            wp_enqueue_style('acf-datepicker', acf_get_url('assets/inc/datepicker/jquery-ui.min.css'), [], '1.11.4');

            // Init vars
            $url = $this->settings['url'];
            $version = $this->settings['version'];

            // Register & include JS
            wp_enqueue_script('acf-rrule-field', "{$url}assets/js/input.js", ['acf-input'], $version);

            // Register & include CSS
            wp_enqueue_style('acf-rrule-field', "{$url}assets/css/input.css", ['acf-input'], $version);
        }

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

        public function load_value($value, $post_id, $field)
        {
            $new_value = [
                'rrule' => null,
                'start_date' => null,
                'start_time' => null,
                'frequency' => 'WEEKLY',
                'interval' => 1,
                'weekdays' => [],
                'monthdays' => [],
                'months' => [],
                'monthly_by' => 'monthdays',
                'bysetpos' => [],
                'byweekday' => [],
                'end_type' => null,
                'end_date' => null,
                'occurrence_count' => null,
                'dates_collection' => null,
                'text' => null,
            ];

            if ($value) {
                $timezoneString = get_option('timezone_string') ?: 'UTC';

                try {
                    $rule = new Rule($value);

                    $rule->setTimezone($timezoneString);

                    /**
                     * Ensure DTEND is reset if UNTIL exists.
                     *
                     * @see https://github.com/marcbelletre/acf-rrule/issues/23
                     */
                    if ($rule->getEndDate() == $rule->getUntil()) {
                        $rule->setEndDate(null);
                    }

                    $start_date = $rule->getStartDate();

                    $new_value['rrule'] = $rule->getString();
                    $new_value['start_date'] = $start_date ? $start_date->format('Ymd') : null;
                    $new_value['start_time'] = $start_date ? $start_date->format('H:i:s') : null;
                    $new_value['frequency'] = $rule->getFreqAsText();
                    $new_value['interval'] = $rule->getInterval();
                    $new_value['weekdays'] = $rule->getByDay() ?: [];
                    $new_value['monthdays'] = $rule->getByMonthDay() ?: [];
                    $new_value['months'] = $rule->getByMonth() ?: [];

                    if ($new_value['frequency'] === 'MONTHLY') {
                        if (count($new_value['weekdays']) > 0) {
                            $new_value['monthly_by'] = 'setpos';
                            $set_position = $rule->getBySetPosition();
                            $new_value['bysetpos'] = $set_position;
                            $new_value['byweekday'] = $new_value['weekdays'];
                        } else {
                            $new_value['monthly_by'] = 'monthdays';
                        }
                    }

                    $locale = explode('_', get_locale());

                    $transformer = new Recurr\Transformer\ArrayTransformer();
                    $textTransformer = new Recurr\Transformer\TextTransformer(
                        new Recurr\Transformer\Translator($locale[0])
                    );

                    $new_value['dates_collection'] = [];

                    foreach ($transformer->transform($rule) as $recurrence) {
                        $new_value['dates_collection'][] = $recurrence->getStart();
                    }

                    if ($rule->getUntil() || $rule->getEndDate()) {
                        $end_date = $rule->getUntil() ?: $rule->getEndDate();

                        $new_value['end_type'] = 'date';
                    } elseif ($rule->getCount()) {
                        $end_date = end($new_value['dates_collection']);

                        $new_value['end_type'] = 'count';
                        $new_value['occurrence_count'] = $rule->getCount();
                    } else {
                        $new_value['end_type'] = 'none';
                    }

                    $new_value['end_date'] = isset($end_date) ? $end_date->format('Ymd') : null;

                    $new_value['first_date'] = !empty($new_value['dates_collection']) ? $new_value['dates_collection'][0] : null;
                    $new_value['last_date'] = !$rule->repeatsIndefinitely() ? end($new_value['dates_collection']) : null;

                    $new_value['text'] = $textTransformer->transform($rule);
                } catch (Exception $e) {
                    //
                }
            } else {
                $new_value = false;
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

        public function update_value($value, $post_id, $field)
        {
            if (is_array($value)) {
                $timezoneString = get_option('timezone_string') ?: 'UTC';
                $timezone = new DateTimeZone($timezoneString);

                $start_date = DateTime::createFromFormat('Ymd', $value['start_date'], $timezone);

                // Bail early if the DateTime object is null
                if (!$start_date) {
                    return $value;
                }

                if (array_key_exists('start_time', $value)) {
                    $start_time = array_pad(explode(':', $value['start_time']), 3, 0);
                    $start_date->setTime(intval($start_time[0]), intval($start_time[1]), intval($start_time[2]));
                } else {
                    $start_date->setTime(0, 0, 0);
                }

                $rule = new Rule;

                $rule->setTimezone($timezoneString)
                    ->setStartDate($start_date, true)
                    ->setFreq($value['frequency'])
                    ->setInterval($value['interval']);

                switch ($value['frequency']) {
                    case 'WEEKLY':
                        // Bail early if weekdays are not set
                        if (!isset($value['weekdays'])) {
                            return $value;
                        }

                        $rule->setByDay($value['weekdays']);

                        break;

                    case 'MONTHLY':
                        // Bail early if monthly_by is not set
                        if (!isset($value['monthly_by'])) {
                            return $value;
                        }

                        if ($value['monthly_by'] == 'monthdays') {
                            // Bail early if monthdays are not set
                            if (!isset($value['monthdays'])) {
                                return $value;
                            }

                            $rule->setByMonthDay($value['monthdays']);
                        } else {
                            // Bail early if bysetpost & byweekday are not set
                            if (!(isset($value['bysetpos']) && isset($value['byweekday']))) {
                                return $value;
                            }

                            $rule->setBySetPosition($value['bysetpos']);
                            $rule->setByDay($value['byweekday']);
                        }

                        break;

                    case 'YEARLY':
                        // Bail early if months are not set
                        if (!isset($value['months'])) {
                            return $value;
                        }

                        $rule->setByMonth($value['months']);

                        break;

                    default:
                        break;
                }

                switch ($value['end_type']) {
                    case 'date':
                        if ($value['end_date']) {
                            $end_date = DateTime::createFromFormat('Ymd', $value['end_date'], $timezone);
                            $end_date->setTime(0, 0, 0);

                            $rule->setUntil($end_date);
                        }

                        break;
                    case 'count':
                        $rule->setCount($value['occurrence_count']);
                        break;
                    default:
                        break;
                }

                $new_value = $rule->getString();

                acf_update_value($new_value, $post_id, $field);

                return $new_value;
            }

            return $value;
        }

        /**
         * Validate the value. If this method returns TRUE, the input value is valid. If
         * FALSE or a string is returned, the input value is invalid and the user is shown a
         * notice. If a string is returned, the string is show as the message text.
         *
         * @param bool   $valid Whether the value is valid.
         * @param mixed  $value The field value.
         * @param array  $field The field array.
         * @param string $input The request variable name for the inbound field.
         *
         * @return bool|string
         */
        public function validate_value($valid, $value, $field, $input)
        {
            if ($field['required'] && !$value['start_date']) {
                return __('The start date is required.', 'acf-rrule-field');
            }

            // Validate only if the start date has been set
            if ($value['start_date']) {
                if ($value['end_type'] === 'date') {
                    if (!$value['end_date']) {
                        $valid = __('The end date is required.', 'acf-rrule-field');
                    } elseif ($value['end_date'] < $value['start_date']) {
                        $valid = __('The start date must be before the end date.', 'acf-rrule-field');
                    }
                }

                if ($value['frequency'] === 'WEEKLY' && !$value['weekdays']) {
                    $valid = __('Please select at least one weekday.', 'acf-rrule-field');
                } elseif ($value['frequency'] === 'MONTHLY') {
                    if ($value['monthly_by'] === 'monthdays' && !$value['monthdays']) {
                        $valid = __('Please select at least one monthday.', 'acf-rrule-field');
                    } elseif ($value['monthly_by'] === 'setpos' && !($value['bysetpos'] && $value['byweekday'])) {
                        $valid = __('Please select at least one weekday.', 'acf-rrule-field');
                    }
                } elseif ($value['frequency'] === 'YEARLY' && !$value['months']) {
                    $valid = __('Please select at least one month.', 'acf-rrule-field');
                }

                if ($value['interval'] < 1) {
                    $valid = __('The frequency must be greater than 1.', 'acf-rrule-field');
                }
            }

            return $valid;
        }
    }

    // Initialize RRule
    new acf_field_rrule($this->settings);

// class_exists check
endif;
