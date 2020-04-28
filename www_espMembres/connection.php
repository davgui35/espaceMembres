<?php
session_start();//OUVERTURE DE LA SESSION

if(isset($_SESSION['connect'])){
    header('location: index.php');
}

require('src/connect.php');

    if(!empty($_POST['email']) && !empty($_POST['password'])){
        
        
        
        //VARIABLES
        $email      = $_POST['email'];
        $password   = $_POST['password'];
        $error      = 1;
        
        //HASH DU MOT DE PASSE
        $password = "aq1".sha1($password."1254")."25";
        //Débug de l'encryptage du mot de passe echo $password;
        //aq100b917fdf09ce34585403a78b2d70fc671b873dc25
        
        //VERIFICATION DE L'EMAIL ET DU MOT DE PASSE
        $req = $db->prepare('SELECT * FROM users WHERE email = ?');
        $req->execute(array($email));
        
        while($user = $req->fetch()){
            //print_r($user); Ce que retourne $user toute de la base de donnée
            //Array ( [id] => 26 [0] => 26 [pseudo] => guigui [1] => guigui [email] => test@test.com [2] => test@test.com [password] => aq100b917fdf09ce34585403a78b2d70fc671b873dc25 [3] => aq100b917fdf09ce34585403a78b2d70fc671b873dc25 [creation_date] => 2020-04-28 23:37:15 [4] => 2020-04-28 23:37:15 [secret] => a6ad00ac113a19d953efb91820d8788e2263b28a118970175 [5] => a6ad00ac113a19d953efb91820d8788e2263b28a118970175 )
            //Si le password est égale au password de la table users envoi success
            if($password == $user['password']){
            //echo "test"; 
            $error = 0;
            $_SESSION['connect'] = 1;//création d'une session qui sera égale à un
            $_SESSION['pseudo'] = $user['pseudo'];//stockage du pseudo pour l'utiliser n'importe quand
            //Si on a la connexion automatique
            if(isset($_POST['connect'])){
               setcookie('log', $user['secret'], time() + 365*24*3600, null, null, false, true);
            }
            //Confirme qu'on ne se trompe pas dans notre mot de passe
            header('location: connection.php?success=1&message=Bienvenue');            
        }
        
        //Important hors de la boucle pour afficher error
        if($error == 1){
            header('location: connection.php?error=1&message=Impossible de se connecter');
        exit();
        }
        
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Connexion à admin</title>
        <link rel="stylesheet" type="text/css" href="css/default.css">
    </head>
    <body>
        <form class="login" action="connection.php" method="post">
            <h1>Connexion</h1>
            <?php if(isset($_GET['error'])== 1){
                echo '<div class="alert error">'.$_GET['message'].'</div>';
                
            }elseif(isset($_GET['success']) == 1){
                echo '<div class="alert success">'.$_GET['message'].'</div>';
            }
            
            ?>            
            <h4>Réservé aux administrateurs<br/><a href="index.php">Sinon inscrivez-vous</a></h4>
            <input type="email" name="email" placeholder="Votre adresse mail" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            
            <p><label><input type="checkbox" name="connect" checked="checked">Connexion automatique</label></p>
            <input type="submit" value="Connexion">
        </form>
    </body>
</html>
