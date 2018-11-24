<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/validator.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/response.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/class/database.php');

class Book
{
    private $Database;

    public function __construct($DatabaseLink)
    {
        $this->Database = $DatabaseLink;
    }

    public function loadBookInfo($id)
    {

        if (empty($id)) response_invalid_request(); //or does it need status code?

        $id = $this->Database->escape($id);

        $sql = "SELECT * FROM book WHERE id='$id'";
        $result = $this->Database->getArray($sql);

        if (!mysqli_num_row($result)) {
            return json_encode(array($result));
        } else response_entity_unavailable();
    }

    public function setBookInfo($bookID, $authors, $genre = null, $publisher = null, $tags = null)
    {
        //insert author
        foreach ($authors as $author) {
            //author is an associative array with 2 keys: name and type
            $this->setAuthor($bookID, $this->getAuthor($author['name']), $author['type']);
        }

        //insert genre
        if (!is_null($genre)) {
            $this->setGenre($bookID, $this->getGenre($genre));
        }

        //insert tags
        if (!is_null($tags)) {
            foreach ($tags as $tag) {
                $this->setTag($bookID, $this->getTag($tag));
            }
        }

        if (!is_null($publisher)) {
            $this->setPublisher($bookID, $this->getPublisher($publisher));
        }

        //new response on success
         response_ok();
    }

    //Edit book title [Only editable entity]
    public function editBookTitle($bookId, $newTitle)
    {
        $newTitle = $this->Database->escape($newTitle);
        $bookId = $this->Database->escape($bookId);

        if ($this->bookExists($bookId)) {
            $sql = "UPDATE book SET title = '$newTitle' WHERE id = '$bookId'";
            $this->Database->query($sql);
            response_ok();
        } else {
            response_invalid_request();
        }
    }

    private function bookExists($bookId)
    {
        $bookId = $this->Database->escape($bookId);
        $sql = "SELECT * FROM book WHERE id = '$bookId'";
        $this->Database->getArray($sql);

        if (isset($result[0])) {
            return false;
        }
        return true;
    }

    private function authorExists($authorId)
    {
        $bookId = $this->Database->escape($authorId);
        $sql = "SELECT * FROM author WHERE id = '$authorId'";
        $this->Database->getArray($sql);

        if (isset($result[0])) {
            return false;
        }
        return true;
    }

    private function genreExists($genreId)
    {
        $bookId = $this->Database->escape($genreId);
        $sql = "SELECT * FROM genre WHERE id = '$genreId'";
        $this->Database->getArray($sql);

        if (isset($result[0])) {
            return false;
        }
        return true;
    }
    private function tagExists($tagId)
    {
        $bookId = $this->Database->escape($tagId);
        $sql = "SELECT * FROM tag WHERE id = '$tagId'";
        $this->Database->getArray($sql);

        if (isset($result[0])) {
            return false;
        }
        return true;
    }

    private function publisherExists($publisherId)
    {
        $bookId = $this->Database->escape($publisherId);
        $sql = "SELECT * FROM publisher WHERE id = '$publisherId'";
        $this->Database->getArray($sql);

        if (isset($result[0])) {
            return false;
        }
        return true;
    }

    //function for getting the id for setBookInfo()
    public function initBook($name)
    {
        $sql = "INSERT INTO book(name) VALUES('$name')";
        $this->Database->query($sql);
        $sql = "SELECT LAST_INSERT_ID()";
        $result = $this->Database->getArray($sql);
        return $result['id'];
    }

    public function getAuthor($string)
    {
        $sql = "SELECT id FROM author WHERE name='$string'";
        $result = $this->Database->getArray($sql);
        if (isset($result[0])) {
            //author found and id returned
            return $result['id'];
        } else {
            //author not found
            $sql = "INSERT INTO author(name) VALUES('$string')";
            $this->Database->query($sql);
            $sql = "SELECT LAST_INSERT_ID()";
            $result = $this->Database->getArray($sql);
            return $result['id'];
        }
    }

    //for relation table
    public function setAuthor($bookID, $authorID, $authorType = 0)
    {
        $sql = "INSERT INTO author_book(book_id, author_id, author_type) VALUES ('$bookID', '$authorID', '$authorType')";
        $this->Database->query($sql);
        response_ok();
    }

    public function getGenre($string)
    {
        $sql = "SELECT id FROM genre WHERE name='$string'";
        $result = $this->Database->getArray($sql);
        if (isset($result[0])) {
            return $result['id'];
        } else {
            $sql = "INSERT INTO genre(name) VALUES('$string')";
            $this->Database->query($sql);

            $sql = "SELECT LAST_INSERT_ID()";
            $result = $this->Database->query($sql);
            return $result['id'];
        }
    }

    public function setGenre($bookID, $genreID)
    {
        $sql = "INSERT INTO book_genre(book_id, genre_id) VALUES ('$bookID', '$genreID')";
        $this->Database->query($sql);
        response_ok();
    }

    //eki jinish vai, koira falao...
    //get author name, and return id upon availability, or create new entry


    //works on author_book relationship table
    public function removeAuthor($bookID, $authorID)
    {
        if ($this->bookExists($bookID) && $this->authorExists($authorID)){
            $sql = "INSERT INTO author_book(isDeleted) VALUES(1) WHERE book_id='$bookID' AND author_id='$authorID'";
            $this->Database->query($sql);
            return response_ok();
        }
        else{
            response_entity_unavailable();
        }
    }

    public function removeGenre($bookID, $genreID)
    {
        if ($this->bookExists($bookID) && $this->genreExists($genreID)) {
            $sql = "INSERT INTO book_genre(isDeleted) VALUES(1) WHERE book_id='$bookID' AND genre_id='$genreID'";
            $this->Database->query($sql);
            return response_ok();
        }
        else{
            response_entity_unavailable();
        }
    }

    public function removePublisher($bookID, $publisherID)
    {
        if (bookExists($bookID) && $this->publisherExists($publisherID)) {
            $sql = "INSERT INTO book_publisher(isDeleted) VALUES(1) WHERE book_id='$bookID' AND publisher_id='$publisherID'";
            $this->Database->query($sql);
            return response_ok();
        }
        else{
            response_entity_unavailable();
        }
    }

    //mobin zaman signing in

    public function removeTag($bookID, $tagID)
    {
        if (bookExists($bookID) && $this->tagExists($tagID)) {
            $sql = "INSERT INTO book_tag(isDeleted) VALUES(1) WHERE book_id='$bookID' AND tag_id='$tagID'";
            $this->Database->query($sql);
            return response_ok();
        }
        else{
            response_entity_unavailable();
        }
    }

    public function getPublisher($string)
    {
        $sql = "SELECT id FROM publisher WHERE name='$string'";
        $result = $this->Database->getArray($sql);
        if (isset($result[0])) {
            return $result['id'];
        } else {
            $sql = "INSERT INTO publisher(name) VALUES('$string')";
            $this->Database->query($sql);

            $sql = "SELECT LAST_INSERT_ID()";
            $result = $this->Database->query($sql);
            return $result['id'];
        }
    }

    public function setPublisher($bookID, $publisherID)
    {
        $sql = "INSERT INTO book_publisher(book_id, publisher_id) VALUES ('$bookID', '$publisherID')";
        $this->Database->query($sql);
        return response_ok();
    }

    public function getTag($string)
    {
        $sql = "SELECT id FROM tag WHERE name='$string'";
        $result = $this->Database->getArray($sql);
        if (isset($result[0])) {
            //genre found and id returned
            return $result['id'];
        } else {
            //genre not found
            $sql = "INSERT INTO tag(name) VALUES('$string')";
            $this->Database->query($sql);

            $sql = "SELECT LAST_INSERT_ID()";
            $result = $this->Database->query($sql);
            return $result['id'];
        }
    }

    public function setTag($bookID, $tagID)
    {
        $sql = "INSERT INTO book_tag(book_id, tag_id) VALUES = '$bookID', '$tagID'";
        $this->Database->query($sql);
            return response_ok();
    }

}
