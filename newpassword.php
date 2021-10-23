<?php include "includes/config.php"?>
<?php include "includes/sanitizer.php"?>
<?php include "includes/functions.php"?>
<?php include "includes/Constants.php"?>
<?php include "phpmailer/sendemail.php"?>
<?php

$func = new Functions($connection);
if (!$_GET["token"]) {
    header("Location:error.php");
}
if (isset($_POST["submit"])) {
    $password1 = FormSanitizer::sanitizeFormPassword($_POST["mdp1"]);
    $password2 = FormSanitizer::sanitizeFormPassword($_POST["mdp2"]);
    $email = $_SESSION["reinitialisation"];
    $querySelect = $connection->prepare("SELECT mdp FROM utilisateur WHERE email=:em");
    $querySelect->bindValue(":em", $email);
    $querySelect->execute();
    $func->reinitialiserMdp($email, $password1, $password2);
    $func->newPassword($password1, $password2);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600" rel="stylesheet"
        type="text/css" />
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="assets/style/style.css" />

    <title>Reinitialisation</title>
    <style>
    body {
        background: url(back.jpg);
        background-repeat: no-repeat;
        background-size: cover;
    }
    </style>
</head>

<body>

    <body>
        <div style="width:20%;height:100%;background-color:white;padding:20px;position:absolute;opacity:0.5">
            <h2 style="text-align:center">Erreurs</h2>
            <ul style="color:red;font-size:20px;">
                <li> <?php echo $func->getError(Constants::$loginFailed); ?></li>

            </ul>
        </div>
        <div class="testbox">
            <h1>Reinitialisation</h1>

            <form action="newpassword.php" method="POST">
                <hr>
                <label id="icon" for="mdp1"><i class="icon-user"></i></label>
                <input type="text" name="mdp1" id="email" placeholder="Nouveau mot de passe" required />
                <label id="icon" for="mdp2"><i class="icon-shield"></i></label>
                <input type="password" name="mdp2" id="mdp" placeholder="Confirmer le mot de passe" required />
                <a href="#" class="button"><input type="submit" value="submit" name="submit" class="button"></a>
        </div>
    </body>

</html>
</body>

</html>