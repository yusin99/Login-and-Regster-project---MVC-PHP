<?php include "includes/config.php"?>
<?php include "includes/functions.php"?>
<?php include "includes/sanitizer.php"?>
<?php include "includes/Constants.php"?>

<?php

if (!$_SESSION["userLoggedIn"]) {
    header("Location:error.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <style>
    th,
    td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    </style>
</head>

<body>
    <h1 style="color:green;text-align:center">Successfuly logged in</h1>
    <?php echo "Hello " . $_SESSION['userLoggedIn']; ?>
    <button><a href="logout.php">LOGOUT BUTTON </a> </button>

    <table cellspacing="5" cellpadding="5" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>PSEUDO</th>
                <th>EMAIL</th>
                <th>GENDER</th>
                <th>Titre</th>
                <th>Date Creation Compte</th>
                <th>Delete</th>
                <th>Modifier</th>
            </tr>
        </thead>
        <tbody>
            <?php
$result = $connection->prepare("SELECT * FROM utilisateur ORDER BY idUtilisateur DESC");
$result->execute();
for ($i = 0; $row = $result->fetch(PDO::FETCH_ASSOC); $i++) {
    ?>
            <tr>
                <td><label><?php echo $row['idUtilisateur']; ?></label></td>
                <td><label><?php echo $row['pseudo']; ?></label></td>
                <td><label><?php echo $row['email']; ?></label></td>
                <td><label><?php echo $row['sexe']; ?></label></td>
                <td><label><?php echo $row['titre']; ?></label></td>
                <td><?php echo $row['date_creation']; ?></td>
                <td><label>
                        <form method="POST"> <input type="hidden" name="idUtilisateur"
                                value='<?php echo $row['idUtilisateur'] ?>'>
                            <input type="submit" value="Delete" name="delete"
                                style="margin-left:30px;background-color:red;color:white;font-size:20px">
                    </label></td>
                <td><label><input type="submit" name="modifier" value="modifier"
                            style="margin-left:30px;background-color:orange;color:white;font-size:20px"></form></label>
                </td>
            </tr>
            <?php }?>
        </tbody>
    </table>

    <?php
if (isset($_REQUEST["delete"])) {
    $sql = "DELETE FROM utilisateur WHERE idUtilisateur = :id";
    $result1 = $connection->prepare($sql);
    $result1->bindParam(':id', $id, PDO::PARAM_INT);
    $id = $_REQUEST['idUtilisateur'];
    $result1->execute();
    echo $result1->rowCount() . " Row deleted </br>";
    unset($result1);
    header("Location:index.php");
}
?>

</body>

</html>