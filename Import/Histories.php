<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Histories
 *
 * @author jam
 */
include_once 'County.php';

class Histories extends County {
    private $importFile;
    private $dateString;
    private $countyCode;
    public $countyName;
    public $SQL;
    function __construct($importFile) {
        // "ALA_H_20120602.txt";
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
