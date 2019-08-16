<?php

// Establish DB-Connection
include('dbconnector.php');

$error = $message = '';
$firstname = $lastname = $username = $email = $password = '';

if($_SERVER['REQUEST_METHOD'] == "POST"){

    // vorname vorhanden, mindestens 1 Zeichen und maximal 30 Zeichen lang
    if(isset($_POST['registerFirstname']) && !empty(trim($_POST['registerFirstname'])) && strlen(trim($_POST['registerFirstname'])) <= 30){
        // Spezielle Zeichen Escapen > Script Injection verhindern
        $firstname = htmlspecialchars(trim($_POST['registerFirstname']));
    } else {
        // Ausgabe Fehlermeldung
        $error .= "Please insert a valid firstname.<br />";
    }

    // nachname vorhanden, mindestens 1 Zeichen und maximal 30 zeichen lang
    if(isset($_POST['registerLastname']) && !empty(trim($_POST['registerLastname'])) && strlen(trim($_POST['registerLastname'])) <= 30){
        // Spezielle Zeichen Escapen > Script Injection verhindern
        $lastname = htmlspecialchars(trim($_POST['registerLastname']));
    } else {
        // Ausgabe Fehlermeldung
        $error .= "Please insert a valid lastname.<br />";
    }

    // benutzername vorhanden, mindestens 6 Zeichen und maximal 30 zeichen lang
    if(isset($_POST['registerUsername']) && !empty(trim($_POST['registerUsername'])) && strlen(trim($_POST['registerUsername'])) <= 30){
        $username = trim($_POST['registerUsername']);
        // entspricht der benutzername unseren vogaben (minimal 6 Zeichen, Gross- und Kleinbuchstaben)
		if(!preg_match("/(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,}/", $username)){
			$error .= "This username doesn't match the required format.<br />";
		}
    } else {
        // Ausgabe Fehlermeldung
        $error .= "Please insert a valid username.<br />";
    }

    // emailadresse vorhanden, mindestens 1 Zeichen und maximal 100 zeichen lang
    if(isset($_POST['registerMail']) && !empty(trim($_POST['registerMail'])) && strlen(trim($_POST['registerMail'])) <= 100){
        $email = htmlspecialchars(trim($_POST['registerMail']));
        // korrekte emailadresse?
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false){
            $error .= "Please insert a valid email-adress<br />";
        }
    } else {
        // Ausgabe Fehlermeldung
        $error .= "Please insert a valid email-adress.<br />";
    }

    // passwort vorhanden, mindestens 8 Zeichen
    if(isset($_POST['registerPassword']) && !empty(trim($_POST['registerPassword']))){
        $password = password_hash(trim($_POST['registerPassword']), PASSWORD_DEFAULT);
        //entspricht das passwort unseren vorgaben? (minimal 8 Zeichen, Zahlen, Buchstaben, keine Zeilenumbrüche, mindestens ein Gross- und ein Kleinbuchstabe)
        if(!preg_match("/(?=^.{8,}$)((?=.*\d+)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $password)){
            $error .= "This password doesn't match the required format.<br />";
        }
    } else {
        // Ausgabe Fehlermeldung
        $error .= "Please insert a valid password.<br />";
    }

    if(isset($_POST['repeatPassword']) && !empty(trim($_POST['repeatPassword']))){
        $passwordRepeated = password_hash(trim($_POST['repeatPassword']), PASSWORD_DEFAULT);
        //entspricht das passwort unseren vorgaben? (minimal 8 Zeichen, Zahlen, Buchstaben, keine Zeilenumbrüche, mindestens ein Gross- und ein Kleinbuchstabe)
        if(!$password == $passwordRepeated){
            $error .= "Passwords don't match.<br />";
        }
    } else {
     $error .= "Please insert a valid password.<br />";
    }

    // wenn kein Fehler vorhanden ist, schreiben der Daten in die Datenbank
    if(empty($error)){

			// Username and Email is available
			// TODO: INPUT Query erstellen, welches firstname, lastname, username, password, email in die Datenbank schreibt
			$query = "INSERT INTO tbl_user (firstname, lastname, username, email, password) VALUES (?,?,?,?,?)";
			// TODO: Query vorbereiten mit prepare();
			$statement = $mysqli->prepare($query);
			// TODO: Parameter an Query binden mit bind_param();
			$statement->bind_param("sssss", $firstname, $lastname, $username, $email, $password);
			// TODO: Query ausführen mit execute();
			$statement->execute();
			// TODO: Verbindung schliessen
			$statement->close();
			// TODO: Weiterleitung auf index.php
			header("Location: http://localhost/M151/PA_AbrahamJakob/index.php");
        } else {
            $error .= "There is something wrong.";
        }
		$result->free();
    }



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>myFinance - Register</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <div id="backgroundDiv">
        <div class="container" id="registerDiv">
            <h3>Register</h3>
            <form method="POST" action="">
                <label for="registerFirstname">Firstname:</label>
                <input id="registerFirstname" name="registerFirstname" class="form-control" type="text" placeholder="Max" autofocus required />
                <br />
                <label for="registerLastname">Lastname:</label>
                <input id="registerLastname" name="registerLastname" class="form-control" type="text" placeholder="Mustermann" required />
                <br />
                <label for="registerUsername">Username:</label>
                <input id="registerUsername" name="registerUsername" class="form-control" type="text" placeholder="Max Mustermann" required />
                <br />
                <label for="registerMail">Email:</label>
                <input id="registerMail" name="registerMail" class="form-control" type="email" placeholder="max.mustermann@gmail.com" required />
                <br />
                <label for="registerPassword">Password:</label>
                <input id="registerPassword" name="registerPassword" class="form-control" type="password" placeholder="Password" required />
                <br />
                <label for="repeatPassword">Retype Password:</label>
                <input id="repeatPassword" name="repeatPassword" class="form-control" type="password" placeholder="Password" required />
                <br />
                <button type="submit" id="btnRegister" class="btn btn-md btn-primary">Send Registration</button>
                <br />
                <?php
                // Ausgabe der Fehlermeldungen
                if(!empty($error)){
                    echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
                }
                ?>
            </form>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>

</html>