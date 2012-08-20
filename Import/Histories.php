<?php
/*
 * Handles actual import of histories and table breakdowns
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
 * @package     Histories.php
 * @version     0.1
 * @author      James Jones (stpetegreens.org)
 * @since       04/11/2012
 * @license     GPL v2
 * @category    FloridaVotersManager
 * @copyright   2011-2012 James Jones, all rights reserved.
 */
include_once 'County.php';

class Histories extends County {
    private $importFile;
    private $dateString;
    private $countyCode;
    public $countyName;
    public $SQL;
    function __construct($importFile) {
        parent::__construct();
        $this->importFile = $importFile;
        $this->dateString = substr($importFile,-12,-4);
        $this->countyCode = substr($importFile,0,3);
        $this->countyName = $this->getCountyName($this->countyCode, true);
        $this->filePath = getcwd()."/../Histories/".$importFile;
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
    function buildSQL() {
        return <<<EOT
CREATE TABLE IF NOT EXISTS `FloridaVoterData`.`Histories` (
  `County Code` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Voter ID` BIGINT(18) UNSIGNED NOT NULL,
  `Election Date` DATE DEFAULT NULL,
  `Election Type` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `History Code` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `Export Date` DATE DEFAULT NULL,
  PRIMARY KEY (`County Code`,`Voter ID`,`Election Date`,`Election Type`,`History Code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DELETE FROM `FloridaVoterData`.`Histories` WHERE `County Code`='{$this->countyCode}' AND `Export Date`=STR_TO_DATE('{$this->dateString}','%Y%m%d');

CREATE TABLE IF NOT EXISTS `FloridaVoterData`.`{$this->countyName} History` LIKE `FloridaVoterData`.`Histories`;

/**
DELETE FROM `FloridaVoterData`.`{$this->countyName} History` WHERE `County Code`='{$this->countyCode}' AND `Export Date`=STR_TO_DATE('{$this->dateString}','%Y%m%d');
**/
DROP TEMPORARY TABLE IF EXISTS countyTemp;

CREATE TEMPORARY TABLE countyTemp LIKE `FloridaVoterData`.`Histories`;

LOAD DATA LOCAL INFILE '{$this->filePath}'
INTO TABLE countyTemp
FIELDS TERMINATED BY '\\t'
LINES TERMINATED BY '\\n'
(
    `County Code`,
    `Voter ID`,
    @Election_Date,
    `Election Type`,
    `History Code`
)
SET `Export Date` = STR_TO_DATE('{$this->dateString}','%Y%m%d'),
`Election Date` = STR_TO_DATE(@Election_Date,'%m/%d/%Y');

REPLACE INTO `FloridaVoterData`.`Histories` SELECT * FROM countyTemp;

REPLACE INTO `FloridaVoterData`.`{$this->countyName} History` SELECT * FROM countyTemp;

DROP TEMPORARY TABLE IF EXISTS countyTemp;
   
EOT;
    }
    
}
//$histories = new Histories("ALA_H_20120602.txt");
//print $histories->SQL;

?>
