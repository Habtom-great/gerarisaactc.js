
<?php
session_start();
include('db.php');
include('header.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_SESSION['user_name'])) {
    $_SESSION['user_name'] = "Default Name"; // Temporary fix; ensure this is set correctly during login
}

// Fetch courses from the database
$sql = "SELECT * FROM courses";
$result = $conn->query($sql);

if ($result === false) {
    die('Error: ' . htmlspecialchars($conn->error));
}

$courses = [];
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $courses[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Watch Video</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="side-bar">
   <div id="close-btn">
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">
      <img src="images_courses/" class="image" alt="">
      <h3 class="name">Habtom Araya-ACCA</h3>
      <p class="role">Tutor</p>
      <a href="profile.html" class="btn">view profile</a>
   </div>

   <nav class="navbar">
      <a href="home.php"><i class="fas fa-home"></i><span>home</span></a>
      <a href="about.php"><i class="fas fa-question"></i><span>about</span></a>
      <a href="courses.php"><i class="fas fa-graduation-cap"></i><span>courses</span></a>
      <a href="teacher_profile.html"><i class="fas fa-chalkboard-user"></i><span>teachers</span></a>
      <a href="contact.php"><i class="fas fa-headset"></i><span>contact us</span></a>
   </nav>
</div>

<section class="watch-video">
   <div class="video-container">
      <div class="video">
         <video src="assets/images_courses/Peach Tree/20240326_170520.mp4" controls poster=" id="video"></video>
      </div>
      <h3 class="title">peach tree tutorial (part 02)</h3>
      <div class="info">
         <p class="date"><i class="fas fa-calendar"></i><span>22-10-2023</span></p>
         <p class="date"><i class="fas fa-heart"></i><span>44 likes</span></p>
      </div>

      <form action="" method="post" class="flex">
         <a href="playlist.html" class="inline-btn">view playlist</a>
         <button><i class="far fa-heart"></i><span>like</span></button>
      </form>
<p class="description">
   <!-- The video description or notes section can be hidden initially -->
   <button onclick="toggleNotes()" class="inline-btn">View Notes</button>
   <div id="notes" style="display:none;">
  <h3> 
Students’ requirements:
You will need no background in any of these accounting areas to benefit from this course. This course is designed and prepared for 11th grade complete, university and/or college students and graduates, business and non-business managers. Whether you are professional person looking to develop new skills, or a person looking to start a new career in accounting or finance, or auditor and decision maker; this course will equip you with what you need to get started in financial basics skill full.

Training resources (Training Materials):
This course is taught in classrooms and online, in English, Tigrinya and Amharic Languages. To get the most from this course, you will be given handouts, short notes, practice exercises, sample video and quiz in English language. And there will be midterm and final exam to complete the course in English language. During and after completion of the course, there will be discussion forums to connect with your peers created on whatsup and telegram.

Time frame:
Over all of this principle of accounting course, it took four months to complete for both the theoretical and software peach tree courses. It is scheduled two days per week which is two hours period per day. There will be crash courses that could be able to completed the course within one month and two weeks.

Future plan:
In the near future it is planned to start training on:
•	Financial accounting, financial management, cost accounting, and auditing.
•	Tally and quick book accounting software
•	Basic electricity and electronics that gives skill on maintaining electrical and electronic equipments, mobiles(cellphones), electrical installation and so on. This will go up to maintaining electrical motors and controls of manufacturing, electrical motors windings, etc.
•	Information Technology: programming, Database management, web development etc.

Congratulations on beginning of this exciting journey.
Good luck!                                                                                         .

</h3>                                                    
<!-- The rest of the notes go here -->
</div>
</p>
</div>
</section>

<section class="comments">
   <h1 class="heading">comments</h1>

   <form action="" class="add-comment" method="post">
      <h3>Your comments</h3>
      <textarea name="comment_box" placeholder="enter your comment" required maxlength="1000" cols="30" rows="10"></textarea>
      <input type="submit" value="add comment" class="inline-btn" name="add_comment">
   </form>

   <h1 class="heading">user comments</h1>
   
   <div class="box-container">
      <div class="box">
         <div class="user">
            <img src="images/pic-1.jpg" alt="">
            <div>
               <h3>name </h3>
               <span>22-10-2023</span>
            </div>
         </div>
         <div class="comment-box">this is a comment form Mr. Desbele</div>
         <form action="" class="flex-btn" method="post">
            <input type="submit" value="edit comment" name="edit_comment" class="inline-option-btn">
            <input type="submit" value="delete comment" name="delete_comment" class="inline-delete-btn">
         </form>
      </div>

      <!-- Additional comment boxes here -->

   </div>
</section>

<footer class="footer">
   &copy; copyright @ 2023 by <span>Habtom Araya-ACCA Web designer</span> | all rights reserved!
</footer>

<!-- custom js file link  -->
<script src="js/script.js"></script>
<script>
function toggleNotes() {
    var notes = document.getElementById("notes");
    if (notes.style.display === "none") {
        notes.style.display = "block";
    } else {
        notes.style.display = "none";
    }
}
</script>

<?php
if (isset($_POST['add_comment'])) {
    $student_name = $_SESSION['user_name']; // Assuming you have the user's name stored in the session
    $comment = $_POST['comment_box'];
    $playlist_id = 1; // Replace with actual playlist ID if needed

    try {
        $stmt = $conn->prepare("INSERT INTO comments (playlist_id, student_name, comments) VALUES (:playlist_id, :student_name, :comments)");
        $stmt->bindParam(':playlist_id', $playlist_id);
        $stmt->bindParam(':student_name', $student_name);
        $stmt->bindParam(':comments', $comments);
        $stmt->execute();
        echo "Comment submitted successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<section class="comments-display">
    <h2>Comments</h2>
    <?php
    $stmtComments = $conn->prepare("SELECT * FROM comments WHERE playlist_id = :playlist_id ORDER BY created_at DESC");
    $stmtComments->bindParam(':playlist_id', $playlist_id);
    $stmtComments->execute();
    $comments = $stmtComments->fetchAll(PDO::FETCH_ASSOC);

    foreach ($comments as $comments) {
        echo "<div class='comments'>";
        echo "<h3>" . htmlspecialchars($comments['student_name']) . "</h3>";
        echo "<p>" . htmlspecialchars($comments['comments']) . "</p>";
        echo "<span>" . htmlspecialchars($comments['created_at']) . "</span>";
        echo "</div>";
    }
    ?>
</section>

</body>
</html>

<?php
include('footer.php');
?>

<?php
session_start();
include('db.php');
include('header.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_SESSION['user_name'])) {
    $_SESSION['user_name'] = "Default Name"; // Temporary fix; ensure this is set correctly during login
}

$video_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($video_id <= 0) {
    echo "Invalid video ID.";
    exit;
}

// Fetch video details from the database
$stmt = $conn->prepare("SELECT * FROM videos WHERE id = :video_id");
$stmt->bindParam(':video_id', $video_id, PDO::PARAM_INT);
$stmt->execute();

$video = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$video) {
    echo "Invalid video ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Watch Video</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="side-bar">
   <div id="close-btn">
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">
      <img src="images/pic-1.jpg" class="image" alt="">
      <h3 class="name">Habtom Araya-ACCA</h3>
      <p class="role">Tutor</p>
      <a href="profile.html" class="btn">view profile</a>
   </div>

   <nav class="navbar">
      <a href="home.php"><i class="fas fa-home"></i><span>home</span></a>
      <a href="about.php"><i class="fas fa-question"></i><span>about</span></a>
      <a href="courses.php"><i class="fas fa-graduation-cap"></i><span>courses</span></a>
      <a href="teacher_profile.html"><i class="fas fa-chalkboard-user"></i><span>teachers</span></a>
      <a href="contact.php"><i class="fas fa-headset"></i><span>contact us</span></a>
   </nav>
</div>

<section class="watch-video">
   <div class="video-container">
      <div class="video">
         <video src="<?php echo htmlspecialchars($video['video_path']); ?>" controls poster="assets/images_courses/Peach Tree/Peachtree-logo.jpg" id="video"></video>
      </div>
      <h3 class="title"><?php echo htmlspecialchars($video['title']); ?></h3>
      <div class="info">
         <p class="date"><i class="fas fa-calendar"></i><span><?php echo htmlspecialchars($video['date']); ?></span></p>
         <p class="date"><i class="fas fa-heart"></i><span>44 likes</span></p>
      </div>

      <form action="" method="post" class="flex">
         <a href="playlist.html" class="inline-btn">view playlist</a>
         <button><i class="far fa-heart"></i><span>like</span></button>
      </form>
<p class="description">
   <!-- The video description or notes section can be hidden initially -->
   <button onclick="toggleNotes()" class="inline-btn">View Notes</button>
   <div id="notes" style="display:none;">
  <h3> 
Students’ requirements:
You will need no background in any of these accounting areas to benefit from this course. This course is designed and prepared for 11th grade complete, university and/or college students and graduates, business and non-business managers. Whether you are professional person looking to develop new skills, or a person looking to start a new career in accounting or finance, or auditor and decision maker; this course will equip you with what you need to get started in financial basics skill full.

Training resources (Training Materials):
This course is taught in classrooms and online, in English, Tigrinya and Amharic Languages. To get the most from this course, you will be given handouts, short notes, practice exercises, sample video and quiz in English language. And there will be midterm and final exam to complete the course in English language. During and after completion of the course, there will be discussion forums to connect with your peers created on whatsup and telegram.

Time frame:
Over all of this principle of accounting course, it took four months to complete for both the theoretical and software peach tree courses. It is scheduled two days per week which is two hours period per day. There will be crash courses that could be able to completed the course within one month and two weeks.

Future plan:
In the near future it is planned to start training on:
•	Financial accounting, financial management, cost accounting, and auditing.
•	Tally and quick book accounting software
•	Basic electricity and electronics that gives skill on maintaining electrical and electronic equipments, mobiles(cellphones), electrical installation and so on. This will go up to maintaining electrical motors and controls of manufacturing, electrical motors windings, etc.
•	Information Technology: programming, Database management, web development etc.

Congratulations on beginning of this exciting journey.
Good luck!                                                                                         .

</h3>                                                    
<!-- The rest of the notes go here -->
</div>
</p>
</div>
</section>

<section class="comments">
   <h1 class="heading">comments</h1>

   <form action="" class="add-comment" method="post">
      <h3>Your comments</h3>
      <textarea name="comment_box" placeholder="enter your comment" required maxlength="1000" cols="30" rows="10"></textarea>
      <input type="submit" value="add comment" class="inline-btn" name="add_comment">
   </form>

   <h1 class="heading">user comments</h1>
   
   <div class="box-container">
      <div class="box">
         <div class="user">
            <img src="images/pic-1.jpg" alt="">
            <div>
               <h3>name </h3>
               <span>22-10-2023</span>
            </div>
         </div>
         <div class="comment-box">this is a comment form Mr. Desbele</div>
         <form action="" class="flex-btn" method="post">
            <input type="submit" value="edit comment" name="edit_comment" class="inline-option-btn">
            <input type="submit" value="delete comment" name="delete_comment" class="inline-delete-btn">
         </form>
      </div>

      <!-- Additional comment boxes here -->

   </div>
</section>

<footer class="footer">
   &copy; copyright @ 2023 by <span>Habtom Araya-ACCA Web designer</span> | all rights reserved!
</footer>

<!-- custom js file link  -->
<script src="js/script.js"></script>
<script>
function toggleNotes() {
    var notes = document.getElementById("notes");
    if (notes.style.display === "none") {
        notes.style.display = "block";
    } else {
        notes.style.display = "none";
    }
}
</script>

<?php
if (isset($_POST['add_comment'])) {
    $student_name = $_SESSION['user_name']; // Assuming you have the user's name stored in the session
    $comment = $_POST['comment_box'];
    $playlist_id = 1; // Replace with actual playlist ID if needed

    try {
        $stmt = $conn->prepare("INSERT INTO comments (playlist_id, student_name, comments) VALUES (:playlist_id, :student_name, :comments)");
        $stmt->bindParam(':playlist_id', $playlist_id);
        $stmt->bindParam(':student_name', $student_name);
        $stmt->bindParam(':comments', $comments);
        $stmt->execute();
        echo "Comment submitted successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

