<?php
    include "connection.php"; // Database connection
    include "student_navbar.php"; // Navbar for student (navigation)

    session_start();

    // Ensure the student is logged in
    if (!isset($_SESSION['login_student_username'])) {
        echo "<script>alert('You must log in first.'); window.location='login.php';</script>";
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="request-table">
        <div class="request-container book-container">
            <h2 class="request-title student-info-title" style="padding-top: 50px;">List of Issued Books</h2>

            <?php
            // Get the student ID from the session variable
            $student_username = $_SESSION['login_student_username'];
            $student_query = mysqli_query($db, "SELECT studentid FROM student WHERE student_username='$student_username'");
            $student_row = mysqli_fetch_assoc($student_query);
            $studentid = $student_row['studentid'];

            // Check for overdue books and calculate fines
            $fine_query = mysqli_query($db, "SELECT SUM(fine) as total_fine FROM issueinfo WHERE studentid='$studentid' AND approve='EXPIRED'");
            $fine_row = mysqli_fetch_assoc($fine_query);
            $total_fine = $fine_row['total_fine'] ? $fine_row['total_fine'] : 0;

            // Query to get issued books
            $issued_books_query = mysqli_query($db, "
                SELECT books.bookid, books.bookname, books.ISBN, books.bookpic, books.price, issueinfo.issuedate, 
                       issueinfo.returndate, issueinfo.approve, issueinfo.fine, authors.authorname, category.categoryname
                FROM issueinfo
                JOIN books ON issueinfo.bookid = books.bookid
                JOIN authors ON authors.authorid = books.authorid
                JOIN category ON category.categoryid = books.categoryid
                WHERE issueinfo.studentid = '$studentid' AND (issueinfo.approve = 'yes' OR issueinfo.approve = 'EXPIRED')
                ORDER BY issueinfo.returndate ASC
            ");

            // Display total fine
            if ($total_fine > 0) {
                echo "<h2 style='padding-left: 1050px;'>Your Fine: &nbsp;$total_fine RS</h2>";
            }

            // Display issued books table
            if (mysqli_num_rows($issued_books_query) == 0) {
                echo "You have no issued books.";
            } else {
                echo "<table class='rtable'>";
                echo "<tr style='background-color: teal;'>
                        <th>Books</th>
                        <th>Author Name</th>
                        <th>Category</th>
                        <th>ISBN</th>
                        <th>Issue Date</th>
                        <th>Return Date</th>
                        <th>Approval Status</th>
                        <th>Fine</th>
                        <th>Action</th>
                    </tr>";

                while ($book_row = mysqli_fetch_assoc($issued_books_query)) {
                    $return_date = strtotime($book_row['returndate']);
                    $current_date = strtotime(date("Y-m-d"));
                    $overdue_days = ($current_date - $return_date) / (60 * 60 * 24); // Calculate overdue days

                    // Update expired books with fine if overdue
                    if ($overdue_days > 0 && $book_row['approve'] == 'yes') {
                        $fine_amount = $overdue_days * 10; // Fine calculation
                        mysqli_query($db, "UPDATE issueinfo SET approve='EXPIRED', fine='$fine_amount' WHERE bookid='{$book_row['bookid']}' AND studentid='$studentid'");
                    }

                    // Display each book in a row
                    echo "<tr>";
                    echo "<td><div class='table-info'><img src='images/{$book_row['bookpic']}'><div><p>{$book_row['bookname']}</p><small>Price: {$book_row['price']} RS</small></div></div></td>";
                    echo "<td>{$book_row['authorname']}</td>";
                    echo "<td>{$book_row['categoryname']}</td>";
                    echo "<td>{$book_row['ISBN']}</td>";
                    echo "<td>{$book_row['issuedate']}</td>";
                    echo "<td>{$book_row['returndate']}</td>";
                    echo "<td>{$book_row['approve']}</td>";
                    echo "<td>{$book_row['fine']}</td>";
                    echo "<td><a href='request_book.php?req={$book_row['bookid']}' onclick='return confirm(\"Are you sure you want to cancel this request?\")'>Delete</a></td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            ?>

        </div>
    </div>

    <?php
    // Handling book request deletion
    if (isset($_GET['req'])) {
        $bookid_to_delete = $_GET['req'];
        // Delete the book request and update the book quantity
        $delete_query = "DELETE FROM issueinfo WHERE bookid = '$bookid_to_delete' AND studentid = '$studentid'";
        mysqli_query($db, $delete_query);

        // Update book quantity
        $book_query = "SELECT quantity FROM books WHERE bookid = '$bookid_to_delete'";
        $book_result = mysqli_query($db, $book_query);
        $book_row = mysqli_fetch_assoc($book_result);
        $new_quantity = $book_row['quantity'] + 1;

        mysqli_query($db, "UPDATE books SET quantity = '$new_quantity' WHERE bookid = '$bookid_to_delete'");

        echo "<script>alert('Request deleted successfully.'); window.location='student_books.php';</script>";
    }
    ?>
</body>
</html>
