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

?>
<!DOCTYPE html>
<html>
<head>

	<?php


  // VERIFICATION DU PSEUDO AVANT QUESTION



if(isset($_POST['formmdp'])) {
$reqmdp = $bdd->prepare('SELECT * FROM account WHERE username = :username');
$reqmdp->bindValue(':username', htmlspecialchars($_POST['usernamemdp']), PDO::PARAM_STR);
   $usernamemdp = htmlspecialchars($_POST['usernamemdp']);
      if(!empty($usernamemdp)) {
        $reqmdp -> execute();
        $userexist = $reqmdp->rowCount();
            if($userexist == 1) {
               $userinfo = $reqmdp->fetch();
                  $_SESSION['username'] = $_POST['usernamemdp'];
                  $_SESSION['question'] = $userinfo['question'];
                  $_SESSION['id'] = $userinfo['id'];
                  header("Location: reponse.php");
          } else {
             $erreur = 'Ce pseudo n\'existe pas!';
          }
   } else {
      $erreur = "Tous les champs doivent être complétés !";
   }
}
?>
	<meta charset="utf-8">
	<title>MOT DE PASSE OUBLIE</title>
	<link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" href="img/logo.ico" />
</head>
<body>
	<article class="formulaire">
    	<h2>MOT DE PASSE OUBLIE ?</h2>
		    <form action="" method="post" >
		            <img src="img/logo_gbaf.png">
		            <input type="text"  name="usernamemdp" placeholder="Entrez votre pseudo">            
		            <input type="submit" name="formmdp">   
	<?php
         if(isset($erreur)) {
            echo '<font color="red" text-align:center>'.$erreur."</font>";
         }
    ?>                                  
		    </form>
    </article>

</body>
</html>