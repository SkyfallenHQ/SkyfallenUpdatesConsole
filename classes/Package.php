<?php


namespace SkyfallenUpdatesConsole;


class Package
{
    static public function createPackage($link,$packageid = "DEFAULT",$packagepath = "DEFAULT",$username,$packagename,$appid,$source = "NOT SPECIFIED"){
        if($packageid == "DEFAULT"){
            $packageid = md5(uniqid(rand(), true));
        }
        if($packagepath == "DEFAULT"){
            $packagepath = "packages/pkg-".$packageid."/";
        }
        $sql = "INSERT INTO `packages` (`packageid`, `packagepath`, `packagename`, `source`, `owner`, `appid`, `packagedate`) VALUES ('".$packageid."', '".$packagepath."', '".$packagename."', '".$source."', '".$username."', '".$appid."', '".date("d/m/Y h:i:sa")."')";
        $packagerealpath = "../".$packagepath;
        if (!file_exists($packagerealpath)) {
            mkdir($packagerealpath, 0777, true);
        }
        if(mysqli_query($link,$sql)){
            return true;
        }else {
            return false;
        }
    }
    static public function listPackages($link,$username,$appid){
        $packages = array();
        $sql = "SELECT * FROM packages WHERE owner='".$username."' and appid='".$appid."'";
        if($res = mysqli_query($link,$sql)){
            if(mysqli_num_rows($res) > 0){
                while ($row = mysqli_fetch_array($res)){
                    $packages[$row["packageid"]] = $row["packagename"];
                }
                return $packages;
            } else {
                return false;
            }
        }else {
            return false;
        }
    }
    static public function getPackageArray($link,$username,$packageid){
        $packages = array();
        $sql = "SELECT * FROM packages WHERE owner='".$username."' and packageid='".$packageid."'";
        if($res = mysqli_query($link,$sql)){
            if(mysqli_num_rows($res) == 1){
                while ($row = mysqli_fetch_array($res)){
                    $packages = $row;
                }
                return $packages;
            } else {
                return false;
            }
        }else {
            return false;
        }
    }
    static public function deletePackage($link,$packageid,$username){
        $sql = "SELECT * FROM packages WHERE owner='".$username."' and packageid='".$packageid."'";
        if($res = mysqli_query($link,$sql)){
            if(mysqli_num_rows($res) == 1){
                while ($row = mysqli_fetch_array($res)){
                    $packages = $row;
                }
            }
        }
        $packagepath = "../".$packages["packagepath"];
        $sql = "DELETE FROM packages WHERE owner='".$username."' and packageid='".$packageid."'";
        $oldpath = getcwd();
        chdir(__DIR__);
        Utils::removedir($packagepath);
        chdir($oldpath);
        if(mysqli_query($link,$sql)){
            return true;
        }else {
            return false;
        }
    }
}