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
$requser = $bdd->prepare('SELECT * FROM account WHERE username = :username AND password = :password');

$requser->bindValue(':username', $_POST['usernameconnect'], PDO::PARAM_STR);
$requser->bindValue(':password', sha1($_POST['passwordconnect']), PDO::PARAM_STR);

    if(isset($_POST['formconnect'])) {
   $usernameconnect = ($_POST['usernameconnect']);
   $passwordconnect = sha1($_POST['passwordconnect']);
      if(!empty($usernameconnect) AND !empty($passwordconnect)) {
        $requser -> execute();
        $userexist = $requser->rowCount();
            if($userexist == 1) {
               $userinfo = $requser->fetch();
               $_SESSION['id'] = $userinfo['id'];
               $_SESSION['nom'] = $userinfo['nom'];
               $_SESSION['prenom'] = $userinfo['prenom'];
               $_SESSION['pseudo'] = $userinfo['username'];
                 if (!empty($_SESSION['nom'])){
                   header("Location: profil.php");
                } else {
                header("Location: inscription.php");
             }
          } else {
             $erreur = 'Mauvais pseudo ou mot de passe !'.'</br>'.'<a href="mdpoublie.php">Mot de passe oublié ?</a>';
          }
   } else {
      $erreur = "Tous les champs doivent être complétés !";
   }
}
?>
<html>
<head>
    <meta charset="utf-8">
    <title>CONNEXION</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" href="img/logo.ico" />
</head>
<body>          
<article class="formulaire">
    <h2>Connexion</h2>
    <form action="" method="post" >
            <img src="img/logo_gbaf.png">
            <input type="text"  name="usernameconnect" placeholder="Pseudo">
            <input type="password" name="passwordconnect" placeholder="Mot de passe">
            <input type="submit" name="formconnect">
    <?php
         if(isset($erreur)) {
            echo '<font color="red" text-align:center>'.$erreur."</font>";
         }
    ?>                                         
    </form>
    <div class="inscription"></div>
</article> 
</body>
</html>