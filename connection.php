
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
            // ******************* Display Filtered Tables (Episodes) **********************
            function displayEpisodeInfo($sort) {
                $dbConn = getConnected();
                
                if ($sort== "desc")
                    $sort = "DESC";
                else 
                     $sort = "ASC";
                
            
                if ($_POST["filter"] == "show") {
                    $sql = "SELECT Episode.showTitle, Episode.episodeName, Episode.price
                            FROM Episode
                            ORDER BY Episode.showTitle ".$sort;
                    $labels = array('Show Title', 'Episode Name', 'Price');
            
                } else if ($_POST["filter"] == "price") {
                    $sql = "SELECT Episode.price, Episode.episodeName, Episode.showTitle
                            FROM Episode
                            ORDER BY Episode.price ".$sort;
                    $labels = array('Price', 'Episode Name', 'Show Title');
                } else {
                    // Default Values
                    $sql = "SELECT Episode.episodeName, Episode.showTitle, Episode.price
                            FROM Episode
                            ORDER BY Episode.episodeName ".$sort;
                            
                    $labels = array('Episode Name', 'Show Title', 'Price');
                    
                }
                
                printTable($dbConn, $sql, $labels);
                
            }
        ?>

        <div>
             <form action="connection.php" method="POST">
            <!--Filter-->
            <label>Filter Episode By: </label>
            <input type="radio" name="filter" value="name"/>
            <label>Episode Name</label>
            
            <input type="radio" name="filter" value="show" />
            <label>Show</label>
            
            <input type="radio"  name="filter" value="price" />
            <label>Price</label>
            
            <?php
                echo $tab;
            ?>
            
            <!--Sorting-->
            <label>Sort By: </label>
            <input type="radio" name="sort" value="asc"/>
            <label>Ascending</label>
            
            <input type="radio" name="sort" value="desc" />
            <label>Descending</label> <br>
            
            <input type="submit" value="Submit" />
        </div>
        
        <?php
            $sort = $_POST["sort"];

            displayEpisodeInfo($sort);   
        ?>
        
    </body>
</html>
