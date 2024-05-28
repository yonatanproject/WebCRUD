    <?php
include('./head.php');
include('./config/database_connection.php');

function isEmailAvailable($db, $email){
    $sql = "SELECT email FROM users WHERE email='$email'";
    return $db->query($sql)->fetchAll() == null;
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
    if ($password == "") {
        $errors['password'] = "Password tidak boleh kosong";
    }

    // cek jika tidak ada error maka lakukan proses penyimpanan data
    if (!$errors) {
        // cek duplikasi data
        if (isEmailAvailable($db, $email)) {
            // hash password untuk keamanan
            $password = password_hash($password, PASSWORD_DEFAULT);
            // buat sql
            $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
            // simpan data
            if ($db->exec($sql)) {
                // berhasil
                echo "<script>alert('Data berhasil disimpan');</script>";
                echo "<meta http-equiv='refresh' content='0; url=index.php'>";
            } else {
                // gagal
                echo "<script>alert('Gagal menyimpan data');</script>";
                echo "<meta http-equiv='refresh' content='0; url=index.php'>";
            }
        } else {
            $errors['email'] = "Email " . $email . " sudah digunakan";
        }
    }
}
?>

<div class="container py-5">
    <h2>Tambah Data User</h2>

    <?php foreach ($errors as $error) : ?>
        <div class="alert bg-danger" role="alert">
            <?= $error ?>
        </div>
    <?php endforeach; ?>
    <form class="mt-4" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input name="name" type="text" class="form-control" id="name">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input name="email" type="email" class="form-control" id="email">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input name="password" type="password" class="form-control" id="password">
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>

<?php
include('./foot.php');
?>