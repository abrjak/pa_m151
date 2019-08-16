<?php

include('dbconnector.php');

$error = $message = '';

if (isset($_SESSION['loggedIn'])){
         $message = "Your Session is valid and you're logged in.";
         session_regenerate_id(true);

         if (empty($error)){
            // Get Username form Session
            $username = $_SESSION['username'];
            // Select-Query to get UserId from DB
	        $query = "SELECT user_id from tbl_user WHERE username = ?";
			$statement = $mysqli->prepare($query);
			$statement->bind_param("s", $username);
			$statement->execute();
            $result = $statement->get_result();
			$statement->close();
            // After Calcs are complete
            $result->free();    

} else {
    $error = "Failed to load Accounts";
}

    } else {
        header('Location: overview.php');
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>myFinance - Overview</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <div id="backgroundDiv">
        <div class="container" id="overviewDiv">
            <h3>Overview</h3>
            <form method="POST" action="">
                <!-- TODO: Overview-Page Design-->
                <?php
                echo "<div class=\"alert alert-success\" role=\"alert\">" . $result . "</div>";
                // Ausgabe der Fehlermeldungen
                if(!empty($error)){
                    echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
                }
                ?>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>

</html>