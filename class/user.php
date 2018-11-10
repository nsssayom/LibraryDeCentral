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
        $options = [
    'cost' => 8,
    ];
        return password_hash($password, PASSWORD_BCRYPT, $options);
    }

    public function login($userLogin, $password)
    {
        $userLogin = $this->Database->escape($userLogin);
        $password = $this->Database->escape($password);
        //$password = $this->processPassword($password);
        $sql = "SELECT * FROM `user` WHERE `username` = '".$userLogin."' OR `email` = '" . $userLogin. "'";
        $result = $this->Database->getArray($sql);
        if (isset($result[0])) {
            //user found
            $user = $result[0];

            if (password_verify($password, $user['password'])) {
                //password matched. Login Successful
                $data = array();

                $tokenReturn = $this->createToken($user['id']);
                if ($tokenReturn === false) {
                    $data['status'] = "270";
                    $result = json_encode(array($data));
                    print_r($result);
                    exit();
                }

                $data ['status'] = "100";
                $data ['token'] = $tokenReturn;

                $response = json_encode(array($data));
                return $response;
            } else {
                //password didn't matched
                $data = array();
                $data ['status'] = "258";
                $response = json_encode(array($data));
                return $response;
            }
        } else {
            //user not found
            $data = array();
            $data ['status'] = "255";
            $response = json_encode(array($data));
            return $response;
        }
    }

    private function createToken($userID)
    {
        $creationTime = time();
        $expiryTime = strtotime('+7 days', $creationTime);

        $token_seed = $userID . "#Heil_Hitler#" . $creationTime . "&Fuhrer_is_Great&";
        $token = hash('sha256', $token_seed);

        $sql = "INSERT INTO login_token (token, uid, creationTime, expiryTime)
              VALUES ('" . $token . "','" . $userID . "','" . $creationTime . "','"
              . $expiryTime. "')";
        if ($this->Database->query($sql) !== false) {
            return $token;
        } else {
            return false;
        }
    }

    private function updateToken($token)
    {
        $now = time();
        $expiryTime = strtotime('+7 days', $now);
        $sql = "UPDATE login_token SET expiryTime = '" . $expiryTime . "' WHERE token = '" . $token . "'";
        if ($this->Database->query($sql) !== false) {
            return true;
        } else {
            return false;
            /*
            $data = array();
            $data['status'] = "275";
            $result = json_encode(array($data));
            print_r($result);
            $this->connection->close();
            exit();
            */
        }
    }

    private function verifyToken($token)
    {
        /*
        return
            true  Okay
            0     Token_Expired
            -1    Invalid_Token
        */

        $sql = "SELECT id, expiryTime FROM `login_token` WHERE token = '" . $token . "';";
        $now = time();
        $result = $this->Database->getArray($sql);

        if (isset($result[0])) {
            $result = $result[0];
            //token found
            if ($result['expiryTime'] > $now) {
                //token ok
                return true;
                exit();
            } else {
                return 0;
                //token expired
                /*$data = array();
                $data['status'] = "280";
                $result = json_encode(array($data));
                print_r($result);
                exit();
                */
            }
        } else {
            return -1;
            //invalid token
            /*$data = array();
            $data['status'] = "285";
            $result = json_encode(array($data));
            print_r($result);
            exit();
            */
        }
    }

    public function isUserNameAvailable($username)
    {
        $username = $this->Database->escape($username);
        $sql = "SELECT id, username, email FROM `user` WHERE username = '" . $username . "'";
        $result = $this->Database->getArray($sql);
        if (isset($result[0])) {
            $data = array();
            $data ['status'] = "301";
            $response = json_encode(array($data));
            return $response;
        }
        return true;
    }

    public function isEmailAvailable($email)
    {
        $email = $this->Database->escape($email);

        if (!validateEmail($email)) {
            $data = array();
            $data ['status'] = "302";
            $response = json_encode(array($data));
            return $response;
        }

        $sql = "SELECT id, username, email FROM `user` WHERE email = '" . $email . "'";
        $result = $this->Database->getArray($sql);
        if (isset($result[0])) {
            $data = array();
            $data ['status'] = "303";
            $response = json_encode(array($data));
            return $response;
        }
        return true;
    }

    public function isPhoneAvailable($phone)
    {
        $phone = $this->Database->escape($phone);
        if (!validatePhone($phone)) {
            $data = array();
            $data ['status'] = "304";
            $response = json_encode(array($data));
            return $response;
        }
        $sql = "SELECT id, username, phone FROM `user` WHERE phone = '" . $phone . "'";
        $result = $this->Database->getArray($sql);
        if (isset($result[0])) {
            $data = array();
            $data ['status'] = "305";
            $response = json_encode(array($data));
            return $response;
        }
        return true;
    }

    //public function register($username, $password, $name, $phone, $email, $dob)
    public function register($username, $password, $code, $email, $name, $dob)
    {
        $username = $this->Database->escape($username);
        $name = $this->Database->escape($name);
        $code = $this->Database->escape($code);
        $email = $this->Database->escape($email);
        $dob = $this->Database->escape($dob);
        $password = $this->Database->escape($password);

        //remove the following line
        $phone = $code;

        $username_availability = $this->isUserNameAvailable($username);
        $email_availability = $this->isEmailAvailable($email);
        $phone_availability = $this->isPhoneAvailable($phone);

        if (isJson($username_availability) || isJson($email_availability) || isJson($phone_availability)) {
            $error = array();
            $response = null;
            if (isJson($username_availability)) {
                $error = array_merge($error, json_decode($username_availability, true));
            }
            if (isJson($email_availability)) {
                $error = array_merge($error, json_decode($email_availability, true));
            }
            if (isJson($phone_availability)) {
                $error = array_merge($error, json_decode($phone_availability, true));
            }
            $response = json_encode($error);
            print_r($response);
            //print_r(json_decode($response));
            return $response;
        }



        //this handels the verification using account kit
        /*
        $phoneResponse = verifyPhoneEmail($code, "phone");
        if (!isJson($phoneResponse)) {
            $phone = $phoneResponse;
        } else {
            return $phoneResponse;
        }
        */

        if (!validateDate($dob, 'YYYY-MM-DD')){
          $data = array();
          $data ['status'] = "312";
          $response = json_encode(array($data));
          return $response;
        }

        $processedPassword = $this->processPassword($password);

        $sql = "INSERT INTO user(username, password, name, phone, dob, email)
                VALUES ('" . $username . "','" . $processedPassword . "','" . $name ."','" . $phone .
                "','" . $dob . "','" . $email ."')";
        if ($this->Database->query($sql)){
          return $this->login($email, $password);
        }
    }
}
