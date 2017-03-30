<!DOCTYPE html>
<html>
    <head>
        
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