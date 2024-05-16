<?php
include "service/database.php";
session_start();

if (!isset($_SESSION['is_login'])) {
    header("location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$zakat_message = "";

// Fetch Zakat types before handling form submission
$sql = "SELECT ID_Jenis_Zakat, Nama_Jenis_Zakat FROM Jenis_Zakat";
$result = $db->query($sql);
$zakat_types = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $zakat_types[] = $row;
    }
}

if (isset($_POST["submit_zakat"])) {
    $id_jenis_zakat = $_POST["jenis_zakat"];
    $jumlah_zakat = $_POST["jumlah_zakat"];
    $jenis_pembayaran = $_POST["jenis_pembayaran"];

    $sql = "INSERT INTO Zakat (ID_Muzaqi, ID_Jenis_Zakat, Jumlah_Zakat, Jenis_Pembayaran) 
            VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("iiss", $user_id, $id_jenis_zakat, $jumlah_zakat, $jenis_pembayaran);

    if ($stmt->execute()) {
        $_SESSION['zakat_message'] = "Pembayaran zakat berhasil.";
    } else {
        $_SESSION['zakat_message'] = "Pembayaran zakat gagal, silahkan coba lagi.";
    }

    $stmt->close();
    $db->close();

    header("location: dashboard.php");
    exit();
}

$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Zakat</title>
</head>
<body>
    <?php include "layout/header.html"; ?>

    <h3>Pembayaran Zakat</h3>
    <form action="zakat.php" method="POST">
        <label for="jenis_zakat">Jenis Zakat:</label>
        <select name="jenis_zakat" id="jenis_zakat" required>
            <?php foreach ($zakat_types as $type): ?>
                <option value="<?= $type['ID_Jenis_Zakat'] ?>"><?= $type['Nama_Jenis_Zakat'] ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="jumlah_zakat">Jumlah Zakat:</label>
        <input type="number" step="0.01" name="jumlah_zakat" id="jumlah_zakat" required />
        <br>
        <label for="jenis_pembayaran">Jenis Pembayaran:</label>
        <input type="text" name="jenis_pembayaran" id="jenis_pembayaran" required />
        <br>
        <button type="submit" name="submit_zakat">Bayar Zakat</button>
    </form>

    <?php include "layout/footer.html"; ?>
</body>
</html>
