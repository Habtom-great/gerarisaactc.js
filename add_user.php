<?php
include('db.php');
include('header_common.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $education_level = trim($_POST['education_level']);
    $age = trim($_POST['age']);
    $sex = trim($_POST['sex']);
    $nationality = trim($_POST['nationality']);
    $country_residence = trim($_POST['country_residence']);
    $role = 'user'; // Default role

    // Image upload handling
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $registration_error = "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        $registration_error = "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["profile_image"]["size"] > 500000) {
        $registration_error = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $registration_error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $registration_error = "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            $profile_image_path = $target_file;
        } else {
            $registration_error = "Sorry, there was an error uploading your file.";
        }
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $registration_error = "Invalid email format.";
    } else {
        // Check if the username or email already exists
        $check_sql = "SELECT id FROM users WHERE email = :email OR username = :username";
        $stmt = $pdo->prepare($check_sql);

        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($pdo->error));
        }

        // Bind parameters and execute the statement
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $registration_error = "Username or Email already exists.";
        } else {
            // Insert new user
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $insert_sql = "INSERT INTO users (username, first_name, last_name, email, password, address, phone, education_level, age, sex, nationality, country_residence, role, profile_image) VALUES (:username, :first_name, :last_name, :email, :password, :address, :phone, :education_level, :age, :sex, :nationality, :country_residence, :role, :profile_image)";
            $stmt = $pdo->prepare($insert_sql);

            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($pdo->error));
            }

            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':education_level', $education_level);
            $stmt->bindParam(':age', $age);
            $stmt->bindParam(':sex', $sex);
            $stmt->bindParam(':nationality', $nationality);
            $stmt->bindParam(':country_residence', $country_residence);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':profile_image', $profile_image_path);

            if ($stmt->execute()) {
                $registration_success = "Registration successful.";
            } else {
                $registration_error = "Registration failed. Please try again.";
                error_log("Registration error: " . htmlspecialchars($stmt->errorInfo()[2]));
            }
        }

        $stmt->closeCursor(); // Close the statement after use
    }

    $pdo = null; // Close the connection after all operations are complete
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <?php if (isset($registration_success)): ?>
            <div class="alert alert-success"><?php echo $registration_success; ?></div>
        <?php elseif (isset($registration_error)): ?>
            <div class="alert alert-danger"><?php echo $registration_error; ?></div>
        <?php endif; ?>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="first_name">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="last_name">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="address">Address</label>
                    <textarea class="form-control" id="address" name="address" required></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="role">Role</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="user/Student">User/Student</option>
                        <option value="admin">Admin</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="profile_image">Profile Image</label>
                    <input type="file" class="form-control" id="profile_image" name="profile_image" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="education_level">Education Level</label>
                    <select class="form-control" id="education_level" name="education_level" required>
                        <option value="High School">High School</option>
                        <option value="Bachelor's Degree">Bachelor's Degree</option>
                        <option value="Master's Degree">Master's Degree</option>
                        <option value="PhD">PhD</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="age">Age</label>
                    <input type="number" class="form-control" id="age" name="age" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="sex">Sex</label>
                    <select class="form-control" id="sex" name="sex" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nationality">Nationality</label>
                    <select class="form-control" id="nationality" name="nationality" required>
                        <option value="">Select your nationality</option>
                        <?php
                        $countries = [
                            "United States", "Canada", "United Kingdom", "Australia", "India", "Germany", "France", "Italy", "Spain", "Brazil",
                            "Mexico", "China", "Japan", "Russia", "South Africa", "Nigeria", "Egypt", "Kenya", "Argentina", "Colombia"
                        ];
                        foreach ($countries as $country): ?>
                            <option value="<?php echo $country; ?>"><?php echo $country; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="country_residence">Country of Residence</label>
                    <select class="form-control" id="country_residence" name="country_residence" required>
                        <option value="">Select your country of residence</option>
                        <?php
                        foreach ($countries as $country): ?>
                            <option value="<?php echo $country; ?>"><?php echo $country; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block" name="register">Register</button>
        </form>
        <div class="text-center mt-3">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php
include('footer.php');
?>

kkkkk
<?php
include('db.php');
include('header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $education_level = isset($_POST['education_level']) ? trim($_POST['education_level']) : '';
    $age = isset($_POST['age']) ? trim($_POST['age']) : '';
    $sex = isset($_POST['sex']) ? trim($_POST['sex']) : '';
    $nationality = isset($_POST['nationality']) ? trim($_POST['nationality']) : '';
    $country_residence = isset($_POST['country_residence']) ? trim($_POST['country_residence']) : '';
    $role = isset($_POST['role']) ? trim($_POST['role']) : 'user'; // Default to 'user' if not set

    // Handle image upload
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($image_ext, $allowed_exts)) {
            $profile_image = 'uploads/' . uniqid() . '.' . $image_ext;
            move_uploaded_file($image_tmp, $profile_image);
        } else {
            $registration_error = "Invalid image format. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $registration_error = "Invalid email format.";
    } else {
        $check_sql = "SELECT id FROM users WHERE email = ? OR username = ?";
        $stmt = $pdo->prepare($check_sql);
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($pdo->errorInfo()[2]));
        }

        $stmt->execute([$email, $username]);

        if ($stmt->rowCount() > 0) {
            $registration_error = "Username or Email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $insert_sql = "INSERT INTO users (username, first_name, last_name, email, password, address, phone, education_level, age, sex, nationality, country_residence, role, profile_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($insert_sql);
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($pdo->errorInfo()[2]));
            }

            $stmt->execute([$username, $first_name, $last_name, $email, $hashed_password, $address, $phone, $education_level, $age, $sex, $nationality, $country_residence, $role, $profile_image]);

            if ($stmt->rowCount() > 0) {
                $registration_success = "Registration successful.";
            } else {
                $registration_error = "Registration failed. Please try again.";
                error_log("Registration error: " . htmlspecialchars($pdo->errorInfo()[2]));
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap-theme.min.css"> <!-- Ensure this path is correct -->
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <?php if (isset($registration_success)): ?>
            <div class="alert alert-success"><?php echo $registration_success; ?></div>
        <?php elseif (isset($registration_error)): ?>
            <div class="alert alert-danger"><?php echo $registration_error; ?></div>
        <?php endif; ?>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="first_name">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="last_name">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="address">Address</label>
                    <textarea class="form-control" id="address" name="address" required></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="role">Role</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="user">Student/User</option>
                        <option value="admin">Admin</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="education_level">Education Level</label>
                    <select class="form-control" id="education_level" name="education_level" required>
                        <option value="High School">High School</option>
                        <option value="Associate Degree">Associate Degree</option>
                        <option value="Bachelor's Degree">Bachelor's Degree</option>
                        <option value="Master's Degree">Master's Degree</option>
                        <option value="Doctorate">Doctorate</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="age">Age</label>
                    <input type="number" class="form-control" id="age" name="age" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="sex">Sex</label>
                    <select class="form-control" id="sex" name="sex" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nationality">Nationality</label>
                    <select class="form-control" id="nationality" name="nationality" required>
                        <option value="">Select your nationality</option>
                        <?php
                        $countries = [
                            "United States", "Canada", "United Kingdom", "Australia", "India", "Germany", "France", "Italy", "Spain", "Brazil",
                            "Mexico", "China", "Japan", "Russia", "South Africa", "Nigeria", "Eritrea", "Ethiopia", "Egypt", "Kenya", "Argentina", "Colombia"
                        ];
                        foreach ($countries as $country): ?>
                            <option value="<?php echo $country; ?>"><?php echo $country; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="country_residence">Country of Residence</label>
                    <select class="form-control" id="country_residence" name="country_residence" required>
                        <option value="">Select your country of residence</option>
                        <?php
                        foreach ($countries as $country): ?>
                            <option value="<?php echo $country; ?>"><?php echo $country; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="image">Profile Image</label>
                    <input type="file" class="form-control-file" id="image" name="image" accept=".jpg,.jpeg,.png,.gif">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block" name="register">Register</button>
        </form>
        <div class="text-center mt-3">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php
include('footer.php');
?>
