<?php include "includes/config.php"?>
<?php include "includes/Constants.php"?>
<?php include "includes/sanitizer.php"?>
<?php include "includes/functions.php"?>

<?php
$func = new Functions($connection);
if (isset($_POST['submit'])) {
    $username = FormSanitizer::sanitizeFormUsername(htmlspecialchars($_POST['nom']));
    $email = FormSanitizer::sanitizeFormEmail(htmlspecialchars($_POST['email']));
    $mdp1 = FormSanitizer::sanitizeFormPassword(htmlspecialchars($_POST['mdp1']));
    $mdp2 = FormSanitizer::sanitizeFormPassword(htmlspecialchars($_POST['mdp2']));
    $sexe = $_POST['gender'];
    $titre = $_POST['titre'];
    $success = $func->register($username, $email, $mdp1, $mdp2, $sexe, $titre);
    if ($success) {
        // Store session
        $_SESSION["userLoggedIn"] = $email;
        header("Location: index.php");
    }
    function getInputValue($name)
    {
        if (isset($_POST["$name"])) {
            echo $_POST[$name];
        }
    }
    // if ($mdp1 === $mdp2) {
    //     $sql = "INSERT INTO utilisateur (pseudo, email, mdp,sexe,titre) VALUES (?,?,?,?,?)";
    //     $success = $connection->prepare($sql)->execute([$username, $email, password_hash($mdp1, PASSWORD_BCRYPT), $sexe, $titre]);
    //     echo "<h1 style='color:green;text-align:center'>Reussite</h1>";
    // } else {
    //     echo "MDP dont' matrch";
    // }
}
?>
<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="assets/style/style.css" />

    <title>Register</title>
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
            <li> <?php echo $func->getError(Constants::$usernameCharacters); ?></li>

            <li><?php echo $func->getError(Constants::$usernameTaken); ?></li>

            <li> <?php echo $func->getError(Constants::$emailInvalid); ?></li>

            <li><?php echo $func->getError(Constants::$emailTaken); ?></li>

            <li> <?php echo $func->getError(Constants::$passwordLength); ?></li>
        </ul>
    </div>

    <div class="testbox">
        <h1>Création de compte</h1>

        <form action="register.php" method="POST">
            <hr>
            <div class="accounttype">
                <input type="radio" value="personal" id="radioOne" name="titre" required />
                <label for="radioOne" class="radio" chec>Particulier</label>
                <input type="radio" value="company" id="radioTwo" name="titre" />
                <label for="radioTwo" class="radio">Professionel</label>
            </div>
            <hr>
            <label id="icon" for="email"><i class="icon-envelope "></i></label>
            <input type="text" name="email" id="name" placeholder="Email" required />

            <label id="icon" for="nom"><i class="icon-user"></i></label>
            <input type="text" name="nom" id="name" placeholder="Name" required />
            <label id="icon" for="mdp"><i class="icon-shield"></i></label>
            <input type="password" name="mdp1" id="name" placeholder="Password" required />

            <label id="icon" for="mdp"><i class="icon-shield"></i></label>

            <input type="password" name="mdp2" id="name" placeholder="Confirm Password" required />
            <div class="gender">
                <input type="radio" value="male" id="male" name="gender" required />
                <label for="male" class="radio" chec>Male</label>
                <input type="radio" value="female" id="female" name="gender" />
                <label for="female" class="radio">Female</label>
            </div>
            <a href="login.php" class="signInMessage">Vous avez déjà un compte?</a>
            <p>En cliquant enregistrer, vous accéptez<a href="#">les conditions d'utilisations</a>.</p>
            <a href="#" class="button"><input type="submit" value="submit" name="submit" class="button"></a>
        </form>
    </div>

</body>

</html>