<?php

include('db.php');
include('header.php');

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, password, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password, $role);
    $stmt->fetch();

    if ($stmt->num_rows == 1 && password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['role'] = $role;
        if ($role === 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: profile.php");
        }
        exit;
    } else {
        $login_error = "Invalid email or password.";
    }
}

// Handle registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $education_level = $_POST['education_level'];
    $birth_date = $_POST['birth_date'];
    $sex = $_POST['sex'];
    $nationality = $_POST['register_Nationality'];
    $country_of_residence = $_POST['register_country_residence'];

    $sql = "INSERT INTO users (username, password, email, first_name, last_name, address, phone, education_level, birth_date, sex, nationality, country_residence) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("ssssssssssss", $username, $password, $email, $first_name, $last_name, $address, $phone, $education_level, $birth_date, $sex, $nationality, $country_of_residence);
    if ($stmt->execute()) {
        $registration_success = "Registration successful. Please log in.";
    } else {
        $registration_error = "Registration failed: " . htmlspecialchars($stmt->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login/Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css"> <!-- Ensure this path is correct -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="form-container">
                    <h2>Login</h2>
                    <?php if (isset($login_error)): ?>
                        <div class="alert alert-danger"><?php echo $login_error; ?></div>
                    <?php endif; ?>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-container">
                    <h2>Register</h2>
                    <?php if (isset($registration_success)): ?>
                        <div class="alert alert-success"><?php echo $registration_success; ?></div>
                    <?php elseif (isset($registration_error)): ?>
                        <div class="alert alert-danger"><?php echo $registration_error; ?></div>
                    <?php endif; ?>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea class="form-control" id="address" name="address" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="education_level">Education Level</label>
                            <select class="form-control" id="education_level" name="education_level" required>
                                <option value="High School">High School</option>
                                <option value="Associate Degree">Associate Degree</option>
                                <option value="Bachelor's Degree">Bachelor's Degree</option>
                                <option value="Master's Degree">Master's Degree</option>
                                <option value="Doctorate">Doctorate</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="birth_date">Birth Date</label>
                            <input type="date" class="form-control" id="birth_date" name="birth_date" required>
                        </div>
                        <div class="form-group">
                            <label for="sex">Sex</label>
                            <select class="form-control" id="sex" name="sex" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="register_Nationality">Nationality</label>
                            <select class="form-control" id="register_Nationality" name="register_Nationality" required>
                                <option value="">Select your Nationality</option>
                                <?php
                                $countries = [
                                    "United States", "Canada", "United Kingdom", "Australia", "India", "Germany", "France", "Italy", "Spain", "Brazil",
                                    "Mexico", "China", "Japan", "Russia", "South Africa", "Nigeria", "Egypt", "Kenya", "Argentina", "Colombia"
                                    // Add more countries as needed
                                ];
                                foreach ($countries as $country): ?>
                                    <option value="<?php echo $country; ?>"><?php echo $country; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="register_country_residence">Country of Residence</label>
                            <select class="form-control" id="register_country_residence" name="register_country_residence" required>
                                <option value="">Select your country of residence</option>
                                <?php
                                $countries = [
                                    "United States", "Canada", "United Kingdom", "Australia", "India", "Germany", "France", "Italy", "Spain", "Brazil",
                                    "Mexico", "China", "Japan", "Russia", "South Africa", "Nigeria", "Egypt", "Kenya", "Argentina", "Colombia"
                                    // Add more countries as needed
                                ];
                                foreach ($countries as $country): ?>
                                    <option value="<?php echo $country; ?>"><?php echo $country; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" name="register">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php
include('footer.php');
?>
