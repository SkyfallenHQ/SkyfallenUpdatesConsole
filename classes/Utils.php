<?php


namespace SkyfallenUpdatesConsole;


class Utils
{
        public static function convertToNonSTDarray($stdarray){
            return json_decode(json_encode($stdarray),true);
        }
        public static function listapps($link,$username){
            $ret = "";
            $sql = "SELECT * FROM importedapps WHERE username='".$username."'";
            if($result = mysqli_query($link, $sql)){
                if(mysqli_num_rows($result) > 0){
                    $ret = $ret.'<div style="text-align: center; width: 100%; text-align: center; margin-top: 50px;">';
                    $ret = $ret."<table class='table' style='width:80%; margin-right: auto; margin-left: auto;'>";
                    $ret = $ret."<thead>";
                    $ret = $ret."<tr>";
                    $ret = $ret."<th scope='col'>App Name</th>";
                    $ret = $ret."<th scope='col'>App ID</th>";
                    $ret = $ret."<th scope='col'>View Packages</th>";
                    $ret = $ret."<th scope='col'>Edit Updates</th>";
                    $ret = $ret."<th scope='col'>Update Seeds</th>";
                    $ret = $ret."<th scope='col'>Delete</th>";
                    $ret = $ret."</tr>";
                    $ret = $ret."</thead>";
                    $ret = $ret."<tbody>";
                    while($row = mysqli_fetch_array($result)){
                        $ret = $ret. "<tr>";
                        $ret = $ret. "<td>" . $row['appname'] . "</td>";
                        $ret = $ret. "<td>" . $row['appid'] . "</td>";
                        $ret = $ret. "<td><a href='?page=packages&appid=" . $row['appid'] . "' >View Packages</a></td>";
                        $ret = $ret. "<td><a href='?page=updates&appid=" . $row['appid'] . "' >Edit Updates</a></td>";
                        $ret = $ret. "<td><a href='?page=seeds&appid=" . $row['appid'] . "' >Update Seeds</a></td>";
                        $ret = $ret. "<td><a href='?page=myapps&del=" . $row['appid'] . "' >Delete</a></td>";
                        $ret = $ret. "</tr>";
                    }
                    $ret = $ret."</table>";
                    mysqli_free_result($result);
                } else{
                    $ret = "No apps found.";
                }
            } else{
                $ret = "ERROR: Could not able to execute $sql. " . mysqli_error($link);
            }

            // Close connection
            mysqli_close($link);
            return $ret;
        }
    public static function removedir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
                        removedir($dir. DIRECTORY_SEPARATOR .$object);
                    else
                        unlink($dir. DIRECTORY_SEPARATOR .$object);
                }
            }
            rmdir($dir);
        }
    }

}