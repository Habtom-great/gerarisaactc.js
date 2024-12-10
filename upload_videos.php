<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'] ?? '';
    $video_date = $_POST['video_date'] ?? '';
    $tutor_name = $_POST['tutor_name'] ?? '';
    $tutor_description = $_POST['tutor_description'] ?? '';
    $description = $_POST['description'] ?? '';
    $video_file = $_FILES['video_file'] ?? null;
    $poster_file = $_FILES['poster_file'] ?? null;

    // Ensure the 'uploads' directory exists and is writable
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // File upload paths
    $video_path = $upload_dir . basename($video_file['name']);
    $poster_path = $upload_dir . basename($poster_file['name']);

    // Move uploaded files
    if (move_uploaded_file($video_file['tmp_name'], $video_path) && move_uploaded_file($poster_file['tmp_name'], $poster_path)) {
        try {
            // Database connection
            $conn = new PDO("mysql:host=localhost;dbname=accounting_course", "root", "");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Insert video details into database
            $stmt = $conn->prepare("INSERT INTO videos (title, video_date, tutor_name, tutor_description, description, video_path, poster_path) VALUES (:title, :video_date, :tutor_name, :tutor_description, :description, :video_path, :poster_path)");
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':video_date', $video_date);
            $stmt->bindParam(':tutor_name', $tutor_name);
            $stmt->bindParam(':tutor_description', $tutor_description);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':video_path', $video_path);
            $stmt->bindParam(':poster_path', $poster_path);

            if ($stmt->execute()) {
                echo "Video and details were successfully uploaded.";
            } else {
                echo "There was an error uploading the video details.";
            }
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        }
    } else {
        echo "There was an error uploading the video files.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Videos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin-top: 50px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Upload Video</h2>
        <form enctype="multipart/form-data" action="" method="post">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="video_date">Video Date</label>
                <input type="date" class="form-control" id="video_date" name="video_date" required>
            </div>
            <div class="form-group">
                <label for="tutor_name">Tutor Name</label>
                <input type="text" class="form-control" id="tutor_name" name="tutor_name" required>
            </div>
            <div class="form-group">
                <label for="tutor_description">Tutor Description</label>
                <textarea class="form-control" id="tutor_description" name="tutor_description" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="video_file">Video File</label>
                <input type="file" class="form-control-file" id="video_file" name="video_file" required>
            </div>
            <div class="form-group">
                <label for="poster_file">Poster File</label>
                <input type="file" class="form-control-file" id="poster_file" name="poster_file" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload Video</button>
        </form>
    </div>
</body>
</html>


kkkkkkkkkkkkk

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'] ?? '';
    $video_date = $_POST['video_date'] ?? '';
    $tutor_name = $_POST['tutor_name'] ?? '';
    $tutor_description = $_POST['tutor_description'] ?? '';
    $description = $_POST['description'] ?? '';
    $video_file = $_FILES['video_file'] ?? null;
    $poster_file = $_FILES['poster_file'] ?? null;

    // File upload paths
    $video_path = 'uploads/' . basename($video_file['name']);
    $poster_path = 'uploads/' . basename($poster_file['name']);

    // Move uploaded files
    if (move_uploaded_file($video_file['tmp_name'], $video_path) && move_uploaded_file($poster_file['tmp_name'], $poster_path)) {
        try {
            // Database connection
            $conn = new PDO("mysql:host=localhost;dbname=accounting_course", "root", "");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Insert video details into database
            $stmt = $conn->prepare("INSERT INTO videos (title, video_date, tutor_name, tutor_description, description, video_path, poster_path) VALUES (:title, :video_date, :tutor_name, :tutor_description, :description, :video_path, :poster_path)");
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':video_date', $video_date);
            $stmt->bindParam(':tutor_name', $tutor_name);
            $stmt->bindParam(':tutor_description', $tutor_description);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':video_path', $video_path);
            $stmt->bindParam(':poster_path', $poster_path);

            if ($stmt->execute()) {
                echo "Video and details were successfully uploaded.";
            } else {
                echo "There was an error uploading the video details.";
            }
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        }
    } else {
        echo "There was an error uploading the video files.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Videos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin-top: 50px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Upload Video</h2>
        <form enctype="multipart/form-data" action="" method="post">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="video_date">Video Date</label>
                <input type="date" class="form-control" id="video_date" name="video_date" required>
            </div>
            <div class="form-group">
                <label for="tutor_name">Tutor Name</label>
                <input type="text" class="form-control" id="tutor_name" name="tutor_name" required>
            </div>
            <div class="form-group">
                <label for="tutor_description">Tutor Description</label>
                <textarea class="form-control" id="tutor_description" name="tutor_description" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="video_file">Video File</label>
                <input type="file" class="form-control-file" id="video_file" name="video_file" required>
            </div>
            <div class="form-group">
                <label for="poster_file">Poster File</label>
                <input type="file" class="form-control-file" id="poster_file" name="poster_file" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload Video</button>
        </form>
    </div>
</body>
</html>
