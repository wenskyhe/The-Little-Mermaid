<?php

ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

sessoion_set_cookie_params([
    'lifetime' => 1800,
    'domain' => 'localhost',
    'path' => '/', //any subpage inside our website
    'secure' => true,
    'httponly'=> true,
]);

session_start();

// if it is the first time starting the session
// by checking if we have 'last_regeneration' created
if(!isset($_SESSION['last_regeneration'])){ 
    
    session_regenerate_id(true); // make the id stronger by regenerate it
    $_SESSION['last_regeneration'] = time();
} else {

    $interval = 60 * 30; 

    if(time() - $_SESSION['last_regeneration'] >= $interval){

        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
}
