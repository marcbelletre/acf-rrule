<div class="acf-input-wrap">
    <input type="hidden" name="<?= $field['name'] ?>">

    <?php if ($field['value'] && $field['value']['text']) : ?>
        <p class="acf-field-rrule-current">
            <?php _e('Current value:', 'acf-rrule-field'); ?>
            <?= $field['value']['text'] ?>
        </p>
    <?php endif; ?>

    <div class="acf-field-rrule-sub-fields">
        <div class="acf-field">
            <div class="acf-columns">
                <div class="acf-column">
                    <div class="acf-field acf-field-date-picker" data-type="date_picker">
                        <div <?php echo acf_esc_attrs($datepicker_options); ?>>
                            <?php
                            $start_date_hidden = '';
                            $start_date_display = '';

                            // Format values
                            if ($field['value']) {
                                $start_date_hidden = acf_format_date($field['value']['start_date'], 'Ymd');
                                $start_date_display = acf_format_date($field['value']['start_date'], $field['date_display_format']);
                            } ?>

                            <div class="acf-label">
                                <label for="<?= $unique_id ?>-start-date">
                                    <?php _e('Start date', 'acf-rrule-field'); ?>
                                </label>
                            </div>

                            <?php acf_hidden_input([
                                'name' => $field['name'] . '[start_date]',
                                'value' => $start_date_hidden,
                            ]); ?>
                            <?php acf_text_input([
                                'id' => $unique_id . '-start-date',
                                'class' => 'input',
                                'value' => $start_date_display,
                            ]); ?>
                        </div>
                    </div>
                </div>

                <?php if ($field['allow_time']) : ?>
                    <div class="acf-column">
                        <div class="acf-field acf-field-time-picker" data-type="time_picker">
                            <div <?php echo acf_esc_attrs($timepicker_options); ?>>
                                <?php $start_time = $field['value'] ? acf_format_date($field['value']['start_time'], $field['time_display_format']) : ''; ?>

                                <div class="acf-label">
                                    <label for="<?= $unique_id ?>-start-time">
                                        <?php _e('Start time', 'acf-rrule-field'); ?>
                                    </label>
                                </div>

                                <?php acf_hidden_input([
                                    'name' => $field['name'] . '[start_time]',
                                    'value' => $start_time,
                                ]); ?>
                                <?php acf_text_input([
                                    'id' => $unique_id . '-start-time',
                                    'class' => 'input',
                                    'value' => $start_time,
                                ]); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="acf-field">
            <?php
            $frequency = [
                'id' => $field['id'] . '-frequency',
                'name' => $field['name'] . '[frequency]',
                'value' => is_array($field['value']) ? $field['value']['frequency'] : null,
                'class' => 'frequency-select',
                'choices' => [
                    'DAILY' => __('Daily', 'acf-rrule-field'),
                    'WEEKLY' => __('Weekly', 'acf-rrule-field'),
                    'MONTHLY' => __('Monthly', 'acf-rrule-field'),
                    'YEARLY' => __('Yearly', 'acf-rrule-field'),
                ],
            ];
            ?>

            <div class="acf-label">
                <label for="<?= $frequency['id'] ?>">
                    <?php _e('Frequency', 'acf-rrule-field'); ?>
                </label>
            </div>

            <div class="acf-input">
                <?php acf_select_input($frequency); ?>
            </div>
        </div>

        <div class="acf-field">
            <?php
            $interval = [
                'id' => $field['id'] . '-interval',
                'name' => $field['name'] . '[interval]',
                'type' => 'number',
                'class' => 'acf-is-prepended acf-is-appended',
                'value' => is_array($field['value']) && $field['value']['interval'] ? $field['value']['interval'] : 1,
                'min' => 1,
                'step' => 1,
            ];
            ?>

            <div class="acf-input">
                <div class="acf-input-prepend">
                    <?= _x('Every', 'RRule Interval', 'acf-rrule-field') ?>
                </div>
                <div class="acf-input-append">
                    <span class="freq-suffix" data-frequency="DAILY"><?= _x('day', 'RRule Interval', 'acf-rrule-field') ?></span>
                    <span class="freq-suffix" data-frequency="WEEKLY"><?= _x('week', 'RRule Interval', 'acf-rrule-field') ?></span>
                    <span class="freq-suffix" data-frequency="MONTHLY"><?= _x('month', 'RRule Interval', 'acf-rrule-field') ?></span>
                    <span class="freq-suffix" data-frequency="YEARLY"><?= _x('year', 'RRule Interval', 'acf-rrule-field') ?></span>
                </div>
                <div class="acf-input-wrap">
                    <?php acf_text_input($interval); ?>
                </div>
            </div>
        </div>

        <div class="acf-field acf-field-button-group" data-type="button_group_multiple" data-frequency="WEEKLY">
            <?php
            $weekdays = [
                'MO' => __('Monday', 'acf-rrule-field'),
                'TU' => __('Tuesday', 'acf-rrule-field'),
                'WE' => __('Wednesday', 'acf-rrule-field'),
                'TH' => __('Thursday', 'acf-rrule-field'),
                'FR' => __('Friday', 'acf-rrule-field'),
                'SA' => __('Saturday', 'acf-rrule-field'),
                'SU' => __('Sunday', 'acf-rrule-field'),
            ];
            ?>

            <div class="acf-label">
                <label>
                    <?php _e('Week days', 'acf-rrule-field'); ?>
                </label>
            </div>

            <div class="acf-input">
                <div class="acf-button-group">
                    <?php foreach ($weekdays as $key => $value) : ?>
                        <?php $selected = is_array($field['value']) && in_array($key, $field['value']['weekdays']); ?>

                        <label class="<?= $selected ? 'selected' : '' ?>">
                            <input type="checkbox" name="<?= $field['name'] ?>[weekdays][]" value="<?= $key ?>" <?= ($selected ? ' checked' : '') ?> />
                            <?= $value ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div id="<?= $field['id'] ?>-monthly-by" class="acf-field monthly-by-options" data-frequency="MONTHLY">
            <div class="acf-columns">
                <div class="acf-column">
                    <div class="acf-label is-inline">
                        <input id="acf-<?= $field['name'] ?>-bymonthdays" type="radio" name="<?= $field['name'] ?>[monthly_by]" value="monthdays" <?= (is_array($field['value']) && $field['value']['monthly_by'] === 'monthdays' ? ' checked' : '') ?> />
                        <label for="acf-<?= $field['name'] ?>-bymonthdays">
                            <?php _e('Month days', 'acf-rrule-field'); ?>
                        </label>
                    </div>

                    <?php $days = range(1, 31); ?>

                    <div class="acf-input<?= is_array($field['value']) && $field['value']['monthly_by'] !== 'monthdays' ? ' is-disabled' : '' ?>" data-monthly-by="monthdays">
                        <table class="acf-rrule-monthdays">
                            <?php foreach (array_chunk($days, 7) as $week) : ?>
                                <tr>
                                    <?php foreach ($week as $day) : ?>
                                        <?php $selected = is_array($field['value']) && in_array($day, $field['value']['monthdays']); ?>

                                        <td>
                                            <input id="acf-<?= $field['name'] ?>-monthdays-<?= $day ?>" type="checkbox" name="<?= $field['name'] ?>[monthdays][]" value="<?= $day ?>" <?= ($selected ? ' checked' : '') ?> />
                                            <label for="acf-<?= $field['name'] ?>-monthdays-<?= $day ?>"><?= $day ?></label>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>

                <div class="acf-column">
                    <div class="acf-label is-inline">
                        <input id="acf-<?= $field['name'] ?>-setpos" type="radio" name="<?= $field['name'] ?>[monthly_by]" value="setpos" <?= (is_array($field['value']) && $field['value']['monthly_by'] === 'setpos' ? ' checked' : '') ?> />
                        <label for="acf-<?= $field['name'] ?>-setpos">
                            <?php _e('Weekdays', 'acf-rrule-field'); ?>
                        </label>
                    </div>

                    <div class="acf-input<?= (is_array($field['value']) && $field['value']['monthly_by'] !== 'setpos' ? ' is-disabled' : '') ?>" data-monthly-by="setpos">
                        <div class="acf-columns">
                            <div class="acf-column">
                                <ul class="acf-checkbox-list">
                                    <?php
                                    $bysetpos = [
                                        '1' => __('First', 'acf-rrule-field'),
                                        '2' => __('Second', 'acf-rrule-field'),
                                        '3' => __('Third', 'acf-rrule-field'),
                                        '4' => __('Fourth', 'acf-rrule-field'),
                                        '5' => __('Fifth', 'acf-rrule-field'),
                                        '-1' => __('Last', 'acf-rrule-field'),
                                    ];
                                    ?>

                                    <?php foreach ($bysetpos as $key => $value) : ?>
                                        <?php
                                        $selected = false;

                                        if (is_array($field['value']) && is_array($field['value']['bysetpos'])) {
                                            $selected = in_array((string) $key, $field['value']['bysetpos']);
                                        }
                                        ?>

                                        <li>
                                            <label class="<?= $selected ? 'selected' : '' ?>">
                                                <input type="checkbox" id="<?= $field['id'] ?>-bysetpos-<?= $key ?>" name="<?= $field['name'] ?>[bysetpos][]" value="<?= $key ?>" <?= ($selected ? ' checked' : '') ?> />
                                                <?php _e($value, 'acf-rrule-field'); ?>
                                            </label>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <div class="acf-column">
                                <ul class="acf-checkbox-list">
                                    <?php foreach ($weekdays as $key => $value) : ?>
                                        <?php $selected = is_array($field['value']) && in_array($key, $field['value']['weekdays']); ?>

                                        <li>
                                            <label class="<?= $selected ? 'selected' : '' ?>">
                                                <input type="checkbox" id="<?= $field['id'] ?>-byweekday-<?= $key ?>" name="<?= $field['name'] ?>[byweekday][]" value="<?= $key ?>" <?= ($selected ? ' checked' : '') ?> />
                                                <?= $value ?>
                                            </label>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="acf-field acf-field-checkbox" data-type="checkbox" data-key="<?= $field['key'] ?>" data-frequency="YEARLY">
            <div class="acf-label">
                <label><?php _e('Month', 'acf-rrule-field'); ?></label>
            </div>

            <div class="acf-input">
                <?php
                $months = [
                    '1' => __('January', 'acf-rrule-field'),
                    '2' => __('February', 'acf-rrule-field'),
                    '3' => __('March', 'acf-rrule-field'),
                    '4' => __('April', 'acf-rrule-field'),
                    '5' => __('May', 'acf-rrule-field'),
                    '6' => __('June', 'acf-rrule-field'),
                    '7' => __('July', 'acf-rrule-field'),
                    '8' => __('August', 'acf-rrule-field'),
                    '9' => __('September', 'acf-rrule-field'),
                    '10' => __('October', 'acf-rrule-field'),
                    '11' => __('November', 'acf-rrule-field'),
                    '12' => __('December', 'acf-rrule-field'),
                ];
                ?>

                <input type="hidden" name="<?= $field['name'] ?>[months]">

                <ul class="acf-checkbox-list acf-hl">
                    <?php foreach ($months as $key => $month) : ?>
                        <?php $selected = is_array($field['value']) && in_array($key, $field['value']['months']); ?>

                        <li>
                            <label class="<?= $selected ? 'selected' : '' ?>">
                                <input type="checkbox" id="acf-<?= $field['name'] ?>-months-<?= $key ?>" name="<?= $field['name'] ?>[months][]" value="<?= $key ?>" <?= ($selected ? ' checked' : '') ?> />
                                <?= $month ?>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="acf-field">
            <div class="acf-label">
                <label for="<?= $field['id'] ?>-end-type">
                    <?php _e('End date', 'acf-rrule-field'); ?>
                </label>
            </div>
            <div class="acf-input">
                <?php acf_select_input([
                    'id' => $field['id'] . '-end-type',
                    'name' => $field['name'] . '[end_type]',
                    'value' => $field['value'] ? $field['value']['end_type'] : null,
                    'class' => 'end-type-select',
                    'choices' => [
                        'date' => __('At a specific date', 'acf-rrule-field'),
                        'count' =>  __('After a number of occurrences', 'acf-rrule-field'),
                        'none' => __('Never', 'acf-rrule-field'),
                    ],
                ]); ?>
            </div>
        </div>

        <div class="acf-field" data-end-type="count">
            <?php
            $occurrence_count = [
                'id' => $field['id'] . '-occurrence-count',
                'name' => $field['name'] . '[occurrence_count]',
                'type' => 'number',
                'class' => 'acf-is-prepended acf-is-appended',
                'value' => is_array($field['value']) ? $field['value']['occurrence_count'] : null,
                'min' => 1,
                'step' => 1,
            ];
            ?>

            <div class="acf-input">
                <div class="acf-input-prepend">
                    <?php _e('After', 'acf-rrule-field'); ?>
                </div>
                <div class="acf-input-append">
                    <?php _e('occurrence(s)', 'acf-rrule-field'); ?>
                </div>
                <div class="acf-input-wrap">
                    <?php acf_text_input($occurrence_count); ?>
                </div>
            </div>
        </div>

        <div class="acf-field acf-field-date-picker" data-type="date_picker" data-end-type="date">
            <div <?php echo acf_esc_attrs($datepicker_options); ?>>
                <?php
                $end_date_hidden = '';
                $end_date_display = '';

                // Format values
                if ($field['value']) {
                    $end_date_hidden = acf_format_date($field['value']['end_date'], 'Ymd');
                    $end_date_display = acf_format_date($field['value']['end_date'], $field['date_display_format']);
                }
                ?>

                <div class="acf-input">
                    <div class="acf-input-prepend">
                        <?php _e('Until', 'acf-rrule-field'); ?>
                    </div>
                    <div class="acf-input-wrap">
                        <?php acf_hidden_input([
                            'name' => $field['name'] . '[end_date]',
                            'value' => $end_date_hidden,
                        ]); ?>
                        <?php acf_text_input([
                            'id' => $unique_id . '-end-date',
                            'class' => 'acf-is-prepended',
                            'value' => $end_date_display,
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
