<?php

include "book.php";

$bookObj = new Book();

$search = $category = "";

if($_SERVER["REQUEST_METHOD"] == "GET"){
    $search = isset($_GET["search"]) ? trim(htmlspecialchars($_GET["search"])) : "";
    $genre = isset($_GET["genre"]) ? trim(htmlspecialchars($_GET["genre"])) : "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Books</title>
    <link rel="stylesheet" href="addBooks.css">
</head>
<body>
    <div class="container">
        <h2>View Books</h2>
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

        <button><a href="addbooks.php">Add Book</a></button>
        <button><a href="deletebook.php">Delete Book</a></button>
        <button><a href="editbook.php">Edit Book</a></button>
        
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
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($book["title"]) ?></td>
                        <td><?= htmlspecialchars($book["author"]) ?></td>
                        <td><?= htmlspecialchars($book["genre"]) ?></td>
                        <td><?= htmlspecialchars($book["publication_year"]) ?></td>
                        <td><?= htmlspecialchars($book["publisher"]) ?></td>
                        <td><?= htmlspecialchars($book["copies"]) ?></td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='7'>No books found!</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</body>
</html>

