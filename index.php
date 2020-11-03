<?php
require_once "configuration.php";
session_name("DeveloperIDSession");
if(isset($_GET["swac_tokenlogin"])){
    $tokenisvalid = json_decode(file_get_contents("https://tokenexchange.theskyfallen.com/?req=isvalid&token=".$_GET["swac_tokenlogin"]),true);
    if($tokenisvalid["result"]["isvalid"] == "YES") {
        $tokenretriaval = json_decode(file_get_contents("https://tokenexchange.theskyfallen.com/?req=retrievedata&token=".$_GET["swac_tokenlogin"]),true);
        $username = $tokenretriaval["result"]["username"];
        session_start();
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $username;

        // Redirect user to welcome page
        header("location: /dashboard/");
    } else {
        die($tokenisvalid["result"]["isvalid"]);
    }
}

// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST" and $_GET["action"] == "register" and ALLOW_REGISTER){

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($loginlink, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.<br>";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.<br>";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.<br>";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.<br>";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["passwordconfirm"]))){
        $confirm_password_err = "Please confirm password.<br>";
    } else{
        $confirm_password = trim($_POST["passwordconfirm"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.<br>";
        }
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password,verified,token) VALUES (?, ?, 'NO', '".md5(uniqid(rand(), true))."')";

        if($stmt = mysqli_prepare($loginlink, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: /");
            } else{
                echo "Something went wrong. Please try again later. ERROR:".mysqli_stmt_error($stmt);
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($loginlink);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign in - Skyfallen</title>
    <link type="text/css" rel="stylesheet" href="css/main.css">
    <script type="text/javascript" rel="script" src="js/main.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body onload="onpagloadcheck()">
<?php
if($_GET["logout"] == "true"){
?>
<script>
    var http = new XMLHttpRequest();
    var url = '/api/';
    var params = 'action=logout';
    http.open('POST', url, true);

    //Send the proper header information along with the request
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState == 4 && http.status == 200) {
            $(document).ready(function() {
                $(".titleo").fadeOut(400,function (){
                    $(".titleo").html("Logged out successfully.");
                });
                $(".titleo").fadeIn(1000);
                $(".titleo").fadeOut(2000,function (){
                    $(".titleo").html("Sign in - Skyfallen Updates Console");
                });
                $(".titleo").fadeIn(1000);
            })
        }
        if(http.readyState == 4 && http.status == 403) {
            alert("An unknown error occured.");
        }
    }
    http.send(params);
</script>
<?php
}
?>
<form method="post" class="centered" id="form">
    <div class="container centered text-center" id="outsider">
        <div id="middle">
            <div class="field text-center">
                <img src="https://theskyfallen.company/wp-content/uploads/2020/07/IMG_0183.png" style="height: 100px;">
            </div>
            <div class="field text-center">
                <h3 class="titleo"><?php if($_GET["action"] != "register"){ echo "Sign in  to Skyfallen Updates Console"; } else { echo "Sign up  to Skyfallen Updates Console"; } ?></h3>
            </div>
            <div class="field">
                <input type="text" required autocomplete="off" id="username" name="username" style="border-radius: 10px 10px 0px 0px;">
                <label for="username" title="Please enter your Username or E-Mail" data-title="Username or E-Mail"></label>
            </div>

            <?php
            if($_GET["action"] == "register"){
                ?>
                <div class="field">
                <input type="password" required autocomplete="off" id="password" name="password" style="border-radius: 0px 0px 0px 0px; border-top: none;">
                <label for="password" title="Please enter your password." data-title="Password"></label>
                </div>
            <?php
            }
            ?>


            <div class="field">
                <input type="password" required autocomplete="off" id="password<?php if($_GET["action"] == "register"){ echo "confirm"; } ?>" name="password<?php if($_GET["action"] == "register"){ echo "confirm"; } ?>" style="border-radius: 0px 0px 10px 10px; border-top: none;">
                <label for="password<?php if($_GET["action"] == "register"){ echo "confirm"; } ?>" title="<?php if($_GET["action"] != "register"){ echo "Please enter your password"; } else { echo "Please confirm your password."; } ?>" data-title="Password <?php if($_GET["action"] == "register"){ echo "Confirm"; } ?>"></label>
            </div>
            <div class="field-button">
                <button type="button" style="border: none; background: transparent;" onclick="<?php if($_GET["action"] != "register"){ echo "swaploading()"; } else { echo "submitform()"; } ?>" id="submit-arrow-pwin-btn" name="submit-arrow-pwin"><img src="img/RightArrow.png" style="height: 30px;" id="submit-arrow-pwin-img" name="submit-arrow-pwin-img"> </button>
            </div>
            <?php  echo $username_err; echo $password_err; echo $confirm_password_err;?>
        </div>
    </div>
</form>
<div class="footer"><p class="text-center"><?php if($_GET["action"] != "register"){ ?> Don't have an account? <a href="/?action=register">Create here <?php } else { ?> Already have a Skyfallen Developer ID? <a href="/?action=login"> Login instead. <?php } ?></a></p></div>
</body>
</html>
