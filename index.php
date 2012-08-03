<?php 
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
        <link rel="stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.1/css/jquery.dataTables.css">
        <script type='text/javascript' src='https://www.google.com/jsapi'></script>
        
        <script language='javascript'>
            google.load("search", "1");
            google.load("jquery", "1.7.1");
            google.load("jqueryui", "1.8.17");
            google.load("maps", "2");
        </script>
            
        <script type="text/javascript" charset="utf8" src="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.1/jquery.dataTables.min.js"></script>
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
