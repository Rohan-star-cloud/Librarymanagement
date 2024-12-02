<?php
// Include your database connection
include "connection.php";

// Query to fetch all data from the `issueinfo` table
$sql = "SELECT * FROM issueinfo";
$result = mysqli_query($db, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issued Books Information</title>
    <style>
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Issue Information</h1>
    <table>
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Book ID</th>
                <th>Issue Date</th>
                <th>Return Date</th>
                <th>Approve Status</th>
                <th>Fine</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if the query returned results
            if (mysqli_num_rows($result) > 0) {
                // Fetch and display each row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['studentid'] . "</td>";
                    echo "<td>" . $row['bookid'] . "</td>";
                    echo "<td>" . $row['issuedate'] . "</td>";
                    echo "<td>" . $row['returndate'] . "</td>";
                    echo "<td>" . $row['approve'] . "</td>";
                    echo "<td>" . $row['fine'] . "</td>";
                    echo "</tr>";
                }
            } else {
                // If no data is found
                echo "<tr><td colspan='6'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
