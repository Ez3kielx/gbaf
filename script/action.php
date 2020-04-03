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

// LIKE "t = 1"
// DISLIKE "t = 2"

if(isset($_GET['t'],$_GET['id']) AND !empty($_GET['t']) AND !empty($_GET['id'])) {
   $getid = (int) $_GET['id'];
   $gett = (int) $_GET['t'];
   $sessionid = $_SESSION['id'];
   $check = $bdd->prepare('SELECT id_acteur FROM acteur WHERE id_acteur = ?');
   $check->execute(array($getid));

   // SI ON LIKE 

   if($check->rowCount() == 1) {
      if($gett == 1) {
         $check_like = $bdd->prepare('SELECT id FROM likes WHERE id_acteur = ? AND id_user = ?');
         $check_like->execute(array($getid,$sessionid));
         $del = $bdd->prepare('DELETE FROM dislike WHERE id_acteur = ? AND id_user = ?');
         $del->execute(array($getid,$sessionid));
         if($check_like->rowCount() == 1) {
            $del = $bdd->prepare('DELETE FROM likes WHERE id_acteur = ? AND id_user = ?');
            $del->execute(array($getid,$sessionid));
         } else {
            $ins = $bdd->prepare('INSERT INTO likes (id_acteur, id_user) VALUES (?, ?)');
            $ins->execute(array($getid, $sessionid));
         }
         
       // SI ON DISLIKE

      } elseif($gett == 2) {
         $check_like = $bdd->prepare('SELECT id FROM dislike WHERE id_acteur = ? AND id_user = ?');
         $check_like->execute(array($getid,$sessionid));
         $del = $bdd->prepare('DELETE FROM likes WHERE id_acteur = ? AND id_user = ?');
         $del->execute(array($getid,$sessionid));
         if($check_like->rowCount() == 1) {
            $del = $bdd->prepare('DELETE FROM dislike WHERE id_acteur = ? AND id_user = ?');
            $del->execute(array($getid,$sessionid));
         } else {
            $ins = $bdd->prepare('INSERT INTO dislike (id_acteur, id_user) VALUES (?, ?)');
            $ins->execute(array($getid, $sessionid));
         }
      }
      header('Location: http://localhost/acteur.php?id='.$getid);
   } else {
      exit('Erreur fatale. <a href="http://localhost/acteur.php">Revenir à l\'accueil</a>');
   }
} else {
   exit('Erreur fatale. <a href="http://localhost/acteur.php">Revenir à l\'accueil</a>');
}
