<?php
    include "connection.php"; // Database connection
    include "admin_navbar.php"; // Admin navbar (navigation)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Kaushan+Script&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

    <!-- Search Bar -->
    <div class="search-bar admin-search">
        <form action="" method="post">
            <input type="search" name="search" placeholder="Search by Student ID" required>
            <button type="submit" name="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <!-- Students Table -->
    <div class="request-table">
        <div class="request-container">
            <h2 class="request-title student-info-title">List of Students</h2>

            <?php
            // Check if the search form has been submitted
            if (isset($_POST['submit'])) {
                $search = mysqli_real_escape_string($db, $_POST['search']);
                $query = "SELECT studentid, FullName, Email, PhoneNumber, studentpic FROM student WHERE studentid = '$search'";

                $result = mysqli_query($db, $query);

                // Check if student is found
                if (mysqli_num_rows($result) == 0) {
                    echo "<p>Sorry! No student found. Try searching again.</p>";
                } else {
                    // Display search results in a table
                    echo "<table class='rtable'>";
                    echo "<tr style='background-color: teal;'>
                            <th>Students</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                          </tr>";

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>
                                <div class='table-info'>
                                    <img src='images/" . $row['studentpic'] . "'>
                                    <div>
                                        <p>Student ID: " . $row['studentid'] . "</p>
                                        <p>" . $row['FullName'] . "</p><br>
                                    </div>
                                </div>
                              </td>";
                        echo "<td>" . $row['Email'] . "</td>";
                        echo "<td>" . $row['PhoneNumber'] . "</td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                }
            } else {
                // If no search is performed, display all students
                $query = "SELECT studentid, FullName, Email, PhoneNumber, studentpic FROM student";
                $result = mysqli_query($db, $query);

                // Display students in a table
                echo "<table class='rtable'>";
                echo "<tr style='background-color: teal;'>
                        <th>Students</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                      </tr>";

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>
                            <div class='table-info'>
                                <img src='images/" . $row['studentpic'] . "'>
                                <div>
                                    <p>Student ID: " . $row['studentid'] . "</p>
                                    <p>" . $row['FullName'] . "</p><br>
                                </div>
                            </div>
                          </td>";
                    echo "<td>" . $row['Email'] . "</td>";
                    echo "<td>" . $row['PhoneNumber'] . "</td>";
                    echo "</tr>";
                }

                echo "</table>";
            }
            ?>

        </div>
    </div>

</body>
</html>
