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
    //-DeleteBook() //on google docs it was remove book
    //-RequestBook()
    //AcceptRequest()
    //DenyRequest()
    //-RequestDelivery()
    //-ConfirmDelivery()
    //-DenyDelivery()

    //I propose
    //-editBook()
    //-and for this editBook() book.php needs the edit functions based on sql "UPDATE" command


    //params needed to be defined
    public function AddBook($params){
            //we don't need to validate coz set book info does that
            $result=$this->Book->setBookInfo($params);
            //this code is minimal because setBookInfo has taken care of all loose ends
            //$result carries whatever status is found
            //matching this result will be sufficient in front end
            return $result;
    }
    public function DeleteBook($id){
        //$id will be collected by onclick event, from the loaded result of the user end
        //it's upto user if he/she finds it by searching or from the list
        //taking name is not convenient, because one user can have multiple book of same name,
        //sorry for my ameture commenting :p
        $result=$this->Book->removeBook($id);
        //again the code is minimal because Book->removeBook passes the relevant status
        return $result;
    }

    public function EditBook($params){
        //work on progress on book.php for it's functions
    }

    //jagged array issue is needed to be fixed
    //gotokaler ta commit marsi
    //ajkeo vule ekta commit korsi\
    //pera nai
    //signing out


    //illuminast signing in
    //there is no need of edit functions
    //see, we will not give users let edit an entry other than book name.
    //to change an author name, remove the previous author, then add a new one. Same goes for genre and tags

}

