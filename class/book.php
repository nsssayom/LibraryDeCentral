<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/validator.php');

class book
{
    private $Database;

    public function __construct($DatabaseLink)
    {
        $this->Database = $DatabaseLink;
    }

    public function loadBookInfo($id)
    {

        if (empty($id)) return false; //or does it need status code?

        $id = $this->Database->escape($id);

        $sql = "SELECT * FROM book WHERE id='$id'";
        $result = $this->Database->getArray($sql);

        if (!mysqli_num_row($result)) { //if mysqli_num_row not zero

            return json_encode($result);

        } else return; //status code?
    }

    public function setBookInfo($param)
    {
        $params = array();
        foreach ($param as $key => $value) {
            $params[$key] = $this->Database->escape($value);
        }

        $data = array();
        $data['status'] = "330";
        $response = json_encode(array($data));

        if (!isset($params['bookid']) &&
            !isset($params['title']) &&
            !isset($params['author'])) {

            return $response;
        }
    }

    private function setGenre($string)
    {
        $sql = "SELECT id FROM genre WHERE name='$string'";
        $result = $this->Database->getArray($sql);
        if (isset($result[0])) {
            //genre found and id returned
            return $result['id'];
        } else {
            //genre not found
            $sql = "INSERT INTO genre(name) VALUES('$string')";
            if ($this->Database->query($sql)) {
                $sql = "SELECT LAST_INSERT_ID()";
                $result = $this->Database->query($sql);
                if (isset($result[0])) {
                    return $result['id'];
                } else {
                    $data = array();
                    $data['status'] = "400";
                    $response = json_encode(array($data));
                    return $response;
                }
            } else {
                $data = array();
                $data['status'] = "400";
                $response = json_encode(array($data));
                return $response;
            }
        }
    }
}
