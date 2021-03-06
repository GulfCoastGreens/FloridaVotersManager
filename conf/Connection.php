<?php
/*
 * Providing for the database connection
 * 
 * This handles creating a PDO connection to the backend database
 * 
 * CONTACT: jamjon3@stpetegreens.org
 * 
 * LICENSE: This source file is free software, under the GPL v2 license, as supplied with this software.
 *
 * This source file is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the license files for details.
 *
 * For details please refer to: https://stpetgreens.org
 * 
 * @package     Connection.php
 * @version     0.1
 * @author      James Jones (stpetegreens.org)
 * @since       04/11/2012
 * @license     GPL v2
 * @category    FloridaVotersManager
 * @copyright   2011-2012 James Jones, all rights reserved.
 */
set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT']."/ContactManager");
set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT']."/ContactManager/conf");
set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT']."/ContactManager/Import");
set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT']."/ContactManager/js/blueimp-jQuery-File-Upload/server/php");

include_once "Database.php";
include_once "Table.php";
include_once "Import.php";
include_once 'County.php';
include_once 'Voters.php';
include_once 'Histories.php';
require_once "upload.class.php";

class Connection {
    public $dbh;
    public $settings;
    public function __construct() {
        $this->settings = parse_ini_file("settings.ini", true);
        $this->dbh = new PDO(
            $this->settings['connection']['dsn'], 
            $this->settings['connection']['user'], 
            $this->settings['connection']['passwd'],
            array(
                PDO::ATTR_PERSISTENT => true,
                PDO::MYSQL_ATTR_LOCAL_INFILE => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            )
        );                
        // $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);         
        // $this->dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, (version_compare($this->dbh->getAttribute(PDO::ATTR_SERVER_VERSION), '5.1.17', '<')));
        $this->dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
    }
    public function __destruct() {
        $this->dbh = null;
        unset($this->settings);
    }
}

?>
