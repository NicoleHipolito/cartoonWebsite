<!DOCTYPE html>
<html>
    <head>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Raleway" rel="stylesheet">
        <link rel = "stylesheet" type = "text/css" href = "style.css" />
    </head>
    <body>
        <center>
            <?php
                include 'header.php';
                session_start();
                session_destroy();
            ?>
            <h1>THANK YOU FOR YOUR ORDER</h1>
            
            <form method="POST">
                <button type="submit" name="relocate" formaction="connection.php">Order Again</button>
            </form>
        </center>
    </body>
</html>