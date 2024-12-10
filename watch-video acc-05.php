<?php
session_start();
include('db.php');
include('header_loggedin.php');

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
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>watch</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="assets/css/style.css">

</head>
<body>

<header class="header">
   
   <section class="flex">

      <a href="home.php" class="logo">Gerar Isaac College Online Course.</a>

      <form action="search.html" method="post" class="search-form">
         <input type="text" name="search_box" required placeholder="search courses..." maxlength="100">
         <button type="submit" class="fas fa-search"></button>
      </form>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="search-btn" class="fas fa-search"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>

      <div class="profile">
         <img src="images_courses/Accounting Image.png" class="image" alt="">
         <h3 class="name">xxxxx</h3>
         <p class="role">student</p>
         <a href="profile.html" class="btn">view profile</a>
         <div class="flex-btn">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
      </div>

   </section>

</header>   

<div class="side-bar">

   <div id="close-btn">
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">

      <img src="assets/tutor/Habtom.jpg" class="image" alt="">
      <h3 class="name">Habtom Araya-ACCA</h3>
      <h3 class="role">Tutor </h3>
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
      <video src="assets/images/Accounting Principles _ Explained with Examples-.mp4" controls poster="images/Accounting Image.png" id="video"></video>
      </div>
      <h3 class="title">Accounting tutorial (part 05)</h3>
      <div class="info">
         <p class="date"><i class="fas fa-calendar"></i><span>22-10-2023</span></p>
         <p class="date"><i class="fas fa-heart"></i><span>44 likes</span></p>
      </div>
      <div class="tutor">
         <img src="assets/images_courses/Accounting Image.png" alt="">
         <div>
            <h3>xxxxx</h3>
            <span>General Overview of Accounting</span>
         </div>
      </div>
      <form action="" method="post" class="flex">
         <a href="playlist.php" class="inline-btn">view playlist</a>
         <button><i class="far fa-heart"></i><span>like</span></button>
      </form>
      <p class="description">
         Gerar Isaac

Training Center 
 
 Geneses 26: 12, 20:1-17, 2 Chronicles 14: 11-15





Principles of Accounting
                Prepared by:
                                                                                       
                      Habtom Araya (ACCA)


Welcome to this Accounting course 
General overview of the course:
This accounting principles course is designed to give you the fundamental skills needed to practice accounting principles. It is specifically focused on basic concepts and fundamentals of accounting principles and applications. You will be introduced to fundamental accounting concepts. And you will learn the basics of accounting, a simple and powerful accounting language for applying and managing financial data, events and/or transactions. 

You will discover and practice how business and non-business organization financial transactions recorded, posted, prepare financial reports (balance sheet, income (profit and loss) statement and owners’ equity statement that allows you to use standard format of accounting languages, terminologies to work with financial data manually and using peach tree accounting software 2010 version, one of popular accounting software. In this, you will gain skill and knowledge of bank reconciliation, Inventory records and reports, taxation fundamentals and get hands-on experience deploying examples.

Trainer background (educational and work experience):
Your instructor, Habtom Araya- ACCA, is graduated and obtained a professional skill ACCA (Associated Chartered Certified Accountants) from United Kingdom. He worked as financial head, financial advisor and as internal auditor and external auditor in different organizations. He trained principle of accounting, financial accounting, cost accounting, and auditing courses in private college, business and non-business organization employees.

In addition to this, he has obtained Advance diplomas and certificates from ‘Coursera’, ‘Alison’, and ‘simple learn’ in Industrial Engineering, Electrical Motors and controls, Data science with R programming, database management, SQL, python programming, C++ programming, web development HTML, CSS and JavaScript etc. He worked as head of maintenance electrical motors and controls of manufacturing organization. And trained basic electricity and electronics.

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
Goodluck!                                                                                         .
CONTENTS

	Introduction: -
	History of accounting, 
	Importance of accounting, 
	Accounting professionals’ values

	Chapter One: - 
	The Accounting terms, Objectives of Accounting
	Users of accounting information, The accounting profession

	Chapter Two: - 
	Accounting Principles 

	Chapter Three: - 
	Elements of accounting

	Chapter Four: -
	 The accounting equation

	Chapter Five: -
	The accounting cycles- Journals, ledgers, Trial Balance, Financial statements

	Chapter Six: - 
	Adjusting entries

	Chapter Seven: -
	Bank Reconciliation 

	Chapter Eight: -

	Inventory

	Chapter Nine: -
	Property plant and Equipment, Depreciation

	Chapter Ten: -
	Payroll

	Chapter Eleven: -
	Taxation: -Profit tax, Value Added Tax (VAT), Withholding Tax
	Chapter Twelve: - 
	Filing and documentation of supporting documents
	Procedures of collections and payments-sales procedures and purchase procedures
	Others controlling and monitoring of finances

	Appendix: - Terms and meaning of accounting 


INTRODUCTION

History of Accounting

The history of accounting has been around almost as long as money itself. Accounting history dates back to ancient civilizations in Mesopotamia, Egypt, and Babylon. For example, during the Roman Empire, the government had detailed records of its finances. However, modern accounting as a profession has only been around since the early 19th century.

The development of monetary systems (gradually replacing direct exchange, known as barter) allowed the results of trade and commerce to be measured more exactly, but FORMAL recording methods followed only slowly. By the end of the fifteenth century, however, DOUBLE ENTRY BOOKKEEPING had become an established method of recording and remains the basis of today’s accounting systems.

Luca Pacioli is considered "The Father of Accounting and Bookkeeping" due to his contributions to the development of accounting as a profession. An Italian mathematician and friend of Leonardo da Vinci, Pacioli published a book on the double-entry system of bookkeeping in 1494.

By 1880, the modern profession of accounting was fully formed and recognized by the Institute of Chartered Accountants in England and Wales. This institute created many of the systems by which accountants practice today. 

Founded in 1904, the Association of Chartered Certified Accountants (ACCA) is the global professional accounting body offering the Chartered Certified Accountant qualification (ACCA). It has 240,952 members and 541,930 future members worldwide. ACCA's headquarters are in London with principal administrative office in Glasgow.

Certified Public Accountant (CPA) is the title of qualified accountants in numerous countries in the English-speaking world. It is generally equivalent to the title of chartered accountant in other English-speaking countries. In the United States, the CPA is a license to provide accounting services to the public. It is awarded by each of the 50 states for practice in that state.
                                                                                                                                                                              Accounting Is Important: Reasons Why Accounting Is Important
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
•	Professional Behavior – to comply with relevant laws and regulations.                                                   

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

</section>



<footer class="footer">

   &copy; copyright @ 2023 by <span>Habtom Araya-ACCA Web designer</span> | all rights reserved!

</footer>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>

