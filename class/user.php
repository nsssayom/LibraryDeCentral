<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/validator.php');

class User
{
    private $Database;

    public function __construct($DatabaseLink)
    {
        $this->Database = $DatabaseLink;
    }

    private function processPassword($password)
    {
        $options = ['cost' => 8,];
        return password_hash($password, PASSWORD_BCRYPT, $options);
    }

    public function login($userLogin, $password)
    {
        //$password = $this->processPassword($password);
        $sql = "SELECT * FROM user WHERE username =  '$userLogin' OR email = '$userLogin'";
        $result = $this->Database->getArray($sql);
        if (isset($result[0])) {
            //user found
            $user = $result[0];
            if (password_verify($password, $user['password'])) {
                //password matched. Creating Token
                $tokenReturn = $this->createToken($user['id']);
                if (!$tokenReturn) {
                    //Token Creation failed
                     response_token_creation_failed();
                }
                //Token Created. Returning Token
                 response_token($tokenReturn);
            } else {
                //password didn't matched
                 response_wrong_password();
            }
        } else {
            //user not found
             response_invalid_user();
        }
    }

    //No JSON Response
    private function createToken($userID)
    {
        $creationTime = time();
        $expiryTime = strtotime('+7 days', $creationTime);

        $token_seed = $userID . "#Heil_Hitler#" . $creationTime . "&Fuhrer_is_Great&";
        $token = hash('sha256', $token_seed);


        $sql = "SELECT * FROM login_token WHERE token = '$token')";
        $result = $this->Database->getArray($sql);
        if (!isset($result[0])){
            createToken($userID);
        }

        $sql = "INSERT INTO login_token (token, uid, creationTime, expiryTime) VALUES ('$token','$userID','$creationTime','$expiryTime')";
        if ($this->Database->query($sql)) {
            return $token;
        } else {
            return false;
        }
    }

    private function updateToken($token)
    {
        $now = time();
        $expiryTime = strtotime('+7 days', $now);
        $sql = "UPDATE login_token SET expiryTime = '$expiryTime' WHERE token = '$token'";
        if ($this->Database->query($sql) !== false) {
            return true;
        } else {
             response_token_update_failed();
        }
    }

    public function verifyToken($token)
    {
        $sql = "SELECT uid, expiryTime FROM login_token WHERE token = '$token';";
        $now = time();
        $result = $this->Database->getArray($sql);

        if (isset($result[0])) {
            $result = $result[0];
            //token found
            if ($result['expiryTime'] > $now) {
                //token ok
                updateToken($token);
                //is_blocked needs to be checked
                return $result['uid'];
            } else {
                //token expired
                response_token_expired();
            }
        } else {
            //token not found
            response_invalid_token();
        }
    }

    public function isUserNameAvailable($username)
    {
        //$sql = "SELECT id, username, email FROM `user` WHERE username = '$username'";
        //testing with bujayer
        $sql = "SELECT id FROM `user` WHERE username = '$username'";
        $result = $this->Database->getArray($sql);
        if (isset($result[0])) {
            response_username_not_available();
        }
        return true;
    }

    public function isSexAvailable($sex){
        if(!validateSex($sex)) {
            response_invalid_request();
        }
        return true;
    }

    public function isEmailAvailable($email)
    {

        if (!validateEmail($email)) {
            response_invalid_email();
        }

        $sql = "SELECT id, username, email FROM `user` WHERE email = '$email'";
        $result = $this->Database->getArray($sql);
        if (isset($result[0])) {
            response_email_not_available();
        }
        return true;
    }

    public function isPhoneAvailable($phone)
    {

        if (!validatePhone($phone)) {
            response_invalid_phone_number();
        }

        $sql = "SELECT id, username, phone FROM `user` WHERE phone = '$phone'";
        $result = $this->Database->getArray($sql);
        if (isset($result[0])) {
            response_phone_number_not_available();
        }

        return true;
    }

    //public function register($username, $password, $name, $phone, $email, $dob)
    public function register($username, $password, $code, $email, $name, $dob,$sex)
    {
        //remove the following line
        $phone = $code;

        $this->isUserNameAvailable($username);
        $this->isEmailAvailable($email);
        $this->isPhoneAvailable($phone);
        $this->isSexAvailable($sex);

        //this handels the verification using account kit
        /*
        $phoneResponse = verifyPhoneEmail($code, "phone");
        if (!isJson($phoneResponse)) {:w
        1
            $phone = $phoneResponse;
        } else {
            return $phoneResponse;
        }
        */

        if (!validateDate($dob, 'YYYY-MM-DD')) {
            response_invalid_dateofbirth();
        }

        $processedPassword = $this->processPassword($password);

        $sql = "INSERT INTO user(username, password, name, phone, dob, email,sex)
                VALUES ('$username', '$processedPassword', '$name', '$phone', '$dob', '$email','$sex')";

        if ($this->Database->query($sql)) {
            $this->login($email, $password);
        }
    }
}
