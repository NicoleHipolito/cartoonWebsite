
<!DOCTYPE html>
<html>
    <head>
        <title>Cartoons</title>
        
        <!--Style (External) Sheet Reference-->
        <link rel = "stylesheet" type = "text/css" href = "style.css" />
    </head>
    <body>
        <?php

            // ******************* ESTABLISH CONNE*********************************
            function getConnected() {
                $servername = getenv('IP');
                $dbPort = 3306;
                $database = "cartoonCatalog";
                $username = getenv('C9_USER');
                $password = "";
                
                $dbConn = new PDO("mysql:host=$servername;port=$dbPort;dbname=$database", $username, $password);
                $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
                return $dbConn;
            }
                
            // ******************* SQL DISPLAY TABLE FUNCTION **********************
            function printTable($dbConn, $sql, $labels) {
                    
                    $stmt = $dbConn->prepare($sql);
                    $stmt->execute();
                    
                    echo '<table>';
                    
                    echo '<tr>';
                    for ($i = 0; $i < count($labels); $i++)
                        echo '<th><b>'.$labels[$i].'</th>';
                    echo '</tr>'; 
                    
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))  {
                        echo '<tr>';
                        foreach($row as $col) {
                            echo '<th>'.$col.'</th>';    
                        }
                        echo '</tr>';
                    }
                    echo '</table>';
                    echo '<br>';
            }
        ?>
        
        <div>
            <form action="connection.php" method="POST">
                
            <label>Filter By: </label>
            <input type="radio" name="filter" value="title" required />
            <label>Show Title</label>
            
            <input type="radio" name="filter" value="date" />
            <label>Air Date</label>
            
            <input type="radio"  name="filter" value="creator" />
            <label>Creator</label> <br>
            
            <div>
                <input type="submit" value="Submit" />
            </div>
            
        </div>
        
        <?php
            $dbConn = getConnected();
            
            $sql = "SELECT *
                    FROM `Show`";
                    
            $labels = array('Show Title', 'Number of Seasons',
                            'Number of Episodes', 'Creator', 'Price');
                    
                        
            if ($_POST["filter"] == "title") {
                $sql = "SELECT *
                        FROM `Show`";
                $labels = array('Show Title', 'Number of Seasons',
                                'Number of Episodes', 'Creator', 'Price');
                 
            } else if ($_POST["filter"] == "date") {
                $sql = "SELECT *
                        FROM Episode";
                $labels = array('Episode Id', 'Episode Name', 'Episode Length',
                                'Air Date', 'Show Title', 'Season', 'Price');
        
            } else if ($_POST["filter"] == "creator") {
                $sql = "SELECT *
                        FROM `Character`";
                $labels = array('Character Name', 'Age', 'Number of Episodes', 'Show Title');
            }
            
            printTable($dbConn, $sql, $labels);
        
        ?>
        
        
        
    </body>
</html>
