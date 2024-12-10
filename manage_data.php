
<?php
// Connect to the database
require 'db.php';

// Initialize variables for users
$search_keyword_user = '';
$sort_by_user = 'last_name';  
$order_user = 'ASC';          
$users = [];

// Initialize variables for courses
$search_keyword_course = '';
$sort_by_course = 'course_title';  
$order_course = 'ASC';             
$courses = [];

// Handle search and sorting for users
if (isset($_GET['search_user'])) {
    $search_keyword_user = $_GET['search_user'];
}
if (isset($_GET['sort_by_user'])) {
    $sort_by_user = $_GET['sort_by_user'];
}
if (isset($_GET['order_user'])) {
    $order_user = $_GET['order_user'];
}

// Handle search and sorting for courses
if (isset($_GET['search_course'])) {
    $search_keyword_course = $_GET['search_course'];
}
if (isset($_GET['sort_by_course'])) {
    $sort_by_course = $_GET['sort_by_course'];
}
if (isset($_GET['order_course'])) {
    $order_course = $_GET['order_course'];
}

// Search and sort query for users
$sql_users = "SELECT * FROM users WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ? ORDER BY $sort_by_user $order_user";
$stmt_users = $conn->prepare($sql_users);
$stmt_users->execute(['%' . $search_keyword_user . '%', '%' . $search_keyword_user . '%', '%' . $search_keyword_user . '%']);
$users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);

// Search and sort query for courses
$sql_courses = "SELECT * FROM courses WHERE course_title LIKE ? OR tutor_name LIKE ? ORDER BY $sort_by_course $order_course";
$stmt_courses = $conn->prepare($sql_courses);
$stmt_courses->execute(['%' . $search_keyword_course . '%', '%' . $search_keyword_course . '%']);
$courses = $stmt_courses->fetchAll(PDO::FETCH_ASSOC);

// Handle delete action for users
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM users WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->execute([$delete_id]);
    header('Location: manage_data.php');
}

// Handle delete action for courses
if (isset($_GET['delete_course_id'])) {
    $delete_course_id = $_GET['delete_course_id'];
    $delete_course_sql = "DELETE FROM courses WHERE course_id = ?";
    $delete_course_stmt = $conn->prepare($delete_course_sql);
    $delete_course_stmt->execute([$delete_course_id]);
    header('Location: manage_data.php');
}

include 'header.php'; // Include the header
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users & Courses</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            padding: 20px;
            color: #333;
            padding-top: 60px; /* Ensure space for the fixed header */
        }

        h2 {
            color: #4b8bbe;
            margin-bottom: 15px;
            font-size: 20px;
        }

      

        form input, form select, button {
            padding: 8px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 180px;
            font-size: 14px;
        }

        button {
            background-color: #28a745;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 14px;
            padding: 10px;
        }

        button:hover {
            background-color: #218838;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 14px;
        }

        table th {
            background-color: #e9f5fc;
            color: #4b8bbe;
        }

        a {
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }

        a:hover {
            text-decoration: underline;
        }

        .actions a {
            margin-right: 8px;
        }

        .add-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .search-sort {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<!-- Manage Users Section -->
<div class="container">
    <h2>Manage Users</h2>
    <form action="manage_data.php" method="GET" class="search-sort">
        <input type="text" name="search_user" placeholder="Search by name or email" value="<?php echo htmlspecialchars($search_keyword_user); ?>">
        <select name="sort_by_user">
            <option value="last_name" <?php if ($sort_by_user == 'last_name') echo 'selected'; ?>>Last Name</option>
            <option value="middle_name" <?php if ($sort_by_user == 'middle_name') echo 'selected'; ?>>Middle Name</option>
            <option value="first_name" <?php if ($sort_by_user == 'first_name') echo 'selected'; ?>>First Name</option>
        </select>
        <select name="order_user">
            <option value="ASC" <?php if ($order_user == 'ASC') echo 'selected'; ?>>Ascending</option>
            <option value="DESC" <?php if ($order_user == 'DESC') echo 'selected'; ?>>Descending</option>
        </select>
        <button type="submit">Search & Sort</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Last Name</th>
                <th>Middle Name</th>
                <th>First Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)) : ?>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($user['middle_name']); ?></td>
                    <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['phone']); ?></td>
                    <td class="actions">
                        <a href="view_data.php?id=<?php echo $user['id']; ?>">Edit</a>
                        <a href="manage_data.php?delete_id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Add New User Form -->
    <h2>Add New User</h2>
    <form action="view_data.php" method="POST">
        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="middle_name" placeholder="Middle Name" required>
        <input type="text" name="last_name" placeholder="Last Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phone" placeholder="Phone" required>
        <button type="submit" name="add_user">Add User</button>
    </form>
</div>

<!-- Manage Courses Section -->
<div class="container">
    <h2>Manage Courses</h2>
    <form action="manage_data.php" method="GET" class="search-sort">
        <input type="text" name="search_course" placeholder="Search by course or tutor name" value="<?php echo htmlspecialchars($search_keyword_course); ?>">
        <select name="sort_by_course">
            <option value="course_title" <?php if ($sort_by_course == 'course_title') echo 'selected'; ?>>Course Title</option>
            <option value="tutor_name" <?php if ($sort_by_course == 'tutor_name') echo 'selected'; ?>>Tutor Name</option>
        </select>
        <select name="order_course">
            <option value="ASC" <?php if ($order_course == 'ASC') echo 'selected'; ?>>Ascending</option>
            <option value="DESC" <?php if ($order_course == 'DESC') echo 'selected'; ?>>Descending</option>
        </select>
        <button type="submit">Search & Sort</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Course ID</th>
                <th>Course Title</th>
                <th>Tutor Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($courses)) : ?>
                <?php foreach ($courses as $course): ?>
                <tr>
                    <td><?php echo htmlspecialchars($course['course_id']); ?></td>
                    <td><?php echo htmlspecialchars($course['course_title']); ?></td>
                    <td><?php echo htmlspecialchars($course['tutor_name']); ?></td>
                    <td class="actions">
                        <a href="view_course.php?course_id=<?php echo $course['course_id']; ?>">Edit</a>
                        <a href="manage_data.php?delete_course_id=<?php echo $course['course_id']; ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No courses found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Add New Course Form -->
    <h2>Add New Course</h2>
    <form action="view_course.php" method="POST">
        <input type="text" name="course_title" placeholder="Course Title" required>
        <input type="text" name="tutor_name" placeholder="Tutor Name" required>
        <button type="submit" name="add_course">Add Course</button>
    </form>
</div>

<?php include 'footer.php'; // Include the footer ?>
