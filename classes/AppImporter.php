<?php


namespace SkyfallenUpdatesConsole;


class AppImporter
{
    public static function importFromAppCenter($secretarray,$username,$link){
        $sql = "INSERT INTO importedapps (appid,appsecret,origin,username,appname,verified) VALUES ('".$secretarray["appid"]."','".$secretarray["appsecret"]."','SKYFALLENAPPCENTER','".$username."','".$secretarray["appname"]."','NO')";
        if(mysqli_query($sql,$link)){
            return true;
        } else {
            return mysqli_error($link);
        }
    }
    public static function getImportedAppNameFromID($link,$appid){

    }

}