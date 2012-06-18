<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package Maintenance
 *
 * @author      Michael Fielding
 * @copyright   (c) 2012 Michael Fielding
 * @license     http://www.opensource.org/licenses/MIT
 */
class Kohana_Maintenance
{
    /**
     * Check if we're in a maintenance window and throw a 503 error if we are.
     *
     * Maintenance windows are configured in config/maintenance.php.
     *
     * @param string $display_timezone If this is null, then UTC is used, otherwise
     *      a string timezone name to use when formatting the beginning and end
     *      of the maintenance window for display.
     * @param string $display_format Sets the date/time format used for displaying the
     *      beginning and end of the maintenance window.
     * @throws HTTP_Exception_503
     */
    static public function check(
            $display_timezone = null,
            $display_format = 'Y-m-d H:i e' )
    {
        $windows = self::get_windows();

        $now = new DateTime();
        foreach ( $windows as $window )
        {
            $from = new DateTime( $window['from'] );
            $to = new DateTime( $window['to'] );

            if ( $from <= $now && $to >= $now )
            {
                $display_timezone_obj = new DateTimeZone($display_timezone==null?'UTC':$display_timezone);
                $from->setTimezone( $display_timezone_obj );
                $to->setTimezone( $display_timezone_obj );

                throw new HTTP_Exception_503($window['message'], array(
                    ':from' => $from->format( $display_format ),
                    ':to' => $to->format( $display_format ),
                ));
            }
        }
    }

    /**
     * Get all the currently-configured maintenance windows from the config
     * file.
     *
     * @return array Array of arrays with entries 'from', 'to' and 'message'.
     *      Each entry is a string.
     */
    static public function get_windows( )
    {
        return Kohana::config('maintenance.windows');
    }
}
