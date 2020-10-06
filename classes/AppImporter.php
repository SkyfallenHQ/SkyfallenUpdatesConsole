<?php


namespace SkyfallenUpdatesConsole;

class AppImporter
{
    public static function importFromAppCenter($secretarray,$username,$link){
        $sql = "INSERT INTO importedapps (appid,appsecret,origin,username,appname,verified) VALUES ('".$secretarray["appid"]."','".$secretarray["appsecret"]."','SKYFALLENAPPCENTER','".$username."','".$secretarray["appname"]."','NO')";
        if(mysqli_query($link,$sql)){
            return true;
        } else {
            return mysqli_error($link);
        }
    }
    public static function getImportedAppNameFromID($link,$appid){
        $sql = "SELECT appname FROM importedapps WHERE appid='".$appid."'";
        if($sql_ret = mysqli_query($link,$sql)){
            if(mysqli_num_rows($sql_ret) == 1){
                while($row = mysqli_fetch_array($sql_ret)){
                    return $row["appname"];
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    public static function removeApp($link,$username,$appid){
        $sql = "DELETE FROM importedapps WHERE username='".$username."' and appid='".$appid."'";
        if(mysqli_query($link,$sql)){
            return true;
        } else {
            return mysqli_error($link);
        }
    }

}