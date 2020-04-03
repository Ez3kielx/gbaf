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

    if(isset($_SESSION['id']) AND $_SESSION['id'] > 0) {
        $getid = intval($_SESSION['id']);
        $requser = $bdd->prepare('SELECT * FROM account WHERE id = ?');
        $requser->execute(array($getid));
        $userinfo = $requser->fetch();
        $id_acteur =intval($_GET["id"]);

?>
<html>
<head>
    <title>ACTEURS</title>
    <link rel="icon" href="img/logo.ico" />
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="fontawesome/css/all.css" rel="stylesheet">
</head>
<body>
    <?php
        include 'header.php';
    ?>
<div class="back">
    <div class="">
    <?php
    
    // RECUPERATION DU CONTENU DE LA PAGE EN FONCTION DE L'ID ACTEUR

        $reqarticle = $bdd->prepare('SELECT * FROM acteur WHERE id_acteur= ?');
        $reqarticle->execute(array($id_acteur));
        if ($id_acteur > 4) {
            header("Location: profil.php");
        }
        while($article = $reqarticle->fetch())
        {
    ?>
            <div class="acteur">
                <div class="imgacteur">
            <img src=<?php echo $article['logo'];?>>
                </div>
                <div class="textacteur">
                    <h2>
                        <a href="https://www.formationsco.com/">
                        <?php echo $article['acteur'];?>
                        </a>
                    </h2>
                    <p>
                        <?php echo $article['description'];?>
                    </p>
                </div>

                <?php
        // LIKES / DISLIKES
      $likes = $bdd->prepare('SELECT id FROM likes WHERE id_acteur = ?');
      $likes->execute(array($id_acteur));
      $likes = $likes->rowCount();
      $dislike = $bdd->prepare('SELECT id FROM dislike WHERE id_acteur = ?');
      $dislike->execute(array($id_acteur));
      $dislike = $dislike->rowCount();
                ?>

<div class="avis">
      <a id="like" href="script/action.php?t=1&id=<?= $id_acteur ?>">J'aime <i class="far fa-thumbs-up"></i></a> (<?= $likes ?>) &nbsp;
      <a id="dislike" href="script/action.php?t=2&id=<?= $id_acteur ?>">Je n'aime pas <i class="far fa-thumbs-down"></i></a>(<?= $dislike ?>)
</div>
            </div>
             
            </div>

    <?php


// POSTER UN COMMENTAIRE

        if(isset($_POST['commenter'])){
            if(!empty($_POST['commentaire'])) {
              $reqa = $bdd->prepare('INSERT INTO post(id_user, id_acteur, post) VALUES (:id_user, :id_acteur, :post)');
                $reqa->bindValue(':id_user', $_SESSION['id'], PDO::PARAM_STR);
                $reqa->bindValue(':id_acteur', $id_acteur, PDO::PARAM_STR);
                $reqa->bindValue(':post',htmlspecialchars($_POST['commentaire']), PDO::PARAM_STR);
                $reqa -> execute();
                $mess='Votre commentaire à bien été publié.';
            }
            else {
                echo  '<font color="red" text-align:center>'.'LE CHAMP EST VIDE'."</font>";
            }
        }

// RECUPERER LE NOMBRE DE COMMENTAIRE PAR ACTEUR

     $reqnbcom = $bdd->prepare('SELECT COUNT(*) AS id_post FROM post WHERE id_acteur = ?');
     $reqnbcom->execute(array($id_acteur));
     $nbcom = $reqnbcom->fetchColumn();
    ?>

            
    <div class="acteur">   
            <h2>Commentaires <?php echo ' ( '.$nbcom.' ) '; ?>:</h2>
    <?php 


// AFFICHER LES COMMENTAIRES

        $reqcom = $bdd->query('SELECT p.id_post id_post, p.post post, a.prenom prenom, a.nom nom, date(date_add) AS date, date(date_add) FROM post p INNER JOIN account a ON p.id_user = a.id WHERE p.id_acteur = '.$id_acteur.' ORDER BY p.id_post DESC LIMIT 5');
        while($com = $reqcom->fetch())
        {
    ?>
            <div class="bloccommentaire">
                <div class="nomcommentaire">

                    <?php
                    echo $com['nom'].' '.$com['prenom']. ' - ' . $com['date']. "<br>";
                    ?>
                </div>
                <div class="textcommentaire">
                    <?php
                    echo $com['post']."<br>";
                    ?>
                </div>

            </div>

        <?php
        }

        ?> 





        <?php

// VOIR PLUS DE COMMENTAIRES

         if (isset($_POST['voirplus'])) {
            $reqcomvp = $bdd->query('SELECT p.id_post id_post, p.post post, a.prenom prenom, a.nom nom, date(date_add) AS date, date(date_add) FROM post p INNER JOIN account a ON p.id_user = a.id WHERE p.id_acteur = '.$id_acteur.' ORDER BY p.id_post DESC LIMIT 5,50');
                while($comvp = $reqcomvp->fetch())
                {
        ?>
                            <div class="bloccommentaire">
                                <div class="nomcommentaire">

                                    <?php
                                    echo $comvp['nom'].' '.$comvp['prenom']. ' - ' . $comvp['date']. "<br>";
                                    ?>
                                </div>
                                <div class="textcommentaire">
                                    <?php
                                    echo $comvp['post']."<br>";
                                    ?>
                                </div>

                            </div>
                            

                        <?php
                        }
                        }
                        ?>
    <form action="" method="post">
        <input type="submit" value="voir plus de commentaire" name="voirplus"/>  
    </form>
                        <?php

// VERIFIER SI L'UTILISATEUR A DEJA COMMENTER 

$reqdeja = $bdd->prepare('SELECT * FROM post WHERE id_user = :id_user AND id_acteur = :id_acteur');
$reqdeja->bindValue(':id_user', $_SESSION['id'], PDO::PARAM_STR);
$reqdeja->bindValue(':id_acteur', $id_acteur, PDO::PARAM_STR);
$reqdeja->execute();
if ($reqdeja->fetchColumn()) {
    echo "</br>VOUS AVEZ DEJA COMMENTÉ CET ACTEUR";
}
else{
                        ?> 


        
          <form action="" method="post">
            <textarea name="commentaire" placeholder="Ajouter un commentaire en tant que <?php echo $_SESSION['prenom'] . ' ' . $_SESSION['nom']; ?>"></textarea><br>
            <?php
                if(isset($mess)) {
                echo '<font text-align:center>'.$mess."</font> </br>";
                }
            ?>
            <input type="submit" value="Publier" name="commenter"/>
          </form>   

  
    </div>
    </div>
<?php 
  
}
}

?>
</body>
</html>

<?php   
}

else {

?>
</body>
</html>


<html>
<head>
    <title> ERREUR 403</title>
    <link rel="icon" href="img/logo.ico" />
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="fontawesome/css/all.css" rel="stylesheet">


</head>
<body class="error">
<div class="interdit">
    <div class="textinterdit">
    <i class="fas fa-exclamation-triangle"></i>
    <p> VOUS N'ETES PAS CONNECTE <br>VOUS NE POUVEZ PAS ACCEDER À CETTE PAGE </p>
    <button><a href="index.php">REVENIR A L'ACCUEIL</a></button>
    </div>
</div>
<?php
}
?>

</body>
</html>