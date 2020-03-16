<?php
session_start();
try
{
    $bdd = new PDO('mysql:hostname:port=localhost:3306;dbname=openc;charset=utf8', 'root', 'root');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}
if(isset($_SESSION['id'])) {
   $requser = $bdd->prepare("SELECT * FROM account WHERE id = ?");
   $requser->execute(array($_SESSION['id']));
   $user = $requser->fetch();

if(isset($_POST['newusername']) AND !empty($_POST['newusername']) AND $_POST['newusername'] != $user['username']) {
      $newusername = htmlspecialchars($_POST['newusername']);
      $insertpseudo = $bdd->prepare("UPDATE account SET username = ? WHERE id = ?");
      $insertpseudo->execute(array($newusername, $_SESSION['id']));
      $_SESSION['pseudo'] = $_POST['newusername'];
      header('Location: profil.php');
   }
   if(isset($_POST['newprenom']) AND !empty($_POST['newprenom']) AND $_POST['newprenom'] != $user['prenom']) {
      $newprenom = htmlspecialchars($_POST['newprenom']);
      $insertmail = $bdd->prepare("UPDATE account SET prenom = ? WHERE id = ?");
      $insertmail->execute(array($newprenom, $_SESSION['id']));
      $_SESSION['prenom'] = $_POST['newprenom'];
      header('Location: profil.php');
   }
     if(isset($_POST['newnom']) AND !empty($_POST['newnom']) AND $_POST['newnom'] != $user['nom']) {
      $newnom = htmlspecialchars($_POST['newnom']);
      $insertmail = $bdd->prepare("UPDATE account SET nom = ? WHERE id = ?");
      $insertmail->execute(array($newnom, $_SESSION['id']));
      $_SESSION['nom'] = $_POST['newnom'];
      header('Location: profil.php');
   }

   if(isset($_POST['newpassword']) AND !empty($_POST['newpassword']) AND isset($_POST['newpassword2']) AND !empty($_POST['newpassword2'])) {
      $mdp1 = sha1($_POST['newpassword']);
      $mdp2 = sha1($_POST['newpassword2']);
      if($mdp1 == $mdp2) {
         $insertmdp = $bdd->prepare("UPDATE account SET password = ? WHERE id = ?");
         $insertmdp->execute(array($mdp1, $_SESSION['id']));
         header('Location: profil.php');
      } else {
         $msg = "Vos deux mdp ne correspondent pas !";
      }
   }

}
   
?>
<html>
<head>
    <meta charset="utf-8">
    <title>Parametres du compte</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" href="img/logo.ico" />
</head>
<body>          
    <?php
        include 'header.php';
    ?>
<article class="formulaire">
    <h2>PARAMETRES</h2>
    <form action="" method="post" >
            <img src="img/parametres.png">
            <label>Nom :</label>
            <input type="text" name="newnom" id="nom" value="<?php echo $_SESSION['nom'] ?>">
            <label>Prénom :</label>
            <input type="text" name="newprenom" id="prenom" value="<?php echo $_SESSION['prenom'] ?>">
            <label>Pseudo :</label>
            <input type="text"  name="newusername" value="<?php echo $_SESSION['pseudo'] ?>">
            <label>Nouveau mot de passe:</label>
            <input type="password" name="newpassword" placeholder="Nouveau mot de passe">
            <label>Confirmez votre nouveau mot de passe :</label>
            <input type="password" name="newpassword2" placeholder="Confirmez votre nouveau mot de passe">  
            <input type="submit" name="updateinscri">
    <?php
         if(isset($erreur)) {
            echo '<font color="red" text-align:center>'.$erreur."</font>";
         }
    ?>                                         
    </form>
</article> 
</body>
</html>