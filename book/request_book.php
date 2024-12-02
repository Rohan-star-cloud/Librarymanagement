<?php
    include "connection.php";
    include "student_navbar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Kaushan+Script&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="request-table">
        <div class="request-container">
            <h2 class="request-title">List of Requested Books</h2>
            <?php
            if(isset($_SESSION['login_student_username']))
            {
                $q1 = mysqli_query($db, "SELECT studentid from student where studentid='$_SESSION[studentid]';");
                $row = mysqli_fetch_assoc($q1);

                $q = mysqli_query($db, "SELECT books.bookpic, books.bookid, books.bookname, books.ISBN, books.price, books.quantity, authors.authorname, category.categoryname 
                                        FROM `issueinfo` 
                                        JOIN `books` ON issueinfo.bookid = books.bookid 
                                        JOIN `student` ON student.studentid = issueinfo.studentid 
                                        JOIN authors ON authors.authorid = books.authorid 
                                        JOIN category ON category.categoryid = books.categoryid 
                                        WHERE student.studentid = '$_SESSION[studentid]' AND issueinfo.approve = '';");

                if(mysqli_num_rows($q) == 0)
                {
                    echo "There's no pending request";
                }
                else
                {
                    echo "<table class='rtable'>";
                    echo "<tr style='background-color: teal;'>";
                    echo "<th>Book ID</th>";
                    echo "<th>Books</th>";
                    echo "<th>Author Name</th>";
                    echo "<th>Category Name</th>";
                    echo "<th>ISBN</th>";
                    echo "</tr>";

                    while($row = mysqli_fetch_assoc($q))
                    {
                        // Debugging output: Check if the dates and approval status are coming correctly
                        // You can remove these debug lines after verifying the output
                        echo "<tr>";
                        echo "<td>".$row['bookid']."</td>";
                        echo "<td>
                            <div class='table-info'>
                                <img src='images/".$row['bookpic']."'>
                                <div>
                                    <p>".$row['bookname']."</p>
                                    <small>Price: ".$row['price']." RS.</small><br>
                                    <a href='?req=".$row['bookid']."'><button type='submit' name='remove'>Remove</button></a>
                                </div>
                            </div>
                        </td>";
                        echo "<td>".$row['authorname']."</td>";
                        echo "<td>".$row['categoryname']."</td>";
                        echo "<td>".$row['ISBN']."</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
            }
            ?>
        </div>
    </div>
    <?php
    if(isset($_GET['req']))
    {
        $id = $_GET['req'];
        mysqli_query($db, "DELETE FROM issueinfo WHERE bookid = $id AND studentid = '$_SESSION[studentid]' AND approve = '';");

        $res = mysqli_query($db, "SELECT quantity FROM books WHERE bookid = $id;");
        while($row = mysqli_fetch_assoc($res))
        {
            if($row['quantity'] == 0)
            {
                mysqli_query($db, "UPDATE books SET quantity = quantity + 1, status = 'Available' WHERE bookid = $id;");
            }
            else
            {
                mysqli_query($db, "UPDATE books SET quantity = quantity + 1 WHERE bookid = $id;");
            }
        }
        ?>  
        <script type="text/javascript">
            alert("Request Deleted successfully.");
        </script>
        <script type="text/javascript">
            window.location = "request_book.php";
        </script>
        <?php
    }
    ?>
</body>
</html>
