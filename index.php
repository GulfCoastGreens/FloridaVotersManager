<?php 
/*
 * The web interface for the Florida Contact Manager
 * 
 * Web front end that contains all the plugins, basic structure and css
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
 * @package     index.php
 * @version     0.1
 * @author      James Jones (stpetegreens.org)
 * @since       04/11/2012
 * @license     GPL v2
 * @category    FloridaVotersManager
 * @copyright   2011-2012 James Jones, all rights reserved.
 */
    include_once "ContactManagerServices.php";
    // Merge the POST and GET vars
    $params = array_merge($_GET, $_POST);

    $vts = new ContactManagerServices();
    $vts->invokeMethod($params);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <!-- <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> -->
        <title>Contact Manager</title>
        <meta name="description" content="A mechanism for using remote voter databases" />
        <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/south-street/jquery-ui.css" type="text/css" rel="Stylesheet" />
        <link rel="stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.2/css/jquery.dataTables.css">
        <link rel="stylesheet" type="text/css" href="js/blueimp-jQuery-File-Upload/css/jquery.fileupload-ui.css">
        <script type='text/javascript' src='https://www.google.com/jsapi'></script>
        
        <script language='javascript'>
            google.load("search", "1");
            google.load("jquery", "1.7.1");
            google.load("jqueryui", "1.8.17");
            google.load("maps", "2");
        </script>
            
        <script type="text/javascript" charset="utf8" src="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.2/jquery.dataTables.min.js"></script>
        <script type='text/javascript' src="js/blueimp-jQuery-File-Upload/js/jquery.fileupload.js"></script>
        <script type='text/javascript' src="js/blueimp-jQuery-File-Upload/js/jquery.fileupload-ui.js"></script>        
        <script type='text/javascript' src='js/jquery.Importer.js'></script>
        <script type='text/javascript' src='js/jquery.ui.Importer.js'></script>
        <script type='text/javascript' src='js/jquery.ui.ContactManager.js'></script>
        <script type='text/javascript' src='js/TableTools-2.1.2/media/js/TableTools.min.js'></script>
        <script language='javascript'>
            google.setOnLoadCallback(function() {
                // executes when complete page is fully loaded, including all frames, objects and images
//                alert("window is loaded");
                $('body').ContactManager();
                var map = new GMap2(
                    $('body')
                    .append(
                        $('<div />',{
                            'id': 'map_canvas'
                        })
                    )
                    .find('div#map_canvas')[0]
                )
                map.setCenter(new GLatLng(37.4419, -122.1419), 13);
                // resizeMap();
//                var map = new GMap2(document.getElementById("map_canvas"));
//                map.setCenter(new GLatLng(37.4419, -122.1419), 13);
  
                
            });

        </script>
    </head>
    <body>
        <?php
        // put your code here
        ?>
    </body>
</html>
