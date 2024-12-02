<?php
    include "connection.php";
    include "admin_navbar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="search-bar admin-search">
        <form action="" method="post">
            <input type="search" name="search" placeholder="Search by Student ID" required>
            <button type="submit" name="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <div class="request-table">
        <div class="request-container book-container">
            <h2 class="request-title student-info-title">Request Information</h2>

            <?php
                if (isset($_POST['submit'])) {
                    $search = $_POST['search'];
                    $q = mysqli_query($db, "SELECT student.studentid, FullName, studentpic, books.bookid, bookname, ISBN, price, bookpic, authors.authorname, category.categoryname
                                            FROM student 
                                            INNER JOIN issueinfo ON student.studentid = issueinfo.studentid 
                                            INNER JOIN books ON issueinfo.bookid = books.bookid 
                                            JOIN authors ON authors.authorid = books.authorid 
                                            JOIN category ON category.categoryid = books.categoryid 
                                            WHERE issueinfo.approve='' AND issueinfo.studentid='$search'");

                    if (mysqli_num_rows($q) == 0) {
                        echo "Sorry! There's no book request by this student ID";
                    } else {
                        echo "<table class='rtable booktable'>";
                        echo "<tr style='background-color: teal;'>";
                        echo "<th>Students</th><th>Books</th><th>Author Name</th><th>Category Name</th><th>ISBN</th><th>Price</th><th>Action</th>";
                        echo "</tr>";

                        while ($row = mysqli_fetch_assoc($q)) {
                            echo "<tr>";
                            echo "<td><div class='table-info'><img src='images/{$row['studentpic']}'><div><p>Student ID: {$row['studentid']}</p><p>{$row['FullName']}</p></div></div></td>";
                            echo "<td><div class='table-info'><img src='images/{$row['bookpic']}'><div><p>Book ID: {$row['bookid']}</p><p>{$row['bookname']}</p></div></div></td>";
                            echo "<td>{$row['authorname']}</td><td>{$row['categoryname']}</td><td>{$row['ISBN']}</td><td>{$row['price']} RS.</td>";
                            echo "<td><a href='issue_book.php?ed={$row['studentid']}&ed1={$row['bookid']}'><button style='font-weight:bold;' type='button' class='btn btn-default actionbtn'>Issue</button></a></td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    }
                } else {
                    // If the search form was not submitted, show all pending requests
                    $res = mysqli_query($db, "SELECT student.studentid, FullName, studentpic, books.bookid, bookname, ISBN, price, bookpic, authors.authorname, category.categoryname 
                                              FROM student 
                                              INNER JOIN issueinfo ON student.studentid = issueinfo.studentid 
                                              INNER JOIN books ON issueinfo.bookid = books.bookid 
                                              JOIN authors ON authors.authorid = books.authorid 
                                              JOIN category ON category.categoryid = books.categoryid 
                                              WHERE issueinfo.approve='';");

                    if (mysqli_num_rows($res) == 0) {
                        echo "There's no pending request.";
                    } else {
                        echo "<table class='rtable booktable'>";
                        echo "<tr style='background-color: teal;'>";
                        echo "<th>Students</th><th>Books</th><th>Author Name</th><th>Category Name</th><th>ISBN</th><th>Price</th><th>Action</th>";
                        echo "</tr>";

                        while ($row = mysqli_fetch_assoc($res)) {
                            echo "<tr>";
                            echo "<td><div class='table-info'><img src='images/{$row['studentpic']}'><div><p>Student ID: {$row['studentid']}</p><p>{$row['FullName']}</p></div></div></td>";
                            echo "<td><div class='table-info'><img src='images/{$row['bookpic']}'><div><p>Book ID: {$row['bookid']}</p><p>{$row['bookname']}</p></div></div></td>";
                            echo "<td>{$row['authorname']}</td><td>{$row['categoryname']}</td><td>{$row['ISBN']}</td><td>{$row['price']} RS.</td>";
                            echo "<td><a href='issue_book.php?ed={$row['studentid']}&ed1={$row['bookid']}'><button style='font-weight:bold;' type='button' class='btn btn-default actionbtn'>Issue</button></a></td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    }
                }
            ?>

        </div>
    </div>
</body>
</html>
