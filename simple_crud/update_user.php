<?php
include('./head.php');
include('./config/database_connection.php');

function isEmailAvailable($db, $email, $userID)
{
    $sql = "SELECT email FROM users WHERE id='$userID'";
    $oldEmail = $db->query($sql)->fetch(PDO::FETCH_ASSOC)['email'];

    $sql = "SELECT email FROM users WHERE email='$email'";
    $checkUser = $db->query($sql)->fetch(PDO::FETCH_ASSOC);

    return ($checkUser == null or $email == $oldEmail);
}

$errors = []; // varioabel yang akan digunakan untuk menampung pesan error validasi

if (!empty($_POST)) {

    // ambil data yang dikirimkan melalui form dan simpan ke dalam variabel
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // lakukan validasi untuk tiap inputan, jika inputan kosong maka tambahkan pesan error kedalam variabel $errors
    if ($name == "") {
        $errors['name'] = "Nama tidak boleh kosong";
    }
    if ($email == "") {
        $errors['email'] = "Email tidak boleh kosong";
    }

    // cek jika tidak ada error maka lakukan proses penyimpanan data
    if (!$errors) {

        $id = $_POST['id'];

        // cek duplikasi data
        if (isEmailAvailable($db, $email, $id)) {
            // cek jika user merubah password
            if ($password != "") {
                // hash password untuk keamanan
                $password = password_hash($password, PASSWORD_DEFAULT);
                // buat sql
                $sql = "UPDATE users SET name='$name', email='$email', password='$password' WHERE id='$id'";
            } else {
                $sql = "UPDATE users SET name='$name', email='$email' WHERE id='$id'";
            }
            // simpan data
            if ($db->exec($sql)) {
                // berhasil
                echo "<script>alert('Data berhasil diperbarui');</script>";
                echo "<meta http-equiv='refresh' content='0; url=index.php'>";
            } else {
                // gagal
                echo "<script>alert('Gagal memperbarui data');</script>";
                echo "<meta http-equiv='refresh' content='0; url=index.php'>";
            }
        } else {
            $errors['email'] = "Email " . $email . " sudah digunakan";
        }
    }
}

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
?>
<div class="container py-5">
    <h2>Update Data User</h2>
    <?php foreach ($errors as $error) : ?>
        <div class="alert bg-danger" role="alert">
            <?= $error ?>
        </div>
    <?php endforeach; ?>
    <form class="mt-4" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input name="name" type="text" class="form-control" id="name" value="<?= $user['name'] ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input name="email" type="email" class="form-control" id="email" value="<?= $user['email'] ?>">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input name="password" type="password" class="form-control" id="password">
        </div>
        <input type="hidden" name="id" value="<?= $user['id'] ?>">
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>

<?php
include('./foot.php');
?>