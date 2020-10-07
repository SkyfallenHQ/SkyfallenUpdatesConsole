<?php


namespace SkyfallenUpdatesConsole;

class VCS
{
    public static function newSeed($link,$seedname,$appid,$username){
        $sql = "INSERT INTO seeds (owner,appid,seed) VALUES ('".$username."','".$appid."','".$seedname."')";
        if(mysqli_query($link,$sql)){
            return true;
        } else {
            return false;
        }
    }
    public static function deleteSeed($link,$seedname,$appid,$username){
        $sql = "DELETE FROM seeds WHERE owner='".$username."' and appid='".$appid."' and seed='".$seedname."'";
        if(mysqli_query($link,$sql)){
            return true;
        } else {
            return false;
        }
    }

    public static function listSeeds($link,$appid,$username){
        $seeds = array();
        $sql = "SELECT * FROM seeds WHERE owner='".$username."' and appid='".$appid."'";
        if($res = mysqli_query($link,$sql)){
            if(mysqli_num_rows($res) > 0){
             while ($row = mysqli_fetch_array($res)){
                 array_push($seeds,$row["seed"]);
             }
             return $seeds;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function newVersion($link,$username,$appid,$type,$seed,$version,$title,$info,$packagedir){
            $datapath = "/updatedl/app-".$appid."/".$seed."/".$type."-updates/".$version.".zip";
            $webpath = $datapath;
            $dirpath = "../updatedl/app-".$appid."/".$seed."/".$type."-updates/";
        if (!file_exists($dirpath)) {
            mkdir($dirpath, 0777, true);
        }
        // Check if version already exists in database
        $sql = "SELECT * FROM versions WHERE appid='".$appid."' and versionid='".$version."'";
        if($result = mysqli_query($link,$sql)){
            if(mysqli_num_rows($result) > 0){
                return false;
            }
            else {
                mysqli_free_result($result);
                // Get real path for our folde
                chdir($packagedir);
                $rootPath = getcwd();
                $zip = new \ZipArchive();
                chdir(__DIR__);
                chdir("..");
                $datapath = getcwd().$datapath;
                $zip->open($datapath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
                /** @var SplFileInfo[] $files */
                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($rootPath),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                );

                foreach ($files as $name => $file)
                {
                    // Skip directories (they would be added automatically)
                    if (!$file->isDir())
                    {
                        // Get real and relative path for current file
                        $filePath = $file->getRealPath();
                        $relativePath = substr($filePath, strlen($rootPath) + 1);
                        // Add current file to archive
                        $zip->addFile($filePath,$relativePath);
                    }
                }

                $zip->close();

                $ivsql = "INSERT INTO versions (appid,appsecret,vtype,seed,versionid,datapath,title,info,releasedate,islatest,owner) VALUES ('".$appid."','NOTREQUIRED','".$type."','".$seed."','".$version."','".$webpath."','".$title."','".$info."','".date("d/m/Y h:i:sa")."','NO','".$username."')";
                if(mysqli_query($link,$ivsql)){
                    return true;
                } else {
                    return mysqli_error($link);
                }
            }
        }

    }
    public static function deleteVersion($link,$appid,$version,$username){
        $versiondata = array();
        $sql = "SELECT * FROM versions WHERE owner='".$username."' and appid='".$appid."' and versionid='".$version."'";
        if($res = mysqli_query($link,$sql)){
            if(mysqli_num_rows($res) == 1){
                while ($row = mysqli_fetch_array($res)){
                    $versiondata = $row;
                }
            } else {
                return false;
            }
        }else {
            return false;
        }
        unlink("..".$versiondata["datapath"]);
        $dvsql = "DELETE FROM versions WHERE appid='".$appid."' and versionid='".$version."'";
        if(mysqli_query($link,$dvsql)){
            return true;
        } else {
            return mysqli_error($link);
        }
    }
    public static function listVersions($link,$username,$appid){
        $versions = array();
        $sql = "SELECT * FROM versions WHERE owner='".$username."' and appid='".$appid."'";
        if($res = mysqli_query($link,$sql)){
            if(mysqli_num_rows($res) > 0){
                while ($row = mysqli_fetch_array($res)){
                    $versions[$row["entry"]] = $row["versionid"];
                }
                return $versions;
            } else {
                return false;
            }
        }else {
            return false;
        }
    }
    public static function getVersionData($link,$username,$appid,$versionentry){
        $versiondata = array();
        $sql = "SELECT * FROM versions WHERE owner='".$username."' and appid='".$appid."' and entry='".$versionentry."'";
        if($res = mysqli_query($link,$sql)){
            if(mysqli_num_rows($res) == 1){
                while ($row = mysqli_fetch_array($res)){
                    $versiondata = $row;
                }
                return $versiondata;
            } else {
                return false;
            }
        }else {
            return false;
        }
    }
    public static function makeLatest($link,$username,$appid,$vid){
        $sql = "UPDATE versions SET islatest='NO' WHERE owner='".$username."' and appid='".$appid."' and islatest='YES'";
        mysqli_query($link,$sql);
        $sql = "UPDATE versions SET islatest='YES' WHERE owner='".$username."' and appid='".$appid."' and versionid='".$vid."'";
        mysqli_query($link,$sql);
    }
}