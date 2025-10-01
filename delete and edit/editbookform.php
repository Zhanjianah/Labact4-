<?php

require_once "book.php";
$bookObj = new Book;

$book = [];
$id = "";

    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        if (isset($_GET["id"]))
        {
            $id = trim(htmlspecialchars($_GET["id"]));
            $book = $bookObj->fetchBook($id);
            if (!$book)
            {
                echo "<a href='viewBooks.php'>View Books</a><br>";
                exit("Book Not Found");
            }
        }
        else
        {
            echo "<a href='viewBooks.php'>Check Book</a><br>";
            exit("Book Not Found");
        }
    }
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $book["title"] = trim(htmlspecialchars($_POST["title"]));
        $book["author"] = trim(htmlspecialchars($_POST["author"]));
        $book["genre"] = trim(htmlspecialchars($_POST["genre"]));
        $book["publication_year"] = trim(htmlspecialchars($_POST["publication_year"]));
        $book["publisher"] = trim(htmlspecialchars($_POST["publisher"]));
        $book["copies"] = trim(htmlspecialchars($_POST["copies"]));

        try {
            $db = new Library();
            $conn = $db->connect();

            $stmt = $conn->prepare("UPDATE book SET title=:title, author=:author, genre=:genre, publication_year=:publication_year, publisher=:publisher, copies=:copies WHERE id=:id");
            $stmt->bindParam(":title", $book["title"]);
            $stmt->bindParam(":author", $book["author"]);
            $stmt->bindParam(":genre", $book["genre"]);
            $stmt->bindParam(":publication_year", $book["publication_year"], PDO::PARAM_INT);
            $stmt->bindParam(":publisher", $book["publisher"]);
            $stmt->bindParam(":copies", $book["copies"], PDO::PARAM_INT);
            $stmt->bindParam(":id", $_GET["id"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("Location: viewbooks.php");
                exit();
            } else {
                echo "Error saving book.";
            }
        } catch (PDOException $e) {
            echo "Database error: " . htmlspecialchars($e->getMessage());
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Book</title>
    <link rel="stylesheet" href="addBooks.css">
</head>
<body>
    <div class="container">
        <h2>Add Book</h2>
        <form method ="post">
        <input type="text" name="title" required value="<?= $book["title"] ?? ""; ?>"><br><br>
        <input type="text" name="author" value="<?= $book["author"] ?? ""; ?>"><br><br>
        <select name="genre" id="genre" required>
            <option value="">Select Genre</option>
            <option value="history" <?= (isset($book["genre"]) && strtolower($book["genre"]) == 'history') ? 'selected' : ""; ?>>History</option>
            <option value="science" <?= (isset($book["genre"]) && strtolower($book["genre"]) == 'science') ? 'selected' : ""; ?>>Science</option>
            <option value="fiction" <?= (isset($book["genre"]) && strtolower($book["genre"]) == 'fiction') ? 'selected' : ""; ?>>Fiction</option>
        </select><br><br>
        <input type="number" name="publication_year" required value="<?= $book["publication_year"] ?? ""; ?>"><br><br>
        <input type="text" name="publisher" required value="<?= $book["publisher"] ?? "" ?>"><br><br>
        <input type="number" name="copies" required value="<?= $book["copies"] ?? "1"; ?>" min="1" required><br><br>
        <input type="submit" value="Edit book">
        </form>
        <button><a href="viewBooks.php">View Books</a></button>
    </div>
</body>
</html>
