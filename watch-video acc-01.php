
<?php
include('db.php');
include('header_loggedin.php');

// Ensure session variable 'user_name' is set correctly
if (!isset($_SESSION['user_name'])) {
    $_SESSION['user_name'] = "Default Name"; // Placeholder name, ensure proper session handling during login
}

// Check if PDO connection is successful
if (!$pdo) {
    die('Database connection failed.');
}

// Fetch video details from the database
$video_id = 103; // Example video ID, adjust as needed
try {
    $stmt = $pdo->prepare("SELECT * FROM videos WHERE video_id = ?");
    $stmt->execute([$video_id]);
    $video = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$video) {
        die('Video not found for ID: ' . htmlspecialchars($video_id));
    }
} catch (PDOException $e) {
    die('Error: ' . htmlspecialchars($e->getMessage()));
}

// Handle comment form submission
$comment_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['comments']) && !empty(trim($_POST['comments']))) {
        $video_id = $_POST['video_id'];
        $user_name = $_POST['user_name'];
        $comment = trim($_POST['comments']);

        try {
            $stmt = $pdo->prepare("INSERT INTO comments (video_id, user_name, comments, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$video_id, $user_name, $comment]);
            $comment_message = "Comment added successfully!";
        } catch (PDOException $e) {
            $comment_message = "Error: " . htmlspecialchars($e->getMessage());
        }
    } else {
        $comment_message = "Please enter a comment before submitting.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Watch Video - Accounting Tutorial</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- Custom CSS -->
   <style>
       body {
           background-color: #f5f5f5;
           font-family: 'Roboto', sans-serif;
           color: #333;
           margin: 0;
           padding: 0;
       }
       .highlight {
           background-color: #ffeb3b;
           padding: 3px 5px;
           border-radius: 3px;
       }
       .side-bar {
           width: 200px;
           background-color: #2c3e50;
           padding: 20px;
           color: #ecf0f1;
           position: fixed;
           top: 0;
           left: 0;
           height: 100%;
       }
       .side-bar a {
           color: #ecf0f1;
           display: block;
           margin: 12px 0;
           font-size: 16px;
           text-decoration: none;
           transition: color 0.3s;
       }
       .side-bar a:hover {
           color: #3498db;
       }
       .side-bar .profile img {
           border-radius: 50%;
           margin-bottom: 10px;
       }
       .watch-video {
           margin-left: 270px;
           padding: 20px;
       }
       .video-container {
           background-color: #fff;
           border-radius: 8px;
           padding: 20px;
           box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
           position: relative;
       }
       .video-container .notes {
           margin-top: 15px;
           display: none;
           padding: 10px;
           background-color: #eaeaea;
           border-radius: 5px;
       }
       .video-container .notes h3 {
           margin-top: 0;
       }
       .video {
           margin-bottom: 15px;
       }
       .video video {
           width: 100%;
           height: auto;
           border-radius: 5px;
       }
       .title {
           font-size: 26px;
           margin-bottom: 10px;
           color: #2980b9;
       }
       .info {
           display: flex;
           justify-content: space-between;
           margin-bottom: 15px;
           color: #7f8c8d;
       }
       .info p {
           margin: 0;
       }
       .tutor {
           display: flex;
           align-items: center;
           margin-bottom: 20px;
       }
       .tutor img {
           border-radius: 50%;
           width: 50px;
           height: 50px;
           margin-right: 15px;
       }
       .description {
           font-size: 16px;
           line-height: 1.6;
           color: #34495e;
       }
       .comments {
           margin-left: 270px;
           padding: 20px;
           margin-top: 20px;
       }
       .comments h1 {
           font-size: 22px;
           margin-bottom: 15px;
       }
       .comments form textarea {
           width: 100%;
           padding: 10px;
           margin-bottom: 10px;
           border-radius: 5px;
           border: 1px solid #bdc3c7;
           resize: vertical;
       }
       .comments .inline-btn {
           background-color: #3498db;
           color: #fff;
           padding: 10px 20px;
           border: none;
           border-radius: 5px;
           cursor: pointer;
           transition: background-color 0.3s;
       }
       .comments .inline-btn:hover {
           background-color: #2980b9;
       }
       .comments .show-comments .comment {
           background-color: #ecf0f1;
           padding: 10px;
           margin-bottom: 10px;
           border-radius: 5px;
       }
       .comments .comment h3 {
           font-size: 18px;
           margin: 0 0 5px;
       }
       .footer {
           background-color: #34495e;
           color: #ecf0f1;
           padding: 20px;
           text-align: center;
           margin-top: 30px;
       }
       .footer .box-container {
           display: flex;
           justify-content: space-around;
           flex-wrap: wrap;
       }
       .footer .box {
           flex: 1;
           min-width: 200px;
           margin: 10px;
       }
       .footer .box a {
           color: #ecf0f1;
           display: block;
           margin: 5px 0;
           text-decoration: none;
       }
   </style>
</head>
<body>

<div class="side-bar">
   <div id="close-btn">
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">
      <img src="assets/tutor/Professor Marc Badia.jpeg" class="image" alt="Tutor Image">
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

<section class="watch-video">
   <div class="video-container">
      <div class="video">
         <video src="assets/Acc-Videos/Begining Balance Recording .mp4" controls poster="images/Accounting Image.png" id="video"></video>
      </div>
      <h3 class="title">Accounting Tutorial (Part 02)</h3>
      <div class="info">
         <p class="date"><i class="fas fa-calendar"></i><span>22-10-2023</span></p>
         <p class="likes"><i class="fas fa-heart"></i><span>44 likes</span></p>
      </div>

      <div class="tutor">
         <img src="assets/tutor/Professor Marc Badia.jpeg" alt="Tutor">
         <div>
            <h4>Professor Marc Badia</h4>
            <p>Accounting Expert</p>
         </div>
      </div>

      <p class="description">
         Accounting recording of beginning balances.
      </p>

      <button class="inline-btn" onclick="toggleNotes()">View Notes</button>
      
      <div id="notes" class="notes">
         <h3>Course Notes</h3>
         <p>
            <!-- Course notes content with time stamps can go here -->
         
            <h3 data-start="0" data-end="10">In this second video, I'm going to introduce you to a very special person, a friend of mine who started her own business.</h3>
            <h3 data-start="10" data-end="20">Christina loved reading books and recommending them to her friends. So she started a bookstore here on campus.</h3>
            <h3 data-start="20" data-end="30">So if you come with me, we're going to talk with her and she will explain to us what transactions took place in the business in this first month of preparation, so that we can help her with her accounting and learn by doing. Come with me.</h3>
            <h3 data-start="30" data-end="40">Hello, how are you? Nice to see you. Great to see you, Mom. It's great. I see that. You're coming. Finally, you're drinking true, right? Yeah. I'm really excited. I'm really excited. It's a new year, new business. When are you studying? Open in two days. Are you studying? Yeah, in two days, I'm opening.</h3>
            <h3 data-start="40" data-end="50">You see, I have everything all set up. So everything looks like writing. Everything all set up is different than numbers. The numbers. And a little bit nervous and overwhelmed about how to get started. You mean the accounting? That's right, me. Yeah, the accounting.</h3>
            <h3 data-start="50" data-end="60">Okay, so then when we go outside, we can talk about accounting. Oh, I'd love to. That'll be great. Because I came here with my students from Coursera and I'm sure that we can help you. Little me with your accounting. Okay, I hope so. Great. Awesome. I'll bring them to the numbers. Okay, let's go. Great. Thanks.</h3>
            <h3 data-start="60" data-end="70">Okay, Christina. So I guess we could start by making a list of all the transactions that have taken place this past month in your business since you started.</h3>
            <h3 data-start="70" data-end="80">Okay, great. I can take note of this. Okay, so I started the bookstore with 50,000 euros. That was 35,000 was mine and 15,000 was my uncle. Okay, so this was the initial contribution of capital to the business? Exactly, exactly. Okay, cool. Capital contribution. 50,000. What else?</h3>
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
    
         </p>
      </div>
   </div>
</section>

<section class="comments">
   <h1 class="heading">Add Comments</h1>
   <form action="" method="post">
      <input type="hidden" name="video_id" value="<?= htmlspecialchars($video['video_id']); ?>">
      <input type="hidden" name="user_name" value="<?= htmlspecialchars($_SESSION['user_name']); ?>">
      <textarea name="comments" rows="4" placeholder="Enter your comment here..." required></textarea>
      <input type="submit" value="Submit Comment" class="inline-btn">
   </form>
   <div class="message"><?= htmlspecialchars($comment_message); ?></div>

   <h1 class="heading">User Comments</h1>
   <div class="show-comments">
      <?php
      $stmt = $pdo->prepare("SELECT * FROM comments WHERE video_id = ? ORDER BY created_at DESC");
      $stmt->execute([$video['video_id']]);
      while ($comment = $stmt->fetch(PDO::FETCH_ASSOC)) {
      ?>
         <div class="comment">
            <h3><?= htmlspecialchars($comment['user_name']); ?></h3>
            <p><?= htmlspecialchars($comment['comments']); ?></p>
         </div>
      <?php } ?>
   </div>
</section>

<!-- Footer Section -->
<footer class="footer">
   <div class="box-container">
      <div class="box">
         <h3>Quick Links</h3>
         <a href="home.php">Home</a>
         <a href="about.php">About</a>
         <a href="courses.php">Courses</a>
         <a href="contact.php">Contact</a>
      </div>
      <div class="box">
         <h3>Connect with Us</h3>
         <a href="#"><i class="fab fa-facebook-f"></i> Facebook</a>
         <a href="#"><i class="fab fa-instagram"></i> Instagram</a>
         <a href="#"><i class="fab fa-linkedin"></i> LinkedIn</a>
         <a href="#"><i class="fab fa-twitter"></i> Twitter</a>
      </div>
   </div>
   <p>&copy; 2023 - All Rights Reserved</p>
</footer>

<script>
   function toggleNotes() {
      var notes = document.getElementById('notes');
      if (notes.style.display === 'none' || notes.style.display === '') {
         notes.style.display = 'block';
      } else {
         notes.style.display = 'none';
      }
   }
</script>
</body>
</html>
