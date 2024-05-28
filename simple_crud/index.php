<?php
include('./head.php');
include('./config/database_connection.php');

function getAllUser($db)
{
    $sql = "SELECT * FROM users";
    return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC); } ?>
<div class="container py-4">
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama</th>
          <th>Email</th>
          <th>Password</th>
          <th>Kelola</th>
        </tr>
      </thead>
      <tbody>
        <?php if (getAllUser($db)) : ?>
        <?php foreach (getAllUser($db) as $user) : ?>
        <tr>
          <td><?= $user['id'] ?></td>
          <td><?= $user['name'] ?></td>
          <td><?= $user['email'] ?></td>
          <td><?= $user['password'] ?></td>
          <td>
            <a
              href="./update_user.php?id=<?= $user['id'] ?>"
              class="btn btn-sm btn-success"
              >Update</a
            >
            <a
              href="./delete_user.php?id=<?= $user['id'] ?>"
              class="btn btn-sm btn-danger"
              >Delete</a
            >
          </td>
        </tr>
        <?php endforeach; ?>
        <?php else : ?>
        <tr>
          <td colspan="5" class="text-center">Tidak ada data</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php
include('./foot.php');
?>