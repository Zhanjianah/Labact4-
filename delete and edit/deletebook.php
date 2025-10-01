<?php

require_once "book.php";
$bookObj = new Book();
$search = "";
$genre = "";
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["search"])) {
        $search = trim(htmlspecialchars($_GET["search"]));
    }

    if (isset($_GET["genre"])) {
        $genre = trim(htmlspecialchars($_GET["genre"]));
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["book_id"])) {
        $book_id = trim(htmlspecialchars($_POST["book_id"]));
        if (!empty($book_id) && filter_var($book_id, FILTER_VALIDATE_INT)) {
            $db = new Library();
            $conn = $db->connect();
            $stmt = $conn->prepare("DELETE FROM book WHERE id = :id");
            $stmt->bindParam(":id", $book_id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                header("Location: viewbooks.php");
                exit();
            } else {
                echo "Error deleting book.";
            }
        } else {
            echo "Invalid book ID.";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Book</title>
    <link rel="stylesheet" href="addBooks.css">
</head>
<body>
    <div class="container">
        <h2>Delete Book</h2>
        <form action="" method="get">
            <label for="">Search:</label>
            <input type="search" name="search" id="search" value="<?= $search ?>">

            <select name="genre" id="genre">
                <option value="">All</option>
                <option value="History" <?= (isset($genre) && $genre == "History") ? "selected":"" ?>>History</option>
                <option value="Science" <?= (isset($genre) && $genre == "Science") ? "selected":"" ?>>Science</option>
                <option value="Fiction" <?= (isset($genre) && $genre == "Fiction") ? "selected":"" ?>>Fiction</option>
            </select>

            <input type="submit" value="Search">
        </form>
    </div>
    <br>
    <div class="container">
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Genre</th>
                    <th>Publication Year</th>
                    <th>Publisher</th>
                    <th>Copies</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $no = 1;
            $books = $bookObj->viewBooks($search, $genre);
            if (!empty($books)) {
                foreach ($books as $book) {
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($book["title"]); ?></td>
                        <td><?= htmlspecialchars($book["author"]); ?></td>
                        <td><?= htmlspecialchars($book["genre"]); ?></td>
                        <td><?= htmlspecialchars($book["publication_year"]); ?></td>
                        <td><?= htmlspecialchars($book["publisher"]); ?></td>
                        <td><?= htmlspecialchars($book["copies"]); ?></td>
                        <td>
                            <form action="" method="post" onsubmit="return confirm('Are you sure you want to delete this book?');">
                                <input type="hidden" name="book_id" value="<?= $book['id']; ?>">
                                <input type="submit" value="Delete">
                            </form> 
                        </td>
                    </tr>
                    <?php
                }   
            } else {
                echo "<tr><td colspan='8'>No books found.</td></tr>";
            }
            ?>
            </tbody>

        </table>
    </div>  
</body>
</html>
<?php
                                