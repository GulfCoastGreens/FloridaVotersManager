<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Table
 *
 * @author jam
 */
include_once 'conf/Connection.php';
class Table extends Connection {
    //put your code here
    public $name;
    function __construct() {
        parent::__construct();
    }
}

?>
