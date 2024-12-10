<?php
// Connect to the database
require 'db.php';

// Check if the form to add a new user has been submitted
if (isset($_POST['add_user'])) {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Insert query
    $sql = "INSERT INTO users (first_name, middle_name, last_name, email, phone) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$first_name, $middle_name, $last_name, $email, $phone]);
    header('Location: manage_data.php');
}

// Check if the form to update an existing user has been submitted
if (isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Update query
    $sql = "UPDATE users SET first_name = ?, middle_name = ?, last_name = ?, email = ?, phone = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$first_name, $middle_name, $last_name, $email, $phone, $id]);
    header('Location: manage_data.php');
}

// Check if editing a user
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Edit User Form -->
    <h2>Edit User</h2>
    <form action="view_data.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
        <input type="text" name="first_name" placeholder="First Name" value="<?php echo $user['first_name']; ?>" required>
        <input type="text" name="middle_name" placeholder="Middle Name" value="<?php echo $user['middle_name']; ?>" required>
        <input type="text" name="last_name" placeholder="Last Name" value="<?php echo $user['last_name']; ?>" required>
        <input type="email" name="email" placeholder="Email" value="<?php echo $user['email']; ?>" required>
        <input type="text" name="phone" placeholder="Phone" value="<?php echo $user['phone']; ?>" required>
        <button type="submit" name="update_user">Update User</button>
    </form>
   
</body>
</html>

