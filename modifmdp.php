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


// MODIFICATION DU MOT DE PASSE EN CAS D'OUBLIE
   
   if(isset($_POST['newpassword']) AND !empty($_POST['newpassword']) AND isset($_POST['newpassword2']) AND !empty($_POST['newpassword2'])) {
      $mdp1 = sha1($_POST['newpassword']);
      $mdp2 = sha1($_POST['newpassword2']);
      if($mdp1 == $mdp2) {
         $insertmdp = $bdd->prepare("UPDATE account SET password = ? WHERE id = ?");
         $insertmdp->execute(array($mdp1, $_SESSION['id']));
         header('Location: index.php');
      } else {
         $msg = "Vos deux mot de passe ne correspondent pas !";
      }
   }

}
?>

<html>
<head>
    <meta charset="utf-8">
    <title>modification mot de passe</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" href="img/logo.ico" />
</head>
<body>          
<article class="formulaire">
    <h2>MODIFICATION DU MOT DE PASSE</h2>
    <form action="" method="post" >
            <img src="img/parametres.png">
            <label>Nouveau mot de passe:</label>
            <input type="password" name="newpassword" placeholder="Nouveau mot de passe">
            <label>Confirmez votre nouveau mot de passe :</label>
            <input type="password" name="newpassword2" placeholder="Confirmez votre nouveau mot de passe">  
            <input type="submit" name="updateinscri">
    <?php
         if(isset($msg)) {
            echo '<font color="red" text-align:center>'.$msg."</font>";
         }
    ?>                                         
    </form>
</article> 
</body>
</html>