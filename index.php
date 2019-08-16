<?php

// Establish DB-Connection
include('dbconnector.php');

$error = "";

// Check if Inputs were made
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($error)){
	// check username
	if(!empty(trim($_POST['loginUser']))){
		$username = trim($_POST['loginUser']);

		// prüfung benutzername
		if(!preg_match("/(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,}/", $username) || strlen($username) > 30){
			$error .= "Der Benutzername entspricht nicht dem geforderten Format.<br />";
		}
	}
	// password
	if(!empty(trim($_POST['loginPassword']))){
		$password = trim($_POST['loginPassword']);
		// passwort gültig?
		if(!preg_match("/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $password)){
			$error .= "Das Passwort entspricht nicht dem geforderten Format.<br />";
		}
	}

    // Check DB for User and Password
    if(empty($error)){
        // TODO SELECT Query erstellen, user und passwort mit Datenbank vergleichen
		$query = "SELECT password, username FROM tbl_user WHERE username=?";
        // TODO prepare()
		$statement = $mysqli->prepare($query);
		// TODO bind_param()
		$statement->bind_param("s", $username);
		// TODO execute()
		$statement->execute();
		// TODO Passwort auslesen und mit dem eingegeben Passwort vergleichen
		$result = $statement->get_result();
		// TODO wenn Passwort falsch, oder kein Benutzer mit diesem Benutzernamem in DB: $error .= "Benutzername oder Passwort sind falsch";
		if($result->num_rows === 0){
		    $error .= "Benutzername oder Passwort sind falsch";
		} else {
			// TODO wenn Passwort korrekt:  $message .= "Sie sind nun eingeloggt";
			while($row = $result->fetch_assoc()){
				if (password_verify($password, $row['password'])){
					$message .= "Sie sind nun eingeloggt";
					session_start();
					$_SESSION['loggedIn'] = true;
					$_SESSION['username'] = $username;
					header('Location: http://localhost/M151/PA_AbrahamJakob/overview.php');
				} else {
					$error .= "Benutzername oder Passwort sind falsch";
				}
			}
		}
		$result->free();
		$statement->close();
	}
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>myFinance - Login</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <div id="backgroundDiv">
        <div class="container" id="loginDiv">
            <h3>Login</h3>
            <form method="POST" action="">
                <label for="loginUser">Username:</label>
                <input id="loginUser" name="loginUser" class="form-control" type="text" placeholder="Max Mustermann" autofocus required />
                <br />
                <label for="loginPassword">Password:</label>
                <input id="loginPassword" name="loginPassword" class="form-control" type="password" placeholder="Password" required />
                <br />
                <button type="submit" id="btnLogin" class="btn btn-md btn-primary">Login</button>
                <br />
                <?php
                // Ausgabe der Fehlermeldungen
                if(!empty($error)){
                    echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
                }
                ?>
            </form>
            <hr />
            <a class="pull-left" href="register.php">Register</a>
            <a class="pull-right" href="forgotpassword.php">I forgot my Password</a>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>

</html>