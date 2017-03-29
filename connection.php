<!DOCTYPE html>
<html>
    <head>
        <title> </title>
    </head>
    <body>
        <?php
            $desc = "";
            $name = "";
            function getConnected(){
                try{
                    $database = "cartoonCatalog";
                    $dbhost = "localhost";
                    $port = "3036";
                    $dbuser = getenv("C9_USER");
                    $dbpass = ""; 
                    // Establish the connection
                    $conn = new PDO("mysql:host=$dbhost;dbport=$port;dbname=$database","$dbuser","$dbpass");
                    return $conn;
                }                
                catch(PDOException $e){
                    echo "Failed to get DB handle: " . $e->getMessage() . "\n";
                    exit;
                }
            }
        ?>
    </body>
</html>