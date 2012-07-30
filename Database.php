<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Database
 *
 * @author jam
 */
include_once 'conf/Connection.php';
class Database extends Connection {
    //put your code here
    public $name;
    public $tables = array();
    function __construct() {
        parent::__construct();
    }
}

?>
