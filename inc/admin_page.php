<div class="wrap">

    <div id="icon-options-general" class="icon32"></div>
    <h2><?_e( 'WP Database Session Handler settings', 'wp-database-session-handler' )?></h2>

    <div id="poststuff">

        <div id="post-body" class="metabox-holder columns-2">

            <!-- main content -->
            <div id="post-body-content">

                <div class="meta-box-sortables ui-sortable">

                    <div class="postbox">

                        <h3><span><?_e( 'Options', 'wp-database-session-handler' )?></span></h3>
                        <div class="inside">

                            <form method="post" action="">

                                <table class="form-table">
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="clean_on_open" id="clean_on_open" value="1" <?=( get_option('sc_session_handler_clean_on_open', '0')==1 ? 'checked' : '')?>>

                                        </td>
                                        <td>
                                            <label for="clean_on_open"><?_e( 'Clean expired sessions on open new session', 'wp-database-session-handler' )?></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="clean_on_gc" id="clean_on_gc" value="1" <?=( get_option('sc_session_handler_clean_on_gc', '0')==1 ? 'checked' : '')?>>

                                        </td>
                                        <td>
                                            <label for="clean_on_gc"><?_e( 'Clean expired sessions on garbage collection', 'wp-database-session-handler' )?></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="number" name="clean_every" id="clean_every" min="0" max="99999" size="4" value="<?=get_option('sc_session_handler_clean_every', '0')?>">

                                        </td>
                                        <td>
                                            <label for="clean_every"><?_e( 'Clean expired sessions every N hours. 0 for disable', 'wp-database-session-handler' )?></label>
                                        </td>
                                    </tr>
                                </table>

                                <p>
                                    <input class="button-primary" type="submit" name="update_options" value="<?_e( 'Save', 'wp-database-session-handler' )?>" />
                                </p>

                            </form>

                        </div> <!-- .inside -->

                    </div> <!-- .postbox -->

                    <div class="postbox">

                        <h3><span><?_e( 'Clean all session', 'wp-database-session-handler' )?></span></h3>
                        <div class="inside">

                            <p>
                                <?php
                                _e( 'Currently there are: ', 'wp-database-session-handler' );
                                echo ' ' . \SC\WPSH\SC_MySqlSessionHandler::count() . ' ';
                                _e( ' sessions ', 'wp-database-session-handler' );
                                ?>
                            </p>
                            <p><?_e( 'If you want to clean all session you can press this button. All connected users will lose their session informations.', 'wp-database-session-handler' )?></p>

                            <form method="post" action="">
                                <input class="button-primary" type="submit" name="clean_all" value="Clean ALL sessions" />
                            </form>

                        </div> <!-- .inside -->

                    </div> <!-- .postbox -->

                    <div class="postbox">

                        <h3><span><?_e( 'View sessions', 'wp-database-session-handler' )?></span></h3>
                        <div class="inside">

                            <p>
                                <?php
                                _e( 'Currently there are: ', 'wp-database-session-handler' );
                                echo ' ' . \SC\WPSH\SC_MySqlSessionHandler::count() . ' ';
                                _e( ' sessions ', 'wp-database-session-handler' );
                                ?>
                            </p>
                            <p><?_e( 'If you want to view the content of all sessions, push the button.', 'wp-database-session-handler' )?></p>

                            <form method="post" action="">
                                <input class="button-primary" type="submit" name="view_all" value="View ALL sessions" />
                            </form>

                        </div> <!-- .inside -->

                    </div> <!-- .postbox -->

                </div> <!-- .meta-box-sortables .ui-sortable -->

            </div> <!-- post-body-content -->

            <!-- sidebar -->
            <div id="postbox-container-1" class="postbox-container">

                <div class="meta-box-sortables">

                    <div class="postbox">

                        <h3><span><?_e( 'Info', 'wp-database-session-handler' )?></span></h3>
                        <div class="inside">

                            <p><?_e( 'In this page you can setup when the sessions will be automatically cleaned.<br><br>You can also check how many sessions actually exist currently and you can clean them.<br><br>Further more, you can view all sessions content.', 'wp-database-session-handler' )?></p>

                        </div> <!-- .inside -->

                    </div> <!-- .postbox -->

                </div> <!-- .meta-box-sortables -->

            </div> <!-- #postbox-container-1 .postbox-container -->

        </div> <!-- #post-body .metabox-holder .columns-2 -->

        <br class="clear">
    </div> <!-- #poststuff -->

</div> <!-- .wrap -->