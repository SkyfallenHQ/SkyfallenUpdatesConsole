<?php
$retarr = array();
if(isset($_GET["appid"]) and isset($_GET["seed"])){
    include_once "../configuration.php";
    $sql = "SELECT * FROM versions WHERE islatest='YES' and appid='".$_GET["appid"]."' and seed='".$_GET["seed"]."'";
    if($res = mysqli_query($link,$sql)){
        if(mysqli_num_rows($res) == 1){
            while($row = mysqli_fetch_array($res)){
                $retarr["version"] = $row["versionid"];
                $retarr["title"] = $row["title"];
                $retarr["description"] = $row["info"];
                $retarr["releasedate"] = $row["releasedate"];
                $retarr["downloadpath"] = DLDOMAIN.$row["datapath"];
            }
        } else {
            $retarr["error"] = "no valid version";
        }
    }else{
        $retarr["error"] = mysqli_error($link);
    }
}
die(json_encode($retarr));