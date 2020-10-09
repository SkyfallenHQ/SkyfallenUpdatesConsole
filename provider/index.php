<?php
include_once "../configuration.php";
$provider["name"] = PROVIDER;
$provider["ounit"] = PROVIDER_OU;
$provider["location"] = PROVIDER_LOCATION;
$provider["url"] = PROVIDER_URL;
$provider["contact"] = PROVIDER_CONTACT_NAME;
$provider["contact_email"] = PROVIDER_CONTACT_EMAIL;
$provider["contact_url"] = PROVIDER_CONTACT_URL;
$provider["info"] = PROVIDER_INFO;
$provider["type"] = PROVIDER_TYPE;
die(json_encode($provider));