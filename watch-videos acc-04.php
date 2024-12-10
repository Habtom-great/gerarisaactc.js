<?php
session_start();
include('db.php');
include('header.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch courses from the database
$sql = "SELECT * FROM courses";
$result = $conn->query($sql);

if ($result === false) {
    die('Error: ' . htmlspecialchars($conn->error));
}

$courses = [];
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
      <h3 class="name">Habtom Araya-ACCA-BBBB</h3>
      <p class="role">tutor</p>
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
         <video src="assets/Acc-videos/udmy.mp4" controls poster="images/Accounting Image.png" id="video"></video>
      </div>
      <h3 class="title">Accounting tutorial (part 04) Using php</h3>
      <div class="info">
         <p class="date"><i class="fas fa-calendar"></i><span>22-10-2023</span></p>
         <p class="date"><i class="fas fa-heart"></i><span>44 likes</span></p>
      </div>
      <div class="tutor">
         <img src="assets/images_courses/Accounting Image.png" alt="">
         <div>
            <h3>xxxxx</h3>
            <span>Peach tree tutorial Acjjjjjj</span>
         </div>
      </div>
      <form action="" method="post" class="flex">
         <a href="playlist.html" class="inline-btn">view playlist</a>
         <button><i class="far fa-heart"></i><span>like</span></button>
      </form>
      <p class="description">
         <!-- The video description or notes section can be hidden initially -->
         <button onclick="toggleNotes()" class="inline-btn">View Notes</button>
         <div id="notes" style="display:none;">
        <h3> Accounting Is Important: Reasons Why Accounting Is Important
Accounting involves preparing and analyzing financial reports, taxes, and other reports. It’s similar to bookkeeping. You can find bookkeepers in smaller businesses while larger organizations usually use accountants. Accountants have certain credentials and experience that bookkeepers often lack. Accountants typically deal with more complex systems, but both bookkeepers and accountants serve an essential role in an organization. Why does accounting matter so much? Here are some reasons:

	Every industry needs an accounting system
	Accounting keeps a business organized
	Accounting helps evaluate a business’ performance                   I
	Accounting helps you stay within the law
	Accounting helps with budgeting and future projections
	Good accounting helps you avoid audits
	Accountants know how to deal with complex taxes or large amounts of money
	Accounting improves an organization’s decision-making

Accounting graduates will be professionally competent in the following areas:
•	Preparing financial statements in accordance with appropriate standards.
•	Interpreting the business implications of financial statement information. 
•	Preparing accounting information for planning and control and for the evaluation of products, projects and divisions.
•	Judging product, project, divisional and organizational performance using managerial accounting information.  
•	Preparing business and individual tax returns in accordance with regulations of the appropriate authorities. 
•	Applying auditing concepts to evaluate the conformity of financial statements with appropriate auditing standards. 
               

 Graduates will /should have professional values:
Fundamental Principles of Ethical Behavior: 
•	Integrity – to be straightforward and honest in all professional and business relationships. 
•	Objectivity – not to compromise professional or business judgements because of bias, conflict of interest or undue influence of others. 
•	Professional Competence and Due Care – to attain and maintain professional knowledge and skill - current technical and professional standards and relevant legislation
•	Confidentiality – to respect the confidentiality of information acquired as a result of professional and business relationships.
•	Professional Behavior – to comply with relevant laws and regulations.  </h3>                                                 

          
            <!-- The rest of the notes go here -->
         </div>
      </p>
   </div>
</section>

<section class="comments">
   <h1 class="heading">5 comments</h1>

   <form action="" class="add-comment">
      <h3>add comments</h3>
      <textarea name="comment_box" placeholder="enter your comment" required maxlength="1000" cols="30" rows="10"></textarea>
      <input type="submit" value="add comment" class="inline-btn" name="add_comment">
   </form>

   <h1 class="heading">user comments</h1>

   <div class="box-container">
      <div class="box">
         <div class="user">
            <img src="images/pic-1.jpg" alt="">
            <div>
               <h3>xxxxx</h3>
               <span>22-10-2023</span>
            </div>
         </div>
         <div class="comment-box">this is a comment form shaikh anas</div>
         <form action="" class="flex-btn">
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
   
</body>
</html>

<?php
include('footer.php');
?>

