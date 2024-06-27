<?php
include "service/database.php";
session_start();

if (!isset($_SESSION['is_login'])) {
    header("location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$zakat_message = "";

if (isset($_SESSION['zakat_message'])) {
    $zakat_message = $_SESSION['zakat_message'];
    unset($_SESSION['zakat_message']);
}

$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC';

$sql = "SELECT ID_Zakat, Jenis_Pembayaran, Jumlah_Zakat, Tanggal_Pembayaran 
        FROM Zakat 
        WHERE ID_Muzaqi = ?
        ORDER BY Tanggal_Pembayaran $sort_order";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$zakat_history = [];
while ($row = $result->fetch_assoc()) {
    $zakat_history[] = $row;
}

$stmt->close();
$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Dashboard</title>
</head>

<style>
    a {
        text-decoration: none;
    }

    a:hover {
        background-color: aqua;
    }
</style>

<body>
    <!-- <?php include "layout/header.html"; ?> -->

    <?php if ($zakat_message): ?>
        <p><?= htmlspecialchars($zakat_message) ?></p>
    <?php endif; ?>

    <h1 class="m-3">Selamat Datang, <?= htmlspecialchars($username); ?></h1>
    <br>
    <a class="m-3" href="zakat.php">Pembayaran Zakat</a>
    <br>
    <br>
    <h2 class="m-2">Riwayat Pembayaran Zakat Anda</h2>

    <form method="get" action="">
        <label class="m-3" for="sort_order">Urutkan berdasarkan tanggal:</label>
        <select name="sort_order" id="sort_order" onchange="this.form.submit()">
            <option value="ASC" <?= $sort_order == 'ASC' ? 'selected' : '' ?>>Dari Awal ke Akhir</option>
            <option value="DESC" <?= $sort_order == 'DESC' ? 'selected' : '' ?>>Dari Akhir ke Awal</option>
        </select>
    </form>

    <table class="table">
        <tr>
            <th>ID Zakat</th>
            <th>Jenis Pembayaran</th>
            <th>Jumlah Zakat</th>
            <th>Tanggal Pembayaran</th>
        </tr>
        <?php foreach ($zakat_history as $zakat): ?>
        <tr>
            <td><?= htmlspecialchars($zakat['ID_Zakat']) ?></td>
            <td><?= htmlspecialchars($zakat['Jenis_Pembayaran']) ?></td>
            <td><?= htmlspecialchars($zakat['Jumlah_Zakat']) ?></td>
            <td><?= htmlspecialchars($zakat['Tanggal_Pembayaran']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <form action="logout.php" method="POST">
        <button class="btn btn-danger m-3" type="submit" name="logout">Logout</button>
    </form>

    <?php include "layout/footer.html"; ?>
</body>
</html>
