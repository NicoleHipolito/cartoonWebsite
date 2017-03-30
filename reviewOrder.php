<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <?php
            include "header.php";
            session_start();
            
            // $count = 0;
            
            // $i = count($_POST["add"]);
            
            // while($i){
            //     array_push($_SESSION["shoppingCart"], $_POST["add"][$count]);
            //     $count++;
            // }
            
            foreach ($_POST["add"] as $data){
                array_push($_SESSION["shoppingCart"], $data);
            }
            
            // $_SESSION["shoppingCart"] = $_POST["add"];
            
            var_dump($_SESSION);
            
            var_dump($_POST);
        ?>
    </body>
</html>