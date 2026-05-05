<!DOCTYPE html>
<!--Allyson Carter-->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="Styles/main.css">
    <link rel="script" type="text/js" href="Scripts/main.js">
</head>
<body>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "mysql";
    $database = "student_directory";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection Failed: " . $conn->connect_error);
    }

    echo "Connected Successfully!";

    $last_name = $_POST['last_name'] ?? "";

    $stmt = $conn->prepare("CALL search_students(?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $last_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            echo "Name: " . $row['first_name'] . " " . $row['last_name'] . "<br>";
        }
    } else {
        echo "No students found.";
    }
    ?>

    <h2>Search Results</h2>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Student ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                </tr>
            <?php endwhile; ?>

        </table>
    <?php else: ?>
        <p>No students found.</p>
    <?php endif; ?>
    
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>