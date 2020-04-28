<?php
session_start();

require('src/connect.php');

    if(!empty($_POST['pseudo']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_confirm'])){
        print_r($_POST);
        
        //VARIABLE
        $pseudo = $_POST['pseudo'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $pass_confirm = $_POST['password_confirm'];
        
        //Test si les mots de passes sont identiques
        
        if($password != $pass_confirm){ 
            header('location: index.php?error=1&message=Vos mots de passes sont pas identiques');
            exit();
        }
        
        //TEST si email est déjà utiliser
      $req = $db->prepare('SELECT count(*) as numberEmail FROM users WHERE email = ?');
      $req->execute(array($email));
      
      while($verification_email = $req->fetch()){
          
          if($verification_email['numberEmail'] != 0){
              
              header('location: index.php?error=2&message=cet email est déja prise');
              exit();
          }
      }
      
      //HASH
      $secret = sha1($email).rand();
      
      //Cryptage du mot de passe
      $password = "aq1".sha1($password."1254")."25";
           
      //Envoie de la requete dans la base de donnée
      
      $req = $db->prepare('INSERT INTO users(pseudo, email, password, secret) VALUES(?, ?, ?, ?)');
      
      $req->execute(array($pseudo,$email,$password, $secret));
                header('location: index.php?success=1&message=Bienvenue,vous êtes maintenant inscris!');
                    exit();
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
        <?php
            if(!isset($_SESSION['connect'])){ ?>
        <form class="login" action="index.php" method="post">
            <h1>Inscription</h1>
                <?php if(isset($_GET['error']) == 1){
                            echo '<div class="alert error">'.$_GET['message'].'</div>';   
                        }elseif(isset($GET['error']) == 2){
                            echo '<div class="alert error">'.$_GET['message'].'</div>';
                        }
                    if(isset($_GET['success'])){
                        echo '<div class="alert success">'.$_GET['message'].'</div>';
                    }
                ?>
            <h4>Réservé aux administrateurs non-inscrits<br/><a href="connection.php">Sinon connectez-vous</a></h4>
            <input type="text" name="pseudo" placeholder="Votre pseudo" required>
            <input type="email" name="email" placeholder="Votre adresse mail" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="password" name="password_confirm" placeholder="Confirmez votre mot de passe">
            <input type="submit" value="Continuer">
        </form>
            <?php } else { ?>
                <p class="info">Bonjour,<?= $_SESSION['pseudo'] ?>! </p>
                <p class="disconnection"><a href="disconnection.php">Déconnection</a></p>
            <?php } ?>
    </body>
</html>
