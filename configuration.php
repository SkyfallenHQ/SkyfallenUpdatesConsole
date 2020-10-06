<?php
require_once "classes/error.php";

//Allow Registration.
define("ALLOW_REGISTER",true);

// Update Server Database
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'main');
define('DB_PASSWORD', '');
define('DB_NAME', 'updatesconsole');
// Finish editing
global $link;

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($link === false) {
    Errors::killapp("Database connection could not be successfully established. Application will now stop all processes.", "App Crashed");
}

// Login Server Database
define('DB_SERVER_LOGIN', 'localhost');
define('DB_USERNAME_LOGIN', 'main');
define('DB_PASSWORD_LOGIN', '');
define('DB_NAME_LOGIN', 'appcenter');
// Finish editing
global $loginlink;

$loginlink = mysqli_connect(DB_SERVER_LOGIN, DB_USERNAME_LOGIN, DB_PASSWORD_LOGIN, DB_NAME_LOGIN);

if ($loginlink === false) {
    Errors::killapp("Database connection to developer login server could not be successfully established. Application will now stop all processes.", "App Crashed");
}

// Code Required By Skyfallen Code Library Classes
// Do not edit
define("API_ENDPOINT","https://devlogin.api.theskyfallen.com/api/");
define("API_TYPE","SKYFALLENAPPCENTERAPI");
define("CONNECTION_INFASTRUCTURE","INTERNET");
define("SANDOX",false);
define("APPNAME","SkyfallenUpdatesConsole");
define("APPID","459d38c4d473e7dfdd464e01b9955eb9");
define("LIVEPATCHING_ENABLED",false);
define("DLDOMAIN","https://updateserver.theskyfallen.com");
define("PANELDOMAIN","https://updatesconsole.theskyfallen.com");