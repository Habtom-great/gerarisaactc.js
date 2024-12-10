
<?php
// Include database connection and header
session_start();
include('db.php'); // Ensure this file sets up $pdo

// Check if the session user_name is set, otherwise assign a default value
if (!isset($_SESSION['user_name'])) {
    $_SESSION['user_name'] = "Default Name"; // Temporary fix; ensure this is set correctly during login
}

// Get the video ID from the URL, fallback to 0 if not present
$video_id = isset($_GET['video_id']) ? intval($_GET['video_id']) : 0;

// Prepare and execute query to fetch course details
$sql = "SELECT * FROM courses WHERE video_id = :video_id"; 
$stmt = $conn->prepare($sql);
$stmt->bindParam(':video_id', $video_id, PDO::PARAM_INT);
if ($stmt->execute()) {
    $course = $stmt->fetch();
} else {
    print_r($stmt->errorInfo());
}

// Check if course exists
if (!$course) {
    die('Course not found');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Watch Video</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
   <link rel="stylesheet" href="assets/css/style.css">
   <style>
      .notes { display: none; margin-top: 20px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
      .highlight { background-color: yellow; }
      .controls { margin-top: 15px; display: flex; justify-content: space-between; align-items: center; }
      .controls select, .controls button { padding: 5px 10px; }
      .video-container { text-align: center; }
      .notes h3 { cursor: pointer; }
   </style>
</head>
<body>

<!-- Sidebar -->
<div class="side-bar">
   <div id="close-btn"><i class="fas fa-times"></i></div>
   <div class="profile">
      <img src="assets/tutor/Professor Marc Badia.jpeg" class="image" alt="Profile Image">
      <h3 class="name">Prof. Marc Badia</h3>
      <p class="role">Tutor</p>
      <a href="profile.html" class="btn">View Profile</a>
   </div>
   <nav class="navbar">
      <a href="home.php"><i class="fas fa-home"></i><span>Home</span></a>
      <a href="about.php"><i class="fas fa-question"></i><span>About</span></a>
      <a href="courses.php"><i class="fas fa-graduation-cap"></i><span>Courses</span></a>
      <a href="teacher_profile.html"><i class="fas fa-chalkboard-user"></i><span>Teachers</span></a>
      <a href="contact.php"><i class="fas fa-headset"></i><span>Contact Us</span></a>
   </nav>
</div>

<!-- Main Content Section -->
<section class="watch-video">
   <div class="video-container">
      <div class="video">
         <video id="video" src="assets/Acc-Videos/Accounting Introduction .mp4" controls poster="images/Accounting Image.png"></video>
      </div>
      <h3 class="title"><?php echo htmlspecialchars($course['course_title']); ?></h3>
      
      <div class="tutor image/ course logo">
         <img src="assets/images_courses/Accounting Image.png" alt="Tutor Image">
         <div>
        
            <span><?php echo htmlspecialchars($course['description']); ?></span>
         </div>
      </div>
         <p class="description">
   <!-- The video description or notes section can be hidden initially -->
   <button onclick="toggleNotes()" class="inline-btn">View Notes</button>
   <div id="notes" class="notes">
      <!-- Course notes go here -->
      <h3 data-start="0" data-end="10">In this second video, I'm going to introduce you to a very special person, a friend of mine who started her own business.</h3>
      <h3 data-start="10" data-end="20">Christina loved reading books and recommending them to her friends. So she started a bookstore here on campus.</h3>
      <h3 data-start="20" data-end="30">So if you come with me, we're going to talk with her and she will explain to us what transactions took place in the business in this first month of preparation, so that we can help her with her accounting and learn by doing. Come with me.</h3>
      <h3 data-start="30" data-end="40">Hello, how are you? Nice to see you. Great to see you, Mom. It's great. I see that. You're coming. Finally, you're drinking true, right? Yeah. I'm really excited. I'm really excited. It's a new year, new business. When are you studying? Open in two days. Are you studying? Yeah, in two days, I'm opening.</h3>
      <h3 data-start="40" data-end="50">You see, I have everything all set up. So everything looks like writing. Everything all set up is different than numbers. The numbers. And a little bit nervous and overwhelmed about how to get started. You mean the accounting? That's right, me. Yeah, the accounting.</h3>
      <h3 data-start="50" data-end="60">Okay, so then when we go outside, we can talk about accounting. Oh, I'd love to. That'll be great. Because I came here with my students from Coursera and I'm sure that we can help you. Little me with your accounting. Okay, I hope so. Great. Awesome. I'll bring them to the numbers. Okay, let's go. Great. Thanks.</h3>
      <h3 data-start="60" data-end="70">Okay, Christina. So I guess we could start by making a list of all the transactions that have taken place this past month in your business since you started.</h3>
      <h3 data-start="70" data-end="80">Okay, great. I can take note of this. Okay, so I started the bookstore with 50,000 euros. That was 35,000 was mine and 15,000 was my uncle.</h3>
      <h3 data-start="80" data-end="90">Okay, I also took out a loan. 20,000 euro loan. Okay. It's a three-year loan. Okay, so you took a loan from a bank that you received when today? On December 31st. Okay, so you took a bank loan and you need to return this principle. You have received 20,000. You need to return it in three years. Exactly, in three years. Okay, very good.</h3>
            <h3 data-start="90" data-end="100">Bank loan, 20,000. What else? Let's see, I also had to buy furniture and equipment. Okay. And that cost me 25,000. Furniture and equipment. Yeah. This happened all over the last couple of days. Let's see, I paid 15,000 in cash. Okay. So this furniture and equipment is worth 25,000. It's worth 25,000. I paid 15,000 in cash.</h3>
            <h3 data-start="100" data-end="110">15,000 in cash. And the other 10,000 I have on credit. Okay, you have to repay the next month. Right. Okay, right. So finance, the other 10,000. Right, what else? Let me see, okay. So I also had to buy software, bookstore management software. And that I did pay in cash. That was 3,000. Okay, 3,000.</h3>
            <h3 data-start="110" data-end="120">Okay, 3,000 in cash. Everything paid in cash. Yes, the whole thing, the software was paid in cash completely. It's very good. All right, and then I had to buy all these books that I need to sell. And that was 40,000. 40,000 euros in books? Yes, 40,000 euros, believe it or not. I guess that textbooks are very expensive. Yeah.</h3>
            <h3 data-start="120" data-end="130">And that was on account. I didn't pay any cash. There's no cash. Okay, no cash. Right. So you have to repay this in what, a couple of months, maybe 60 days or? I think it's 60 days. 60 days, okay. Yeah, 60 days. Anyway, very good, okay. All right, and then finally I signed a lease on January 1st.</h3>
            <h3 data-start="130" data-end="140">Mm-hmm. And I paid the whole year's rent upfront. And that was 6,000. So this is the rent for the premises? Exactly. For the shop itself, that space. And you have already paid in advance the full year? 6,000, that's in cash. Okay. So you have the right to use these premises for one year? Exactly. Exactly.</h3>
            <h3 data-start="140" data-end="150">So I have until December 31st this coming year. Okay, very good. This year actually. Anything else? Let me see. I should put it all down here. No, I think that's it. Okay, so I think that with this lease we'll come back in a few hours and we can come back with the summary, you know, of all the accounting. Great.</h3>
            <h3 data-start="150" data-end="160">Let me stop here for a moment in our conversation with Christina. Let's think about the business cycle of any business. So whenever you have a business idea or a business plan, the first thing that you need is to raise capital. So we are at the financing stage. So we go to a family, friends and folks, for example, to get to raise new capital.</h3>
            <h3 data-start="160" data-end="170">In the case of Christina, she contributed capital herself. And also she got capital from her uncle. She also got capital from the banks, in this case in the form of a loan. So all these transactions take place in this financing stage where you're raising capital. Once you have the capital, the next thing is to start making investments so that's investing process, the investing stage, in which you want to purchase long-term resources that you need in order to start a business.</h3>
            <h3 data-start="170" data-end="180">So for example here, in the case of the campus bookstore, they purchase furniture and equipment. They purchase the software they need to manage the business. So these are investments at investing stage. And finally once everything is ready, actually in a couple of days here in the case of Christina, they're going to start operations. They're going to open up to the public.</h3>
            <h3 data-start="180" data-end="190">And so they're going to start selling the books. And hopefully if they sell these books at the price higher than the cost, they will make some profits. Part of these profits will help to reward the capital providers. So for example, in the case of the shareholders, they might receive dividends. In the case of the banks with the bank loan, you'll pay some interest.</h3>
            <h3 data-start="190" data-end="200">And whatever is left, you can reinvest it in the business. So actually it's a sort of self-financing of the business. So as you see, Christina's business, the bookstore so far is especially in the first two stages. Now in a couple of days we're going to start with the operating stage, opening up to the public. So accounting helps you control for all the transactions that take place in all these business cycles.</h3>
            <h3 data-start="200" data-end="210">And so let me go back to Christina. Let me ask her if she's got any other information that can help us. Has she done any sort of accounting? Let's see that. By the way, is there anything that you have kept track of? I mean, any sort of accounting, you know? Oh, I do. I do. I have all the receipts. I have the cash receipts and I have the receipts of all the payments that I've made.</h3>
            <h3 data-start="210" data-end="220">Okay, so you have a list with all the cash payments and cash receipts. Exactly. Okay, so I'll take that with me. Okay, all right, great. I'll give it to you. Very good.</h3>
      </h3>
      
      <!-- Add remaining notes here, similar to the provided notes -->
   </div>
</p>

<script>
   // Toggle the visibility of the notes
   function toggleNotes() {
      var notes = document.getElementById('notes');
      notes.style.display = (notes.style.display === 'block') ? 'none' : 'block';
   }

   // Synchronize the video with the notes, highlighting them based on video time
   var video = document.getElementById('video');
   var notes = document.querySelectorAll('#notes h3');

   video.ontimeupdate = function() {
      var currentTime = video.currentTime;
      notes.forEach(function(note) {
         var start = parseFloat(note.getAttribute('data-start'));
         var end = parseFloat(note.getAttribute('data-end'));
         if (currentTime >= start && currentTime <= end) {
            note.classList.add('highlight');  // Highlight active note
         } else {
            note.classList.remove('highlight');  // Remove highlight when not in range
         }
      });
   };

   // CSS to style highlighted notes
   var style = document.createElement('style');
   style.innerHTML = `
      .highlight {
         background-color: yellow;
         font-weight: bold;
      }
   `;
   document.head.appendChild(style);
</script>

            
</section>

<!-- Comments Section -->
<section class="comments">
   <form action="" class="add-comment" method="post">
      <h3>Your Comment</h3>
      <textarea name="comment_box" placeholder="Enter your comment" required maxlength="1000" cols="30" rows="10"></textarea>
      <input type="submit" value="Add Comment" class="inline-btn" name="add_comment">
   </form>
      <!-- Display Comments -->
      <?php
      $comments_sql = "SELECT * FROM comments WHERE video_id = ?";
      $stmt = $conn->prepare($comments_sql);
      $stmt->execute([$video_id]); // Bind video ID
      $comments = $stmt->fetchAll();
      foreach ($comments as $comment) {
         echo '<div class="box">';
         echo '   <div class="user">';
         echo '      <img src="images/pic-1.jpg" alt="User Image">';
         echo '      <div>';
         echo '         <h3>' . htmlspecialchars($comment['user_name']) . '</h3>';
         echo '         <span>' . htmlspecialchars($comment['date']) . '</span>';
         echo '      </div>';
         echo '   </div>';
         echo '   <div class="comment-box">' . htmlspecialchars($comment['comment_text']) . '</div>';
         echo '</div>';
      }
      ?>
   </div>
</section>

<!-- Footer -->
<footer class="footer bg-dark text-white">
   <div class="container">
      <div class="row">
         <div class="col-md-12 text-center py-4">
            <p>&copy; 2024 Gerar Isaac College Online Courses. All rights reserved.</p>
         </div>
         <div class="col-md-12 text-center py-1">
            <a href="#" target="_blank" class="social-icon"><i class="fab fa-facebook-f"></i></a>
            <a href="#" target="_blank" class="social-icon"><i class="fab fa-whatsapp"></i></a>
            <a href="#" target="_blank" class="social-icon"><i class="fab fa-youtube"></i></a>
            <a href="#" target="_blank" class="social-icon"><i class="fab fa-telegram-plane"></i></a>
         </div>
      </div>
   </div>
</footer>

<script>
// Toggle notes visibility
function toggleNotes() {
   var notes = document.getElementById('notes');
   notes.style.display = notes.style.display === 'block' ? 'none' : 'block';
}

// Set video speed
function setSpeed() {
   var video = document.getElementById('video');
   var speed = document.getElementById('speed').value;
   video.playbackRate = speed;
}

// Toggle subtitles by highlighting corresponding notes
function toggleSubtitle() {
   var video = document.getElementById('video');
   var notes = document.querySelectorAll('.notes h3');

   video.ontimeupdate = function() {
      var currentTime = video.currentTime;
      notes.forEach(function(note) {
         var start = parseFloat(note.getAttribute('data-start'));
         var end = parseFloat(note.getAttribute('data-end'));
         if (currentTime >= start && currentTime <= end) {
            note.classList.add('highlight');
         } else {
            note.classList.remove('highlight');
         }
      });
   };
}
</script>

</body>
</html>

