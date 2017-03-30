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
            
            $_SESSION["shoppingCart"] = array_unique($_SESSION["shoppingCart"]);
            echo "<table>";
            echo "<tr style='background:#000099;color:white;'><th><b>Episode Name</b></th><th><b>Price</b></th></tr>";
            
            foreach ($_SESSION["shoppingCart"] as $data) {
                $sql = "SELECT `Episode`.price
                        FROM `Episode`
                        WHERE `Episode`.episodeName = '$data'";
                
                
                $stmt = $dbConn->prepare($sql);
                $stmt->execute();
                
                $tableData = $stmt->fetch(PDO::FETCH_ASSOC);
                
                echo "<tr style='background:lightblue;'><td>$data</td>";
                echo "<td>".$tableData["price"]."</td></tr>";
                $_SESSION["totalPrice"] += $tableData["price"];
            }
            echo "<tr style='background:#E6F3F7;'><td><b>Total Price</b></td><td><b>".$_SESSION["totalPrice"]."</b></td></tr>";
            echo "</table";
            
            
        ?>
    </body>
</html>