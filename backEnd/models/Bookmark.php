<?php

class Bookmark
{
    private $id;
    private $url;
    private $title;
    private $dateAdded;
    private $dbConnection;
    private $dbTable = 'Bookmark';

    public function __construct($dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->dbTable . "(URL, title, dateAdded) VALUES(:url, :title, now());";
        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindParam(":url", $this->url);
        $stmt->bindParam(":title", $this->title);
        if ($stmt->execute()) {
            return true;
        }
        // Print an error message
        printf("Error: %s", $stmt->error);
        return false;
    }

    public function searchByTitle($searchTerm = null)
{
    $query = "SELECT * FROM " . $this->dbTable . " WHERE title LIKE :searchTerm";
    $stmt = $this->dbConnection->prepare($query);

    $searchTerm = '%' . $searchTerm . '%';
    $stmt->bindParam(":searchTerm", $searchTerm);

    if ($stmt->execute() && $stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return [];
}

public function readOne()
    {
        $query = "SELECT * FROM " . $this->dbTable . " WHERE id=:id";
        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindParam(":id", $this->id);
        if ($stmt->execute() && $stmt->rowCount() == 1) {
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            $this->id = $result->id;
            $this->url = $result->URL;
            $this->title = $result->title;
            $this->dateAdded = $result->dateAdded;
            return true;
        }
        return false;
    }


    public function readAll()
    {
        $query = "SELECT * FROM " . $this->dbTable;
        $stmt = $this->dbConnection->prepare($query);
        if ($stmt->execute() && $stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function update()
    {
        $query = "UPDATE " . $this->dbTable . " SET URL=:url, title=:title WHERE id=:id";
        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindParam(":url", $this->url);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":id", $this->id);
        if ($stmt->execute() && $stmt->rowCount() == 1) {
            return true;
        }
        return false;
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->dbTable . " WHERE id=:id";
        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindParam(":id", $this->id);
        if ($stmt->execute() && $stmt->rowCount() == 1) {
            return true;
        }
        return false;
    }
}
