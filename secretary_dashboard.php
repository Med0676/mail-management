<!-- CSS styles -->
<style>
 body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f2f2f2;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }

    h2 {
        text-align: center;
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    input[type="text"],
    input[type="file"],
    input[type="date"],
    select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #fff;
        transition: border-color 0.3s ease;
    }

    input[type="text"]:focus,
    input[type="file"]:focus,
    input[type="date"]:focus,
    select:focus {
        border-color: #4CAF50;
        outline: none;
    }

    .button-container {
        text-align: right;
        margin-bottom: 20px;
    }

    button[type="submit"],
    button[name="logout"] {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button[type="submit"]:hover,
    button[name="logout"]:hover {
        background-color: #45a049;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
    }

    .pdf-link {
        color: #4CAF50;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .pdf-link:hover {
        color: #45a049;
    }
</style>

<!-- Logout button -->
<form action="secretary_dashboard.php" method="POST">
    <div class="button-container">
        <button type="submit" name="logout">Logout</button>
    </div>
</form>

<!-- Add courier form -->
<form action="secretary_dashboard.php" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="subject">Subject:</label>
        <input type="text" id="subject" name="subject" placeholder="Subject" required>
    </div>
    <div class="form-group">
        <label for="pdf_file">PDF File:</label>
        <input type="file" id="pdf_file" name="pdf_file" required>
    </div>
    <div class="form-group">
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required>
    </div>
    <div class="form-group">
        <label for="type">Type:</label>
        <select id="type" name="type" required>
            <option value="arrival">Arrival</option>
            <option value="departure">Departure</option>
        </select>
    </div>
    <div class="button-container">
        <button type="submit" name="add_courier">Add Courier</button>
    </div>
</form>

<!-- PHP code -->
<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'secretary') {
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

<!-- Display couriers in a table -->
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
