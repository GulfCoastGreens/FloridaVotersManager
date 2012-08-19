<?php
/*
 * Bootstraps the basic schema with static values and codes required for the Florida Contact Manager
 * 
 * Builds and populates tables of codes related to voter and history databases
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
 * @package     Bootstrap.php
 * @version     0.1
 * @author      James Jones (stpetegreens.org)
 * @since       04/11/2012
 * @license     GPL v2
 * @category    FloridaVotersManager
 * @copyright   2011-2012 James Jones, all rights reserved.
 */
include_once 'County.php';

class Bootstrap extends County {
    private $SQL;
    function __construct() {
        parent::__construct();
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

CREATE DATABASE IF NOT EXISTS `FloridaVoterData` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;

CREATE DATABASE IF NOT EXISTS `FloridaVoterCodes` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;

USE `FloridaVoterCodes`;

DROP procedure IF EXISTS `bootstrapCountyCodes`;

delimiter $$

create procedure bootstrapCountyCodes()     
begin
    CREATE TABLE IF NOT EXISTS `County Codes` (
      `County Code` char(3) COLLATE utf8_unicode_ci NOT NULL,
      `County Description` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
      PRIMARY KEY (`County Code`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    IF ((SELECT COUNT(*) FROM `County Codes`) < 1) THEN
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('ALA','Alachua');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('BAK','Baker');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('BAY','Bay');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('BRA','Bradford');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('BRE','Brevard');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('BRO','Broward');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('CAL','Calhoun');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('CHA','Charlotte');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('CIT','Citrus');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('CLA','Clay');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('CLL','Collier');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('CLM','Columbia');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('DAD','Miami-Dade');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('DES','Desoto');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('DIX','Dixie');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('DUV','Duval');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('ESC','Escambia');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('FLA','Flagler');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('FRA','Franklin');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('GAD','Gadsden');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('GIL','Gilchrist');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('GLA','Glades');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('GUL','Gulf');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('HAM','Hamilton');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('HAR','Hardee');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('HEN','Hendry');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('HER','Hernando');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('HIG','Highlands');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('HIL','Hillsborough');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('HOL','Holmes');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('IND','Indian River');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('JAC','Jackson');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('JEF','Jefferson');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('LAF','Lafayette');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('LAK','Lake');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('LEE','Lee');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('LEO','Leon');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('LEV','Levy');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('LIB','Liberty');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('MAD','Madison');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('MAN','Manatee');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('MON','Monroe');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('MRN','Marion');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('MRT','Martin');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('NAS','Nassau');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('OKA','Okaloosa');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('OKE','Okeechobee');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('ORA','Orange');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('OSC','Osceola');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('PAL','Palm Beach');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('PAS','Pasco');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('PIN','Pinellas');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('POL','Polk');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('PUT','Putnam');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('SAN','Santa Rosa');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('SAR','Sarasota');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('SEM','Seminole');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('STJ','St. Johns');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('STL','St. Lucie');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('SUM','Sumter');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('SUW','Suwannee');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('TAY','Taylor');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('UNI','Union');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('VOL','Volusia');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('WAK','Wakulla');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('WAL','Walton');
        INSERT INTO `County Codes` (`County Code`,`County Description`) VALUES ('WAS','Washington');
    END IF;

END;
$$

delimiter ;

CALL bootstrapCountyCodes();

DROP procedure IF EXISTS `bootstrapCountyCodes`;

DROP procedure IF EXISTS `bootstrapDistrictCourtNumbers`;

delimiter $$

create procedure bootstrapDistrictCourtNumbers()     
begin
    CREATE TABLE IF NOT EXISTS `District Court Numbers` (
      `District Court` int(11) unsigned NOT NULL,
      `County Code` char(3) COLLATE utf8_unicode_ci NOT NULL,
      `County Description` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
      PRIMARY KEY (`District Court`,`County Code`),
      KEY `County Code` (`County Code`),
      CONSTRAINT `CountyCodeForDistrictCourtNumbers` FOREIGN KEY (`County Code`) REFERENCES `County Codes` (`County Code`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    IF ((SELECT COUNT(*) FROM `District Court Numbers`) < 1) THEN
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'ALA','Alachua');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'BAK','Baker');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'BAY','Bay');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'BRA','Bradford');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'CAL','Calhoun');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'CLA','Clay');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'CLM','Columbia');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'DIX','Dixie');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'DUV','Duval');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'ESC','Escambia');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'FRA','Franklin');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'GAD','Gadsden');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'GIL','Gilchrist');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'GUL','Gulf');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'HAM','Hamilton');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'HOL','Holmes');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'JAC','Jackson');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'JEF','Jefferson');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'LAF','Lafayette');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'LEO','Leon');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'LEV','Levy');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'LIB','Liberty');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'MAD','Madison');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'NAS','Nassau');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'OKA','Okaloosa');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'SAN','Santa Rosa');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'SUW','Suwannee');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'TAY','Taylor');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'UNI','Union');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'WAK','Wakulla');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'WAL','Walton');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (1,'WAS','Washington');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (2,'CHA','Charlotte');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (2,'CLL','Collier');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (2,'DES','Desoto');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (2,'GLA','Glades');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (2,'HAR','Hardee');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (2,'HEN','Hendry');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (2,'HIG','Highlands');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (2,'HIL','Hillsborough');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (2,'LEE','Lee');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (2,'MAN','Manatee');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (2,'PAS','Pasco');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (2,'PIN','Pinellas');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (2,'POL','Polk');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (2,'SAR','Sarasota');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (3,'DAD','Miami-Dade');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (3,'MON','Monroe');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (4,'BRO','Broward');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (4,'IND','Indian River');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (4,'MRT','Martin');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (4,'OKE','Okeechobee');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (4,'PAL','Palm Beach');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (4,'STL','St. Lucie');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (5,'BRE','Brevard');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (5,'CIT','Citrus');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (5,'FLA','Flagler');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (5,'HER','Hernando');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (5,'LAK','Lake');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (5,'MRN','Marion');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (5,'ORA','Orange');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (5,'OSC','Osceola');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (5,'PUT','Putnam');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (5,'SEM','Seminole');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (5,'STJ','St. Johns');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (5,'SUM','Sumter');
        INSERT INTO `District Court Numbers` (`District Court`,`County Code`,`County Description`) VALUES (5,'VOL','Volusia');
    END IF;

END;
$$

delimiter ;

CALL bootstrapDistrictCourtNumbers();

DROP procedure IF EXISTS `bootstrapDistrictCourtNumbers`;

DROP procedure IF EXISTS `bootstrapGenderCodes`;

delimiter $$

create procedure bootstrapGenderCodes()     
begin
    CREATE TABLE IF NOT EXISTS `Gender Codes` (
      `Gender Code` char(1) COLLATE utf8_unicode_ci NOT NULL,
      `Gender Description` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
      PRIMARY KEY (`Gender Code`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
    IF ((SELECT COUNT(*) FROM `Gender Codes`) < 1) THEN
        INSERT INTO `Gender Codes` (`Gender Code`,`Gender Description`) VALUES ('F','Female');
        INSERT INTO `Gender Codes` (`Gender Code`,`Gender Description`) VALUES ('M','Male');
        INSERT INTO `Gender Codes` (`Gender Code`,`Gender Description`) VALUES ('U','Unknown');
    END IF;
END;
$$

delimiter ;

CALL bootstrapGenderCodes();

DROP procedure IF EXISTS `bootstrapGenderCodes`;

DROP procedure IF EXISTS `bootstrapHistoryCodes`;

delimiter $$

create procedure bootstrapHistoryCodes()     
begin
    CREATE TABLE IF NOT EXISTS `History Codes` (
      `History Code` char(1) COLLATE utf8_unicode_ci NOT NULL,
      `History Description` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
      PRIMARY KEY (`History Code`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
    IF ((SELECT COUNT(*) FROM `History Codes`) < 1) THEN
        INSERT INTO `History Codes` (`History Code`,`History Description`) VALUES ('A','Voted Absentee');
        INSERT INTO `History Codes` (`History Code`,`History Description`) VALUES ('B','Absentee Ballot NOT Counted');
        INSERT INTO `History Codes` (`History Code`,`History Description`) VALUES ('E','Voted Early');
        INSERT INTO `History Codes` (`History Code`,`History Description`) VALUES ('F','Provisional Ballot – Early Vote');
        INSERT INTO `History Codes` (`History Code`,`History Description`) VALUES ('N','Did Not Vote');
        INSERT INTO `History Codes` (`History Code`,`History Description`) VALUES ('P','Provisional Ballot Not Counted');
        INSERT INTO `History Codes` (`History Code`,`History Description`) VALUES ('Y','Voted at Polls');
        INSERT INTO `History Codes` (`History Code`,`History Description`) VALUES ('Z','Provisional Ballot – Vote at Poll');
    END IF;

END;
$$

delimiter ;

CALL bootstrapHistoryCodes();

DROP procedure IF EXISTS `bootstrapHistoryCodes`;

DROP procedure IF EXISTS `bootstrapPartyCodes`;

delimiter $$

create procedure bootstrapPartyCodes()     
begin
    CREATE TABLE IF NOT EXISTS `Party Codes` (
      `Party Code` char(3) COLLATE utf8_unicode_ci NOT NULL,
      `Party Description` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
      `Party Simple Name` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
      PRIMARY KEY (`Party Code`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
    IF ((SELECT COUNT(*) FROM `Party Codes`) < 1) THEN
        INSERT INTO `Party Codes` (`Party Code`,`Party Description`,`Party Simple Name`) VALUES ('AEL','Americans Elect Florida','Americans Elect');
        INSERT INTO `Party Codes` (`Party Code`,`Party Description`,`Party Simple Name`) VALUES ('AIP','American’s Party of Florida','Americans');
        INSERT INTO `Party Codes` (`Party Code`,`Party Description`,`Party Simple Name`) VALUES ('CPF','Constitution Party of Florida','Constitution');
        INSERT INTO `Party Codes` (`Party Code`,`Party Description`,`Party Simple Name`) VALUES ('DEM','Florida Democratic Party','Democratic');
        INSERT INTO `Party Codes` (`Party Code`,`Party Description`,`Party Simple Name`) VALUES ('ECO','Ecology Party of Florida','Ecology');
        INSERT INTO `Party Codes` (`Party Code`,`Party Description`,`Party Simple Name`) VALUES ('FPP','Florida Pirate Party','Pirate');
        INSERT INTO `Party Codes` (`Party Code`,`Party Description`,`Party Simple Name`) VALUES ('FWP','Florida Whig Party','Whig');
        INSERT INTO `Party Codes` (`Party Code`,`Party Description`,`Party Simple Name`) VALUES ('GRE','Green Party of Florida','Green');
        INSERT INTO `Party Codes` (`Party Code`,`Party Description`,`Party Simple Name`) VALUES ('IDP','Independence Party of Florida','Independence');
        INSERT INTO `Party Codes` (`Party Code`,`Party Description`,`Party Simple Name`) VALUES ('INT','Independent Party of Florida','Independent');
        INSERT INTO `Party Codes` (`Party Code`,`Party Description`,`Party Simple Name`) VALUES ('LIB','Libertarian Party of Florida','Libertarian');
        INSERT INTO `Party Codes` (`Party Code`,`Party Description`,`Party Simple Name`) VALUES ('OBJ','Objectivist Party of Florida','Objectivist');
        INSERT INTO `Party Codes` (`Party Code`,`Party Description`,`Party Simple Name`) VALUES ('PSL','Party for Socialism and Liberation-Florida','Socialism and Liberation');
        INSERT INTO `Party Codes` (`Party Code`,`Party Description`,`Party Simple Name`) VALUES ('REF','Reform Party','Reform');
        INSERT INTO `Party Codes` (`Party Code`,`Party Description`,`Party Simple Name`) VALUES ('REP','Republican Party of Florida','Republican');
        INSERT INTO `Party Codes` (`Party Code`,`Party Description`,`Party Simple Name`) VALUES ('TPF','Tea Party of Florida','Tea');
    END IF;
END;
$$

delimiter ;

CALL bootstrapPartyCodes();

DROP procedure IF EXISTS `bootstrapPartyCodes`;

DROP procedure IF EXISTS `bootstrapRaceCodes`;

delimiter $$

create procedure bootstrapRaceCodes()     
begin
    CREATE TABLE IF NOT EXISTS `Race Codes` (
      `Race Code` int(11) unsigned NOT NULL DEFAULT '0',
      `Race Description` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
      PRIMARY KEY (`Race Code`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
    IF ((SELECT COUNT(*) FROM `Race Codes`) < 1) THEN
        INSERT INTO `Race Codes` (`Race Code`,`Race Description`) VALUES (1,'American Indian or Alaskan Native');
        INSERT INTO `Race Codes` (`Race Code`,`Race Description`) VALUES (2,'Asian Or Pacific Islander');
        INSERT INTO `Race Codes` (`Race Code`,`Race Description`) VALUES (3,'Black, Not Hispanic');
        INSERT INTO `Race Codes` (`Race Code`,`Race Description`) VALUES (4,'Hispanic');
        INSERT INTO `Race Codes` (`Race Code`,`Race Description`) VALUES (5,'White, Not Hispanic');
        INSERT INTO `Race Codes` (`Race Code`,`Race Description`) VALUES (6,'Other');
        INSERT INTO `Race Codes` (`Race Code`,`Race Description`) VALUES (7,'Multi-racial');
        INSERT INTO `Race Codes` (`Race Code`,`Race Description`) VALUES (9,'Unknown');
    END IF;
END;
$$

delimiter ;

CALL bootstrapRaceCodes();

DROP procedure IF EXISTS `bootstrapRaceCodes`;

DROP procedure IF EXISTS `bootstrapVoterStatusCodes`;

delimiter $$

create procedure bootstrapVoterStatusCodes()     
begin
    CREATE TABLE IF NOT EXISTS `Voter Status Codes` (
      `Status Code` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
      `Status Description` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
      PRIMARY KEY (`Status Code`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
    IF ((SELECT COUNT(*) FROM `Voter Status Codes`) < 1) THEN
        INSERT INTO `Voter Status Codes` (`Status Code`,`Status Description`) VALUES ('ACT','Active');
        INSERT INTO `Voter Status Codes` (`Status Code`,`Status Description`) VALUES ('INA','Inactive');
        INSERT INTO `Voter Status Codes` (`Status Code`,`Status Description`) VALUES ('PRE','Preregister Minors (17 Years Of Age)');
    END IF;
END;
$$

delimiter ;

CALL bootstrapVoterStatusCodes();

DROP procedure IF EXISTS `bootstrapVoterStatusCodes`;

-- ----------------------------
-- Procedure structure for `buildPartyVoters`
-- ----------------------------
DROP PROCEDURE IF EXISTS `buildPartyVoters`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `buildPartyVoters`(countyCode CHAR(3),countyName VARCHAR(128),importDate DATE)
BEGIN
	DECLARE partyCode VARCHAR(3) DEFAULT '';
	DECLARE partyName VARCHAR(128) DEFAULT '';
	DECLARE no_more_rows BOOLEAN;
	DECLARE loop_cntr INT DEFAULT 0;
	DECLARE num_rows INT DEFAULT 0;
	DECLARE party_cur CURSOR FOR
		SELECT 
			`Party Code`,
			`Party Simple Name`
		FROM  `FloridaVoterCodes`.`Party Codes`;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET no_more_rows = TRUE;

	OPEN party_cur;
	select FOUND_ROWS() into num_rows;
	the_loop: LOOP
		FETCH  party_cur
		INTO   partyCode,
			partyName;
		IF no_more_rows THEN
			CLOSE party_cur;
			LEAVE the_loop;
		END IF;
		SET @countyCode = countyCode;
		SET @partyCode = partyCode;
		SET @importDate = importDate;
	
		SET @l_sql =CONCAT('CREATE TABLE IF NOT EXISTS `FloridaVoterData`.`',countyName,' ',partyName,' Voters` LIKE `FloridaVoterData`.`Voters`');
		PREPARE stmt1 FROM @l_sql;
		EXECUTE stmt1 ;

		DEALLOCATE PREPARE stmt1;

		SET @l_sql =CONCAT('DELETE FROM `FloridaVoterData`.`',countyName,' ',partyName,' Voters` WHERE `County Code`=? AND `Export Date`=?');
		PREPARE stmt1 FROM @l_sql;
		EXECUTE stmt1 USING @countyCode,@importDate;

		DEALLOCATE PREPARE stmt1;

		SET @l_sql =CONCAT('INSERT INTO `FloridaVoterData`.`',countyName,' ',partyName,' Voters` SELECT * FROM `FloridaVoterData`.`',countyName,' Voters` WHERE `Party Affiliation`=?');
		PREPARE stmt1 FROM @l_sql;
		EXECUTE stmt1 USING @partyCode;

		DEALLOCATE PREPARE stmt1;    

	END LOOP the_loop;



END
;;
DELIMITER ;

EOT;
    }
}

?>
