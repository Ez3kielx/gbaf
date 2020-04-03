<?php

try
{
    $bdd = new PDO('mysql:hostname:port=localhost:3306;dbname=openc;charset=utf8', 'root', 'root');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}


// INSERTION DU NOUVEAU MEMBRE DANS LA BDD





if(isset($_POST['forminscription'])){
$req = $bdd->prepare('INSERT INTO account(nom, prenom, username, password, question, reponse) VALUES(:nom, :prenom, :username, :password, :question, :reponse)');

$req->bindValue(':nom', htmlspecialchars($_POST['nom']), PDO::PARAM_STR);
$req->bindValue(':prenom', htmlspecialchars($_POST['prenom']), PDO::PARAM_STR);
$req->bindValue(':username', htmlspecialchars($_POST['username']), PDO::PARAM_STR);
$req->bindValue(':password', sha1($_POST['password']), PDO::PARAM_STR);
$req->bindValue(':question', $_POST['question'], PDO::PARAM_STR);
$req->bindValue(':reponse', htmlspecialchars($_POST['reponse']), PDO::PARAM_STR);
     if(!empty($_POST['nom']) AND !empty($_POST['prenom']) AND !empty($_POST['username']) AND !empty($_POST['password']) AND !empty($_POST['reponse'])) {
        $pseudolength = strlen($_POST['username']);
            if($pseudolength <= 255) {
                if($_POST['password'] == $_POST['password2']) {
                     $req->execute();
                     header('Location: index.php');
                } else {
                    $erreur = "Vos mots de passes ne correspondent pas !";
                }
            } else {
                $erreur = "Votre pseudo ne doit pas dépasser 255 caractères !";
            }

    } else {
        $erreur = "Tout les champs doivent etre rempli !";
        }
 }   
   
?>
<html>
<head>
    <meta charset="utf-8">
    <title>INSCRIVEZ VOUS</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" href="img/logo.ico" />
</head>
<body>          
<article class="formulaire">
    <h2>Inscription</h2>
    <form action="" method="post" >
            <img src="img/logo_gbaf.png">
            <input type="text" name="nom" id="nom" placeholder="Nom">
            <input type="text" name="prenom" id="prenom" placeholder="Prenom">
            <input type="text"  name="username" placeholder="Pseudo">
            <input type="password" name="password" placeholder="Mot de passe">
            <input type="password" name="password2" placeholder="Confirmez votre mot de passe">  
               <select name="question" id="question">
                    <option value="">--Choisissez une question secrete:--</option>
                    <option value="Quel est la marque de votre premiere voiture ?">Quel est la marque de votre premiere voiture ?</option>
                    <option value="uel est le nom de votre premier animal de compagnie ?">Quel est le nom de votre premier animal de compagnie ?</option>
                    <option value="Quel est le nom de jeune fille de votre mere ?">Quel est le nom de jeune fille de votre mere ?</option>
                    <option value="Quel est le nom de votre premiere école ?">Quel est le nom de votre premiere école ? </option>
                </select>
            <input type="text" name="reponse" placeholder="Reponse" class="form-control">
            <input type="submit" name="forminscription">
    <?php
         if(isset($erreur)) {
            echo '<font color="red" text-align:center>'.$erreur."</font>";
         }
    ?>                                         
    </form>
</article> 
</body>
</html>