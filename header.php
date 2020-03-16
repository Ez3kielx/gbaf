<?php
session_start();
?>
<body>
      <div id="header">
        <a href="profil.php"><img class="logo" src="img/logo_gbaf.png" ></a>
        <div id="menu">
        <nav>
            <ul>
                <li class="deroulant"><a href="#" id="nom"> <?php echo $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . ' ▼'; ?> </a>
                    <ul class="sous">
                        <li><a href="parametre.php"> Parametre du compte </a></li>
                        <li><a href="deconnexion.php"> Se déconnecter</a></li>
                    </ul>
                </li>
                <li>
                    <img class="avatar" src="img/avatar.png">
                </li>
            </ul>
        </nav>
        </div>
        
      </div>
   </body>
</html>