<?php
include('./head.php');
include('./config/database_connection.php');

if (!empty($_GET) and isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id='$id'";
    $user = $db->query($sql)->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        header('location:./index.php');
    }
} else {
    header('location:./index.php');
}

if (!empty($_POST)) {
    $id = $_POST['id'];
    $sql = "DELETE FROM users WHERE id='$id'";
    // hapus data
    if ($db->exec($sql)) {
        // berhasil
        echo "<script>alert('Data berhasil Dihapus');</script>";
        echo "<meta http-equiv='refresh' content='0; url=index.php'>";
    } else {
        // gagal
        echo "<script>alert('Gagal menghapus data');</script>";
        echo "<meta http-equiv='refresh' content='0; url=index.php'>";
    }
}
?>

<div class="container py-4 text-center">
    <h4>Hapus data user berikut ini?</h4>
    <br>
    <div>Nama: <?= $user['name'] ?></div>
    <div>Email: <?= $user['email'] ?></div>
    <br>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">
        <button type="submit" class="btn btn-success">OK</button>
        <a href="./index.php" class="btn btn-danger">BATAL</a>
    </form>
</div>

<?php
include('./foot.php');
?>