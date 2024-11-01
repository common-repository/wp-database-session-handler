<?php
/*
Plugin Name: WP Database Session Handler
Plugin URI: http://www.stefanocanziani.com/wp_plugin/sc-session-handler.zip
Description: Add handling of user session inside SQL Database. This session manager is useful if you have an environment with more than one frontend server and a load balancer who switch the web traffic dinamically between frontend hosts.
Version: 1.0.1
Author: Stefano Canziani
Author URI: http://www.stefanocanziani.com
License: GPLv2 or later
Text Domain: wp-database-session-handler
Domain Path: /languages
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2018-2020 Stefano Canziani.
*/


// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}
// define versione
define( 'SC_DB_SESSION_VERSION', '1.0.0' );
define( 'SC_DB_SESSION__MINIMUM_WP_VERSION', '4.0' );
define( 'SC_DB_SESSION__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// define textdomain
load_plugin_textdomain( 'wp-database-session-handler' );

// include plugin files
include_once( plugin_dir_path( __FILE__ ) . '/admin.php');
include_once( plugin_dir_path( __FILE__ ) . '/session_handler.php');
include_once( plugin_dir_path( __FILE__ ) . '/cron.php');

// check class defined
if (!class_exists('SC\WPSH\SC_MySqlSessionHandler')) {
    exit('WP Database Session Handler requires WPSH class');
}
$session_handler =  new SC\WPSH\SC_MySqlSessionHandler();
$session_cron =  new SC\WPSH\SC_MySqlSessionCron();


// plugin activation
register_activation_hook( __FILE__, array($session_handler,'create_table') );
register_activation_hook( __FILE__,  array($session_cron,'cron_set') );
// plugin deactivation
register_deactivation_hook( __FILE__, array($session_handler,'delete_table') );
register_deactivation_hook( __FILE__,  array($session_cron,'cron_unset') );

// init session handler plugin
$session_handler::init();


// init admin menu
if (is_admin()) {
    add_action( 'admin_menu', function () {
        add_options_page(
             __( 'DB Session Handler settings', 'wp-database-session-handler' ),
             __( 'DB Session Handler', 'wp-database-session-handler' ),
            'manage_options',
            'sc_mysqlsessionadmin',
            'SC\WPSH\SC_MySqlSessionAdmin::sc_settings_page'
        );
    } );
}

// activate cron job
add_action('sc_mysqlsession_cron_hook', 'SC\WPSH\SC_MySqlSessionCron::cron_job');
