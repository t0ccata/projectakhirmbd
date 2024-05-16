<?php
include "service/database.php";
session_start();

$register_message = "";

if (isset($_SESSION["is_login"])) {
    header("location: dashboard.php");
    exit();
}

if (isset($_POST["register"])) {
    $muzaqi = $_POST["muzaqi"];
    $password = $_POST["password"];
    $alamat = $_POST["alamat"];
    $notelp = $_POST["notelp"];
    $email = $_POST["email"];

    $sql = "INSERT INTO Muzaqi (Nama_Muzaqi, Password, Alamat, No_Telepon, Email) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("sssss", $muzaqi, $password, $alamat, $notelp, $email);

    if ($stmt->execute()) {
        $register_message = "Daftar akun berhasil, silahkan login.";
    } else {
        $register_message = "Daftar akun gagal, silahkan coba lagi.";
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
    <title>Register</title>
</head>
<body>
    <?php include "layout/header.html"; ?>

    <h3>Daftar Akun</h3>
    <i><?= $register_message ?></i>
    <form action="register.php" method="POST">
        <input type="text" placeholder="Nama Muzaqi" name="muzaqi" required />
        <input type="password" placeholder="Password" name="password" required />
        <input type="text" placeholder="Alamat" name="alamat" required />
        <input type="text" placeholder="No. Telp" name="notelp" required />
        <input type="text" placeholder="Email" name="email" required />
        <button type="submit" name="register">Daftar Sekarang</button>
    </form>

    <?php include "layout/footer.html"; ?>
</body>
</html>
