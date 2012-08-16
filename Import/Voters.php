<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Voters
 *
 * @author jam
 */
include_once 'County.php';

class Voters extends County {
    private $importFile;
    private $dateString;
    private $countyCode;
    public $countyName;
    public $SQL;
    function __construct($importFile) {
        // "ALA_20120602.txt";
        parent::__construct();
        $this->importFile = $importFile;
        $this->dateString = substr($importFile,-12,-4);
        $this->countyCode = substr($importFile,0,3);
        $this->countyName = $this->getCountyName($this->countyCode, true);
        $this->filePath = getcwd()."/Voters/".$importFile;
        $this->SQL = $this->buildSQL();
        $sth = $this->dbh->prepare($this->SQL);
        $sth->execute();
        try {
            $i = 1;
            do {
                echo "Processing Multiquery item ".$i."\n";
                $i++;
            } while ($sth->nextRowset());                            
        } catch(PDOException $e) {
            print $e->getMessage(); 
            print $e->getTraceAsString();
        }        
    }
    
    function buildSQL() {
        return <<<EOT
CREATE TABLE IF NOT EXISTS `FloridaVoterData`.`Voters` (
  `County Code` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Voter ID` bigint(18) unsigned NOT NULL,
  `Name Last` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Name Suffix` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Name First` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Name Middle` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Suppress Address` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Residence Address Line 1` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Residence Address Line 2` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Residence City USPS` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Residence State` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Residence Zipcode` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Mailing Address Line 1` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Mailing Address Line 2` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Mailing Address Line 3` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Mailing City` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Mailing State` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Mailing Zipcode` varchar(12) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Mailing Country` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Gender` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Race` bigint(18) unsigned DEFAULT NULL,
  `Birth Date` date DEFAULT NULL,
  `Registration Date` date DEFAULT NULL,
  `Party Affiliation` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Precinct` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Precinct Group` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Precinct Split` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Precinct Suffix` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Voter Status` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Congressional District` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `House District` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Senate District` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `County Commission District` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `School Board District` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Daytime Area Code` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Daytime Phone Number` varchar(7) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Daytime Phone Extension` varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Export Date` date NOT NULL,
  PRIMARY KEY (`County Code`,`Voter ID`,`Export Date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DELETE FROM `FloridaVoterData`.`Voters` WHERE `County Code`='{$this->countyCode}' AND `Export Date`=STR_TO_DATE('{$this->dateString}','%Y%m%d');

CREATE TABLE IF NOT EXISTS `FloridaVoterData`.`{$this->countyName} Voters` LIKE `FloridaVoterData`.`Voters`;

DELETE FROM `FloridaVoterData`.`{$this->countyName} Voters` WHERE `County Code`='{$this->countyCode}' AND `Export Date`=STR_TO_DATE('{$this->dateString}','%Y%m%d');

DROP TEMPORARY TABLE IF EXISTS countyTemp;

CREATE TEMPORARY TABLE countyTemp LIKE `FloridaVoterData`.`Voters`;

LOAD DATA LOCAL INFILE '{$this->filePath}'
    INTO TABLE countyTemp
    FIELDS TERMINATED BY '\\t'
    LINES TERMINATED BY '\\n'
    (`County Code`,
    `Voter ID`,
    `Name Last`,
    `Name Suffix`,
    `Name First`,
    `Name Middle`,
    `Suppress Address`,
    `Residence Address Line 1`,
    `Residence Address Line 2`,
    `Residence City USPS`,
    `Residence State`,
    `Residence Zipcode`,
    `Mailing Address Line 1`,
    `Mailing Address Line 2`,
    `Mailing Address Line 3`,
    `Mailing City`,
    `Mailing State`,
    `Mailing Zipcode`,
    `Mailing Country`,
    `Gender`,
    `Race`,
    @Birth_Date,
    @Registration_Date,
    `Party Affiliation`,
    `Precinct`,
    `Precinct Group`,
    `Precinct Split`,
    `Precinct Suffix`,
    `Voter Status`,
    `Congressional District`,
    `House District`,
    `Senate District`,
    `County Commission District`,
    `School Board District`,
    `Daytime Area Code`,
    `Daytime Phone Number`,
    `Daytime Phone Extension`
    )
    SET `Export Date` = STR_TO_DATE('{$this->dateString}','%Y%m%d'),
    `Birth Date` = STR_TO_DATE(@Birth_Date,'%m/%d/%Y'),
    `Registration Date` = STR_TO_DATE(@Registration_Date,'%m/%d/%Y');

INSERT INTO `FloridaVoterData`.`Voters` SELECT * FROM countyTemp;
INSERT INTO `FloridaVoterData`.`{$this->countyName} Voters` SELECT * FROM countyTemp;

CREATE TABLE IF NOT EXISTS `FloridaVoterData`.`Green Voters` LIKE `FloridaVoterData`.`Voters`;
DELETE FROM `FloridaVoterData`.`Green Voters` WHERE `County Code`='{$this->countyCode}' AND `Export Date`=STR_TO_DATE('{$this->dateString}','%Y%m%d');

CREATE TABLE IF NOT EXISTS `FloridaVoterData`.`{$this->countyName} Green Voters` LIKE `FloridaVoterData`.`Voters`;

DELETE FROM `FloridaVoterData`.`{$this->countyName} Green Voters` WHERE `County Code`='{$this->countyCode}' AND `Export Date`=STR_TO_DATE('{$this->dateString}','%Y%m%d');

INSERT INTO `FloridaVoterData`.`{$this->countyName} Green Voters` SELECT * FROM countyTemp WHERE `Party Affiliation`='GRE';

INSERT INTO `FloridaVoterData`.`Green Voters` SELECT * FROM countyTemp WHERE `Party Affiliation`='GRE';

-- CALL `FloridaVoterCodes`.`buildPartyVoters`('{$this->countyCode}','{$this->countyName}',STR_TO_DATE('{$this->dateString}','%Y%m%d'));

DROP TEMPORARY TABLE IF EXISTS countyTemp;
   
EOT;
    }


}

//$voters = new Voters("ALA_20120602.txt");
//print $voters->SQL;
// print "County name is: ".$voters->countyName;
?>
