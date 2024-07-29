<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Task</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .button-d {
            text-decoration: none;
            background-color: red;
            padding: 8px;
            color: white;
            border-radius: 5px;
        }

        .button-d:hover {
            color: lightgray;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            CRUD Table
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Genre</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="bookList">
                                    <!-- Book data will be appended here by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal for Editing Book -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editBookForm">
                        <div class="mb-3">
                            <label for="editTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="editTitle" name="Title" required>
                        </div>
                        <div class="mb-3">
                            <label for="editAuthor" class="form-label">Author</label>
                            <input type="text" class="form-control" id="editAuthor" name="Author" required>
                        </div>
                        <div class="mb-3">
                            <label for="editGenre" class="form-label">Genre</label>
                            <input type="text" class="form-control" id="editGenre" name="Genre" required>
                        </div>
                        <input type="hidden" id="editId" name="id">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script src="tab.js" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchBooks();

            async function fetchBooks() {
                const response = await fetch('api/books.php');
                const books = await response.json();
                const bookList = document.getElementById('bookList');
                bookList.innerHTML = '';
                books.forEach(book => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${book.Title}</td>
                        <td>${book.Author}</td>
                        <td>${book.Genre}</td>
                        <td>
                            <a href="#" class="button-d" onclick="deleteBook(${book.id})">Delete</a>
                            <a href="#" class="button-d" onclick="showEditModal(${book.id}, '${book.Title}', '${book.Author}', '${book.Genre}')">Update</a>
                        </td>
                    `;
                    bookList.appendChild(row);
                });
            }

            async function deleteBook(id) {
                if (confirm('Are you sure you want to delete this book?')) {
                    const response = await fetch(`api/books.php?id=${id}`, {
                        method: 'DELETE'
                    });
                    const result = await response.json();
                    alert(result.message);
                    fetchBooks();
                }
            }

            async function showEditModal(id, title, author, genre) {
                document.getElementById('editId').value = id;
                document.getElementById('editTitle').value = title;
                document.getElementById('editAuthor').value = author;
                document.getElementById('editGenre').value = genre;
                var editModal = new bootstrap.Modal(document.getElementById('editModal'), {});
                editModal.show();
            }

            document.getElementById('editBookForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                const id = document.getElementById('editId').value;
                const title = document.getElementById('editTitle').value;
                const author = document.getElementById('editAuthor').value;
                const genre = document.getElementById('editGenre').value;

                const response = await fetch(`/api/books.php`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id,
                        Title: title,
                        Author: author,
                        Genre: genre
                    })
                });

                const result = await response.json();
                alert(result.message);
                fetchBooks();
                var editModal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
                editModal.hide();
            });
        });
    </script>
</body>

</html>