<?php

namespace SC\WPSH;


class SC_MySqlSessionAdmin
{

    public static function sc_settings_page() {
        if( !current_user_can( 'manage_options' ) ) {

            wp_die( __( 'Your permissions are not sufficient to view the page', 'wp-database-session-handler' ) );

        }


        if ($_REQUEST['update_options']) {
            if ($_REQUEST['clean_on_open'])
                update_option('sc_session_handler_clean_on_open', '1', 'yes');
            else
                delete_option("sc_session_handler_clean_on_open");
            if ($_REQUEST['clean_on_gc'])
                update_option('sc_session_handler_clean_on_gc', '1', 'yes');
            else
                delete_option("sc_session_handler_clean_on_gc");
            if ($_REQUEST['clean_every']>0)
                update_option('sc_session_handler_clean_every', $_REQUEST['clean_every'], 'yes');
            else
                delete_option("sc_session_handler_clean_every");
        }
        if ($_REQUEST['clean_all']) {
            SC_MySqlSessionHandler::destroy_all();
            _e( 'Cleaned all sessions.', 'wp-database-session-handler' );
        }

        require( 'inc/admin_page.php' );


        if ($_REQUEST['view_all']) {
            $rows = SC_MySqlSessionHandler::sessions_content();
            if ($rows) {
                if ($rows->num_rows && $rows->num_rows > 0) {

                    echo '<table width="100%">';
                    echo '<thead><tr><td>'.__( 'ID', 'wp-database-session-handler' ).'</td><td>&nbsp;</td><td>'.__( 'CONTENT', 'wp-database-session-handler' ).'</td><td>'.__( 'IP', 'wp-database-session-handler' ).'</td><td>'.__( 'LAST UPDATE', 'wp-database-session-handler' ).'</td></tr></thead>';
                    echo '<tbody>';
                    while ($obj = $rows->fetch_object()) {
                        printf ("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>\n", $obj->id, ($obj->id == session_id() ? ' <- you' : '&nbsp;' ), $obj->data, $obj->ip, date('m/d/Y H:i:s',$obj->ts));
                    }
                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo 'empty result';
                }
            } else {
                echo 'errore getting result';
            }
        }

    }
}