<?php

require_once "book.php";

$bookObj = new Book;

$title = "";
$author = "";
$genre = "";
$publication_year = "";
$publisher = "";
$copies = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim(htmlspecialchars($_POST["title"] ?? ""));
    $author = trim(htmlspecialchars($_POST["author"] ?? ""));
    $genre = trim(htmlspecialchars($_POST["genre"] ?? ""));
    $publication_year = trim(htmlspecialchars($_POST["publication_year"] ?? ""));
    $publisher = trim(htmlspecialchars($_POST["publisher"] ?? ""));
    $copies = trim(htmlspecialchars($_POST["copies"] ?? ""));

    if (empty($title)) {
        $title_err = "Title is required";
    }

    if (empty($author)) {
        $author_err = "Author is required";
    }

    if (empty($genre)) {
        $genre_err = "Genre is required";
    }

    if (empty($publication_year)) {
        $publication_year_err = "Publication year is required";
    } else if (!filter_var($publication_year, FILTER_VALIDATE_INT)) {
        $publication_year_err = "Invalid publication year";
    } else if ($publication_year > date("Y")) {
        $publication_year_err = "Publication year cannot be in the future";
    }

    if (empty($publisher)) {
        $publisher_err = "Publisher is required";
    }

    if (empty($copies)) {
        $copies_err = "Copies is required";
    } else if (!filter_var($copies, FILTER_VALIDATE_INT) || $copies < 1) {
        $copies_err = "Copies must be a positive integer";
    }

    if(empty($title_err) && empty($author_err) && empty($genre_err) 
        && empty($publication_year_err) && empty($publisher_err) && empty($copies_err)) {
        
        try {
            $db = new Library();
            $conn = $db->connect();

            $stmt = $conn->prepare("INSERT INTO book (title, author, genre, publication_year, publisher, copies) 
                                    VALUES (:title, :author, :genre, :publication_year, :publisher, :copies)");
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":author", $author);
            $stmt->bindParam(":genre", $genre);
            $stmt->bindParam(":publication_year", $publication_year, PDO::PARAM_INT);
            $stmt->bindParam(":publisher", $publisher);
            $stmt->bindParam(":copies", $copies, PDO::PARAM_INT);

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

        <form method = "POST">
        <input type="text" name="title" placeholder="Title" required value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>"><br><br>
        <input type="text" name="author" placeholder="Author" required value="<?php echo isset($_POST['author']) ? htmlspecialchars($_POST['author']) : ''; ?>"><br><br>
        <select name="genre" id="genre" required>
            <option value="">Select Genre</option>
            <option value="history" <?php if(isset($_POST['genre']) && strtolower($_POST['genre'])=='history') echo 'selected'; ?>>History</option>
            <option value="science" <?php if(isset($_POST['genre']) && strtolower($_POST['genre'])=='science') echo 'selected'; ?>>Science</option>
            <option value="fiction" <?php if(isset($_POST['genre']) && strtolower($_POST['genre'])=='fiction') echo 'selected'; ?>>Fiction</option>
        </select><br><br>
        <input type="number" name="publication_year" placeholder="Publication Year" required value="<?php echo isset($_POST['publication_year']) ? htmlspecialchars($_POST['publication_year']) : ''; ?>"><br><br>
        <input type="text" name="publisher" placeholder="Publisher" required value="<?php echo isset($_POST['publisher']) ? htmlspecialchars($_POST['publisher']) : ''; ?>"><br><br>
        <input type="number" name="copies" placeholder="Copies" required value="<?php echo isset($_POST['copies']) ? htmlspecialchars($_POST['copies']) : '1'; ?>" min="1" required><br><br>
        <input type="submit" value="Add book">
        </form>
        <button><a href="viewBooks.php">View Books</a></button>
    </div>
</body>
</html>
