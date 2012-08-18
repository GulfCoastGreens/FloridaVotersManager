<?php

/*
 * Providing for the import of raw text file interation for importing into the database
 * 
 * This handles iterating over directories containing voter and history files and passing those files into the voter and history parsers
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
 * @package     Import.php
 * @version     0.1
 * @author      James Jones (stpetegreens.org)
 * @since       04/11/2012
 * @license     GPL v2
 * @category    FloridaVotersManager
 * @copyright   2011-2012 James Jones, all rights reserved.
 */
include_once 'County.php';
include_once 'Voters.php';
include_once 'Histories.php';
class Import extends County {
    //put your code here
    function __construct() {
        parent::__construct();
        $this->importVoters();
        $this->importHistories();
    }
    
    function importVoters() {
        $fileCount = 0;
        $importPath = getcwd()."/../Voters/";
        if ($handle = opendir($importPath)) {
            /* This is the correct way to loop over the directory. */
            while (false !== ($filename = readdir($handle))) {
                if ($filename != "." && $filename != ".." && $filename != ".htaccess") {
                    if(!is_dir($importPath."/".$filename)) {
                        print "Parsing ".$filename." file count of ".(++$fileCount)."\n";
                        new Voters($filename);
                    }                    
                }                
            }            
        }        
    }
    function importHistories() {
        $fileCount = 0;
        $importPath = getcwd()."/../Histories/";
        if ($handle = opendir($importPath)) {
            /* This is the correct way to loop over the directory. */
            while (false !== ($filename = readdir($handle))) {
                if ($filename != "." && $filename != ".." && $filename != ".htaccess") {
                    if(!is_dir($importPath."/".$filename)) {
                        print "Parsing ".$filename." file count of ".(++$fileCount)."\n";
                        new Histories($filename);
                    }                    
                }                
            }            
        }        
    }
}

new Import();

?>
