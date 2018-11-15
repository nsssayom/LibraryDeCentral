<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/validator.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/response.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/class/database.php');

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


    public function setBookInfo($title, $authors, $genre = null, $publisher = null, $tags = null)
    {
        $bookID = $this->initBook($title);

        //insert author
        foreach ($authors as $author) {
            $this->setAuthor($bookID, $this->getAuthor($author), $author['type']);
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
        return response_ok();
    }

    //Edit book title [Only editable entity]
    public function editBookTitle($bookId, $newTitle)
    {
        $newTitle = $this->Database->escape($newTitle);
        $bookId = $this->Database->escape($bookId);

        if ($this->bookExists($bookId)) {
            $sql = "UPDATE book SET title = '$newTitle' WHERE id = '$bookId'";
            $this->Database->query($sql);
            return response_ok();
        } else {
            return response_invalid_request();
        }
    }

    private function bookExists($bookId)
    {
        $bookId = $this->Database->escape($bookId);
        $sql = "SELECT * FROM book WHERE id = '$bookId'";
        $this->Database->getArray($sql);
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

    private function getAuthor($string)
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

    private function setAuthor($bookID, $authorID, $authorType = 0)
    {
        $sql = "INSERT INTO author_book(book_id, author_id, author_type) VALUES = '$bookID', '$authorID', '$authorType'";
        $this->Database->query($sql);
        return response_ok();

    }

    private function getGenre($string)
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

    private function setGenre($bookID, $genreID)
    {
        $sql = "INSERT INTO book_genre(book_id, genre_id) VALUES = '$bookID', '$genreID'";
        $this->Database->query($sql);
        return response_ok();
    }

    //eki jinish vai, koira falao...
    //get author name, and return id upon availability, or create new entry

    public function removeBook($id)
    {
        $sql = "INSERT INTO book(isDeleted) VALUES(1) WHERE id='$id'";
        $this->Database->query($sql);
        return response_ok();
    }

    //works on author_book relationship table

    public function removeAuthor($bookID, $authorID)
    {
        $sql = "INSERT INTO author_book(isDeleted) VALUES(1) WHERE book_id='$bookID' AND author_id='$authorID'";
        $this->Database->query($sql);
        return response_ok();
    }

    public function removeGenre($bookID, $genreID)
    {
        $sql = "INSERT INTO book_genre(isDeleted) VALUES(1) WHERE book_id='$bookID' AND genre_id='$genreID'";
        $this->Database->query($sql);
        return response_ok();
    }

    public function removePublisher($bookID, $publisherID)
    {
        $sql = "INSERT INTO book_publisher(isDeleted) VALUES(1) WHERE book_id='$bookID' AND publisher_id='$publisherID'";
        $this->Database->query($sql);
        return response_ok();
    }

    //mobin zaman signing in

    public function removeTag($bookID, $tagID)
    {
        $sql = "INSERT INTO book_tag(isDeleted) VALUES(1) WHERE book_id='$bookID' AND tag_id='$tagID'";
        $this->Database->query($sql);
        return response_ok();
    }

    private function getPublisher($string)
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

    private function setPublisher($bookID, $publisherID)
    {
        $sql = "INSERT INTO book_publisher(book_id, publisher_id) VALUES = '$bookID', '$publisherID'";
        $this->Database->query($sql);
        return response_ok();
    }

    private function getTag($string)
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

    private function setTag($bookID, $tagID)
    {
        $sql = "INSERT INTO book_tag(book_id, tag_id) VALUES = '$bookID', '$tagID'";
        $this->Database->query($sql);
            return response_ok();
    }

}
