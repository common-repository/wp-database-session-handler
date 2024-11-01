<?php

namespace SC\WPSH;


class SC_MySqlSessionHandler {

    /**
     * a database MySQLi connection resource
     * @var resource
     */
    protected static $dbConnection;

    /**
     * the name of the DB table which handles the sessions
     * @var string
     */
    protected static $dbTable;

    /**
     * define if already initiated
     * @var bool
     */
    protected static $initiated;


    public static function init() {
        if ( ! self::$initiated ) {
            self::activate_session_handler();
        }
    }

    /**
     * Create db connection and set handler
     */
    public static function activate_session_handler() {
        global $wpdb;
        // add db data
        self::setDbDetails(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        // TODO: OR alternatively send a MySQLi ressource
        // $session->setDbConnection($mysqli);
        self::setDbTable("{$wpdb->prefix}sc_session_handler");

        session_write_close();
        $handler = new self();
        $res = session_set_save_handler(array($handler, 'open'),
            array($handler, 'close'),
            array($handler, 'read'),
            array($handler, 'write'),
            array($handler, 'destroy'),
            array($handler, 'gc'));
        // The following prevents unexpected effects when using objects as save handlers.
        register_shutdown_function('session_write_close');
        session_start();
        self::$initiated = true;
    }

    /**
     * Create table on plugin activation
     */
    public static function create_table() {

        $current_db_version = '0.1';
        $created_db_version = get_option('sc_session_handler_version', '0.0');

        if (version_compare($created_db_version, $current_db_version, '<')) {
            global $wpdb;

            $collate = '';
            if ($wpdb->has_cap('collation')) {
                $collate = $wpdb->get_charset_collate();
            }

            $table = "CREATE TABLE  IF NOT EXISTS {$wpdb->prefix}sc_session_handler (
                      `id` varchar(200) NOT NULL,
                      `data` longtext NOT NULL,
                      `ip` varchar(100) NOT NULL,
                      `timestamp` int(255) NOT NULL,
                      PRIMARY KEY (`id`)
		    ) $collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($table);

            add_option('sc_session_handler_version', '0.1', '', 'no');

        }
    }

    /**
     * Remove table on plugin deactivation
     */
    public static function delete_table() {
        global $wpdb;
        $table = "DROP TABLE IF EXISTS {$wpdb->prefix}sc_session_handler ";
        $wpdb->query($table);
        delete_option("sc_session_handler_version");
    }

    /**
     * Set db data if no connection is being injected
     * @param 	string	$dbHost
     * @param	string	$dbUser
     * @param	string	$dbPassword
     * @param	string	$dbDatabase
     */
    public static function setDbDetails($dbHost, $dbUser, $dbPassword, $dbDatabase)
    {
        self::$dbConnection = new \mysqli($dbHost, $dbUser, $dbPassword, $dbDatabase);

        if (mysqli_connect_error()) {
            throw new \Exception('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
        }
    }

    /**
     * Inject DB connection from outside
     * @param 	object	$dbConnection	expects MySQLi object
     */
    public function setDbConnection($dbConnection)
    {
        self::$dbConnection = $dbConnection;
    }

    /**
     * Set table used for connection
     * @param 	string	$dbTable
     */
    public function setDbTable($dbTable)
    {
        self::$dbTable = $dbTable;
    }

    /**
     * Open the session
     * @return bool
     */
    public function open()
    {
        //delete old session handlers
        if (get_option('sc_session_handler_clean_on_gc')) {
            $limit = time() - (3600 * 24);
            $sql = sprintf("DELETE FROM %s WHERE timestamp < %s", self::$dbTable, $limit);
            return self::$dbConnection->query($sql);
        } else {
            return true;
        }
    }

    /**
     * Close the session
     * @return bool
     */
    public function close()
    {
        return self::$dbConnection->close();
    }

    /**
     * Read the session
     * @param int session id
     * @return string string of the sessoin
     */
    public function read($id)
    {
        $sql = sprintf("SELECT data FROM %s WHERE id = '%s'", self::$dbTable, self::$dbConnection->escape_string($id));
        if ($result = self::$dbConnection->query($sql)) {
            if ($result->num_rows && $result->num_rows > 0) {
                $record = $result->fetch_assoc();
                return $record['data'];
            } else {
                return '';  //use empty string instead of null!
            }
        } else {
            return '';  //use empty string instead of null!
        }

        return true;
    }

    /**
     * Write the session
     * @param int session id
     * @param string data of the session
     */
    public function write($id, $data)
    {

        $sql = sprintf("REPLACE INTO %s VALUES('%s', '%s', '%s', '%s')",
            self::$dbTable,
            self::$dbConnection->escape_string($id),
            self::$dbConnection->escape_string($data),
            self::$dbConnection->escape_string($_SERVER['REMOTE_ADDR']),
            time());
        return self::$dbConnection->query($sql);
    }

    /**
     * Destoroy the session
     * @param int session id
     * @return bool
     */
    public function destroy($id)
    {
        $sql = sprintf("DELETE FROM %s WHERE `id` = '%s'", self::$dbTable, self::$dbConnection->escape_string($id));
        return self::$dbConnection->query($sql);
    }

    /**
     * Destoroy all the sessions
     * @return bool
     */
    public static function destroy_all()
    {
        $sql = sprintf("TRUNCATE TABLE %s", self::$dbTable);
        return self::$dbConnection->query($sql);
    }

    /**
     * Garbage Collector
     * @param int life time (sec.)
     * @return bool
     * @see session.gc_divisor      100
     * @see session.gc_maxlifetime 1440
     * @see session.gc_probability    1
     * @usage execution rate 1/100
     *        (session.gc_probability/session.gc_divisor)
     */
    public function gc($max)
    {
        if (get_option('sc_session_handler_clean_on_gc')) {
            $sql = sprintf("DELETE FROM %s WHERE `timestamp` < '%s'", self::$dbTable, time() - intval($max));
            return $this->dbConnection->query($sql);
        } else {
            return true;
        }


    }


    /**
     * Delete by cron
     * @return bool
     */
    public static function cron_delete()
    {
        $frequency = get_option('sc_session_handler_clean_every', '0');
        //delete old session handlers
        if ($frequency>0) {
            $limit = time() - (3600 * $frequency);
            $sql = sprintf("DELETE FROM %s WHERE timestamp < %s", self::$dbTable, $limit);
            return self::$dbConnection->query($sql);
        } else {
            return true;
        }
    }


    /**
     * Get session count
     * @return int
     */
    public static function count()
    {
        $sql = sprintf("SELECT count(*) as count FROM %s", self::$dbTable);
        return mysqli_fetch_assoc(self::$dbConnection->query($sql))['count'];
    }

    /**
     * Get session content
     * @return array
     */
    public static function sessions_content()
    {
        $sql = sprintf("SELECT id, data, ip, `timestamp` AS ts FROM %s ORDER BY `timestamp` DESC ", self::$dbTable);
        return self::$dbConnection->query($sql);
    }
}


