<?php
if (! function_exists('humanDate')) {
    /**
     * Format date.
     * @param $date
     * @param bool $short
     * @return string
     */
    function humanDate($date, $short = false)
    {
        if( ! $date)
            return null;

        if(is_string($date))
            $date = \Carbon\Carbon::parse($date);

        $format = 'd.m.Y H:i:s';
        if($short)
            $format = 'd.m.Y';

        return $date->format($format);
    }
}