<?php include "includes/config.php"?>
<?php include "includes/functions.php"?>
<?php include "includes/sanitizer.php"?>
<?php include "includes/Constants.php"?>

<?php

if (!$_SESSION["userLoggedIn"]) {
    header("Location:error.php");
}

?>


<?php include "includes/header.php"?>
<div class="container d-flex flex-column p-4 mb-5 mt-5"
    style="text-align:center;background-color:white;border-radius:15px">
    <div class="row">
        <div class="col-xs-6 col-md-8 col-lg-12 d-flex flex-column">
            <h1 style="color:green;text-align:center" class="mt-2">Bienvenue chez nous,</h1>
            <h4><strong><?php echo $_SESSION['userLoggedIn']; ?></strong></h4>
            <button class="btn btn-success mt-2"><a href="logout.php">LOGOUT BUTTON </a> </button>
            <button class="btn btn-success mt-2"><a href="formuser.php?new=1">Nouveau Utilisateur</a> </button>
        </div>
    </div>
</div>
<div class="container h-25" style="max-height:30%">
    <table cellspacing="5" cellpadding="5" width="100%" style="background-color:white;">
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
$result = $connection->prepare("SELECT * FROM utilisateur ORDER BY idUtilisateur ASC");
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

                        <form method="POST">
                            <input type="hidden" name="idUtilisateur" value='<?php echo $row['idUtilisateur'] ?>'>
                            <input type="button" value="Delete" class="btn btn-danger" name="myBtn" id="myBtn">
                        </form>
                    </label>
                </td>
                <td>
                    <label>
                        <form method="POST">
                            <a href="formuser.php?<?php echo "id=" . $row['idUtilisateur'] . ""; ?>">
                                <input type="button" name="modifier" value="modifier" class="btn btn-warning"></a>
                        </form>
                    </label>
                </td>
            </tr>
            <div id="myModal" class="modal">
                <div class="modal-content">
                    <form action="" method="POST">
                        <h2 style="text-align:center">Etes vous sur de vouloir supprimer
                            <?php echo $row['pseudo'] ?>?</h2>
                        <div class="row">
                            <div class="col-md-5"></div>
                            <div class="col-md-1"> <input type="submit" name="deleteBtn" value="Supprimer"
                                    class="btn btn-danger">
                            </div>
                            <div class="col-md-6"> <span><input type="submit" name="close" value="Close"
                                        class="btn btn-danger"></span></div>
                        </div>
                    </form>
                </div>
            </div>
            <?php }?>
        </tbody>
    </table>
</div>

<?php

if (isset($_POST["deleteBtn"])) {
    $sql = "DELETE FROM utilisateur WHERE idUtilisateur = :id";
    $result1 = $connection->prepare($sql);
    $result1->bindParam(':id', $id, PDO::PARAM_INT);
    $id = $_REQUEST['idUtilisateur'];
    $result1->execute();
    unset($result1);
    header("Location:index.php");
}

?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous">
</script>
<script>
var modal = document.getElementById("myModal");
var btn = document.getElementById("myBtn");
var span = document.getElementsByClassName("close")[0];
btn.onclick = function() {
    modal.style.display = "block";
}
span.onclick = function() {
    modal.style.display = "none";
}
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
</body>

</html>