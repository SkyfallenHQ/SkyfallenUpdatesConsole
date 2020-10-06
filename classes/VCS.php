<?php


namespace SkyfallenUpdatesConsole;


class VCS
{
    public static function newVersion($link,$appid,$type,$seed,$version,$datapath = "DEFAULT",$title,$info,$packagedir){
        if($datapath == "DEFAULT"){
            $datapath = "app-".$appid."/".$seed."/".$type."-updates/".$version.".zip";
        }
        // Check if version already exists in database
        $sql = "SELECT * FROM versions WHERE appid='".$appid."' and versionid='".$version."'";
        if($result = mysqli_query($link,$sql)){
            if(mysqli_num_rows($result) > 0){
                return false;
            }
            else {
                mysqli_free_result($result);
                // Get real path for our folder
                $rootPath = realpath($packagedir);

                $zip = new ZipArchive();
                $zip->open($datapath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

                /** @var SplFileInfo[] $files */
                $files = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($rootPath),
                    RecursiveIteratorIterator::LEAVES_ONLY
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
                        $zip->addFile($filePath, $relativePath);
                    }
                }

                $zip->close();

                $ivsql = "INSERT INTO versions (appid,vtype,seed,versionid,datapath,title,info,releasedate) VALUES ('".$appid."','".$type."','".$seed."','".$version."','".$datapath."','".$title."','".$info."','".date("d/m/Y h:i:sa")."')";
                if(mysqli_query($link,$ivsql)){
                    return true;
                } else {
                    return mysqli_error($link);
                }
            }
        }

    }
    public static function deleteVersion($link,$appid,$version){
        $dvsql = "DELETE FROM versions WHERE appid='".$appid."' and versionid='".$version."'";
        if(mysqli_query($link,$dvsql)){
            return true;
        } else {
            return mysqli_error($link);
        }
    }
}