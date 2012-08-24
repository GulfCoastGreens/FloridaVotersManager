<?php
/*
 * Provides services for ajax calls
 * 
 * The service class for the interface handled by the jquery plugin
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
 * @package     ContactManagerServices.php
 * @version     0.1
 * @author      James Jones (stpetegreens.org)
 * @since       04/11/2012
 * @license     GPL v2
 * @category    FloridaVotersManager
 * @copyright   2011-2012 James Jones, all rights reserved.
 */
set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT']."/ContactManager/conf");
include_once "Connection.php";

class ContactManagerServices extends Connection {
    //put your code here
    private $params;
    public $request;
    function __construct() {
        parent::__construct();
    }
    public function invokeMethod($params) {
        $user = $_SERVER['PHP_AUTH_USER'];
        error_log($user);
        // return $sth->fetchAll(PDO::FETCH_OBJ);        
        ob_start('ob_gzhandler');
        $this->params = $params;
        if (isset($this->params['method'])) {
            if (isset($this->params['params'])) {
                $this->request = json_decode($this->params['params']);
                error_log("REQUEST: ".var_export($this->request,true));
            } else {
                error_log("NO PARAMETERS");
            }
            error_log("Method is {$this->params['method']}");
            header('Content-type: application/json');
            switch($this->params["method"]) {
                case "someMethod":
                    echo json_encode((object) array('status' => $this->showDatabases()));
                    break;
                case "showDatabases":
                    echo json_encode((object) array('databases' => $this->showDatabases()));
                    break;
                case "getDatabaseTables":
                    echo json_encode((object) array('tables' => $this->getDatabaseTables($this->request->databaseName)));
                    break;
                case "getColumnsFromTable":
                    echo json_encode((object) array('columns' => $this->getColumnsFromTable($this->request->databaseName,$this->request->tableName)));
                    break;
                case "getSearchOptions":
                    echo json_encode((object) array(
                        'counties' => $this->getRows("FloridaVoterCodes","County Codes",$this->request->conditions=array())->fetchAll(PDO::FETCH_OBJ),
                        'genders' => $this->getRows("FloridaVoterCodes","Gender Codes",$this->request->conditions=array())->fetchAll(PDO::FETCH_OBJ),
                        'races' => $this->getRows("FloridaVoterCodes","Race Codes",$this->request->conditions=array())->fetchAll(PDO::FETCH_OBJ),
                        'parties' => $this->getRows("FloridaVoterCodes","Party Codes",$this->request->conditions=array())->fetchAll(PDO::FETCH_OBJ),
                        'statuses' => $this->getRows("FloridaVoterCodes","Voter Status Codes",$this->request->conditions=array())->fetchAll(PDO::FETCH_OBJ),
                        'voterColumns' => $this->getColumnsFromTable("FloridaVoterData","Voters"),
                        'importDates' => $this->getImportDates()
                    ));                    
                    break;
                case "getRows":
                    ini_set('memory_limit', '4000M');
                    echo json_encode((object) array('rows' => $this->getRows($this->request->databaseName,$this->request->tableName,$this->request->conditions=array())->fetchAll(PDO::FETCH_OBJ)));
//                    $row = $this->getRows($this->request->databaseName,$this->request->tableName,$this->request->conditions=array())->fetch(PDO::FETCH_OBJ, PDO::FETCH_ORI_NEXT);
//                    echo json_encode((object) array('rows' => $this->getRows($this->request->databaseName,$this->request->tableName,$this->request->conditions=array())));                    
//                    
//                    echo json_encode((object) array('rows' => $this->getRows($this->request->databaseName,$this->request->tableName,$this->request->conditions=array())));                    
                    break;
                case "getSearchRows":
                    ini_set('memory_limit', '4000M');
                    // error_log("REQUEST: ".var_export($this->request));
                    echo json_encode((object) array('rows' => $this->getSearchRows("FloridaVoterData","Voters",$this->request)->fetchAll(PDO::FETCH_OBJ)));                    
                    break;
                case "getContactTypes":
                    echo json_encode((object) array('types' => $this->getRows("FloridaVoterData","Contact Types",$this->request)->fetchAll(PDO::FETCH_OBJ)));                    
                    break;
                case "getContacts":
                    echo json_encode((object) array('contacts' => $this->getContacts($this->request->contactType)));                    
                    break;
                case "addNewContact";
                    echo json_encode((object) array('contact' => $this->addNewContact($this->request->first,$this->request->last,$this->request->nickname,$this->request->contactType)));
                    break;
                case "addNewContactType":
                    echo json_encode((object) array('contactType' => $this->addNewContactType($this->request->description)));                    
                    break;
                case "addContactToContactType":
                    echo json_encode((object) array('contactTypeMember' => $this->addContactToContactType($this->request->contactId,$this->request->contactType)));                                        
                    break;
                case "removeContactFromContactType":
                    echo json_encode((object) array('success' => $this->removeContactFromContactType($this->request->contactId,$this->request->contactType))); 
                    break;
                case "getContactTypesForContact":
                    echo json_encode((object) array('contactTypes' => $this->getContactTypesForContact($this->request->contactId))); 
                    break;
                case "getContact":
                    echo json_encode((object) array('contact' => $this->getContact($this->request->contactId))); 
                    break;
                case "getContactUser":
                    echo json_encode((object) array('user' => $this->getContactUser($this->request->user))); 
                    break;
                case "getContactEmails":
                    echo json_encode((object) array('emails' => $this->getContactEmails($this->request->contactId))); 
                    break;
                case "getContactPhoneNumbers":
                    echo json_encode((object) array('phoneNumbers' => $this->getContactPhoneNumbers($this->request->contactId))); 
                    break;
                case "addContactEmail":
                    echo json_encode((object) array('contactEmails' => $this->addContactEmail($this->request->contactId,$this->request->email))); 
                    break;
                case "removeContactEmail":
                    echo json_encode((object) array('contactEmails' => $this->removeContactEmail($this->request->contactId,$this->request->email))); 
                    break;
                case "updateContactEmail":
                    echo json_encode((object) array('contactEmails' => $this->updateContactEmail($this->request->contactId,$this->request->email,$this->request->updateEmail))); 
                    break;
                case "addContactPhoneNumber":
                    echo json_encode((object) array('contactPhoneNumbers' => $this->addContactPhoneNumber($this->request->contactId,$this->request->phone))); 
                    break;
                case "removeContactPhoneNumber":
                    echo json_encode((object) array('contactPhoneNumbers' => $this->removeContactPhoneNumber($this->request->contactId,$this->request->phone))); 
                    break;
                case "updateContactPhoneNumber":
                    echo json_encode((object) array('contactPhoneNumbers' => $this->updateContactPhoneNumber($this->request->contactId,$this->request->phone,$this->request->updatePhone))); 
                    break;
                case "getVoterInfo":
                    echo json_encode((object) array(
                        'voter' => $this->getVoter($this->request->voterId),
                        'history' => $this->getHistory($this->request->voterId)
                    ));                     
                    break;
                case "updateContactVoterID":
                    echo json_encode((object) array('contact' => $this->updateContactVoterID($this->request->contactId,$this->request->voterId)));                     
                    break;
                case "importRawData":
                    $import = new Import();
                    echo json_encode((object) array('status' => $import->status));
                    break;
                case "uploadVoterZip":
                    return $this->uploadVoterZip();
                    break;
                default:
                    print "Not Matched";
                    break;
            }
            exit;
        }
    }
    private function uploadVoterZip() {
        error_log("Found the function");
        ini_set('memory_limit', '1024M');
        ini_set('post_max_size', '1024M');
        ini_set('upload_max_filesize', '1024M'); 
        $upload_handler = new UploadHandler();
        
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Content-Disposition: inline; filename="files.json"');
        header('X-Content-Type-Options: nosniff');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: OPTIONS, HEAD, GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: X-File-Name, X-File-Type, X-File-Size');

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'OPTIONS':
                break;
            case 'HEAD':
            case 'GET':
                return $upload_handler->get();
                break;
            case 'POST':
                if (isset($_REQUEST['_method']) && $_REQUEST['_method'] === 'DELETE') {
                    return $upload_handler->delete();
                } else {
                    return $upload_handler->post();
                }
                break;
            case 'DELETE':
                return $upload_handler->delete();
                break;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
        }        
    }
    private function importRawData() {
        $importDates = $this->getImportDates();
        $getStatuses = function($importDate) {
            return $importDate["status"];
        };
        if(in_array("pending",array_map($importDates,$getStatuses))) {
            new Import();
            $findPending = function($importDate) {
                return $importDate->status == "pending";
            };
            $sth = $this->dbh->prepare("UPDATE `FloridaVoterCodes`.`Import Dates` SET status='processing' WHERE status='pending'");
            $sth->execute();
            return (object) array(
                "importDates" => array_filter($importDate, $findPending)
            );
        } elseif(in_array("pending",array_map($importDates,$getStatuses))) {
            
        }        
    }
    private function getImportDates() {
        $SQL = "SELECT * FROM `FloridaVoterCodes`.`Import Dates` ORDER BY `Import Date` DESC";
        $sth = $this->dbh->prepare($SQL);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_OBJ);                
    }
    private function updateContactVoterID($contactId,$voterId) {
        $SQL = "UPDATE Contacts SET `Voter ID` = :voterId WHERE `Contact ID` = :contactId";
        $sth = $this->dbh->prepare($SQL);
        $sth->execute(array(
            ":contactId" => $contactId,
            ":voterId" => $voterId
        ));
        return $this->getContact($contactId);
    }
    private function updateContactPhoneNumber($contactId,$phone,$updatePhone) {
        $contactPhoneNumbers = $this->getContactPhoneNumbers($contactId);
        $getPhoneNumbers = function($contactPhoneNumber) {
            return $contactPhoneNumber["Contact Phone"];
        };
        if(in_array($phone,array_map($getPhoneNumbers,$contactPhoneNumbers))) {
            $SQL = "UPDATE `FloridaVoterData`.`Contact Phone` SET `Contact Phone` = :updatePhone WHERE `Contact ID` = :contactId AND `Contact Phone` = :phone";
            $sth = $this->dbh->prepare($SQL);
            $sth->execute(array(
                ":contactId" => $contactId,
                ":phone" => $phone,
                ":updatePhone" => $updatePhone
            ));
            $contactPhoneNumbers = $this->getContactPhoneNumbers($contactId);
        }
        return $contactPhoneNumbers;
    }
    private function removeContactPhoneNumber($contactId,$phone) {
        $contactPhoneNumbers = $this->getContactPhoneNumbers($contactId);
        $getPhoneNumbers = function($contactPhoneNumber) {
            return $contactPhoneNumber["Contact Phone"];
        };
        if(in_array($phone,array_map($getPhoneNumbers,$contactPhoneNumbers))) {
            $SQL = "DELETE FROM `FloridaVoterData`.`Contact Phone` WHERE `Contact ID` = :contactId AND `Contact Phone` = :phone";
            $sth = $this->dbh->prepare($SQL);
            $sth->execute(array(
                ":contactId" => $contactId,
                ":phone" => $phone
            ));
            $contactPhoneNumbers = $this->getContactPhoneNumbers($contactId);
        }
        return $contactPhoneNumbers;
    }
    private function addContactPhoneNumber($contactId,$phone) {
        $contactPhoneNumbers = $this->getContactPhoneNumbers($contactId);
        $getPhoneNumbers = function($contactPhoneNumber) {
            return $contactPhoneNumber["Contact Phone"];
        };
        if(!in_array($phone,array_map($getPhoneNumbers,$contactPhoneNumbers))) {
            $SQL = "INSERT INTO `FloridaVoterData`.`Contact Phone` (`Contact ID`,`Contact Phone`) VALUES (:contactId,:phone)";
            $sth = $this->dbh->prepare($SQL);
            $sth->execute(array(
                ":contactId" => $contactId,
                ":phone" => $phone
            ));
            $contactPhoneNumbers = $this->getContactPhoneNumbers($contactId);
        }
        return $contactPhoneNumbers;
    }
    private function updateContactEmail($contactId,$email,$updateEmail) {
        $contactEmails = $this->getContactEmails($contactId);
        $getEmails = function($contactEmail) {
            return $contactEmail["Contact Email"];
        };
        if(in_array($email,array_map($getEmails,$contactEmails))) {
            $SQL = "UPDATE `FloridaVoterData`.`Contact Email` SET `Contact Email` = :updateEmail WHERE `Contact ID` = :contactId AND `Contact Email` = :email";
            $sth = $this->dbh->prepare($SQL);
            $sth->execute(array(
                ":contactId" => $contactId,
                ":email" => $email,
                ":updateEmail" => $updateEmail
            ));
            $contactEmails = $this->getContactEmails($contactId);
        }
        return $contactEmails;
    }
    private function removeContactEmail($contactId,$email) {
        $contactEmails = $this->getContactEmails($contactId);
        $getEmails = function($contactEmail) {
            return $contactEmail["Contact Email"];
        };
        if(in_array($email,array_map($getEmails,$contactEmails))) {
            $SQL = "DELETE FROM `FloridaVoterData`.`Contact Email` WHERE `Contact ID` = :contactId AND `Contact Email` = :email";
            $sth = $this->dbh->prepare($SQL);
            $sth->execute(array(
                ":contactId" => $contactId,
                ":email" => $email
            ));
            $contactEmails = $this->getContactEmails($contactId);
        }
        return $contactEmails;
    }
    private function addContactEmail($contactId,$email) {
        $contactEmails = $this->getContactEmails($contactId);
        $getEmails = function($contactEmail) {
            return $contactEmail["Contact Email"];
        };
        if(!in_array($email,array_map($getEmails,$contactEmails))) {
            $SQL = "INSERT INTO `FloridaVoterData`.`Contact Email` (`Contact ID`,`Contact Email`) VALUES (:contactId,:email)";
            $sth = $this->dbh->prepare($SQL);
            $sth->execute(array(
                ":contactId" => $contactId,
                ":email" => $email
            ));
            $contactEmails = $this->getContactEmails($contactId);
        }
        return $contactEmails;
    }
    private function getContactEmails($contactId) {
        $SQL = "SELECT * FROM `FloridaVoterData`.`Contact Email` WHERE `Contact ID`=:contactId";
        $sth = $this->dbh->prepare($SQL);
        $sth->execute(array(
            ":contactId" => $contactId
        ));
        return $sth->fetchAll(PDO::FETCH_OBJ);        
    }
    private function getContactPhoneNumbers($contactId) {
        $SQL = "SELECT * FROM `FloridaVoterData`.`Contact Phone` WHERE `Contact ID`=:contactId";
        $sth = $this->dbh->prepare($SQL);
        $sth->execute(array(
            ":contactId" => $contactId
        ));
        return $sth->fetchAll(PDO::FETCH_OBJ);        
    }
    private function getContactUser($user) {
        $SQL = "SELECT * FROM `FloridaVoterData`.`Contact Users` WHERE `user`=:user";
        $sth = $this->dbh->prepare($SQL);
        $sth->execute(array(
            ":user" => $user
        ));
        $result = $sth->fetchAll(PDO::FETCH_OBJ);
        if(count($result) > 0) {
            if(isset($result[0]->{"Contact ID"})) {
                $result[0]->{"Contact"} = $this->getContact($result[0]->{"Contact ID"});
                unset($result[0]->{"Contact ID"});
            }
            return $result[0];
        } else {
            return (object) array();
        }
    }
    private function addNewContact($first,$last,$nickname,$contactType="") {
        $SQL = "INSERT INTO `FloridaVoterData`.`Contacts` (`Name First`,`Name Last`,`Nickname`) VALUES (:first,:last,:nickname)";
        $sth = $this->dbh->prepare($SQL);
        $sth->execute(array(
            ":first" => $first,
            ":last" => $last,
            ":nickname" => $nickname
        ));
        $contactId = $this->dbh->lastInsertId();
        error_log("Contact ID is: ".$contactId);
        $SQL = "SELECT * FROM `FloridaVoterData`.`Contacts` WHERE `Contact ID` = :contactId";
        $sth = $this->dbh->prepare($SQL);
        $sth->execute(array(
            ":contactId" => $contactId
        ));
        $result = $sth->fetchAll(PDO::FETCH_OBJ);
        if($contactType != "") {
            $this->addContactToContactType($contactId,$contactType);
        } else {
            error_log("No default contact type specified!");
        }
        $result[0]->{"Contact Types"} = $this->getContactTypesForContact($contactId);
        return $result[0];
    }
    private function addNewContactType($contactDescription) {
        // See if this one already exists
        $SQL = "SELECT * FROM `FloridaVoterData`.`Contact Types` WHERE `Contact Description` = :contactDescription";
        $sth = $this->dbh->prepare($SQL);
        $sth->execute(array(
            ":contactDescription" => $contactDescription
        ));
        $result = $sth->fetchAll(PDO::FETCH_OBJ);
        if(count($result) < 1) {
            $SQL = "INSERT INTO `FloridaVoterData`.`Contact Types` (`Contact Description`) VALUES (:contactDescription)";
            $sth = $this->dbh->prepare($SQL);
            $sth->execute(array(
                ":contactDescription" => $contactDescription
            ));
            $contactType = $this->dbh->lastInsertId();
            $SQL = "SELECT * FROM `FloridaVoterData`.`Contact Types` WHERE `Contact Type` = :contactType";
            $sth = $this->dbh->prepare($SQL);
            $sth->execute(array(
                ":contactType" => $contactType
            ));
            $result = $sth->fetchAll(PDO::FETCH_OBJ);            
        }
        return $result[0];
    }
    private function addContactToContactType($contactId,$contactType) {
        // See if this one already exists
        $SQL = "SELECT * FROM `FloridaVoterData`.`Contact Type Members` WHERE `Contact ID` = :contactId AND `Contact Type`=:contactType";
        $sth = $this->dbh->prepare($SQL);
        $sth->execute(array(
            ":contactId" => $contactId,
            ":contactType" => $contactType
        ));
        $result = $sth->fetchAll(PDO::FETCH_OBJ);
        if(count($result) < 1) {
            $SQL = "INSERT INTO `FloridaVoterData`.`Contact Type Members` (`Contact ID`,`Contact Type`) VALUES (:contactId,:contactType)";
            $sthInsert = $this->dbh->prepare($SQL);
            $sthInsert->execute(array(
                ":contactId" => $contactId,
                ":contactType" => $contactType
            ));
            $sth->execute(array(
                ":contactId" => $contactId,
                ":contactType" => $contactType
            ));
            $result = $sth->fetchAll(PDO::FETCH_OBJ);            
        }        
        return $result[0];
    }
    private function removeContactFromContactType($contactId,$contactType) {
        $SQL = "DELETE FROM `FloridaVoterData`.`Contact Type Members` WHERE `Contact ID` = :contactId AND `Contact Type`=:contactType";
        $sth = $this->dbh->prepare($SQL);
        $sth->execute(array(
            ":contactId" => $contactId,
            ":contactType" => $contactType
        ));
        return ($sth->rowCount() > 0)?true:false;
    }
    private function getContact($contactId) {
        $SQL="SELECT * FROM `FloridaVoterData`.`Contacts` WHERE `Contact ID` = :contactId";
        $sth = $this->dbh->prepare($SQL);
        $sth->execute(array(
            ":contactId" => $contactId
        ));
        $result = $sth->fetchAll(PDO::FETCH_OBJ);
        for($i = 0; $i < count($result); ++$i) {
            $result[$i]->{"Contact Types"} = $this->getContactTypesForContact($result[$i]->{"Contact ID"});
            $result[$i]->{"Contact Phones"} = $this->getContactPhoneNumbers($result[$i]->{"Contact ID"});
            $result[$i]->{"Contact Emails"} = $this->getContactEmails($result[$i]->{"Contact ID"}); 
        }
        return (count($result) > 0)?$result[0]:null;        
    }
    private function getContacts($contactType="") {
        $SQL="SELECT * FROM `FloridaVoterData`.`Contacts`";
        if($contactType != "") {
             $SQL = $SQL." WHERE `Contact ID` IN(SELECT `Contact ID` FROM `FloridaVoterData`.`Contact Type Members` WHERE `Contact Type`=:contactType)";
        }
        $sth = $this->dbh->prepare($SQL,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        ($contactType == "")?$sth->execute():$sth->execute(array(":contactType" => $contactType));
        $result = $sth->fetchAll(PDO::FETCH_OBJ);
        for($i = 0; $i < count($result); ++$i) {
            $result[$i]->{"Contact Types"} = $this->getContactTypesForContact($result[$i]->{"Contact ID"});            
            $result[$i]->{"Contact Phones"} = $this->getContactPhoneNumbers($result[$i]->{"Contact ID"});
            $result[$i]->{"Contact Emails"} = $this->getContactEmails($result[$i]->{"Contact ID"});            
        }
        return $result;
    }
    private function getContactTypesForContact($contactId) {
        $SQL = "SELECT * FROM `FloridaVoterData`.`Contact Types` WHERE `Contact Type` IN(SELECT `Contact Type` FROM `FloridaVoterData`.`Contact Type Members` WHERE `Contact ID`= :contactId)";
        $sth = $this->dbh->prepare($SQL);
        $sth->execute(array(
            ":contactId" => $contactId
        ));
        return $sth->fetchAll(PDO::FETCH_OBJ);
    }
    private function showDatabases() {
        $databases = array();
        $sth = $this->dbh->prepare("SHOW DATABASES");
        $sth->execute();
        foreach($sth->fetchAll(PDO::FETCH_OBJ) as $database) {
            if(!in_array($database->Database, array("mysql","information_schema","performance_schema"))) {
                $databases[] = (object) array(
                    "name"=>$database->Database,
                    "tables"=>$this->getDatabaseTables($database->Database)
                );
            }
        }        
        return $databases;
    }
    private function getDatabaseTables($databaseName) {
        $tables = array();
        $sth = $this->dbh->prepare("SHOW TABLES FROM ".$databaseName);
        $sth->execute();
        foreach($sth->fetchAll(PDO::FETCH_OBJ) as $table) {
            $tables[] = (object) array(
                "name" => $table->{"Tables_in_".$databaseName},
                "columns" => $this->getColumnsFromTable(
                    $databaseName, 
                    $table->{"Tables_in_".$databaseName}
                )
            );
        }
        return $tables;
    }
    private function getColumnsFromTable($databaseName,$tableName) {
        $sth = $this->dbh->prepare("SHOW COLUMNS FROM `".$databaseName."`.`".$tableName."`");
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_OBJ);
    }
    private function getRows($databaseName,$tableName,$conditions=array()) {
        ini_set('max_execution_time', 1500); //300 seconds = 5 minutes
        $SQL="SELECT * FROM `".$databaseName."`.`".$tableName."`";
        $sth = $this->dbh->prepare($SQL,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $sth->execute();
        // ini_set('memory_limit', '2000M');
        // return $sth->fetchAll(PDO::FETCH_OBJ);
        return $sth;
    }
    private function getVoter($voterId) {
        ini_set('max_execution_time', 1500); //300 seconds = 5 minutes
        $SQL = "SELECT * FROM `FloridaVoterData`.`Voters` WHERE `Voter ID` = :voterId";
        $sth = $this->dbh->prepare($SQL);
        $sth->execute(array(
            ":voterId" => $voterId
        ));
        return $sth->fetchAll(PDO::FETCH_OBJ);
    }
    private function getHistory($voterId) {
        ini_set('max_execution_time', 1500); //300 seconds = 5 minutes
        $SQL = "SELECT * FROM `FloridaVoterData`.`Histories` WHERE `Voter ID` = :voterId";
        $sth = $this->dbh->prepare($SQL);
        $sth->execute(array(
            ":voterId" => $voterId
        ));
        return $sth->fetchAll(PDO::FETCH_OBJ);
    }
    private function getSearchRows($databaseName,$tableName,$conditions=array()) {
        ini_set('max_execution_time', 1500); //300 seconds = 5 minutes
        $SQL="SELECT * FROM `".$databaseName."`.`".$tableName."`";
        if(in_array("Voter ID",$conditions)) {
            $SQL = $SQL." WHERE `Voter ID`=:voterId";
            $sth = $this->dbh->prepare($SQL,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
            $sth->execute(array(':voterId' => $conditions["Voter ID"]));
            return $sth;
        } else {
            $prevalues=array();
            $sqlwhere=array();
            foreach($conditions as $key => $value) {
                switch($key) {
                    case "first":
                        $prevalues = array_merge($prevalues,array(":".$key => "%".$value."%"));
                        $sqlwhere[] = "(`Name First` LIKE :first)";
                        break;
                    case "middle":
                        $prevalues = array_merge($prevalues,array(":".$key => "%".$value."%"));
                        $sqlwhere[] = "(`Name Middle` LIKE :middle)";
                        break;
                    case "last":
                        $prevalues = array_merge($prevalues,array(":".$key => "%".$value."%"));
                        $sqlwhere[] = "(`Name Last` LIKE :last)";
                        break;
                    case "gender":
                        $prevalues = array_merge($prevalues,array(":".$key => $value));
                        $sqlwhere[] = "(`Gender` = :gender)";
                        break;
                    case "race":
                        $prevalues = array_merge($prevalues,array(":".$key => $value));
                        $sqlwhere[] = "(`Race` = :race)";
                        break;
                    case "bornBefore":
                        $prevalues = array_merge($prevalues,array(":".$key => $value));
                        $sqlwhere[] = "(`Birth Date` <= :bornBefore)";
                        break;
                    case "bornAfter":
                        $prevalues = array_merge($prevalues,array(":".$key => $value));
                        $sqlwhere[] = "(`Birth Date` >= :bornAfter)";
                        break;
                    case "county":
                        $prevalues = array_merge($prevalues,array(":".$key => $value));
                        $sqlwhere[] = "(`County Code` = :county)";
                        break;
                    case "address":
                        error_log("%".$value."%");
                        $prevalues = array_merge($prevalues,array(":".$key => "%".$value."%"));
                        $sqlwhere[] = "(`Residence Address Line 1` LIKE :address OR `Residence Address Line 2` LIKE :address OR `Mailing Address Line 1` LIKE :address OR `Mailing Address Line 2` LIKE :address OR `Mailing Address Line 3` LIKE :address)";
                        break;
                    case "city":
                        $prevalues = array_merge($prevalues,array(":".$key => "%".$value."%"));
                        $sqlwhere[] = "(`Residence City USPS` LIKE :city OR `Mailing City` LIKE :city)";
                        break;
                    case "zip":
                        $prevalues = array_merge($prevalues,array(":".$key => "%".$value."%"));
                        $sqlwhere[] = "(`Residence Zipcode` LIKE :zip OR `Mailing Zipcode` LIKE :zip)";
                        break;
                    case "party":
                        $prevalues = array_merge($prevalues,array(":".$key => $value));
                        $sqlwhere[] = "(`Party Affiliation` = :party)";
                        break;
                    case "status":
                        $prevalues = array_merge($prevalues,array(":".$key => $value));
                        $sqlwhere[] = "(`Voter Status` = :status)";
                        break;
                    case "congressionalDistrict":
                        $prevalues = array_merge($prevalues,array(":".$key => "%".$value."%"));
                        $sqlwhere[] = "(`Congressional District` LIKE :congressionalDistrict)";
                        break;
                    case "houseDistrict":
                        $prevalues = array_merge($prevalues,array(":".$key => "%".$value."%"));
                        $sqlwhere[] = "(`House District` LIKE :houseDistrict)";
                        break;
                    case "senateDistrict":
                        $prevalues = array_merge($prevalues,array(":".$key => "%".$value."%"));
                        $sqlwhere[] = "(`Senate District` LIKE :senateDistrict)";
                        break;
                    case "countyCommissionDistrict":
                        $prevalues = array_merge($prevalues,array(":".$key => "%".$value."%"));
                        $sqlwhere[] = "(`County Commission District` LIKE :countyCommissionDistrict)";
                        break;
                    case "schoolBoardDistrict":
                        $prevalues = array_merge($prevalues,array(":".$key => "%".$value."%"));
                        $sqlwhere[] = "(`School Board District` LIKE :schoolBoardDistrict)";
                        break;
                    case "precinct":
                        $prevalues = array_merge($prevalues,array(":".$key => "%".$value."%"));
                        $sqlwhere[] = "(`Precinct` LIKE :precinct)";
                        break;
                    case "precinctGroup":
                        $prevalues = array_merge($prevalues,array(":".$key => "%".$value."%"));
                        $sqlwhere[] = "(`Precinct Group` LIKE :precinctGroup)";
                        break;
                    case "precinctSplit":
                        $prevalues = array_merge($prevalues,array(":".$key => "%".$value."%"));
                        $sqlwhere[] = "(`Precinct Split` LIKE :precinctSplit)";
                        break;
                    case "precinctSuffix":
                        $prevalues = array_merge($prevalues,array(":".$key => "%".$value."%"));
                        $sqlwhere[] = "(`Precinct Suffix` LIKE :precinctSuffix)";
                        break;   
                    case "exportDate":
                        $prevalues = array_merge($prevalues,array(":".$key => $value));
                        $sqlwhere[] = "(`Export Date` = :exportDate)";
                        break;
                }
            }
            error_log($SQL." WHERE ".implode(" AND ",$sqlwhere));
            $sth = $this->dbh->prepare($SQL." WHERE ".implode(" AND ",$sqlwhere),array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
            $sth->execute($prevalues);
            // ini_set('memory_limit', '2000M');
            // return $sth->fetchAll(PDO::FETCH_OBJ);
            return $sth;            
        }
    }
}

?>
