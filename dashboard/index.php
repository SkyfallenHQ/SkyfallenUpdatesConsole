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
<?php if($_GET["page"] == "packages" and isset($_GET["appid"]) and !isset($_GET["edit"])){
    ?>
    <div style="margin-left: auto; margin-right: auto; width: 80%; text-align: center;">
        <h3>App Name: <?php echo \SkyfallenUpdatesConsole\AppImporter::getImportedAppNameFromID($link,$_GET["appid"]); ?></h3>
        <h4>App ID: <?php echo $_GET["appid"]; ?></h4>
        <form method="post" style="width: 40%; margin-right: auto; margin-left: auto;">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Package Name" aria-label="New Package" name="newpkgname" aria-describedby="button-addon2">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Create New Package</button>
            </div>
        </div>
        </form>
    </div>
    <?php
    if(isset($_POST["newpkgname"])){
        \SkyfallenUpdatesConsole\Package::createPackage($link,"DEFAULT","DEFAULT",$_SESSION["username"],$_POST["newpkgname"],$_GET["appid"],"NOT SPECIFIED");
        header("location: ?page=packages&appid=".$_GET["appid"]);
    }
    if(isset($_GET["del"])){
        \SkyfallenUpdatesConsole\Package::deletePackage($link,$_GET["del"],$_SESSION["username"]);
        header("location: ?page=packages&appid=".$_GET["appid"]);
    }
    echo '<div style="text-align: center; width: 100%; text-align: center; margin-top: 50px;">';
    echo "<table class='table' style='width:80%; margin-right: auto; margin-left: auto;'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th scope='col'>Package Name</th>";
    echo "<th scope='col'>Package ID</th>";
    echo "<th scope='col'>Edit Package</th>";
    echo "<th scope='col'>Delete</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach (\SkyfallenUpdatesConsole\Package::listPackages($link,$_SESSION["username"],$_GET["appid"]) as $pkgid => $pkgname){
        echo "<tr>";
        echo "<td>" . $pkgname. "</td>";
        echo "<td>" . $pkgid . "</td>";
        echo "<td><a href='?page=packages&appid=" . $_GET['appid'] . "&edit=".$pkgid."' >Edit Package</a></td>";
        echo "<td><a href='?page=packages&appid=".$_GET["appid"]."&del=" . $pkgid . "' >Delete</a></td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>
<?php if($_GET["page"] == "packages" and isset($_GET["appid"]) and isset($_GET["edit"])){
    ?>
    <div style="margin-left: auto; margin-right: auto; width: 80%; text-align: center;">
        <h3>App Name: <?php echo \SkyfallenUpdatesConsole\AppImporter::getImportedAppNameFromID($link,$_GET["appid"]); ?></h3>
        <h4>App ID: <?php echo $_GET["appid"]; ?></h4>
    </div>
    <?php
    echo '<div style="text-align: center; width: 100%; text-align: center; margin-top: 50px;">';
    ?>
    <form method="post" style="width: 40%; margin-right: auto; margin-left: auto;">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Git URL" aria-label="Git URL" name="giturl" aria-describedby="button-addon2">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Clone</button>
            </div>
        </div>
        </form>
    <?php
    echo "<table class='table' style='width:80%; margin-right: auto; margin-left: auto;'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th scope='col'>File Name</th>";
    echo "<th scope='col'>BROWSE</th>";
    echo "<th scope='col'>DELETE</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    $pkgarray = \SkyfallenUpdatesConsole\Package::getPackageArray($link,$_SESSION["username"],$_GET["edit"]);
    chdir("../".$pkgarray["packagepath"]);
    if(isset($_GET["dir"])){
        chdir($_GET["dir"]);
    }
    if(isset($_GET["del"])){
        if(!unlink($_GET["del"])){
            \SkyfallenUpdatesConsole\Utils::removedir($_GET["del"]);
        }
    }
    if(isset($_POST["giturl"])){
        \Cz\Git\GitRepository::cloneRepository($_POST["giturl"],"");
    }
    $root = getcwd();

    function is_in_dir($file, $directory, $recursive = true, $limit = 1000) {
        $directory = realpath($directory);
        $parent = realpath($file);
        $i = 0;
        while ($parent) {
            if ($directory == $parent) return true;
            if ($parent == dirname($parent) || !$recursive) break;
            $parent = dirname($parent);
        }
        return false;
    }

    $path = null;
    if (isset($_GET['file'])) {
        $path = $_GET['file'];
        if (!is_in_dir($_GET['file'], $root)) {
            $path = null;
        } else {
            $path = '/'.$path;
        }
    }

    if (is_file($root.$path)) {
        readfile($root.$path);
        return;
    }
    if(isset($_GET["dir"])){
        echo "<tr>";
        echo "<td><a href='?page=packages&appid=" . $_GET['appid'] . "&edit=" . $_GET["edit"]."'>..</a></td>";
        echo "<td><a href='?page=packages&appid=" . $_GET['appid'] . "&edit=" . $_GET["edit"]."'>..</a></td>";
        echo "<td><a href='?page=packages&appid=" . $_GET['appid'] . "&edit=" . $_GET["edit"]."'>..</a></td>";
        echo "</tr>";
    }
    //if ($path) echo '<a href="?file='.urlencode(substr(dirname($root.$path), strlen($root) + 1)).'">..</a><br />';
    foreach (glob($root.$path.'/*') as $file) {
        $file = realpath($file);
        $flink = substr($file, strlen($root) + 1);
        echo "<tr>";
        echo "<td>" . basename($file). "</td>";
        if(!isset($_GET["dir"])) {
            if (is_dir($file)) {
                echo "<td><a href='?page=packages&appid=" . $_GET['appid'] . "&edit=" . $_GET["edit"] . "&dir=" . urlencode($flink) . "' >BROWSE</a></td>";
                echo "<td><a href='?page=packages&appid=" . $_GET['appid'] . "&edit=" . $_GET["edit"] . "&del=" . urlencode($flink) . "' >DELETE</a></td>";
            } else {
                echo "<td><a>FILE</a></td>";
                echo "<td><a href='?page=packages&appid=" . $_GET['appid'] . "&edit=" . $_GET["edit"] . "&del=" . urlencode($flink) . "' >DELETE</a></td>";
                echo "</tr>";
            }
        }else{
            if (is_dir($file)) {
                echo "<td><a href='?page=packages&appid=" . $_GET['appid'] . "&edit=" . $_GET["edit"] . "&dir=".$_GET["dir"]."/" . urlencode($flink) . "' >BROWSE</a></td>";
                echo "<td><a href='?page=packages&appid=" . $_GET['appid'] . "&edit=" . $_GET["edit"] . "&dir=".$_GET["dir"]."&del=" . urlencode($flink) . "' >DELETE</a></td>";
            } else {
                echo "<td><a>FILE</a></td>";
                echo "<td><a href='?page=packages&appid=" . $_GET['appid'] . "&edit=" . $_GET["edit"] . "&dir=".$_GET["dir"]."&del=" . urlencode($flink) . "' >DELETE</a></td>";
                echo "</tr>";
            }
        }
    }
    echo "</table>";
}
?>

</body>
</html>