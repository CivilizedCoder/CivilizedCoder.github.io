<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/mainStyle.css">
    <title>Login - MyMovies</title>
</head>
<body>
<h1>Login to MyMovies</h1>

<?php
$server = "localhost";
$user = "myMovies";
$password = "myMovPass";
$db = "sakila";

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = strip_tags($_POST['email']);
    $passwordInput = strip_tags($_POST['password']);

    try {
        $conn = new PDO("mysql:host=$server;dbname=$db", $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM customer WHERE email = :email";
        $query = $conn->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();

        $user = $query->fetch(PDO::FETCH_ASSOC);

    
        if ($user) {  
            header("Location: selection.html");
            exit();
        } else {
            $error = "Invalid login. Please try again.";
        }
    } catch (PDOException $e) {

        $error = "Error, please try again later.";
    }
}
?>

<?php if ($error): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form action="login.php" method="POST">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <button type="submit">Login</button>
</form>
</body>
</html>
