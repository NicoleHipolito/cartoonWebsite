<?php
    session_start();
    if (empty($_SESSION["shoppingCart"])){
        $_SESSION["shoppingCart"] = array();
    }
    
    // if addEpisode == true
    // add it array_push($_SESSION["shoppingCart"], $_POST["episodeId"])
?>
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
            function printTable($dbConn, $sql) {
                    $count = 0;
                    $stmt = $dbConn->prepare($sql);
                    $stmt->execute();
                    
                    echo "<form method='POST' action='reviewOrder.php'>";
                    echo '<table>';
                    
                    echo '<tr>';
                    echo '<th><b>Episode Name</th>';
                    echo '<th><b>Show Title</th>';
                    echo '<th><b>Price</th>';
                    echo '<th><b>Add to Cart</th>';
                    echo '</tr>'; 
                    
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))  {
                        $count += 1;
                        echo '<tr>';
                        echo '<td>'.$row["episodeName"].'</td>';
                        echo '<td>'.$row["showTitle"].'</td>';
                        echo '<td>'.$row["price"].'</td>';
                        echo '<td><input type="checkbox" name="add['.$count.']" value="'.$row["episodeName"].'"></td>';
                        echo '</tr>';
                    } 
                    echo '</table>';
                    echo '<input type="submit" value="Place Order">';
                    echo '</form>';
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
                
                printTable($dbConn, $sql);
                
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
            </form>
        </div>
        
        <?php
            $sort = $_POST["sort"];

            displayEpisodeInfo($sort); 
        ?>
        <!--<form method="POST" action="reviewOrder.php">-->
        <!--<input type="submit" value="Place Order">-->
        <!--</form>-->
        
    </body>
</html>
