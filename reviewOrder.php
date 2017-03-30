<?php
    include "header.php";
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <h2>Your order:</h2>
        <?php
            $servername = getenv('IP');
            $dbPort = 3306;
            $database = "cartoonCatalog";
            $username = getenv('C9_USER');
            $password = "";
            
            $dbConn = new PDO("mysql:host=$servername;port=$dbPort;dbname=$database", $username, $password);
            $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        
            foreach ($_POST["add"] as $data){
                array_push($_SESSION["shoppingCart"], $data);
            }
            
            // $_SESSION["shoppingCart"] = $_POST["add"];
            
            // var_dump($_SESSION);
            // echo '<br>';
            // var_dump($_SESSION["shoppingCart"]);
            
            foreach ($_SESSION["shoppingCart"] as $data) {
                $sql = "SELECT `Episode`.price
                        FROM `Episode`
                        WHERE `Episode`.episodeName = '$data'";
                
                
                $stmt = $dbConn->prepare($sql);
                $stmt->execute();
                
                $price = $stmt->fetch(PDO::FETCH_ASSOC);
                
                echo "Episode Name: ". $data;
                echo " - Price: $".$price["price"];
                echo "<br>";
            }
            
            
        ?>
    </body>
</html>