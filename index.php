<?php
session_start();
include "database.php";

$error = "";

if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {

        $row = mysqli_fetch_assoc($result);

        // Bisa hash atau plaintext
        if (password_verify($password, $row['password_hash']) || $password === $row['password_hash']) {

            $_SESSION['user_id']   = $row['user_id'];
            $_SESSION['full_name'] = $row['full_name'];
            $_SESSION['email']     = $row['email'];

            header("Location: home.php");
            exit;
        } else {
            $error = "⚠️ Password salah!";
        }
    } else {
        $error = "⚠️ Username tidak ditemukan!";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="login-container">
    <div class="login-card">

        <h2 class="login-title">Login</h2>

        <?php if ($error != ""): ?>
            <p class="login-error" style="color:red; text-align:center;">
                <?php echo $error; ?>
            </p>
        <?php endif; ?>

        <form method="POST">
            <label class="login-label">Username</label>
            <input type="text" name="username" class="login-input" required>

            <label class="login-label">Password</label>
            <input type="password" name="password" class="login-input" required>

            <button type="submit" name="login" class="login-btn">Login</button>
        </form>

    </div>
</div>

</body>
</html>
