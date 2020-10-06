<?php


namespace SkyfallenUpdatesConsole;


class DB
{
  public static function execute($link,$sql){
      if(mysqli_query($link,$sql)){
          return true;
      } else {
          return mysqli_error($link);
      }
  }
  public static function setAppOption($link,$appid,$option,$val){
      $sql = "INSERT INTO appsettings (appid,setting,settingvalue) VALUES ('".$appid."','".$option."','".$val."')";
      if(mysqli_query($link,$sql)){
          return true;
      } else {
          return mysqli_error($link);
      }
  }
    public static function removeAppOption($link,$appid,$option){
        $sql = "DELETE FROM appsettings WHERE appid='".$appid."' and setting='".$option."'";
        if(mysqli_query($link,$sql)){
            return true;
        } else {
            return mysqli_error($link);
        }
    }
    public static function getAppOption($link,$appid,$option){
        $sql = "SELECT * FROM appsettings WHERE appid='".$appid."' and setting='".$option."'";
        if($res = mysqli_query($link,$sql)){
            if(mysqli_num_rows($res) == 1){
                while ($row = mysqli_fetch_array($res)){
                    return $row["settingvalue"];
                }
            }else {
                return false;
            }
        } else {
            return false;
        }
    }
}