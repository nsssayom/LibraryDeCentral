<?php ?>
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/validator.php');
public
function verifyPhoneEmail($code, $mode)
{
    if ($mode != "mail" || $mode != "phone") {
        return false;
    }
    $accountKitConfigStr = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/config/accountKitConfig.json');
    $accountKitConfig = json_decode($accountKitConfigStr, true);
    // Initialize variables
    $app_id = $accountKitConfig['app_id'];//'1152182654930467';
    $secret = $accountKitConfig['secret'];//'5c7f6b7f69d67ee98159b7fc81fe1477';
    $version = $accountKitConfig['version'];//'v1.1';
    // Method to send Get request to url
    function doCurl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $data;
    }

    // Exchange authorization code for access token
    $token_exchange_url = 'https://graph.accountkit.com/' . $version . '/access_token?' .
        'grant_type=authorization_code' .
        '&code=' . $code .
        "&access_token=AA|$app_id|$secret";
    $data = doCurl($token_exchange_url);
    $user_id = $data['id'];
    $user_access_token = $data['access_token'];
    $refresh_interval = $data['token_refresh_interval_sec'];
    // Get Account Kit information
    $me_endpoint_url = 'https://graph.accountkit.com/' . $version . '/me?' .
        'access_token=' . $user_access_token;
    $data = doCurl($me_endpoint_url);
    $phone = isset($data['phone']) ? $data['phone']['number'] : '';
    $email = isset($data['email']) ? $data['email']['address'] : '';
    if ($mode == "email") {
        if (!empty($email)) {
            return $email;
        } else {
            $data = array();
            $data['status'] = "310";
            $response = json_encode(array($data));
            return $response;
        }
    } else if ($mode == "phone") {
        if (!empty($phone)) {
            return $phone;
        } else {
            $data = array();
            $data['status'] = "311";
            $response = json_encode(array($data));
            return $response;
        }
    }
}

