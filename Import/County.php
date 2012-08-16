<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of County
 *
 * @author jamesjones
 */
include_once '../conf/Connection.php';

class County extends Connection {
    //put your code here
    public $name;
    public $code;
    public function __construct() {
        parent::__construct();
    }
    
    public function getCountyName($countyCode="",$safe=false) {
        $sth = $this->dbh->prepare("SELECT `County Description` FROM `FloridaVoterCodes`.`County Codes` WHERE `County Code`=:countyCode");
        $sth->execute(array(':countyCode' => $countyCode));   
        $countyName = $sth->fetchColumn();
        return ($safe)?$countyName:$this->safeDBNames($countyName);
    }
    
    public function safeDBNames($input) {
        return str_replace("-"," ",str_replace(".","",$input));
    }
    
    public function getCounties() {
        try {
            $sth = $this->dbh->prepare(
                "SELECT * FROM `FloridaVoterCodes`.`County Codes` ORDER BY `County Code`"
            );
            $sth->execute();
            return $sth->fetchAll(PDO::FETCH_OBJ);
        } catch(PDOException $e) {
            print $e->getMessage(); 
            return array();
        }
    }
}

?>
