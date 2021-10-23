<?php include "includes/config.php"?>
<?php include "includes/sanitizer.php"?>
<?php include "includes/functions.php"?>
<?php include "includes/Constants.php"?>
<?php

// var_dump($account);
$func = new Functions($connection);
if (isset($_POST["submit"])) {

    $email = FormSanitizer::sanitizeFormUsername($_POST["email"]);
    $password = FormSanitizer::sanitizeFormPassword($_POST["mdp"]);
    $success = $func->login($email, $password);
    if ($success) {
        // Store session
        $_SESSION["userLoggedIn"] = $email;
        header("Location: index.php");
    }
}

function getInputValue($name)
{
    if (isset($_POST["$name"])) {
        echo $_POST[$name];
    }
}

?>


<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="assets/style/style.css" />

    <title>Login</title>
    <style>
    body {
        background: url(back.jpg);
        background-repeat: no-repeat;
        background-size: cover;
    }
    </style>
</head>

<body>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600" rel="stylesheet"
        type="text/css" />
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.css" rel="stylesheet" />
    <div style="width:20%;height:100%;background-color:white;padding:20px;position:absolute;opacity:0.5">
        <h2 style="text-align:center">Erreurs</h2>
        <ul style="color:red;font-size:20px;">
            <li> <?php echo $func->getError(Constants::$loginFailed); ?></li>
            <li> <?php echo $func->getError(Constants::$tokenExpired); ?></li>
        </ul>
    </div>
    <div class="testbox">
        <h1>Login</h1>

        <form action="login.php" method="POST">
            <hr>
            <label id="icon" for="email"><i class="icon-user"></i></label>
            <input type="text" name="email" id="email" placeholder="Email" required />
            <label id="icon" for="mdp"><i class="icon-shield"></i></label>
            <input type="password" name="mdp" id="mdp" placeholder="Password" required />
            <a href="#" class="button"><input type="submit" value="submit" name="submit" class="button"></a>
            <br>
            <a href="register.php" class="signInMessage">
                <p>Vous avez besoin d'un compte ?</p>
            </a>
            <a href="reinitialisation.php">
                <p> OU Mot de passe oublie ?</p>
            </a>

            <p>En cliquant Login, vous accéptez<a href="#">les conditions d'utilisations</a>.</p>
        </form>
    </div>
</body>

</html>