<?php
include('db.php');
session_start();
// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Pagination settings
$items_per_page = 10;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

// Handle sorting
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'last_name'; // Default sort by last name
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC'; // Default order is ascending

$valid_columns = ['id', 'first_name', 'middle_name', 'last_name', 'email', 'phone', 'profile_image', 'address', 'country', 'registration_date', 'modified_date'];
$sort_by = in_array($sort_by, $valid_columns) ? $sort_by : 'last_name';
$order = ($order === 'ASC' || $order === 'DESC') ? $order : 'ASC';

// Handle search
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

// Display preferences
$show_registration_date = isset($_GET['registration_date']) && $_GET['registration_date'] === 'on';
$show_modified_date = isset($_GET['modified_date']) && $_GET['modified_date'] === 'on';
$show_address = isset($_GET['address']) && $_GET['address'] === 'on';
$show_country = isset($_GET['country']) && $_GET['country'] === 'on';

// Default columns to display
$default_columns = ['id', 'last_name', 'middle_name', 'first_name', 'phone', 'email', 'profile_image'];
$columns = array_merge($default_columns, $show_address ? ['address'] : [], $show_country ? ['country'] : [], $show_registration_date ? ['registration_date'] : [], $show_modified_date ? ['modified_date'] : []);

// Check if columns exist before preparing query
$stmt = $pdo->prepare("SHOW COLUMNS FROM users");
$stmt->execute();
$existing_columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

$columns = array_intersect($columns, $existing_columns);
$columns_str = implode(', ', $columns);

// Fetch user data for the current page with sorting and dynamic columns
try {
    // Build the base query with search
    $search_sql = $search_query ? "WHERE id LIKE :search OR first_name LIKE :search OR middle_name LIKE :search OR last_name LIKE :search" : '';
    
    // Fetch total number of users
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users $search_sql");
    if ($search_query) {
        $stmt->bindValue(':search', "%$search_query%");
    }
    $stmt->execute();
    $total_items = $stmt->fetchColumn();
    $total_pages = ceil($total_items / $items_per_page);

    // Fetch user data
    $stmt = $pdo->prepare("SELECT $columns_str FROM users $search_sql ORDER BY $sort_by $order LIMIT :offset, :limit");
    if ($search_query) {
        $stmt->bindValue(':search', "%$search_query%");
    }
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
    $users = []; // Ensure $users is an empty array in case of an error
}

include('header_loggedin.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .dashboard-container {
            margin-top: 30px;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .title-section {
            text-align: center;
            margin-bottom: 20px;
        }
        .title-section h1 {
            color: #007bff;
            font-size: 2.5rem;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            display: inline-block;
        }
        .subtitle-section {
            text-align: center;
            margin-bottom: 20px;
        }
        .subtitle-section h2 {
            color: #343a40;
            font-size: 1.8rem;
            margin: 0;
        }
        .table {
            margin-top: 20px;
            border-collapse: separate;
            border-spacing: 0;
        }
        .table th, .table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
            font-size: 0.9rem;
        }
        .table th {
            background-color: #007bff;
            color: #ffffff;
            text-transform: uppercase;
        }
        .table tr:hover {
            background-color: #f1f1f1;
        }
        .table img {
            max-width: 80px;
            height: auto;
            border-radius: 50%;
        }
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a {
            color: #007bff;
            padding: 8px 12px;
            text-decoration: none;
            border: 1px solid #ddd;
            margin: 0 2px;
            border-radius: 4px;
            font-size: 0.9rem;
        }
        .pagination a:hover {
            background-color: #e9ecef;
        }
        .pagination .active a {
            background-color: #007bff;
            color: #ffffff;
            border-color: #007bff;
        }
    </style>
</head>
<body>

<div class="container dashboard-container">
    <div class="title-section">
        <h1>Admin Dashboard</h1>
    </div>

    <div class="subtitle-section">
        <h2>Users/Students List</h2>
        <a href="add_user.php" class="btn btn-success">Add New User</a>
    </div>

    <!-- Search and Sorting Forms -->
    <div class="row mb-3">
        <div class="col-md-6">
            <form method="GET" class="form-inline">
                <input type="text" name="search" class="form-control mr-2" placeholder="Search by ID or Name" value="<?= htmlspecialchars($search_query) ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
        <div class="col-md-6">
            <form method="GET" class="form-inline justify-content-md-end mt-2 mt-md-0">
                <!-- Preserve search parameter in sorting form -->
                <?php if (!empty($search_query)): ?>
                    <input type="hidden" name="search" value="<?= htmlspecialchars($search_query) ?>">
                <?php endif; ?>
                <label for="sort_by" class="mr-2">Sort By:</label>
                <select name="sort_by" id="sort_by" class="form-control mr-2">
                    <option value="id" <?= $sort_by == 'id' ? 'selected' : '' ?>>ID</option>
                    <option value="last_name" <?= $sort_by == 'last_name' ? 'selected' : '' ?>>Last Name</option>
                    <option value="middle_name" <?= $sort_by == 'middle_name' ? 'selected' : '' ?>>Middle Name</option>
                    <option value="first_name" <?= $sort_by == 'first_name' ? 'selected' : '' ?>>First Name</option>
                    <option value="email" <?= $sort_by == 'email' ? 'selected' : '' ?>>Email</option>
                    <option value="phone" <?= $sort_by == 'phone' ? 'selected' : '' ?>>Phone</option>
                    <option value="profile_image" <?= $sort_by == 'profile_image' ? 'selected' : '' ?>>Profile Image</option>
                    <?php if (in_array('registration_date', $existing_columns)): ?><option value="registration_date" <?= $sort_by == 'registration_date' ? 'selected' : '' ?>>Registration Date</option><?php endif; ?>
                    <?php if (in_array('modified_date', $existing_columns)): ?><option value="modified_date" <?= $sort_by == 'modified_date' ? 'selected' : '' ?>>Modified Date</option><?php endif; ?>
                    <?php if (in_array('address', $existing_columns)): ?><option value="address" <?= $sort_by == 'address' ? 'selected' : '' ?>>Address</option><?php endif; ?>
                    <?php if (in_array('country', $existing_columns)): ?><option value="country" <?= $sort_by == 'country' ? 'selected' : '' ?>>Country</option><?php endif; ?>
                </select>
                <label for="order" class="mr-2">Order:</label>
                <select name="order" id="order" class="form-control mr-2">
                    <option value="ASC" <?= $order == 'ASC' ? 'selected' : '' ?>>Ascending</option>
                    <option value="DESC" <?= $order == 'DESC' ? 'selected' : '' ?>>Descending</option>
                </select>
                <button type="submit" class="btn btn-primary">Sort</button>
            </form>
        </div>
    </div>

    <!-- Display preferences form -->
    <form method="GET" class="form-inline mb-3">
        <input type="hidden" name="search" value="<?= htmlspecialchars($search_query) ?>">
        <input type="hidden" name="sort_by" value="<?= htmlspecialchars($sort_by) ?>">
        <input type="hidden" name="order" value="<?= htmlspecialchars($order) ?>">
        <label class="mr-2">Show:</label>
        <?php if (in_array('registration_date', $existing_columns)): ?>
            <input type="checkbox" name="registration_date" class="form-check-input mr-1" <?= $show_registration_date ? 'checked' : '' ?>> Registration Date
        <?php endif; ?>
        <?php if (in_array('modified_date', $existing_columns)): ?>
            <input type="checkbox" name="modified_date" class="form-check-input ml-2 mr-1" <?= $show_modified_date ? 'checked' : '' ?>> Modified Date
        <?php endif; ?>
        <?php if (in_array('address', $existing_columns)): ?>
            <input type="checkbox" name="address" class="form-check-input ml-2 mr-1" <?= $show_address ? 'checked' : '' ?>> Address
        <?php endif; ?>
        <?php if (in_array('country', $existing_columns)): ?>
            <input type="checkbox" name="country" class="form-check-input ml-2 mr-1" <?= $show_country ? 'checked' : '' ?>> Country
        <?php endif; ?>
        <button type="submit" class="btn btn-secondary ml-3">Apply</button>
    </form>

    <!-- Display User Table -->
    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <?php foreach ($columns as $column): ?>
                        <th><?= ucfirst(str_replace('_', ' ', $column)) ?></th>
                    <?php endforeach; ?>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="<?= count($columns) + 2 ?>" class="text-center">No users found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <?php foreach ($columns as $column): ?>
                                <td>
                                    <?php if ($column == 'profile_image'): ?>
                                        <img src="<?= htmlspecialchars($user[$column]) ?>" alt="Profile Image">
                                    <?php else: ?>
                                        <?= htmlspecialchars($user[$column]) ?>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                            <td><a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-warning btn-sm">Edit</a></td>
                            <td><a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav>
        <ul class="pagination">
            <?php if ($current_page > 1): ?>
                <li class="page-item"><a class="page-link" href="?page=<?= $current_page - 1 ?>&sort_by=<?= $sort_by ?>&order=<?= $order ?>&search=<?= htmlspecialchars($search_query) ?>">Previous</a></li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i == $current_page ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $i ?>&sort_by=<?= $sort_by ?>&order=<?= $order ?>&search=<?= htmlspecialchars($search_query) ?>"><?= $i ?></a></li>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <li class="page-item"><a class="page-link" href="?page=<?= $current_page + 1 ?>&sort_by=<?= $sort_by ?>&order=<?= $order ?>&search=<?= htmlspecialchars($search_query) ?>">Next</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include('footer.php'); ?>

kkkkkkkkkkkkkk
<?php
include('db.php');

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Pagination settings
$items_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

// Handle sorting
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'id'; // Default sort by ID
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC'; // Default order is ascending

$valid_columns = ['id', 'first_name', 'last_name'];
$sort_by = in_array($sort_by, $valid_columns) ? $sort_by : 'id';
$order = ($order === 'ASC' || $order === 'DESC') ? $order : 'ASC';

try {
    // Fetch total number of users
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users");
    $stmt->execute();
    $total_items = $stmt->fetchColumn();
    $total_pages = ceil($total_items / $items_per_page);

    // Fetch user data for the current page with sorting
    $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, phone, role, profile_image FROM users ORDER BY $sort_by $order LIMIT :offset, :limit");
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!$users) {
        $users = []; // Ensure $users is an empty array if no data is returned
    }
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
    $users = []; // Ensure $users is an empty array in case of an error
}

include('header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Your existing styles */
      
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .dashboard-container {
            margin-top: 30px;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .title-section {
            text-align: center;
            margin-bottom: 20px;
        }
        .title-section h1 {
            color: #007bff;
            font-size: 2.5rem;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            display: inline-block;
        }
        .subtitle-section {
            text-align: center;
            margin-bottom: 20px;
        }
        .subtitle-section h2 {
            color: #343a40;
            font-size: 2rem;
            margin: 0;
        }
        .table {
            margin-top: 20px;
            border-collapse: separate;
            border-spacing: 0;
        }
        .table th, .table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .table th {
            background-color: #007bff;
            color: #ffffff;
            text-transform: uppercase;
        }
        .table tr:hover {
            background-color: #f1f1f1;
        }
        .user-image {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ddd;
        }
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a {
            color: #007bff;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #ddd;
            margin: 0 2px;
            border-radius: 4px;
        }
        .pagination a:hover {
            background-color: #e9ecef;
        }
        .pagination .active a {
            background-color: #007bff;
            color: #ffffff;
            border-color: #007bff;
        }
  
    </style>
</head>
<body>

<div class="container dashboard-container">
    <div class="title-section">
        <h1>Admin Dashboard</h1>
    </div>

    <div class="subtitle-section">
        <h2>Users/Students List</h2>
        <a href="add_user.php" class="btn btn-success mb-3">Add New User</a>
    </div>

    <!-- Sorting Dropdown -->
    <form method="GET" class="form-inline mb-3">
        <label for="sort_by" class="mr-2">Sort By:</label>
        <select name="sort_by" id="sort_by" class="form-control mr-2">
            <option value="id" <?= $sort_by == 'id' ? 'selected' : '' ?>>ID</option>
            <option value="first_name" <?= $sort_by == 'first_name' ? 'selected' : '' ?>>First Name</option>
            <option value="last_name" <?= $sort_by == 'last_name' ? 'selected' : '' ?>>Last Name</option>
        </select>

        <select name="order" id="order" class="form-control mr-2">
            <option value="ASC" <?= $order == 'ASC' ? 'selected' : '' ?>>Ascending</option>
            <option value="DESC" <?= $order == 'DESC' ? 'selected' : '' ?>>Descending</option>
        </select>

        <button type="submit" class="btn btn-primary">Sort</button>
    </form>

    <!-- Users/Students Section -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Image</th>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($user['profile_image']) ?>" alt="User Image" class="user-image"></td>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['first_name']) ?></td>
                    <td><?= htmlspecialchars($user['last_name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['phone']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination Controls -->
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php if ($current_page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $current_page - 1 ?>&sort_by=<?= $sort_by ?>&order=<?= $order ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&sort_by=<?= $sort_by ?>&order=<?= $order ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <?php if ($current_page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $current_page + 1 ?>&sort_by=<?= $sort_by ?>&order=<?= $order ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php include('footer.php'); ?>


kkkkkkkkkkkkk

<?php
include('db.php');

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Pagination settings
$items_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

// Handle sorting
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'id'; // Default sort by ID
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC'; // Default order is ascending

$valid_columns = ['id', 'first_name', 'last_name'];
$sort_by = in_array($sort_by, $valid_columns) ? $sort_by : 'id';
$order = ($order === 'ASC' || $order === 'DESC') ? $order : 'ASC';

try {
    // Fetch total number of users
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users");
    $stmt->execute();
    $total_items = $stmt->fetchColumn();
    $total_pages = ceil($total_items / $items_per_page);

    // Fetch user data for the current page with sorting
    $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, role, profile_image, phone FROM users ORDER BY $sort_by $order LIMIT :offset, :limit");
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}

include('header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <style>
        /* Your existing styles */
      
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .dashboard-container {
            margin-top: 30px;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .title-section {
            text-align: center;
            margin-bottom: 20px;
        }
        .title-section h1 {
            color: #007bff;
            font-size: 2.5rem;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            display: inline-block;
        }
        .subtitle-section {
            text-align: center;
            margin-bottom: 20px;
        }
        .subtitle-section h2 {
            color: #343a40;
            font-size: 2rem;
            margin: 0;
        }
        .table {
            margin-top: 20px;
            border-collapse: separate;
            border-spacing: 0;
        }
        .table th, .table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .table th {
            background-color: #007bff;
            color: #ffffff;
            text-transform: uppercase;
        }
        .table tr:hover {
            background-color: #f1f1f1;
        }
        .user-image {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ddd;
        }
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a {
            color: #007bff;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #ddd;
            margin: 0 2px;
            border-radius: 4px;
        }
        .pagination a:hover {
            background-color: #e9ecef;
        }
        .pagination .active a {
            background-color: #007bff;
            color: #ffffff;
            border-color: #007bff;
        }
  
    </style>
</head>
<body>

<div class="container dashboard-container">
    <div class="title-section">
        <h1>Admin Dashboard</h1>
    </div>

    <div class="subtitle-section">
        <h2>Users/Students List</h2>
        <a href="add_user.php" class="btn btn-success mb-3">Add New User</a>
    </div>

    <!-- Sorting Dropdown -->
    <form method="GET" class="form-inline mb-3">
        <label for="sort_by" class="mr-2">Sort By:</label>
        <select name="sort_by" id="sort_by" class="form-control mr-2">
            <option value="id" <?= $sort_by == 'id' ? 'selected' : '' ?>>ID</option>
            <option value="first_name" <?= $sort_by == 'first_name' ? 'selected' : '' ?>>First Name</option>
            <option value="last_name" <?= $sort_by == 'last_name' ? 'selected' : '' ?>>Last Name</option>
        </select>

        <select name="order" id="order" class="form-control mr-2">
            <option value="ASC" <?= $order == 'ASC' ? 'selected' : '' ?>>Ascending</option>
            <option value="DESC" <?= $order == 'DESC' ? 'selected' : '' ?>>Descending</option>
        </select>

        <button type="submit" class="btn btn-primary">Sort</button>
    </form>

    <!-- Users/Students Section -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Image</th>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($user['profile_image']) ?>" alt="User Image" class="user-image"></td>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['first_name']) ?></td>
                    <td><?= htmlspecialchars($user['last_name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['phone']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination Controls -->
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php if ($current_page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $current_page - 1 ?>&sort_by=<?= $sort_by ?>&order=<?= $order ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&sort_by=<?= $sort_by ?>&order=<?= $order ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <?php if ($current_page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $current_page + 1 ?>&sort_by=<?= $sort_by ?>&order=<?= $order ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php include('footer.php'); ?>


