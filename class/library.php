<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/validator.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/response.php');

class Library
{
    private $Database;
    private $Book;

    public function __construct($DatabaseLink)
    {
        $this->Database = $DatabaseLink;
        //should it be this way?
        $this->Book= new book;
    }

    //function prototypes from google doc
    //-AddBook()        //done
    //-DeleteBook()     //done
    //-RequestBook()    //
    //AcceptRequest()
    //DenyRequest()
    //-RequestDelivery()
    //-ConfirmDelivery()
    //-DenyDelivery()


    public function AddBook($params){
        $params = $this->Database->escape_recursive($params);
        $bookID = $this->book->initBook($params['title']);

        $this->Book->setBookInfo($bookID, $params['authors'], $params['genre'], $params['publisher'], $params['tags'] );
        $userID = $params['userID'];
        $sql = "INSERT INTO book_user(book_id, user_id) VALUES ('$bookID', '$userID')";
        $this->Database->query($sql);
        response_ok();
    }
    public function DeleteBook($id){
        $sql = "INSERT INTO book(isDeleted) VALUES(1) WHERE id='$id'";
        $this->Database->query($sql);
        response_ok();
    }

    public function EditBook($params){
        //work on progress on book.php for it's functions
    }

}

