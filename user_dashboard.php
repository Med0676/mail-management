<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: index.php");
    exit();
}

// Logout functionality
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Connect to the database (replace with your database credentials)
$conn = mysqli_connect('localhost', 'root', '', 'courrier');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Add a new courier
if (isset($_POST['add_courier'])) {
    $subject = $_POST['subject'];
    $pdf_file = $_FILES['pdf_file']['name'];
    $pdf_file_temp = $_FILES['pdf_file']['tmp_name'];
    $date = $_POST['date'];
    $type = $_POST['type'];

    // Move the uploaded PDF file to the desired location
    $pdf_directory = 'pdf_files/';
    move_uploaded_file($pdf_file_temp, $pdf_directory . $pdf_file);

    // Insert the courier into the database
    $query = "INSERT INTO couriers (subject, pdf_file, date, type) VALUES ('$subject', '$pdf_file', '$date', '$type')";
    mysqli_query($conn, $query);
}

// Fetch all couriers
$query = "SELECT * FROM couriers";
$result = mysqli_query($conn, $query);

$couriers = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $couriers[] = $row;
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>User Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script>
        function refreshPage() {
            location.reload();
        }
    </script>
</head>
<body>
    <div class="table-container">
        <h2>Welcome, User!</h2>
        <form action="user_dashboard.php" method="POST">
            <div class="button-container">
                <button type="submit" name="logout">Logout</button>
            </div>
        </form>
        <h3>Couriers</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Subject</th>
                <th>PDF File</th>
                <th>Date</th>
                <th>Type</th>
            </tr>
            <?php foreach ($couriers as $courier) { ?>
                <tr>
                    <td><?php echo $courier['id']; ?></td>
                    <td><?php echo $courier['subject']; ?></td>
                    <td>
                        <a href="pdf_files/<?php echo $courier['pdf_file']; ?>" target="_blank">View PDF</a>
                    </td>
                    <td><?php echo $courier['date']; ?></td>
                    <td><?php echo $courier['type']; ?></td>
                </tr>
            <?php } ?>
        </table>
    
    </div>
    <script src="script.js"></script>
</body>
</html>
