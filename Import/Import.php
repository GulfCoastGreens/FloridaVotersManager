<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Import
 *
 * @author jam
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
        $importPath = getcwd()."/../Voters/";
        if ($handle = opendir($importPath)) {
            /* This is the correct way to loop over the directory. */
            while (false !== ($filename = readdir($handle))) {
                if ($filename != "." && $filename != ".." && $filename != ".htaccess") {
                    if(!is_dir($importPath."/".$filename)) {
                        print $filename."\n";
                        new Voters($filename);
                    }                    
                }                
            }            
        }        
    }
    function importHistories() {
        $importPath = getcwd()."/../Histories/";
        if ($handle = opendir($importPath)) {
            /* This is the correct way to loop over the directory. */
            while (false !== ($filename = readdir($handle))) {
                if ($filename != "." && $filename != ".." && $filename != ".htaccess") {
                    if(!is_dir($importPath."/".$filename)) {
                        print $filename."\n";
                        new Histories($filename);
                    }                    
                }                
            }            
        }        
    }
}

new Import();

?>
