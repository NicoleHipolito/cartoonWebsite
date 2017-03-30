
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
            // ******************* Display Filtered Tables (Shows) **********************
            function displayShowInfo($sort) {
                $dbConn = getConnected();
                
                // Default Values
                $sql = "SELECT *
                        FROM `Show`";
                        
                $labels = array('Show Title', 'Number of Seasons',
                                'Number of Episodes', 'Creator', 'Price');
               
                if ($sort == "desc")
                    $sort = "DESC";
                else 
                     $sort = "ASC";
                
               if ($_POST["filter"] == "price") {
                    $sql = "SELECT `Show`.price,`Show`.showTitle, `Show`.creator
                            FROM `Show`
                            ORDER BY `Show`.price ".$sort;
                    $labels = array('Price', 'Show Title', 'Creator');
            
                } else if ($_POST["filter"] == "creator") {
                    $sql = "SELECT `Show`.creator, `Show`.showTitle, `Show`.price
                            FROM `Show`
                            ORDER BY `Show`.creator ".$sort;
                    $labels = array('Creator', 'Show Title', 'Price');
                } else {
                    $sql = "SELECT *
                            FROM `Show`
                            ORDER BY `Show`.showTitle ".$sort;
                    $labels = array('Show Title', 'Number of Seasons',
                                    'Number of Episodes', 'Creator', 'Price');
                }
                
                printTable($dbConn, $sql, $labels);
                
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
            <!--Displaying Either Episodes or Shows-->
            <label>Display </label>
            <input type="radio" name="display" value="show" required/>
            <label>Show</label>
            
            <input type="radio" name="display" value="episode" />
            <label>Episode</label>
        </div>
        
        <?php
            if ($_POST["display"] == "episode") {
        ?>
            <div>
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
            </div>
        
        <?php
            } else if ($_POST["display"] == "show") {
        ?>
            <div>
                <!--Filter-->
                <label>Filter Show By: </label>
                <input type="radio" name="filter" value="title"/>
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
            </div>
            
        <?php
            } 
        ?>
        <div>
            <input type="submit" value="Submit" />
        </div>
        
        <?php
            
            $sort = $_POST["sort"];
            
            if ($_POST["display"] == "episode") {
                displayEpisodeInfo($sort);   
            } else {
                displayShowInfo($sort);
            }
        ?>
        
        
        
    </body>
</html>
