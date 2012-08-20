<?php
/*
 * Provides some county functions used in parsing
 * 
 * Some basic county based functions
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
 * @package     County.php
 * @version     0.1
 * @author      James Jones (stpetegreens.org)
 * @since       04/11/2012
 * @license     GPL v2
 * @category    FloridaVotersManager
 * @copyright   2011-2012 James Jones, all rights reserved.
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
    function updateImportDatesStatus($dateString,$message) {
        $sth = $this->dbh->prepare("SELECT * FROM `FloridaVoterCodes`.`Import Dates` WHERE `Import Date` = STR_TO_DATE('{$dateString}','%Y%m%d')");
        $sth->execute();
        $importDate = $sth->fetch();
        $usth = $this->dbh->prepare("UPDATE `FloridaVoterCodes`.`Import Dates` SET status=:message WHERE `Import Date` = STR_TO_DATE('{$dateString}','%Y%m%d')");
        $usth->execute(array(
            ":message" => $importDate->message."\n".$message
        ));
    }    
}

?>
