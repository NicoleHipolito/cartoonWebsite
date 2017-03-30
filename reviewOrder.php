<?php
    include "header.php";
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <center>
            <h2>Your order:</h2>
            <?php
                $servername = getenv('IP');
                $dbPort = 3306;
                $database = "cartoonCatalog";
                $username = getenv('C9_USER');
                $password = "";
                
                $dbConn = new PDO("mysql:host=$servername;port=$dbPort;dbname=$database", $username, $password);
                $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            
                if (isset($_POST["add"])){
                    foreach ($_POST["add"] as $data){
                        array_push($_SESSION["shoppingCart"], $data);
                    }
                }
                
                $_SESSION["shoppingCart"] = array_unique($_SESSION["shoppingCart"]);
                $prices = array();
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
                    
                    if (!array_key_exists($data,$prices)){
                        $prices["$data"] = $tableData["price"];
                    }
                }
                echo "<tr style='background:#E6F3F7;'><td><b>Total Price</b></td><td><b>".array_sum($prices)."</b></td></tr>";
                echo "</table>";
                
            ?>
            <form method="POST">
                <button type="submit" name="relocate" formaction="connection.php">Add Items</button>
                <button type="submit" name="relocate" formaction="checkout.php">Checkout</button>
            </form>
        </center>
    </body>
</html>