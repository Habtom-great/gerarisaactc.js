<?php

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
include('db.php');
include('header.php');

$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<h2>Manage Users</h2>
<a href="add_user.php" class="btn btn-primary">Add New User</a>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td>
                    <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                    <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include('footer.php'); ?>
