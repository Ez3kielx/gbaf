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

$reqquest = $bdd->prepare('SELECT * FROM account WHERE username = :username AND reponse = :reponse');
$reqquest->bindValue(':username', $_SESSION['username'], PDO::PARAM_STR);
$reqquest->bindValue(':reponse', $_POST['reponse'], PDO::PARAM_STR);

if(isset($_POST['formrep'])) {
   $reponse = ($_POST['reponse']);
      if(!empty($reponse)) {
        $reqquest -> execute();
        $repok = $reqquest->rowCount();
            if($repok == 1) {
               $userinfo = $reqquest->fetch();
               header("Location:modifmdp.php");
                 
          } else {
             $erreur = 'Mauvaise réponse !';
          }
   } else {
      $erreur = "Merci de répondre à la question.";
   }
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>QUESTION SECRETE</title>
	<link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" href="img/logo.ico" />
</head>
<body>
<article class="formulaire">
    	<h2>QUESTION SECRETE</h2>
 		    <form action="" method="post" >
		            <img src="img/logo_gbaf.png">
		        </br>
		                <?php
		   				 echo $_SESSION['question'];
	 					?>
		            <input type="text"  name="reponse" placeholder="Entrez votre reponse">            
		            <input type="submit" name="formrep">   
		            <?php
         if(isset($erreur)) {
            echo '<font color="red" text-align:center>'.$erreur."</font>";
         }
    ?>                                  
		    </form>
    </article>
</body>
</html>