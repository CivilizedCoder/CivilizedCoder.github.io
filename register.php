<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/mainStyle.css">
    <title>Register - MyMovies</title>
</head>
<body>
<h1>Register to MyMovies</h1>

<?php
$server = "localhost";
$user = "myMovies";
$password = "myMovPass";
$db = "sakila";

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = strip_tags($_POST['first_name']);
    $lastName = strip_tags($_POST['last_name']);
    $email = strip_tags($_POST['email']);
    $passwordInput = strip_tags($_POST['password']);

    try {
        $conn = new PDO("mysql:host=$server;dbname=$db", $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if the email already exists
        $sql = "SELECT * FROM customer WHERE email = :email";
        $query = $conn->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();

        if ($query->rowCount() > 0) {
            $error = "Error: Email already in use.";
        } else {
            // Insert new user if email does not exist
            $insertSQL = "INSERT INTO customer (email, store_id, address_id, first_name, last_name) VALUES (:email, 1, 1, :first_name, :last_name)";
            $insertQuery = $conn->prepare($insertSQL);
            $insertQuery->bindParam(':email', $email, PDO::PARAM_STR);
            $insertQuery->bindParam(':first_name', $firstName, PDO::PARAM_STR);
            $insertQuery->bindParam(':last_name', $lastName, PDO::PARAM_STR);
            // Consider hashing and storing the password securely in a real application

            $insertQuery->execute();

            // Redirect to selection.html after successful registration
            header("Location: selection.html");
            exit();
        }
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<?php if ($error): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form action="register.php" method="POST"> <!-- Updated action -->
    <label for="first_name">First Name:</label>
    <input type="text" id="first_name" name="first_name" required>
    <br>
    <label for="last_name">Last Name:</label>
    <input type="text" id="last_name" name="last_name" required>
    <br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <button type="submit">Register</button>
</form>
</body>
</html>
