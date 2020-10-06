<?php


namespace SkyfallenCodeLibrary;


class AppCenterAPI
{
    public static function listpublicapps($username,$endpoint = API_ENDPOINT){
        //The url you wish to send the POST request to
        $fields = [
            'username'          => $username,
            'action'            => "listpublicapps"
        ];

        $fields_string = http_build_query($fields);

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL, $endpoint);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        $http_resp = curl_getinfo($ch)["http_code"];
        if($http_resp == 200) {
            $resp_array = json_decode($result);
            return $resp_array;
        } elseif($http_resp == 403){
            return false;
        } else {
            die("Skyfallen App Center API has encountered an unknown error.");
        }
    }
    public static function listprivateapps($username,$password,$endpoint = API_ENDPOINT){
        //The url you wish to send the POST request to
        $fields = [
            'username'          => $username,
            'password'          => $password,
            'action'            => "listprivateapps"
        ];

        $fields_string = http_build_query($fields);

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL, $endpoint);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        $http_resp = curl_getinfo($ch)["http_code"];
        if($http_resp == 200) {
            $resp_array = json_decode($result);
            return $resp_array;
        } elseif($http_resp == 403){
            return false;
        } else {
            die("Skyfallen App Center API has encountered an unknown error.");
        }
    }
    public static function verifycombination($username,$appid,$appsecret,$endpoint = API_ENDPOINT){
        //The url you wish to send the POST request to
        $fields = [
            'username'          => $username,
            'action'            => "verifysecret",
            'appid'             => $appid,
            'appsecret'         => $appsecret

        ];

        $fields_string = http_build_query($fields);

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL, $endpoint);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        $http_resp = curl_getinfo($ch)["http_code"];
        if($http_resp == 200) {
            return true;
        } elseif($http_resp == 403){
            return false;
        } else {
            die("Skyfallen App Center API has encountered an unknown error.");
        }
    }

    public static function getsecret($username,$appid,$password,$endpoint = API_ENDPOINT){
        //The url you wish to send the POST request to
        $fields = [
            'username'          => $username,
            'action'            => "getsecret",
            'appid'             => $appid,
            'password'         => $password

        ];

        $fields_string = http_build_query($fields);

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL, $endpoint);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        $http_resp = curl_getinfo($ch)["http_code"];
        if($http_resp == 200) {
            $resparr = json_decode($result);
            return $resparr;
        } elseif($http_resp == 403){
            return false;
        } else {
            die("Skyfallen App Center API has encountered an unknown error.");
        }
    }
}