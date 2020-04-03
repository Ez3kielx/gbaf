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

?>
<html>
<head>
    <title>ACCUEIL</title>
    <link rel="icon" href="img/logo.ico" />
    <link rel="stylesheet" type="text/css" href="style.css">



</head>
<body>
    <?php
        include 'header.php';
    ?>
<div class="back">
    <div class="presentation">
        <h1>Bienvenue sur l'extranet du GBAF</h1>
        <p>Le Groupement Banque Assurance Français (GBAF) est une fédération
        représentant les 6 grands groupes français.
        Même s’il existe une forte concurrence entre ces entités, elles vont toutes travailler
        de la même façon pour gérer près de 80 millions de comptes sur le territoire
        national.
        Le GBAF est le représentant de la profession bancaire et des assureurs sur tous
        les axes de la réglementation financière française. Sa mission est de promouvoir
        l'activité bancaire à l’échelle nationale. C’est aussi un interlocuteur privilégié des
        pouvoirs publics.</p>
    </div>
    <div class="illustration"> 
    </div>
    <div class="acteurs">

        <h2>Acteurs et partenaires</h2>

    <?php

// Affichage dees articles dans la page d'accueil

        $reqarticle = $bdd->query('SELECT * FROM acteur');

        while($article = $reqarticle->fetch())
        {

            ?>
            <div class="acteur">
                <div class="acteurpres">
            <img src=<?php echo $article['logo'];?>>
                </div>
                <div class="acteurpres">
                    <h3>
                        <?php echo $article['acteur'];?>
                    </h3>
                    <p>
                        <?php
                        if (strlen($article['description'])>170) $article['description']=substr($article['description'], 0, 170)."...";
                            { 
                                echo $article['description'];
                                ?>
                                </br>
                                <button><a href='acteur.php?id=<?php echo $article['id_acteur'];?>'>VOIR PLUS</a></button>
                                <?php
                            }
                        ?>
                    </p>
                </div>
            </div>
            <?php
        }
        $reqarticle->closeCursor();
            ?>
    </div>
</div>

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