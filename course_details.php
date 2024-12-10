<?php
session_start();
include('db.php'); // Database connection
include('header.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "accounting_course";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection



// Fetch Course ID
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 1;

// SQL query to fetch course details
$course_sql = "SELECT * FROM courses WHERE course_id = $course_id";
$course_result = $conn->query($course_sql);

// Check if the course query was successful
if ($course_result === false) {
    echo "Error in SQL query: " . $conn->error;
    exit();
}

// Check if the course exists
if ($course_result->num_rows > 0) {
    $course = $course_result->fetch_assoc();
    $videos = explode(',', $course['course_videos']);
    $subtitles = explode(',', $course['course_subtitle']);
} else {
    echo "No course found for the given ID.";
    exit();
}

// Fetch comments
$comments_sql = "SELECT * FROM comments WHERE course_id = $course_id ORDER BY created_at DESC";
$comments_result = $conn->query($comments_sql);

// Check if the comments query was successful
if ($comments_result === false) {
    echo "Error in comments SQL query: " . $conn->error;
    exit();
}

// Check if comments exist
$comments = [];
if ($comments_result->num_rows > 0) {
    while ($comment = $comments_result->fetch_assoc()) {
        $comments[] = $comment;
    }
} else {
    echo "No comments found for the given course.";
}

// Fetch likes and dislikes
$likes_sql = "SELECT COUNT(*) AS likes FROM course_likes WHERE course_id = $course_id AND reaction = 'like'";
$dislikes_sql = "SELECT COUNT(*) AS dislikes FROM course_likes WHERE course_id = $course_id AND reaction = 'dislike'";

$likes_result = $conn->query($likes_sql);
$dislikes_result = $conn->query($dislikes_sql);

// Check if the likes and dislikes queries were successful
if ($likes_result === false) {
    echo "Error in likes SQL query: " . $conn->error;
    exit();
}

if ($dislikes_result === false) {
    echo "Error in dislikes SQL query: " . $conn->error;
    exit();
}

$likes_count = $likes_result->fetch_assoc()['likes'];
$dislikes_count = $dislikes_result->fetch_assoc()['dislikes'];

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['course_title']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header, .footer {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
        }
        .tutor-info {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f8f9fa;
        }

        .tutor-info img {
            border-radius: 50%;
            width: 80px; /* Adjust size */
            height: 80px;
            margin-right: 15px;
            border: 3px solid #007BFF; /* Border around image */
        }

        .tutor-info h5 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .tutor-info p {
            margin: 0;
            color: #555;
            font-size: 14px;
        }
        .subtitles {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 10px;
            max-height: 500px;
            overflow-y: auto;
        }
        .subtitles .subtitle-item {
            cursor: pointer;
            padding: 15px;
            border-bottom: 1px solid #ddd;
            display: flex;
            align-items: center;
            transition: background-color 0.3s ease;
        }
        .subtitles .subtitle-item:hover {
            background-color: #f0f0f0;
        }
        .subtitles .subtitle-item.active {
            background-color: #007BFF;
            color: white;
        }
        .video-container {
            margin-bottom: 20px;
        }
        .course-note {
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 10px;
            max-height: 200px;
            overflow-y: auto;
            font-size: 14px;
            border: 1px solid #ddd;
            display: none;
        }
        .comments-section {
            margin-top: 20px;
        }
        .comment-item {
            margin-bottom: 10px;
            font-size: 14px;
        }
        .toggle-note {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
        }
        textarea {
            resize: none;
        }
        .like-dislike-buttons {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="header">
    <h1><?php echo htmlspecialchars($course['course_title']); ?></h1>
</div>
<div class="tutor-info">
    <img src="<?php echo htmlspecialchars($course['tutor_image']) ?: 'default-tutor.jpg'; ?>" alt="Tutor">
    <div>
        <h5><?php echo htmlspecialchars($course['tutor_name']) ?: 'Unknown Instructor'; ?></h5>
        <p><?php echo htmlspecialchars($course['course_title']) ?: 'Course Title'; ?></p>
    </div>
</div>
<div class="container my-5">
    <div class="row">
        <!-- Subtitles Sidebar -->
        <div class="col-md-3">
            <h5>Course Subtitles</h5>
            <ul class="list-group subtitles">
                <?php foreach ($subtitles as $index => $subtitle): ?>
                    <li class="list-group-item subtitle-item" id="subtitle-<?php echo $index; ?>" data-video-index="<?php echo $index; ?>">
                        🎥 <?php echo htmlspecialchars($subtitle); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Video Player -->
        <div class="col-md-9">
            <div class="video-container">
                <div class="ratio ratio-16x9">
                    <video id="mainVideo" controls>
                        <source src="<?php echo htmlspecialchars(trim($videos[0])) ?: 'default-video.mp4'; ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>

            <!-- Course Note -->
            <div class="course-note mt-4" id="course-note">
                <h5>Course Note</h5>
                <p><?php echo nl2br(htmlspecialchars($course['course_note'])); ?></p>
            </div>

            <!-- Toggle Course Note Button -->
            <button class="btn btn-info toggle-note" id="toggle-note-btn">Show/Hide Course Note</button>

            <!-- Like/Dislike Buttons -->
            <div class="like-dislike-buttons">
                <button class="btn btn-success" id="like-btn">Like (<?php echo $likes_count; ?>)</button>
                <button class="btn btn-danger" id="dislike-btn">Dislike (<?php echo $dislikes_count; ?>)</button>
            </div>

            <!-- Comments Section -->
            <div class="comments-section mt-4">
                <h5>Comments</h5>
                <div class="comments-container">
                    <?php if (count($comments) > 0): ?>
                        <ul class="list-group">
                            <?php foreach ($comments as $comment): ?>
                                <li class="list-group-item comment-item">
                                    <strong><?php echo htmlspecialchars($comment['user_name']); ?></strong>: 
                                    <?php echo htmlspecialchars($comment['comment_text']); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>No comments yet.</p>
                    <?php endif; ?>
                </div>
                <form action="comments.php" method="POST" class="mt-3">
                    <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                    <div class="mb-3">
                        <textarea name="comment_text" class="form-control" placeholder="Add a comment..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="footer">
    <p>&copy; 2024 Online Accounting Course</p>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const videoPlayer = document.getElementById('mainVideo');
        const subtitleItems = document.querySelectorAll('.subtitle-item');
        const courseNote = document.getElementById('course-note');
        const toggleNoteBtn = document.getElementById('toggle-note-btn');

        // Highlight subtitle and switch videos
        subtitleItems.forEach((item, index) => {
            item.addEventListener('click', () => {
                subtitleItems.forEach(sub => sub.classList.remove('active'));
                item.classList.add('active');
                videoPlayer.src = "<?php echo htmlspecialchars(trim($videos[0])) ?: 'default-video.mp4'; ?>";
                videoPlayer.play();
            });
        });

        // Toggle course note visibility
        toggleNoteBtn.addEventListener('click', () => {
            if (courseNote.style.display === 'none') {
                courseNote.style.display = 'block';
            } else {
                courseNote.style.display = 'none';
            }
        });

        // Like and Dislike Buttons
        const likeBtn = document.getElementById('like-btn');
        const dislikeBtn = document.getElementById('dislike-btn');

        likeBtn.addEventListener('click', () => {
            alert('Liked!');
        });

        dislikeBtn.addEventListener('click', () => {
            alert('Disliked!');
        });
    });
</script>

</body>
</html>
