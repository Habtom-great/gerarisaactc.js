<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'accounting_course';
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get video details securely
$video_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM videos WHERE video_id = ?");
$stmt->bind_param("i", $video_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if video is found
if ($result && $result->num_rows > 0) {
    $video = $result->fetch_assoc();
} else {
    die("Video not found.");
}

// Close the statement
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($video['title']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container my-5">
    <h1 class="text-center text-primary mb-4"><?= htmlspecialchars($video['title']); ?></h1>
    
    <?php if (file_exists("uploads/" . htmlspecialchars($video['file_name']))): ?>
        <video controls class="d-block mx-auto mb-3" style="max-width: 100%; height: auto;">
            <source src="uploads/<?= htmlspecialchars($video['file_name']); ?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    <?php else: ?>
        <p class="text-danger text-center">Video file not found.</p>
    <?php endif; ?>

    <p class="text-secondary text-center"><?= htmlspecialchars($video['course_description']); ?></p>
    <a href="upload_videos.php" class="btn btn-secondary d-block mx-auto">Back to Videos</a>
</div>
</body>
</html>
