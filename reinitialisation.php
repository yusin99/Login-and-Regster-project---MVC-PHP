<?php include "includes/config.php"?>
<?php include "includes/sanitizer.php"?>
<?php include "includes/functions.php"?>
<?php include "includes/Constants.php"?>
<?php include "phpmailer/sendemail.php"?>
<?php

$func = new Functions($connection);
if (isset($_POST["submit"])) {
    // $password1 = FormSanitizer::sanitizeFormPassword($_POST["mdp1"]);
    // $password2 = FormSanitizer::sanitizeFormPassword($_POST["mdp2"]);
    $email = FormSanitizer::sanitizeFormPassword($_POST["email"]);
    $query = $connection->prepare("SELECT * FROM utilisateur WHERE email=:em");
    $query->bindValue(":em", $email);
    $query->setFetchMode(PDO::FETCH_ASSOC);
    $query->execute();
    $table = $query->fetchAll();

    $hash = crypt($email, rand());
    $hash = crypt($hash, time());

    $queryUpdate = $connection->prepare('UPDATE utilisateur SET token = :hs , reinitialisation_tentative_date = NOW() WHERE email = :em');
    $queryUpdate->bindValue(":hs", $hash);
    $queryUpdate->bindValue(":em", $email);
    $queryUpdate->execute();

    if ($query->rowCount() == 1) {
        send_mail($email, "sujet du mail : Reinitialisation mot de passe", "<a href='localhost/loginRegister/newpassword.php?id=" . $table[0]['idUtilisateur'] . "&token=$hash'>Click here</a>");
        echo $_SESSION["reinitialisation"] = $email;
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
    <button><a href="login.php">Return to login</a></button>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600" rel="stylesheet"
        type="text/css" />
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.css" rel="stylesheet" />
    <div style="width:20%;height:100%;background-color:white;padding:20px;position:absolute;opacity:0.5">
        <h2 style="text-align:center">Erreurs</h2>
        <ul style="color:red;font-size:20px;">
            <li><?php echo $func->getError(Constants::$emailInvalid); ?></li>
        </ul>
    </div>
    <div class="testbox">
        <h1>Reinitialisation</h1>

        <form action="reinitialisation.php" method="POST">
            <hr>
            <label id="icon" for="email"><i class="icon-user"></i></label>
            <input type="text" name="email" id="email" placeholder="Email" required />
            <input type="submit" name="submit">
        </form>
    </div>
</body>

</html>