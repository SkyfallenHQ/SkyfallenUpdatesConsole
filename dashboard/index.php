<?php
require_once "../SkyfallenCodeLib/AppAPIs/AppCenterAPI.php";
require_once "../classes/AppImporter.php";
require_once "../classes/Utils.php";
require_once "../classes/error.php";
require_once "../classes/DB.php";
require_once "../classes/Package.php";
require_once "../configuration.php";
require_once "../vendor/autoload.php";
session_name("DeveloperIDSession");
session_start();
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: /");
    exit;
}
?>
<html>
<head>
    <title>App Updates Console - Skyfallen Developers</title>
    <!-- CSS only -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <!-- JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</head>
<body>
<?php if(!isset($_GET["wizard"]) and !isset($_GET["page"])){ ?>
    <div class="card text-center" style="width: 60%; margin-right: auto; margin-left: auto; margin-top: 30px; margin-bottom: 30px;">
        <div class="card-header">
            Updates Console
        </div>
        <div class="card-body">
            <h5 class="card-title">IMPORT APPS</h5>
            <p class="card-text">Use this to import apps from Skyfallen App Center</p>
            <a href="?wizard=appimporter" class="btn btn-primary">Start Wizard</a>
        </div>
        <div class="card-footer text-muted">
            WIZARD
        </div>
    </div>
    <div class="card text-center" style="width: 60%; margin-right: auto; margin-left: auto; margin-top: 30px; margin-bottom: 30px;">
        <div class="card-header">
            Updates Console
        </div>
        <div class="card-body">
            <h5 class="card-title">MY APPS</h5>
            <p class="card-text">This page will show you all of your apps</p>
            <a href="?page=myapps" class="btn btn-primary">MY APPS</a>
        </div>
        <div class="card-footer text-muted">
            PAGE
        </div>
    </div>
<?php } ?>
<?php if($_GET["wizard"] == "appimporter" and ($_GET["step"] == "1" or !isset($_GET["step"]))){ ?>
    <div class="card text-center" style="width: 60%; margin-right: auto; margin-left: auto; margin-top: 30px; margin-bottom: 30px;">
        <div class="card-header">
            Step 1
        </div>
        <div class="card-body">
            <h5 class="card-title">Confirm Your Password</h5>
            <p class="card-text">Confirm your password if you want to also list private apps.</p>
            <form method="post" action="?wizard=appimporter&step=2">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Confirm Password</span>
                    </div>
                    <input type="password" class="form-control" placeholder="Confirm Password" aria-label="Confirm Password" name="password" aria-describedby="basic-addon1">
                </div>
                <button type="submit" class="btn btn-primary">Next</button>
            </form>
        </div>
        <div class="card-footer text-muted">
            WIZARD
        </div>
    </div>
<?php } ?>
<?php if($_GET["wizard"] == "appimporter" and $_GET["step"] == "2"){ ?>
    <div class="card text-center" style="width: 60%; margin-right: auto; margin-left: auto; margin-top: 30px; margin-bottom: 30px;">
        <div class="card-header">
            Step 2
        </div>
        <div class="card-body">
            <h5 class="card-title">Pick an app</h5>
            <p class="card-text">Pick an app to import from App Center</p>
            <form method="post" action="?wizard=appimporter&step=3">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="inputGroupSelect01">Your Apps</label>
                    </div>
                    <select class="custom-select" id="inputGroupSelect01" name="appid">
                        <?php
                        $publicapps = SkyfallenCodeLibrary\AppCenterAPI::listpublicapps($_SESSION["username"]);
                        foreach ($publicapps as $appname => $appid){
                            echo "<option value='".$appid."'>".$appname."</option>";
                        }
                        $privateapps = SkyfallenCodeLibrary\AppCenterAPI::listprivateapps($_SESSION["username"],$_POST["password"]);
                        foreach ($privateapps as $appname => $appid){
                            echo "<option value='".$appid."'>".$appname."</option>";
                        }
                        ?>
                    </select>
                </div>
                <p>Confirm Password again to retrieve app secret.</p>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Confirm Password</span>
                    </div>
                    <input type="password" class="form-control" placeholder="Confirm Password" aria-label="Confirm Password" name="password" aria-describedby="basic-addon1">
                </div>
                <button type="submit" class="btn btn-primary">Next</button>
            </form>
        </div>
        <div class="card-footer text-muted">
            WIZARD
        </div>
    </div>
<?php } ?>
<?php if($_GET["wizard"] == "appimporter" and $_GET["step"] == "3" and $_POST["password"] and $_POST["appid"]){ ?>
    <div class="card text-center" style="width: 60%; margin-right: auto; margin-left: auto; margin-top: 30px; margin-bottom: 30px;">
        <div class="card-header">
            Step 3
        </div>
        <div class="card-body">
            <h5 class="card-title">Finishing...</h5>
            <p class="card-text"><?php
                $apiresp = \SkyfallenCodeLibrary\AppCenterAPI::getsecret($_SESSION["username"],$_POST["appid"],$_POST["password"]);
                if($apiresp != false){
                    $apiresp = \SkyfallenUpdatesConsole\Utils::convertToNonSTDarray($apiresp);
                    if(\SkyfallenUpdatesConsole\AppImporter::importFromAppCenter($apiresp,$_SESSION["username"],$link)){
                        echo "Successfully Completed.";
                    } else {
                        echo "App could not be successfully imported to the database.";
                    }
                } else {
                    echo "Password Invalid.";
                }
                ?></p>
            <a href="/dashboard/" class="btn btn-primary">Home Page</a>
        </div>
        <div class="card-footer text-muted">
            WIZARD
        </div>
    </div>
<?php } ?>
<?php if($_GET["page"] == "myapps" and !isset($_GET["del"])){
    echo \SkyfallenUpdatesConsole\Utils::listapps($link,$_SESSION["username"]);
} ?>
<?php if($_GET["page"] == "myapps" and isset($_GET["del"])){
    \SkyfallenUpdatesConsole\AppImporter::removeApp($link,$_SESSION["username"],$_GET["del"]);
    echo \SkyfallenUpdatesConsole\Utils::listapps($link,$_SESSION["username"]);
}
?>
<?php if($_GET["page"] == "packages" and isset($_GET["appid"])){
    ?>
<div style="margin-left: auto; margin-right: auto; width: 80%; text-align: center;">
    <h3>App Name: <?php echo \SkyfallenUpdatesConsole\AppImporter::getImportedAppNameFromID($link,$_GET["appid"]); ?></h3>
    <h4>App ID: <?php echo $_GET["appid"]; ?></h4>
</div>
<?php

}
?>

</body>
</html>