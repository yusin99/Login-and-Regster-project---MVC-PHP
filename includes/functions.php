<?php
class Functions
{

    private $con;
    private $errorArray = array();

    public function __construct($con)
    {
        $this->con = $con;
    }

    public function checkToken()
    {
        if (empty($_GET['token'])) {
            echo 'Aucun token n\'a été spécifié';
            exit;
        }
        $query = $this->con->prepare('SELECT reinitialisation_tentative_date FROM utilisateur WHERE token = ?');
        $query->bindValue(1, $_GET["token"]);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);

        if (empty($row)) {
            header("Location:error.php");
            exit;
        }
        $tokenDate = strtotime('+49 seconds', strtotime($row['reinitialisation_tentative_date']));
        $todayDate = time();
        if ($tokenDate < $todayDate) {
            echo "Token Expiré";
            exit;
        }

    }

    public function selectUserLogin($email, $password)
    {
        $success = $this->login($email, $password);
        $sql = "SELECT * FROM utilisateur WHERE email=:em";
        $query = $this->con->prepare($sql);
        $query->bindValue(':em', $email);
        $query->execute();
        $id = $query->fetch();
        if ($success) {
            // Store session
            $_SESSION["userLoggedIn"] = $email;
            header("Location: index.php?idUtilisateur=" . $id['idUtilisateur'] . "");
        }
    }
    public function selectUserRegister($username, $email, $mdp1, $mdp2, $sexe, $titre)
    {
        $success = $this->register($username, $email, $mdp1, $mdp2, $sexe, $titre);

        $sql = "SELECT idUtilisateur FROM utilisateur WHERE email=:em";
        $query = $this->con->prepare($sql);
        $query->bindValue(':em', $email);
        $query->execute();
        $id = $query->fetch();

        if ($success) {
            // Store session
            $_SESSION["userLoggedIn"] = $email;
            header("Location: index.php?idUtilisateur=" . $id['email'] . "");
        }
    }

    public function reinitialiserMdp($em, $pw1, $pw2)
    {
        $this->validatePasswords($pw1, $pw2);
        if (empty($this->errorArray)) {
            if ($pw1 === $pw2) {
                $pw1 = password_hash($pw1, PASSWORD_BCRYPT);
                $query2 = $this->con->prepare("UPDATE utilisateur SET mdp ='$pw1',token = '' WHERE email='$em'");
                $query2->execute();
                header("Location:successReinit.php");
            }
        }
        array_push($this->errorArray, Constants::$loginFailed);
        echo "Error avec le mot de passe";
        return false;
    }

    public function getErrorArray()
    {
        return $this->errorArray;
    }

    public function register($un, $em, $pw, $pw2, $sexe, $titre)
    {
        $this->validateUsername($un);
        $this->validateEmails($em);
        $this->validatePasswords($pw, $pw2);
        if (empty($this->errorArray)) {
            return $this->insertUserDetails($un, $em, $pw, $sexe, $titre);
        }
        return false;
    }

    public function getIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function login($em, $pw)
    {
        $ip = $this->getIp();
        $query = $this->con->prepare("SELECT * FROM utilisateur WHERE email=:em");
        $query->bindValue(":em", $em);
        $query->execute();
        $query2 = $this->con->prepare("INSERT INTO connexion (ip) VALUES ('$ip')");
        $query2->execute();
        $accExist = $query->fetch(PDO::FETCH_OBJ);
        if ($accExist != null) {
            if (password_verify($pw, $accExist->mdp)) {
                return true;
            }
        }
        array_push($this->errorArray, Constants::$loginFailed);
        return false;

    }

    private function insertUserDetails($un, $em, $pw, $sx, $tit)
    {
        $pw = password_hash($pw, PASSWORD_BCRYPT);

        $query = $this->con->prepare("INSERT INTO utilisateur (pseudo, email, mdp,sexe,titre)
                                        VALUES (:un, :em, :pw,:sx,:tit)");

        $query->bindValue(":un", $un);
        $query->bindValue(":em", $em);
        $query->bindValue(":pw", $pw);
        $query->bindValue(":sx", $sx);
        $query->bindValue(":tit", $tit);

        return $query->execute();
    }

    private function validateUsername($un)
    {
        if (strlen($un) < 2 || strlen($un) > 25) {
            array_push($this->errorArray, Constants::$usernameCharacters);
            return;
        }

        $query = $this->con->prepare("SELECT * FROM utilisateur WHERE pseudo=:un");
        $query->bindValue(":un", $un);

        $query->execute();

        if ($query->rowCount() != 0) {
            array_push($this->errorArray, Constants::$usernameTaken);
        }
    }

    private function validateEmails($em)
    {
        if ($em === '') {
            array_push($this->errorArray, Constants::$emailsDontMatch);
            return;
        }

        if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errorArray, Constants::$emailInvalid);
            return;
        }

        $query = $this->con->prepare("SELECT * FROM utilisateur WHERE email=:em");
        $query->bindValue(":em", $em);

        $query->execute();

        if ($query->rowCount() != 0) {
            array_push($this->errorArray, Constants::$emailTaken);
        }
    }

    private function validatePasswords($pw, $pw2)
    {
        $regex = "^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$^";
        if ($pw != $pw2) {
            array_push($this->errorArray, Constants::$passwordsDontMatch);
            return;
        }

        if ((strlen($pw) < 8 || strlen($pw) > 25) && !preg_match($regex, $pw)) {
            array_push($this->errorArray, Constants::$passwordLength);
        }
    }

    public function getError($error)
    {
        if (in_array($error, $this->errorArray)) {
            return "<span class='errorMessage'>$error</span>";
        }
    }

}