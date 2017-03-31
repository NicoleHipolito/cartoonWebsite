<?php
    session_start();
    if (empty($_SESSION["shoppingCart"])){
        $_SESSION["shoppingCart"] = array();
    }
    if(empty($_SESSION["totalPrice"])){
        $_SESSION["totalPrice"] = 0;
    }
    
    // if addEpisode == true
    // add it array_push($_SESSION["shoppingCart"], $_POST["episodeId"])
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Cartoons</title>
        
        <!--Style (External) Sheet Reference-->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Raleway" rel="stylesheet">
        <link rel = "stylesheet" type = "text/css" href = "style.css" />
        <script type="text/javascript" src="jquery-3.2.0.js"></script>
        <script type="text/javascript">
            var isShown = false;
            $(document).ready(function(){
                $('.togglebutton').click(function() {
                    $("#"+ $(this).data('id')).toggle();
                });
            });
        </script>
    </head>
    <body>
        <div class="wrapper">
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
                        $index = 0;
                        $stmt = $dbConn->prepare($sql);
                        $stmt->execute();
                        
                        echo "<form method='POST' action='reviewOrder.php'>";
                        echo '<table>';
                        
                        echo '<tr style="background:#982F74;">';
                        echo '<th><b>Episode Name</th>';
                        echo '<th><b>Show Title</th>';
                        echo '<th><b>Price</th>';
                        echo '<th><b>Add to Cart</th>';
                        echo '</tr>'; 
                        
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))  {
                            $count += 1;
                            echo '<tr>';
                            // echo '<td>';
                            // $row["episodeName"]
                            echo '<td>';
                                echo "<span class='togglebutton' id='fakeLink' data-id=" . $index . ">" . $row['episodeName'] . "</span>";
                                echo "<div id=" . $index ." style='display:none'>";
                                    echo "<br>Creator: " . $row["creator"] . "<br>"
                                        ."Length: " . $row["length"] . " mins<br>"
                                        ."Air Date: " . $row["airDate"] . "<br>"
                                        ."Season: " . $row["seasonNum"] . "<br>";
                                        $characters = getChar($row["showTitle"]);
                                        echo "<br><u>Character List:</u>\n";
                                        echo "<ul>";
                                        foreach ($characters as $character) {
                                            echo "<li>".$character["characterName"];
                                            if($character["age"]!="N/A")
                                                echo "(".$character["age"]." yrs old)</li>";
                                            else
                                                echo "(".$character["age"].")</li>";
                                        }
                                        echo "</ul>";
                                        ++$index;
                                echo "</div>";
                            echo '</td>';
                            // echo '</td>';
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
                        $sql = 'SELECT e.*, s.creator
                                FROM Episode as e
                                JOIN `Show` as s
                                ON e.showTitle = s.showTitle 
                                ORDER BY e.showTitle '.$sort;
                        $labels = array('Show Title', 'Episode Name', 'Price');
                
                    } else if ($_POST["filter"] == "price") {
                        $sql = 'SELECT e.*, s.creator
                                FROM Episode as e
                                JOIN `Show` as s
                                ON e.showTitle = s.showTitle 
                                ORDER BY e.price '.$sort;
                        $labels = array('Price', 'Episode Name', 'Show Title');
                    } else {
                        // Default Values
                        $sql = 'SELECT e.*, s.creator
                                FROM Episode as e
                                JOIN `Show` as s
                                ON e.showTitle = s.showTitle 
                                ORDER BY e.episodeName '.$sort;
                        $labels = array('Episode Name', 'Show Title', 'Price');
                    
                    }
                    printTable($dbConn, $sql);
                    
                    
                    
                }
                function getChar($show) {
                    $dbConn = getConnected();
                    $sql = "SELECT c.characterName, c.age
                            FROM `Character` as c
                            WHERE c.showTitle = '$show'";
                    $stmt = $dbConn->prepare($sql);
                    $stmt->execute();
                    $charArray = array();
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        array_push($charArray, $row);
                    
                    }
                    return $charArray;
                    
                }
            ?>
            <center>
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
                    <label>Descending</label>
                    
                    <?php
                        echo $tab;
                    ?>
                    
                    <input type="submit" value="Submit" />
                    </form>
                </div>
            </center>
            <br>
            <?php
                $sort = $_POST["sort"];
                echo "<center>";
                displayEpisodeInfo($sort); 
                echo "</center>";
            ?>
            <!--<form method="POST" action="reviewOrder.php">-->
            <!--<input type="submit" value="Place Order">-->
            <!--</form>-->
        </div>
    </body>
</html>
