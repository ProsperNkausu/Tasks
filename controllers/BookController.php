<?php
class BookController
{
    private $book;

    public function __construct($book)
    {
        $this->book = $book;
    }

    public function index()
    {
        $stmt = $this->book->read();
        $books = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $book_item = [
                "id" => $id,
                "Title" => $Title,
                "Author" => $Author,
                "Genre" => $Genre
            ];
            array_push($books, $book_item);
        }
        return $books;
    }

    public function create($data)
    {
        if (isset($data['Title']) && isset($data['Author']) && isset($data['Genre'])) {
            $this->book->Title = $data['Title'];
            $this->book->Author = $data['Author'];
            $this->book->Genre = $data['Genre'];
            if ($this->book->create()) {
                return ['status' => 'success', 'message' => 'Book created successfully.'];
            }
        }
        return ['status' => 'error', 'message' => 'Failed to create book.'];
    }

    public function update($data)
    {
        if (isset($data['id']) && isset($data['Title']) && isset($data['Author']) && isset($data['Genre'])) {
            $this->book->id = $data['id'];
            $this->book->Title = $data['Title'];
            $this->book->Author = $data['Author'];
            $this->book->Genre = $data['Genre'];
            if ($this->book->update()) {
                return ['status' => 'success', 'message' => 'Book updated successfully.'];
            }
        }
        return ['status' => 'error', 'message' => 'Failed to update book.'];
    }

    public function delete($id)
    {
        $this->book->id = $id;
        if ($this->book->delete()) {
            return ['status' => 'success', 'message' => 'Book deleted successfully.'];
        }
        return ['status' => 'error', 'message' => 'Failed to delete book.'];
    }
}
