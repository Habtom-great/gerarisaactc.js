
<?php
session_start();
include('db.php');
include('header_common.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Check if both email and password are provided
    if ($email && $password) {
        // Prepare and execute the SQL statement to fetch user by email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the user exists and verify the password
        if ($user && password_verify($password, $user['password'])) {
            // Store user data in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['full_name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['user_image'] = $user['profile_image'] ?: 'path/to/default/image.jpg';
            $_SESSION['session_id'] = session_id(); // Store session ID for reference

            // Redirect based on the user's role
            switch ($user['role']) {
                case 'admin':
                    header("Location: admin_dashboard.php");
                    break;
                case 'staff':
                    header("Location: staff_dashboard.php");
                    break;
                case 'user':
                    header("Location: user_dashboard.php");
                    break;
                default:
                    $error = "Unknown role. Please contact support.";
            }
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Please enter both email and password.";
    }
}
?>

<!-- HTML Form for Login -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e0f7fa;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .login-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin: 50px auto;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            color: #00796b;
            text-align: center;
            font-size: 24px;
        }

        .login-container input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #b2dfdb;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .login-container button {
            width: 100%;
            padding: 12px;
            border: none;
            background: #00796b;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
            font-size: 16px;
        }

        .login-container button:hover {
            background: #004d40;
            transform: scale(1.05);
        }

        .error-message {
            color: #d32f2f;
            margin-bottom: 15px;
            text-align: center;
        }

        .links {
            text-align: center;
            margin-top: 20px;
        }

        .links a {
            color: #00796b;
            text-decoration: none;
            font-weight: bold;
            margin: 0 10px;
        }

        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    <?php if (!empty($error)): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <p class="links">
        <a href="password_recovery.php">Forgot your password?</a> |
        <a href="register.php">Don't have an account? Register here.</a>
    </p>
</div>

</body>
</html>

kkkkkkkkkk
<?php

include('db.php');
include('header_common.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Check if both email and password are provided
    if ($email && $password) {
        // Prepare and execute the SQL statement to fetch user by email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the user exists and verify the password
        if ($user && password_verify($password, $user['password'])) {
            // Store user data in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['full_name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['user_image'] = $user['profile_image'] ? $user['profile_image'] : 'path/to/default/image.jpg';

            // Redirect based on the user's role
            if ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php");
            } elseif ($user['role'] === 'staff') {
                header("Location: staff_dashboard.php");
            } elseif ($user['role'] === 'user') {
                header("Location: user_dashboard.php");
            } else {
                $error = "Unknown role. Please contact support.";
            }
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Please enter both email and password.";
    }
}
?>

<!-- HTML Form for Login -->
<div class="login-container">
    <h2>Login</h2>
    <?php if (!empty($error)): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <p class="links">
        <a href="password_recovery.php">Forgot your password?</a> |
        <a href="register.php">Don't have an account? Register here.</a>
    </p>
</div>

<?php include('footer.php'); ?>

<!-- Styling for the Login Form -->
<style>
    body {
        font-family: Arial, sans-serif;
        background: #e0f7fa;
        margin: 0;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .login-container {
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
        margin: 50px auto;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-container h2 {
        margin-bottom: 20px;
        color: #00796b;
        text-align: center;
        font-size: 24px;
    }

    .login-container input {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border: 1px solid #b2dfdb;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 16px;
    }

    .login-container button {
        width: 100%;
        padding: 12px;
        border: none;
        background: #00796b;
        color: white;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.3s, transform 0.2s;
        font-size: 16px;
    }

    .login-container button:hover {
        background: #004d40;
        transform: scale(1.05);
    }

    .error-message {
        color: #d32f2f;
        margin-bottom: 15px;
        text-align: center;
    }

    .links {
        text-align: center;
        margin-top: 20px;
    }

    .links a {
        color: #00796b;
        text-decoration: none;
        font-weight: bold;
        margin: 0 10px;
    }

    .links a:hover {
        text-decoration: underline;
    }

    header, footer {
        background: #00796b;
        color: white;
        text-align: center;
        padding: 15px;
    }

    footer {
        position: relative;
        width: 100%;
        bottom: 0;
        margin-top: auto;
    }
</style>
