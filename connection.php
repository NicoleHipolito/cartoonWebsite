
<!DOCTYPE html>
<html>
    <head>
        <title>Cartoons</title>
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
            function printTable($dbConn, $sql) {
                $stmt = $dbConn->prepare($sql);
                $stmt->execute();
                
                echo '<table>';
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC))  {
                    echo '<tr>';
                    foreach($row as $col) {
                        echo '<th>'.$col.'</th>';    
                    }
                    echo '</tr>';
                }
                echo '</table>';
            }
        ?>
        
        <div>
            <form action="poop.php" method="POST">
                
            <label>Filter By</label><br>
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
                    FROM `Character`";
                    
            $labels = array('Testing123..');
                        
            if ($_POST["filter"] == "title") {
                $sql = "SELECT *
                        FROM `Show`";
                $labels = array('Show Title'); // TODO, fill in rest
                 
            } else if ($_POST["filter"] == "date") {
                $sql = "SELECT *
                        FROM Episode";
                $labels = array('Episode Id'); // TODO, fill in rest
        
            } else if ($_POST["filter"] == "creator") {
                $sql = "SELECT *
                        FROM `Character`";
                $labels = array('Character Name'); // TODO, fill in rest
            }
            
            printTable($dbConn, $sql);
        
        ?>
        
        
        
    </body>
</html>
