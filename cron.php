<?php
/**
 * Created by PhpStorm.
 * User: furbi
 * Date: 16/03/2018
 * Time: 18:41
 */

namespace SC\WPSH;


class SC_MySqlSessionCron
{
    public static function init() {
        add_action('sc_mysqlsession_cron_hook', 'bitech_cron_job');
    }
    public static function cron_set() {
        wp_schedule_event(time(), 'hourly', 'sc_mysqlsession_cron_hook');
    }

    public static function cron_unset() {
        wp_clear_scheduled_hook('sc_mysqlsession_cron_hook');
    }

    public static function cron_job() {
        $frequency = get_option('sc_session_handler_clean_every', '0');

        print_r($frequency);
        if ($frequency > 0) {
            SC_MySqlSessionHandler::cron_delete();
        }
    }

}