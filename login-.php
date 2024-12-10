<?php

include('header.php'); // Include your header file
include('db.php'); // Include your database connection file



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    // Prepare the SQL query
    $sql = "SELECT * FROM users WHERE email = :email AND role = :role";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':role', $role, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify the password and set session variables
    if ($result && password_verify($password, $result['password'])) {
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['role'] = $result['role'];

        // Redirect based on role
        if ($_SESSION['role'] == 'admin') {
            header("Location: admin_dashboard.php"); // Redirect to admin dashboard
            exit;
        } elseif ($_SESSION['role'] == 'staff') {
            header("Location: staff_dashboard.php"); // Redirect to staff dashboard
            exit;
        } else {
            header("Location: courses.php"); // Redirect to user/student dashboard
            exit;
        }
    } else {
        $login_error = "Invalid email or password.";
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['recover'])) {
    $email = $_POST['recover_email'] ?? '';

    // Prepare the SQL query for password recovery
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Implement the password recovery logic (e.g., sending an email with a reset link)
        $recovery_message = "Password recovery instructions have been sent to your email.";
    } else {
        $recovery_error = "No user found with that email.";
    }
}
?>

<div class="container">
    <h2>Login</h2>
    <form method="POST" action="login.php">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <select id="role" name="role" class="form-control" required>
                <option value="Student/User">Student/User</option>
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
            </select>
        </div>
        <?php if (isset($login_error)): ?>
            <div class="alert alert-danger"><?= $login_error ?></div>
        <?php endif; ?>
        <button type="submit" name="login" class="btn btn-primary">Login</button>
        <a href="#" class="btn btn-link" data-toggle="modal" data-target="#recoverModal">Forgot Password?</a>
    </form>
</div>

<!-- Password Recovery Modal -->
<div class="modal fade" id="recoverModal" tabindex="-1" aria-labelledby="recoverModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recoverModalLabel">Password Recovery</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="login.php">
                    <div class="form-group">
                        <label for="recover_email">Email:</label>
                        <input type="email" id="recover_email" name="recover_email" class="form-control" required>
                    </div>
                    <?php if (isset($recovery_error)): ?>
                        <div class="alert alert-danger"><?= $recovery_error ?></div>
                    <?php elseif (isset($recovery_message)): ?>
                        <div class="alert alert-success"><?= $recovery_message ?></div>
                    <?php endif; ?>
                    <button type="submit" name="recover" class="btn btn-primary">Recover Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); // Include your footer file ?>

kkkkkkkkkkkkk
kkkkkk
<?php

include('header.php');

// Your existing PHP logic for login goes here
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    $sql = "SELECT * FROM users WHERE email = :email AND role = :role";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':role', $role, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && password_verify($password, $result['password'])) {
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['role'] = $result['role'];

        if ($_SESSION['role'] == 'admin') {
            header("Location: admin_dashboard.php");
            exit;
        } else {
            header("Location: courses.php");
            exit;
        }
    } else {
        $login_error = "Invalid email or password.";
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['recover'])) {
    $email = $_POST['recover_email'] ?? '';

    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Here you should implement the password recovery logic (e.g., sending an email with a reset link)
        $recovery_message = "Password recovery instructions have been sent to your email.";
    } else {
        $recovery_error = "No user found with that email.";
    }
}
?>

<div class="container">
    <h2>Login</h2>
    <form method="POST" action="login.php">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <select id="role" name="role" class="form-control" required>
                <option value="Student/User">Student/User</option>
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
            </select>
        </div>
        <?php if (isset($login_error)): ?>
            <div class="alert alert-danger"><?= $login_error ?></div>
        <?php endif; ?>
        <button type="submit" name="login" class="btn btn-primary">Login</button>
        <a href="#" class="btn btn-link" data-toggle="modal" data-target="#recoverModal">Forgot Password?</a>
    </form>
</div>

<!-- Password Recovery Modal -->
<div class="modal fade" id="recoverModal" tabindex="-1" aria-labelledby="recoverModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recoverModalLabel">Password Recovery</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="login.php">
                    <div class="form-group">
                        <label for="recover_email">Email:</label>
                        <input type="email" id="recover_email" name="recover_email" class="form-control" required>
                    </div>
                    <?php if (isset($recovery_error)): ?>
                        <div class="alert alert-danger"><?= $recovery_error ?></div>
                    <?php elseif (isset($recovery_message)): ?>
                        <div class="alert alert-success"><?= $recovery_message ?></div>
                    <?php endif; ?>
                    <button type="submit" name="recover" class="btn btn-primary">Recover Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>

/*<------ stand by ----->/*
<?php

include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT id, full_name FROM users WHERE email = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            header('Location: profile.php');
            exit();
        } else {
            $login_error = "Invalid email or password.";
        }
    } elseif (isset($_POST['register'])) {
        $full_name = $_POST['register_full_name'];
        $email = $_POST['register_email'];
        $password = $_POST['register_password'];

        $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $full_name, $email, $password);

        if ($stmt->execute()) {
            $register_success = "Registration successful. You can now log in.";
        } else {
            $register_error = "Registration failed: " . $conn->error;
        }
    } elseif (isset($_POST['forgot_password'])) {
        $email = $_POST['forgot_password_email'];
        // Implement password reset logic here
    } elseif (isset($_POST['forgot_username'])) {
        $email = $_POST['forgot_username_email'];
        // Implement username retrieval logic here
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register - Online Accounting Course</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            font-family: Arial, sans-serif;
        }
        .auth-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .auth-container h1 {
            margin-bottom: 30px;
            color: #333;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
        }
        .auth-container .btn-primary {
            background: #6e8efb;
            border: none;
        }
        .auth-container .btn-primary:hover {
            background: #576ed1;
        }
        .auth-container .form-group {
            margin-bottom: 20px;
        }
        .auth-container .form-group label {
            margin-bottom: 10px;
        }
        .auth-container .form-group input {
            height: 40px;
            font-size: 16px;
        }
        .auth-container .tab-content {
            margin-top: 20px;
        }
        .auth-container .tab-content .tab-pane {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container auth-container">
        <h1>Login / Register</h1>
        <ul class="nav nav-tabs" id="authTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="login-tab" data-toggle="tab" href="#login" role="tab" aria-controls="login" aria-selected="true">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="register-tab" data-toggle="tab" href="#register" role="tab" aria-controls="register" aria-selected="false">Register</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="forgot-tab" data-toggle="tab" href="#forgot" role="tab" aria-controls="forgot" aria-selected="false">Forgot Password/Username</a>
            </li>
        </ul>
        <div class="tab-content" id="authTabContent">
            <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                <?php if (isset($login_error)): ?>
                    <div class="alert alert-danger"><?php echo $login_error; ?></div>
                <?php endif; ?>
                <form method="POST" action="auth.php">
                    <div class="form-group">
                    <div class="form-group">
                        <label for="register_username">Username</label>
                        <input type="text" class="form-control" id="register_username" name="register_username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" name="login">Login</button>
                </form>
            </div>
            <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
                <?php if (isset($register_error)): ?>
                    <div class="alert alert-danger"><?php echo $register_error; ?></div>
                <?php elseif (isset($register_success)): ?>
                    <div class="alert alert-success"><?php echo $register_success; ?></div>
                <?php endif; ?>
                <form method="POST" action="auth.php">
                    <div class="form-group">
                        
