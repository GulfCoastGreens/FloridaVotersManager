<?php
/*
 * Handles actual import of voters and table breakdowns
 * 
 * This handles county files and the merge into the database
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
 * @package     Voters.php
 * @version     0.1
 * @author      James Jones (stpetegreens.org)
 * @since       04/11/2012
 * @license     GPL v2
 * @category    FloridaVotersManager
 * @copyright   2011-2012 James Jones, all rights reserved.
 */
include_once 'County.php';

class Voters extends County {
    private $importFile;
    private $dateString;
    private $countyCode;
    public $countyName;
    public $partySQL;
    public $SQL;
    function __construct($importFile) {
        parent::__construct();
        $this->importFile = $importFile;
        $this->dateString = substr($importFile,-12,-4);
        $this->countyCode = substr($importFile,0,3);
        $this->countyName = $this->getCountyName($this->countyCode, true);
        $this->filePath = getcwd()."/../Voters/".$importFile;
        $this->partySQL = $this->buildParties();
        $this->SQL = $this->buildSQL();
        $sth = $this->dbh->prepare($this->SQL);
        $sth->execute();
        try {
            $i = 1;
            do {
                $this->updateImportDatesStatus($this->dateString,"Processing Multiquery item ".$i);
                $i++;
            } while ($sth->nextRowset());                            
        } catch(PDOException $e) {
            $this->updateImportDatesStatus($this->dateString,$e->getMessage());
            $this->updateImportDatesStatus($this->dateString,$e->getTraceAsString());
        }        
    }
    function buildParties() {
        $sth = $this->dbh->prepare("SELECT * FROM `FloridaVoterCodes`.`Party Codes` ORDER BY `Party Simple Name`");
        $sth->execute();
        $SQL = "";
        foreach($sth->fetchAll(PDO::FETCH_OBJ) as $partyObj) {
            $partyCode = $partyObj->{'Party Code'};
            $party = $partyObj->{'Party Simple Name'};
            $SQL .= <<<EOT
CREATE TABLE IF NOT EXISTS `FloridaVoterData`.`{$party} Voters` LIKE `FloridaVoterData`.`Voters`;
DELETE FROM `FloridaVoterData`.`{$party} Voters` WHERE `County Code`='{$this->countyCode}' AND `Export Date`=STR_TO_DATE('{$this->dateString}','%Y%m%d');

CREATE TABLE IF NOT EXISTS `FloridaVoterData`.`{$this->countyName} {$party} Voters` LIKE `FloridaVoterData`.`Voters`;

DELETE FROM `FloridaVoterData`.`{$this->countyName} {$party} Voters` WHERE `County Code`='{$this->countyCode}' AND `Export Date`=STR_TO_DATE('{$this->dateString}','%Y%m%d');

INSERT INTO `FloridaVoterData`.`{$this->countyName} {$party} Voters` SELECT * FROM countyTemp WHERE `Party Affiliation`='{$partyCode}';

INSERT INTO `FloridaVoterData`.`{$party} Voters` SELECT * FROM countyTemp WHERE `Party Affiliation`='{$partyCode}';
   
EOT;
        }
        return $SQL;
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

{$this->partySQL}

DROP TEMPORARY TABLE IF EXISTS countyTemp;
   
EOT;
    }


}

# $voters = new Voters("ALA_20120602.txt");
# print $voters->SQL;
// print "County name is: ".$voters->countyName;
?>
