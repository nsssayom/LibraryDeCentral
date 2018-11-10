<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/validator.php');
 class Book{
    private $Database;
    private $id;
    private $title;

    public function __construct($DatabaseLink)
    {
        $this->Database = $DatabaseLink;
    }

    public function getId(){
        return $id;
    }

    public function getTitle(){

    }
    public function setTitle($str){

        $this->$title=$this->Database->escape($str);
        $sql="INSERT INTO `book` (`id`, `title`, `edition`, `isbn`, `pub_id`) VALUES ('$title')";
        $this->$Database->query($sql);
        
    }
}