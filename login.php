
<?php
session_start();

// Define the valid user credentials
$validCredentials = [
    'secretary' => [
        'username' => 'med',
        'password' => 'med123'
    ],
    'user' => [
        'username' => 'test',
        'password' => 'test123'
    ]
];

// Check if the form is submitted
if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['role'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Check if the user credentials are valid
    if (isset($validCredentials[$role]) && $username === $validCredentials[$role]['username'] && $password === $validCredentials[$role]['password']) {
        // Authentication successful, set session variables
        $_SESSION['role'] = $role;
        $_SESSION['username'] = $username;
        header("Location: " . $role . "_dashboard.php");
        exit();
    } else {
        // Authentication failed, redirect to index.html with error message
        $errorMessage = "Invalid username or password for the selected role.";
        header("Location: index.php?error=" . urlencode($errorMessage));
        exit();
    }
} else {
    // Invalid request, redirect to index.html
    header("Location: index.php");
    exit();
}
?>
