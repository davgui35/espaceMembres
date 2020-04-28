<?php
session_start();//INITIALISE LA SESSION
session_unset();//DESACTIVER LA SESSION
session_destroy();//DETRUIT LA SESSION
\setcookie('log', '', time()-3444, null, null, false, true);//DETRUIRE LES COOKIES
header('location: index.php');//ACCUEIL NON CONNECTE

?>