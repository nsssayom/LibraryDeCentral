<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/validators.php');
class User
{
    private $Database;

    public function __construct($DatabaseLink)
    {
        $this->Database = $DatabaseLink;
    }

    public function processPassword($password)
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

        $result = $this->Database->getArray("SELECT * FROM `user` WHERE `username` = '".$userLogin."' OR `email` = '" . $userLogin. "'");
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

    public function verifyToken($token)
    {
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

    public function isUsernameAvailable($username){

    }

    public function register($username, $password, $name, $phone, $email, $dob){
      $username = mysqli_real_escape_string($this->connection, $username);
      $name = mysqli_real_escape_string($this->connection, $name);
      $phone = mysqli_real_escape_string($this->connection, $phone);
      $email = mysqli_real_escape_string($this->connection, $email);
      $dob = mysqli_real_escape_string($this->connection, $dob);

    }
}
