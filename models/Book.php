<?php
class Book
{
    private $conn;
    private $table_name = "book_list";

    public $id;
    public $Title;
    public $Author;
    public $Genre;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY Title";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " SET Title=:Title, Author=:Author, Genre=:Genre";
        $stmt = $this->conn->prepare($query);

        $this->Title = htmlspecialchars(strip_tags($this->Title));
        $this->Author = htmlspecialchars(strip_tags($this->Author));
        $this->Genre = htmlspecialchars(strip_tags($this->Genre));

        $stmt->bindParam(":Title", $this->Title);
        $stmt->bindParam(":Author", $this->Author);
        $stmt->bindParam(":Genre", $this->Genre);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update()
    {
        $query = "UPDATE " . $this->table_name . " SET Title = :Title, Author = :Author, Genre = :Genre WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->Title = htmlspecialchars(strip_tags($this->Title));
        $this->Author = htmlspecialchars(strip_tags($this->Author));
        $this->Genre = htmlspecialchars(strip_tags($this->Genre));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':Title', $this->Title);
        $stmt->bindParam(':Author', $this->Author);
        $stmt->bindParam(':Genre', $this->Genre);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
