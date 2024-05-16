<?php
include "service/database.php";
session_start();

$login_message = "";

if (isset($_SESSION["is_login"])) {
    header("location: dashboard.php");
    exit();
}

if (isset($_POST["login"])) {
    $muzaqi = $_POST["muzaqi"];
    $password = $_POST["password"];

    $sql = "SELECT ID_Muzaqi, Nama_Muzaqi FROM Muzaqi WHERE Nama_Muzaqi = ? AND Password = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ss", $muzaqi, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id_muzaqi, $nama_muzaqi);
        $stmt->fetch();

        $_SESSION['is_login'] = true;
        $_SESSION['user_id'] = $id_muzaqi;
        $_SESSION['username'] = $nama_muzaqi;

        header("location: dashboard.php");
        exit();
    } else {
        $login_message = "Login gagal, nama atau password salah.";
    }

    $stmt->close();
    $db->close();
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
    <?php include "layout/header.html"; ?>

    <h3>Login</h3>
    <i><?= $login_message ?></i>
    <form action="login.php" method="POST">
        <input type="text" placeholder="Nama Muzaqi" name="muzaqi" required />
        <input type="password" placeholder="Password" name="password" required />
        <button type="submit" name="login">Login</button>
    </form>

    <?php include "layout/footer.html"; ?>
</body>
</html>
