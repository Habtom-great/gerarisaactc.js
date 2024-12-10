
<?php

include('header.php');
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include('db.php');
include('header.php');

if (!isset($_GET['id'])) {
    die('Video ID not specified.');
}

$video_id = intval($_GET['id']);
$query = "SELECT * FROM videos WHERE id = ?";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt->bind_param("i", $video_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result === false) {
    die('Execute failed: ' . htmlspecialchars($stmt->error));
}
$video = $result->fetch_assoc();
if ($video === null) {
    die('Video not found.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($video['title']); ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .video-container {
            position: relative;
            padding-top: 56.25%; /* 16:9 Aspect Ratio */
        }
        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .note-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><?php echo htmlspecialchars($video['title']); ?></h2>
        <div class="row">
            <div class="col-md-8">
                <div class="video-container">
                    <iframe src="<?php echo htmlspecialchars($video['video_url']); ?>" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
            <div class="col-md-4">
                <div class="note-container">
                    <h4>Notes</h4>
                    <p><?php echo nl2br(htmlspecialchars($video['note'])); ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
include('footer.php');
?>

kkkkkkkk
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include('db.php');
include('header.php');

$video_id = intval($_GET['id']);
$query = "SELECT * FROM videos WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $video_id);
$stmt->execute();
$result = $stmt->get_result();
$video = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $video['title']; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .video-container {
            position: relative;
            padding-top: 56.25%; /* 16:9 Aspect Ratio */
        }
        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .note-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><?php echo $video['title']; ?></h2>
        <div class="row">
            <div class="col-md-8">
                <div class="video-container">
                    <iframe src="<?php echo $video['video_url']; ?>" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
            <div class="col-md-4">
                <div class="note-container">
                    <h4>Notes</h4>
                    <p><?php echo nl2br($video['note']); ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
include('footer.php');
?>
