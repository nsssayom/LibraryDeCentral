<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/validator.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/response.php');

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
            //is arry check needed
            $params[$key] = $this->Database->escape($value);
        }


        if (isset($params['title']) && isset($params['author[0]'])) {
            // fist push the name into database;
            //then get the id of the corresponding row for other data's to be pushed
            $bookID = $this->initBook($params['title']);
            //if initBook() was properly executed
            if (!isJson($bookID)) {

                // author will be an array();
                // type will be an array() by default initialised to zero
                // index based entry

                //insert author
                $i=0; //for type loop
                foreach($params['author'] as $author) {
                    //first get author id
                    //escape will be here
                    $author=$this->Database->escape($author);

                   $authorID = $this->getAuthor($author);

                    if (!isJson($authorID)) {
                        //now set relation author_book
                        $type = $params['type[i]'];

                        $result = $this->setAuthor($bookID, $authorID, $type);

                        if (isJson(($result))) {
                            return response_database_error();
                        }
                    }
                }

                //insert genre
                if (isset($params['genre'])) {
                    $genre = $this->getGenre(params['genre']);
                    if (!isJson($genre)) {
                        $result = $this->setGenre($bookID, $genre);
                        if (isJson($result)) {
                            return response_database_error();
                        }
                    }
                }

                if (isset($tag['tag'])) {
                    for ($i = 0; $i < $params['tag'] . size(); $i++) {
                        //first get author id

                        $tagID = $this->getTag($params['tag[i]']);

                        if (!isJson($tagID)) {
                            //now set relation book_tag
                            $result = $this->setAuthor($bookID, $tagID);

                            if (isJson(($result))) {
                                return response_database_error();
                            }
                        }
                    }
                }
            } else {
                return response_database_error();
            }

        } else return response_invalid_request();
    }


    //function for getting the id for setBookInfo()
    public function initBook($name)
    {

        $sql = "INSERT INTO book(name) VALUES('$name')";

        $result = $this->Database->query($sql);

        if ($result) {
            $sql = "SELECT LAST_INSERT_ID()";
            $result = $this->Database->query($sql);
            if (isset($result[0])) {
                return $result['id'];
            } else {
                return response_database_error();
            }
        } else return response_database_error();
    }

    private function getAuthor($string)
    {
        $sql = "SELECT id FROM author WHERE name='$string'";
        $result = $this->Database->getArray($sql);
        if (isset($result[0])) {
            //author found and id returned
            return $result['id'];
        } else {
            //genre not found
            $sql = "INSERT INTO author(name) VALUES('$string')";
            if ($this->Database->query($sql)) {
                $sql = "SELECT LAST_INSERT_ID()";
                $result = $this->Database->query($sql);
                if (isset($result[0])) {
                    return $result['id'];
                } else {
                    return response_database_error();
                }
            } else {
                return response_database_error();
            }
        }
    }

    //for relation table

    private function setAuthor($bookID, $authorID, $authorType = 0)
    {
        $sql = "INSERT INTO author_book(book_id, author_id, author_type) VALUES = '$bookID', '$authorID', '$authorType'";
        if ($this->Database->query($sql)) {
            return response_ok();
        } else {
            return response_database_error();
        }
    }

    private function getGenre($string)
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
                    return response_database_error();
                }
            } else {

                return response_database_error();
            }
        }
    }

    private function setGenre($bookID, $genreID)
    {
        $sql = "INSERT INTO book_genre(book_id, genre_id) VALUES = '$bookID', '$genreID'";
        if ($this->Database->query($sql)) {
            return response_ok();
        } else {
            return response_database_error();
        }
    }

    //eki jinish vai, koira falao...

    //get author name, and return id upon availability, or create new entry

    public function removeBook($id)
    {
        $sql = "INSESRT INTO book(isDeleted) VALUES(1) WHERE id='$id";
        if ($this->Database->query($sql)) {
            return response_ok();
        } else {
            return response_database_error();
        }
    }

    //works on author_book relationship table

    public function removeAuthor($bookID, $authorID)
    {
        $sql = "INSERT INTO author_book(isDeleted) VALUES(1) WHERE book_id='$bookID' AND author_id='$authorID'";
        if ($this->Database->query($sql)) {
            return response_ok();
        } else {
            return response_database_error()();
        }
    }

    public function removeGenre($bookID, $genreID)
    {
        $sql = "INSERT INTO book_genre(isDeleted) VALUES(1) WHERE book_id='$bookID' AND genre_id='$genreID'";
        if ($this->Database->query($sql)) {
            return response_ok();
        } else {
            return response_database_error()();
        }
    }

    public function removePubliser($bookID, $publiserID)
    {
        $sql = "INSERT INTO book_publiser(isDeleted) VALUES(1) WHERE book_id='$bookID' AND publisher_id='$publiserID'";
        if ($this->Database->query($sql)) {
            return response_ok();
        } else {
            return response_database_error()();
        }
    }

    //mobin zaman signing in

    public function removeTag($bookID, $tagID)
    {
        $sql = "INSERT INTO book_tag(isDeleted) VALUES(1) WHERE book_id='$bookID' AND tag_id='$tagID'";
        if ($this->Database->query($sql)) {
            return response_ok();
        } else {
            return response_database_error()();
        }
    }

    private function getPublisher($string)
    {
        $sql = "SELECT id FROM publisher WHERE name='$string'";
        $result = $this->Database->getArray($sql);
        if (isset($result[0])) {
            //genre found and id returned
            return $result['id'];
        } else {
            //genre not found
            $sql = "INSERT INTO publisher(name) VALUES('$string')";
            if ($this->Database->query($sql)) {
                $sql = "SELECT LAST_INSERT_ID()";
                $result = $this->Database->query($sql);
                if (isset($result[0])) {
                    return $result['id'];
                } else {
                    return response_database_error();
                }
            } else {
                return response_database_error();
            }
        }
    }

    private function setPublisher($bookID, $publisherID)
    {
        $sql = "INSERT INTO book_publisher(book_id, publisher_id) VALUES = '$bookID', '$publisherID'";
        if ($this->Database->query($sql)) {
            return response_ok();
        } else {
            return response_database_error();
        }
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
            if ($this->Database->query($sql)) {
                $sql = "SELECT LAST_INSERT_ID()";
                $result = $this->Database->query($sql);
                if (isset($result[0])) {
                    return $result['id'];
                } else {
                    return response_database_error();
                }
            } else {
                return response_database_error();
            }
        }
    }

    private function setTag($bookID, $tagID)
    {
        $sql = "INSERT INTO book_tag(book_id, tag_id) VALUES = '$bookID', '$tagID'";
        if ($this->Database->query($sql)) {
            return response_ok();
        } else {
            return response_database_error();
        }
    }

}
