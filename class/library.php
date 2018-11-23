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
    //sofistication: is EditBook() is necessary?
    public function EditBook($params){
    }
    //sofistication: initiating

    public function requestBook($bookID,$borrowerID){
        $bookID=$this->Database->escape($bookID);
        $userID=$this->Database->escape($borrowerID);

        $sql="INSERT into issue (book_id,borrower_id,is_requested) VALUES ($bookID,$borrowerID,1)";
        $this->Database->query($sql);
        response_ok();
    }


    public function acceptRequest($requestID){
        $requestID=$this->Database->escape($requestID);
        $sql="UPDATE issue SET is_accepted=1, accept_time=CURRENT_TIMESTAMP WHERE id='$requestID'";
        $this->Database->query($sql);
        response_ok();
    }

    public function denyRequest($requestID){
        $requestID=$this->Database->escape($requestID);
        $sql="UPDATE issue SET is_denied=1, deny_time=CURRENT_TIMESTAMP WHERE id='$requestID'";
        $this->Database->query($sql);
        response_ok();
    }

    public function cancelRequest($userID,$requestID){
        $requestID=$this->Database->escape($requestID);
        $userID=$this->Database->escape($userID);
        $sql="UPDATE issue SET cancel_delivery=1, cancel_time=CURRENT_TIMESTAMP , cancel_by='$userID' WHERE id='$requestID'";
        $this->Database->query($sql);
        response_ok();
    }
    //delivery will start from here
    //sofistication signing out

}

