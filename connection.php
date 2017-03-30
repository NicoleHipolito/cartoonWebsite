
<!DOCTYPE html>
<html>
    <head>
        <title>Cartoons</title>
        
        <!--Style (External) Sheet Reference-->
        <link rel = "stylesheet" type = "text/css" href = "style.css" />
    </head>
    <body>
        <?php
            include "header.php";
            $tab = '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;';
            
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
            // ******************* Display Filtered Tables **********************
            function displayShowInfo() {
                $dbConn = getConnected();
                
                // Default Values
                $sql = "SELECT *
                        FROM `Show`";
                        
                $labels = array('Show Title', 'Number of Seasons',
                                'Number of Episodes', 'Creator', 'Price');
               
                if ($_POST["sort"] == "desc")
                    $sort = "DESC";
                else 
                     $sort = "ASC";
                
                if ($_POST["filter"] == "title") {
                    $sql = "SELECT *
                            FROM `Show`
                            ORDER BY `Show`.showTitle ".$sort;
                    $labels = array('Show Title', 'Number of Seasons',
                                    'Number of Episodes', 'Creator', 'Price');
                     
                } else if ($_POST["filter"] == "price") {
                    $sql = "SELECT `Show`.price,`Show`.showTitle, `Show`.creator
                            FROM `Show`
                            ORDER BY `Show`.price ".$sort;
                    $labels = array('Price', 'Show Title', 'Creator');
            
                } else if ($_POST["filter"] == "creator") {
                    $sql = "SELECT `Show`.creator, `Show`.showTitle, `Show`.price
                            FROM `Show`
                            ORDER BY `Show`.creator ".$sort;
                    $labels = array('Creator', 'Show Title', 'Price');
                }
                
                printTable($dbConn, $sql, $labels);
                
            }
            
        ?>
        
        <div>
            <form action="connection.php" method="POST">
                
            <!--Filter-->
            <label>Filter Show By: </label>
            <input type="radio" name="filter" value="title" required />
            <label>Show Title</label>
            
            <input type="radio" name="filter" value="price" />
            <label>Price</label>
            
            <input type="radio"  name="filter" value="creator" />
            <label>Creator</label>
            
            <?php
                echo $tab;
            ?>
            <!--Sorting-->
            <label>Sort By: </label>
            <input type="radio" name="sort" value="asc"/>
            <label>Ascending</label>
            
            <input type="radio" name="sort" value="desc" />
            <label>Descending</label>
            
            
            <div>
                <input type="submit" value="Submit" />
            </div>
            
        </div>
        
        <?php
            displayShowInfo();
        ?>
        
        
        
    </body>
</html>
