
<?php

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
include('db.php');
include('header.php');

$sql = "SELECT * FROM courses";
$result = $conn->query($sql);
?>

<h2>Manage Courses</h2>
<a href="add_course.php" class="btn btn-primary">Add New Course</a>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['description']); ?></td>
                <td>
                    <a href="edit_course.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                    <a href="delete_course.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include('footer.php'); ?>


------------------
<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
include('db.php');
include('header.php');

// Handle add, edit, delete operations here...

$sql = "SELECT * FROM courses";
$result = $conn->query($sql);
?>

<h2>Manage Courses</h2>
<a href="add_course.php" class="btn btn-primary">Add New Course</a>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['description']); ?></td>
                <td>
                    <a href="edit_course.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                    <a href="delete_course.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include('footer.php'); ?>
