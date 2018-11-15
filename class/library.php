<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/validator.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/response.php');

class library
{
    private $Database;
    //a book object is needed to be initiated, I not sure how, and now I understand I don't know how database is being initiated
    private $Book;

    public function __construct($DatabaseLink)
    {
        $this->Database = $DatabaseLink;
        //should it be this way?
        $this->Book= new book;
    }

    //function prototypes from google doc
    //-GetId()
    //-AddBook()
    //-RemoveBook()
    //-RequestBook()
    //AcceptRequest()
    //DenyRequest()
    //-RequestDelivery()
    //-ConfirmDelivery()
    //-DenyDelivery()

    //params needed to be defined
    public function AddBook($params){
            //we don't need to validate coz set book info does that
            $result=$this->Book->setBookInfo($params);
            //this code is minimal because setBookInfo has taken care of all loose ends
            //$result carries whatever status is found
            //matching this result will be sufficient in front end
            return $result;
    }

}

