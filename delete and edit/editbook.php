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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Book</title>
    <link rel="stylesheet" href="addBooks.css">
</head>
<body>
    <div class="container">
        <h2>Edit Book</h2>
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
        <button><a href="addBooks.php">Add Book</a></button>
        <button><a href="viewBooks.php">View Books</a></button>
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
                    <td><a href="editbookform.php?id=<?= $book['id'] ?>">Edit</a></td>
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