<?php
session_start();
if (!isset($_SESSION['is_login'])) {
    header("location: index.php");
    exit();
}

$zakat_message = "";
if (isset($_SESSION['zakat_message'])) {
    $zakat_message = $_SESSION['zakat_message'];
    unset($_SESSION['zakat_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <?php include "layout/header.html"; ?>

    <?php if ($zakat_message): ?>
        <p><?= htmlspecialchars($zakat_message) ?></p>
    <?php endif; ?>

    <h1>Selamat Datang <?= htmlspecialchars($_SESSION['username']); ?></h1>
    <a href="zakat.php">Pembayaran Zakat</a>
    <form action="logout.php" method="POST">
        <button type="submit" name="logout">Logout</button>
    </form>

    <?php include "layout/footer.html"; ?>
</body>
</html>
