<?php

namespace AcfRRule;

use DateTime;
use Exception;
use InvalidArgumentException;
use function RRule\not_empty;

class RRule
    extends
    \RRule\RRule
{

    public function getByDay()
    {

        return $this->byweekday;
    }


    public function getByDayAsText()
    {

        return array_intersect_key( array_flip( self::$week_days ), array_flip( $this->byweekday ) );
    }


    /**
     * @return int[]|null
     */
    public function getByHour()
    {

        if ( ! empty( $this->byhour ) )
        {
            return $this->byhour;
        }

        if ( $this->freq < self::HOURLY )
        {
            return [
                $this->dtstart
                    ? (int) $this->dtstart->format( 'G' )
                    : 0,
            ];
        }

        return null;
    }


    /**
     * @return int[]|null
     */
    public function getByMinute()
    {

        if ( ! empty( $this->byminute ) )
        {
            return $this->byminute;
        }

        if ( $this->freq < self::MINUTELY )
        {
            return [
                $this->dtstart
                    ? (int) $this->dtstart->format( 'i' )
                    : 0,
            ];
        }

        return null;
    }


    public function getByMonth()
    {

        return $this->bymonth;
    }


    public function getByMonthDay()
    {

        return $this->bymonthday;
    }


    public function getBySecond()
    {

        return $this->bysecond
            ?: [
                $this->dtstart
                    ? (int) $this->dtstart->format( 's' )
                    : 0,
            ];
    }


    public function getBySetPosition()
    {

        return $this->bysetpos;
    }


    public function getCount()
    {

        return $this->count;
    }


    public function getFreq()
    {

        return $this->freq;
    }


    public function getFreqAsText()
    {

        return array_search( $this->freq, self::$frequencies, true );
    }


    // WKST

    public function getInterval()
    {

        return $this->interval;
    }


    /**
     * @return \DateTime|null
     */
    public function getStartDate()
    {

        return $this->dtstart;
    }


    public function getUntil()
    {

        return $this->until;
    }


    public function setByDay( $byDay )
    {

        if ( ! not_empty( $byDay ) )
        {
            $this->byweekday     = null;
            $this->byweekday_nth = null;

            return $this;
        }
        if ( ! is_array( $byDay ) )
        {
            $byDay = explode( ',', $byDay );
        }
        $new     = [];
        $new_nth = [];
        foreach ( $byDay as $value )
        {
            $value = strtoupper( trim( $value ) );
            $valid = preg_match( '/^([+-]?[0-9]+)?([A-Z]{2})$/', $value, $matches );
            if ( ! $valid
                || ( not_empty(
                        $matches[1]
                    )
                    && ( $matches[1] == 0 || $matches[1] > 53 || $matches[1] < - 53 ) )
                || ! array_key_exists( $matches[2], self::$week_days ) )
            {
                throw new InvalidArgumentException( 'Invalid BYDAY value: ' . $value );
            }

            if ( $matches[1] )
            {
                $new_nth[] = [
                    self::$week_days[ $matches[2] ],
                    (int) $matches[1],
                ];
            }
            else
            {
                $new[] = self::$week_days[ $matches[2] ];
            }
        }
        $this->byweekday     = &$new;
        $this->byweekday_nth = &$new_nth;

        return $this->validate();
    }


    /**
     * @param $byHour
     *
     * @return \RRule\RRule
     */
    public function setByHour( $byHour )
    {

        if ( ! not_empty( $byHour ) )
        {
            $this->byhour = null;

            return $this;
        }

        if ( ! is_array( $byHour ) )
        {
            $byHour = explode( ',', $byHour );
        }

        $new = [];
        foreach ( $byHour as $value )
        {
            if ( filter_var(
                    $value,
                    FILTER_VALIDATE_INT,
                    [
                        'options' => [
                            'min_range' => 0,
                            'max_range' => 23,
                        ],
                    ]
                ) === false )
            {
                throw new InvalidArgumentException( 'Invalid BYHOUR value: ' . $value );
            }
            $new[] = (int) $value;
        }

        $this->byhour = &$new;
        sort( $this->byhour );

        return $this->validate();
    }


    public function setByMinute( $byMinute )
    {

        if ( ! not_empty( $byMinute ) )
        {
            $this->byminute = null;

            return $this;
        }

        if ( ! is_array( $byMinute ) )
        {
            $byMinute = explode( ',', $byMinute );
        }

        $new = [];
        foreach ( $byMinute as $value )
        {
            if ( filter_var(
                    $value,
                    FILTER_VALIDATE_INT,
                    [
                        'options' => [
                            'min_range' => 0,
                            'max_range' => 59,
                        ],
                    ]
                ) === false )
            {
                throw new InvalidArgumentException( 'Invalid BYMINUTE value: ' . $value );
            }
            $new[] = (int) $value;
        }
        $this->byminute = &$new;
        sort( $this->byminute );

        return $this->validate();
    }


    /**
     * The BYMONTH rule part specifies a COMMA-separated list of months
     * of the year.  Valid values are 1 to 12.
     *
     * @param $byMonth
     *
     * @return $this|\AcfRRule\RRule
     */
    public function setByMonth( $byMonth )
    {

        if ( ! not_empty( $byMonth ) )
        {
            $this->bymonth = null;

            return $this;
        }

        if ( ! is_array( $byMonth ) )
        {
            $byMonth = explode( ',', $byMonth );
        }

        $new = [];
        foreach ( $byMonth as $value )
        {
            if ( filter_var(
                    $value,
                    FILTER_VALIDATE_INT,
                    [
                        'options' => [
                            'min_range' => 1,
                            'max_range' => 12,
                        ],
                    ]
                ) === false )
            {
                throw new InvalidArgumentException( 'Invalid BYMONTH value: ' . $value );
            }
            $new[] = (int) $value;
        }
        $this->bymonth = &$new;

        return $this;
    }


    /**
     * The BYMONTHDAY rule part specifies a COMMA-separated list of days
     * of the month.  Valid values are 1 to 31 or -31 to -1.  For
     * example, -10 represents the tenth to the last day of the month.
     * The BYMONTHDAY rule part MUST NOT be specified when the FREQ rule
     * part is set to WEEKLY.
     *
     * @param $byMonthDay string[]|string|null
     */
    public function setByMonthDay( $byMonthDay )
    {

        if ( ! not_empty( $byMonthDay ) )
        {
            $this->bymonthday          = null;
            $this->bymonthday_negative = null;

            return $this;
        }

        if ( $this->freq === self::WEEKLY )
        {
            throw new InvalidArgumentException(
                'The BYMONTHDAY rule part MUST NOT be specified when the FREQ rule part is set to WEEKLY.'
            );
        }

        if ( ! is_array( $byMonthDay ) )
        {
            $byMonthDay = explode( ',', $byMonthDay );
        }

        $new          = [];
        $new_negative = [];
        foreach ( $byMonthDay as $value )
        {
            if ( ! $value
                || filter_var(
                    $value,
                    FILTER_VALIDATE_INT,
                    [
                        'options' => [
                            'min_range' => - 31,
                            'max_range' => 31,
                        ],
                    ]
                ) === false )
            {
                throw new InvalidArgumentException(
                    'Invalid BYMONTHDAY value: ' . $value . ' (valid values are 1 to 31 or -31 to -1)'
                );
            }
            $value = (int) $value;
            if ( $value < 0 )
            {
                $new_negative[] = $value;
            }
            else
            {
                $new[] = $value;
            }
        }
        $this->bymonthday          = &$new;
        $this->bymonthday_negative = &$new_negative;

        return $this;
    }


    public function setBySecond( $bySecond )
    {

        if ( ! not_empty( $bySecond ) )
        {
            $this->bysecond = null;

            return $this;
        }

        if ( ! is_array( $bySecond ) )
        {
            $bySecond = explode( ',', $bySecond );
        }

        $new = [];
        foreach ( $bySecond as $value )
        {
            // yes, "60" is a valid value, in (very rare) cases on leap seconds
            //  December 31, 2005 23:59:60 UTC is a valid date...
            // so is 2012-06-30T23:59:60UTC
            if ( filter_var(
                    $value,
                    FILTER_VALIDATE_INT,
                    [
                        'options' => [
                            'min_range' => 0,
                            'max_range' => 60,
                        ],
                    ]
                ) === false )
            {
                throw new InvalidArgumentException( 'Invalid BYSECOND value: ' . $value );
            }
            $new[] = (int) $value;
        }
        $this->bysecond = &$new;
        sort( $this->bysecond );

        return $this->validate();
    }


    /**
     * @param $bySetPos int[]|int|string|null Array or comma-separated list of days of year (valid values are 1 to 366
     *                  or -366 to -1)
     *
     * @return \RRule\RRule
     */
    public function setBySetPos( $bySetPos )
    {

        if ( ! not_empty( $bySetPos ) )
        {
            $this->bysetpos = null;

            return $this;
        }

        if ( ! ( not_empty( $this->byweekno ) || not_empty( $this->byyearday )
            || not_empty( $this->bymonthday )
            || not_empty( $this->byweekday )
            || not_empty( $this->byweekday_nth )
            || not_empty( $this->bymonth )
            || not_empty( $this->byhour )
            || not_empty( $this->byminute )
            || not_empty( $this->bysecond ) ) )
        {
            throw new InvalidArgumentException(
                'The BYSETPOS rule part MUST only be used in conjunction with another BYxxx rule part.'
            );
        }

        $new            = $this->parseYearDay( $bySetPos );
        $this->bysetpos = &$new;

        return $this;
    }


    public function setByWeeNo( $byWeekNo )
    {

        if ( ! not_empty( $byWeekNo ) )
        {
            $this->byweekno = null;

            return $this;
        }

        if ( $this->freq !== self::YEARLY )
        {
            throw new InvalidArgumentException(
                'The BYWEEKNO rule part MUST NOT be used when the FREQ rule part is set to anything other than YEARLY.'
            );
        }

        if ( ! is_array( $byWeekNo ) )
        {
            $byWeekNo = explode( ',', $byWeekNo );
        }

        $new = [];
        foreach ( $byWeekNo as $value )
        {
            if ( ! $value
                || filter_var(
                    $value,
                    FILTER_VALIDATE_INT,
                    [
                        'options' => [
                            'min_range' => - 53,
                            'max_range' => 53,
                        ],
                    ]
                ) === false )
            {
                throw new InvalidArgumentException(
                    'Invalid BYWEEKNO value: ' . $value . ' (valid values are 1 to 53 or -53 to -1)'
                );
            }
            $new[] = (int) $value;
        }
        $this->byweekno = &$new;

        return $this->validate();
    }


    public function setByYearDay( $byYearDay )
    {

        if ( ! not_empty( $byYearDay ) )
        {
            $this->byyearday = null;

            return $this;
        }

        if ( $this->freq === self::DAILY || $this->freq === self::WEEKLY || $this->freq === self::MONTHLY )
        {
            throw new InvalidArgumentException(
                'The BYYEARDAY rule part MUST NOT be specified when the FREQ rule part is set to DAILY, WEEKLY, or MONTHLY.'
            );
        }

        $new             = $this->parseYearDay( $byYearDay );
        $this->byyearday = &$new;

        return $this->validate();
    }


    public function setCount( $count )
    {

        if ( not_empty( $count ) )
        {
            if ( filter_var( $count, FILTER_VALIDATE_INT, [ 'options' => [ 'min_range' => 1 ] ] ) === false )
            {
                throw new InvalidArgumentException( 'COUNT must be a positive integer (> 0)' );
            }
            $this->count = (int) $count;
        }

        return $this->validate();
    }


    public function setFreq( $frequency )
    {

        if ( is_numeric( $frequency ) && in_array( (int) $frequency, self::$frequencies, true ) )
        {
            $this->freq = $frequency;
        }
        else
        { // string
            $frequency = strtoupper( $frequency );
            if ( ! array_key_exists( $frequency, self::$frequencies ) )
            {
                throw new InvalidArgumentException(
                    'The FREQ rule part must be one of the following: '
                    . implode( ', ', array_keys( self::$frequencies ) )
                );
            }
            $this->freq = self::$frequencies[ $frequency ];
        }

        switch ( $this->freq )
        {
        case self::YEARLY:
            $this->setByDay( null );
            $this->setByWeeNo( null );
            break;

        case self::MONTHLY:
            $this->setByDay( null );
            break;

        case self::WEEKLY:
            $this->setByMonthDay( null );
            break;
        }

        return $this->validate();
    }


    public function setInterval( $interval )
    {

        if ( filter_var( $interval, FILTER_VALIDATE_INT, [ 'options' => [ 'min_range' => 1 ] ] ) === false )
        {
            throw new InvalidArgumentException(
                'The INTERVAL rule part must be a positive integer (> 0)'
            );
        }
        $this->interval = (int) $interval;

        return $this;
    }


    public function setStart( $dtstart )
    {

        if ( not_empty( $dtstart ) )
        {
            try
            {
                $this->dtstart = self::parseDate( $dtstart );
            }
            catch ( Exception $e )
            {
                throw new InvalidArgumentException(
                    'Failed to parse DTSTART ; it must be a valid date, timestamp or \DateTime object'
                );
            }
        }
        else
        {
            $this->dtstart = new DateTime(); // for PHP 7.1+ this contains microseconds which causes many problems
            if ( version_compare( PHP_VERSION, '7.1.0' ) >= 0 )
            {
                // remove microseconds
                $this->dtstart->setTime(
                    $this->dtstart->format( 'H' ),
                    $this->dtstart->format( 'i' ),
                    $this->dtstart->format( 's' ),
                    0
                );
            }
        }

        return $this;
    }


    public function setUntil( $until )
    {

        if ( not_empty( $until ) )
        {
            try
            {
                $this->until = self::parseDate( $until );
            }
            catch ( Exception $e )
            {
                throw new InvalidArgumentException(
                    'Failed to parse UNTIL ; it must be a valid date, timestamp or \DateTime object'
                );
            }
        }

        return $this->validate();
    }


    public function setWkst( $wkst )
    {

        if ( ! not_empty( $wkst ) )
        {
            $this->wkst = null;

            return $this;
        }

        $wkst = strtoupper( $wkst );
        if ( ! array_key_exists( $wkst, self::$week_days ) )
        {
            throw new InvalidArgumentException(
                'The WKST rule part must be one of the following: '
                . implode( ', ', array_keys( self::$week_days ) )
            );
        }
        $this->wkst = self::$week_days[ $wkst ];

        return $this;
    }


    /**
     * @param $byYearDay
     *
     * @return array
     */
    public function &parseYearDay( $byYearDay )
    {

        if ( ! is_array( $byYearDay ) )
        {
            $byYearDay = explode( ',', $byYearDay );
        }

        $new = [];
        foreach ( $byYearDay as $value )
        {
            if ( ! $value
                || filter_var(
                    $value,
                    FILTER_VALIDATE_INT,
                    [
                        'options' => [
                            'min_range' => - 366,
                            'max_range' => 366,
                        ],
                    ]
                ) === false )
            {
                throw new InvalidArgumentException(
                    'Invalid BYSETPOS value: ' . $value . ' (valid values are 1 to 366 or -366 to -1)'
                );
            }

            $new[] = (int) $value;
        }

        return $new;
    }


    public function validate()
    {

        if ( $this->until && $this->count )
        {
            throw new InvalidArgumentException( 'The UNTIL or COUNT rule parts MUST NOT occur in the same rule' );
        }

        if ( ! empty( $this->byweekday_nth ) )
        {
            if ( ! ( $this->freq === self::MONTHLY || $this->freq === self::YEARLY ) )
            {
                throw new InvalidArgumentException(
                    'The BYDAY rule part MUST NOT be specified with a numeric value when the FREQ rule part is not set to MONTHLY or YEARLY.'
                );
            }
            if ( $this->freq === self::YEARLY && not_empty( $this->byweekno ) )
            {
                throw new InvalidArgumentException(
                    'The BYDAY rule part MUST NOT be specified with a numeric value with the FREQ rule part set to YEARLY when the BYWEEKNO rule part is specified.'
                );
            }
        }

        if ( $this->freq < self::HOURLY )
        {
            // for frequencies DAILY, WEEKLY, MONTHLY AND YEARLY, we can build
            // an array of every time of the day at which there should be an
            // occurrence - default, if no BYHOUR/BYMINUTE/BYSECOND are provided
            // is only one time, and it's the DTSTART time. This is a cached version
            // if you will, since it'll never change at these frequencies
            $this->timeset = [];
            foreach ( $this->getByHour() as $hour )
            {
                foreach ( $this->getByMinute() as $minute )
                {
                    foreach ( $this->getBySecond() as $second )
                    {
                        $this->timeset[] = [
                            $hour,
                            $minute,
                            $second,
                        ];
                    }
                }
            }
        }

        return $this;
    }

}