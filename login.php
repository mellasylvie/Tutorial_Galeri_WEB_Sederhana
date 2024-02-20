<?php
session_start();

if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}
require 'fungsi.php';
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM user WHERE Username='$username'");

    // Cek username
    if (mysqli_num_rows($result) === 1) {
        // cek password
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['Password'])) {
            // atur session
            $_SESSION['userID'] = $row['UserID'];
            $_SESSION['username'] = $username;
            header("Location:index.php");
            exit;
        }
    }
    $error = true;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <main>
        <h1>Halaman Login</h1>
        <?php if (isset($error)) : ?>
            <p>Username/Password Salah</p>
        <?php endif; ?>
        <form action="" method="post">
            <div>
                <label for="username">Username :</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div>
                <button type="submit" name="login">Masuk</button>
            </div>
            <div>
                <p>Belum punya akun ? <a href="registrasi.php">Daftar Disini</a></p>
            </div>
        </form>
    </main>
</body>

</html>