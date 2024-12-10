
<?php
// Database connection
$host = 'localhost';
$dbname = 'accounting_course'; 
$username = 'root'; 
$password = ''; 
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// Fetch weeks and modules
$weeks = [
    1 => ['Chapter 1: History of Accounting', 'Chapter 2: Importance of Accounting'],
    2 => ['Chapter 3: Accounting Professionals’ Values', 'Chapter 4: Accounting Terms'],
    // More weeks can be added here
];

$modules = [
    'Introduction' => [
        'Chapter 1: History of Accounting' => [],
        'Chapter 2: Importance of Accounting' => [],
        'Chapter 3: Accounting Professionals’ Values' => []
    ],
    'Accounting Cycles' => [
        'Chapter 1: Journals' => [],
        'Chapter 2: Ledgers' => [],
        'Chapter 3: Trial Balance' => [],
        'Chapter 4: Financial Statements' => []
    ],
    // More modules can be added here
];

// Get parameters from the URL
$week = isset($_GET['week']) ? (int)$_GET['week'] : 1;
$class = isset($_GET['class']) ? (int)$_GET['class'] : 1;
$module = isset($_GET['module']) ? $_GET['module'] : 'Introduction';
$chapter = isset($_GET['chapter']) ? $_GET['chapter'] : 'Chapter 1: History of Accounting';

// Validate week and class
if (!isset($weeks[$week]) || $class < 1 || $class > 2) {
    die('Invalid week or class.');
}

$chapterName = isset($weeks[$week][$class - 1]) ? $weeks[$week][$class - 1] : null;

if ($chapterName && isset($modules[$module][$chapterName])) {
    $currentChapter = $modules[$module][$chapterName];
} else {
    die('Chapter not found.');
}

// Fetch video
try {
    $stmt = $pdo->prepare("SELECT * FROM videos WHERE week_number = ? AND module_name = ? AND module_title = ?");
    $stmt->execute([$week, $module, $chapterName]);
    $video = $stmt->fetch();
} catch (PDOException $e) {
    die('Error fetching video: ' . htmlspecialchars($e->getMessage()));
}

// Fetch course notes
try {
    $stmt = $pdo->prepare("SELECT course_notes FROM course_notes WHERE week_number = ? AND module_name = ? AND module_title = ?");
    $stmt->execute([$week, $module, $chapterName]);
    $notes = $stmt->fetchColumn();
} catch (PDOException $e) {
    die('Error fetching course notes: ' . htmlspecialchars($e->getMessage()));
}

// Determine next and previous chapters
$chapterTitles = array_keys($modules[$module]);
$index = array_search($chapterName, $chapterTitles);
$nextChapter = $index < count($chapterTitles) - 1 ? $chapterTitles[$index + 1] : null;
$previousChapter = $index > 0 ? $chapterTitles[$index - 1] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($chapterName); ?> - Week <?= $week; ?> - Class <?= $class; ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@400;700&display=swap">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            flex-direction: column;
            height: 100vh;
            margin: 0;
        }
        header, footer {
            background-color: #007bff;
            color: white;
            padding: 1rem;
            text-align: center;
        }
        header a, footer a {
            color: white;
            text-decoration: none;
        }
        header a:hover, footer a:hover {
            text-decoration: underline;
        }
        .container {
            flex: 1;
            display: flex;
        }
        .sidebar {
            width: 25%;
            background-color: #fff;
            padding: 1rem;
            box-shadow: 2px 0 4px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }
        .sidebar h2, .sidebar h3 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
        }
        .main-content {
            flex: 3;
            padding: 1rem;
            background-color: #fff;
        }
        .video-item video {
            width: 100%;
            max-width: 800px;
            display: block;
        }
        .notes, .controls {
            margin-top: 1rem;
        }
        .notes {
            background-color: #f9f9f9;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .controls button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 0.5rem;
        }
    </style>
    <script>
        function toggleNotes() {
            var notes = document.getElementById('notes');
            var button = document.getElementById('toggleNotesButton');
            if (notes.style.display === 'none' || notes.style.display === '') {
                notes.style.display = 'block';
                button.textContent = 'Hide Course Notes';
            } else {
                notes.style.display = 'none';
                button.textContent = 'View Course Notes';
            }
        }
    </script>
</head>
<body>

<header>
    <h1><a href="index.php">Accounting Course</a></h1>
</header>

<div class="container">
    <aside class="sidebar">
        <h2>Weeks</h2>
        <ul>
            <?php foreach ($weeks as $w => $chapters): ?>
                <li><a href="?week=<?= $w; ?>&class=1&module=<?= urlencode($module); ?>&chapter=<?= urlencode($chapters[0]); ?>">Week <?= $w; ?> - Class 1</a></li>
                <li><a href="?week=<?= $w; ?>&class=2&module=<?= urlencode($module); ?>&chapter=<?= urlencode($chapters[1]); ?>">Week <?= $w; ?> - Class 2</a></li>
            <?php endforeach; ?>
        </ul>

        <h2>Modules</h2>
        <?php foreach ($modules as $mod => $chapters): ?>
            <div>
                <h3><?= htmlspecialchars($mod); ?></h3>
                <ul>
                    <?php foreach ($chapters as $chapterTitle => $subtitles): ?>
                        <li><a href="?week=<?= $week; ?>&module=<?= urlencode($mod); ?>&chapter=<?= urlencode($chapterTitle); ?>"><?= htmlspecialchars($chapterTitle); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </aside>

    <div class="main-content">
        <h1><?= htmlspecialchars($module); ?> - <?= htmlspecialchars($chapterName); ?></h1>
        <div class="video-item">
            <video controls>
                <source src="<?= htmlspecialchars($video['video_url']); ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        <button id="toggleNotesButton" onclick="toggleNotes()">View Course Notes</button>
        <div id="notes" class="notes" style="display: none;">
            <h2>Course Notes</h2>
            <p><?= nl2br(htmlspecialchars($notes)); ?></p>
        </div>

        <div class="controls">
            <?php if ($previousChapter): ?>
                <button onclick="window.location.href='?week=<?= $week; ?>&module=<?= urlencode($module); ?>&chapter=<?= urlencode($previousChapter); ?>'">Previous Chapter</button>
            <?php endif; ?>
            <?php if ($nextChapter): ?>
                <button onclick="window.location.href='?week=<?= $week; ?>&module=<?= urlencode($module); ?>&chapter=<?= urlencode($nextChapter); ?>'">Next Chapter</button>
            <?php endif; ?>
        </div>
    </div>
</div>

<footer>
    <p>&copy; <?= date('Y'); ?> Accounting Course. All rights reserved.</p>
</footer>

</body>
</html>

kkkkkkkkkkkkkkkkkk
<?php
// Database connection
$host = 'localhost';
$dbname = 'accounting_course'; 
$username = 'root'; 
$password = ''; 
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// Fetch weeks and modules
$weeks = [
    1 => ['Chapter 1: History of Accounting', 'Chapter 2: Importance of Accounting'],
    2 => ['Chapter 3: Accounting Professionals’ Values', 'Chapter 4: Accounting Terms'],
    3 => ['Chapter 5: Principles', 'Chapter 6: Overview'],
    4 => ['Chapter 7: Basics', 'Chapter 8: Journals'],
    5 => ['Chapter 9: Ledgers', 'Chapter 10: Trial Balance'],
    6 => ['Chapter 11: Financial Statements', 'Chapter 12: Filing and Documentation'],
                                               
    // Add additional weeks as needed
];

$modules = [
    'Introduction' => [
        'Chapter 1: History of Accounting' => [],
        'Chapter 2: Importance of Accounting' => [],
        'Chapter 3: Accounting Professionals’ Values' => []
    ],
    'Recording Transactions' => [
        'Chapter 1: Accounting Terms and Objectives' => [],
        'Chapter 2: Users of Accounting Information' => []
    ],
    'Accounting Principles' => [
        'Chapter 1: Principles' => []
    ],
    'Elements of Accounting' => [
        'Chapter 1: Overview' => []
    ],
    'The Accounting Equation' => [
        'Chapter 1: Basics' => []
    ],
    'Accounting Cycles' => [
        'Chapter 1: Journals' => [],
        'Chapter 2: Ledgers' => [],
        'Chapter 3: Trial Balance' => [],
        'Chapter 4: Financial Statements' => []
    ],
    'Adjusting Entries' => [
        'Chapter 1: Introduction' => [],
        'Chapter 2: Adjustments' => []
    ],
    'Bank Reconciliation' => [
        'Chapter 1: Reconciliation Basics' => []
    ],
    'Inventory' => [
        'Chapter 1: Overview' => []
    ],
    'Property, Plant, and Equipment' => [
        'Chapter 1: Introduction' => [],
        'Chapter 2: Depreciation' => []
    ],
    'Payroll' => [
        'Chapter 1: Basics' => []
    ],
    'Taxation' => [
        'Chapter 1: Profit Tax' => [],
        'Chapter 2: VAT' => [],
        'Chapter 3: Withholding Tax' => []
    ],
    'Filing and Documentation' => [
        'Chapter 1: Supporting Documents' => [],
        'Chapter 2: Collections and Payments' => [],
        'Chapter 3: Controlling and Monitoring' => []
    ]
];

$week = isset($_GET['week']) ? (int)$_GET['week'] : 1;
$class = isset($_GET['class']) ? (int)$_GET['class'] : 1; // Class 1 or 2 per week
$module = isset($_GET['module']) ? $_GET['module'] : 'Introduction';
$chapter = isset($_GET['chapter']) ? $_GET['chapter'] : 'Chapter 1: History of Accounting';

// Ensure valid week and class
if (!isset($weeks[$week]) || $class < 1 || $class > 2) {
    die('Invalid week or class.');
}

// Determine current chapter based on week and class
$chapterName = isset($weeks[$week][$class - 1]) ? $weeks[$week][$class - 1] : null;

if ($chapterName && isset($modules[$module][$chapterName])) {
    $currentChapter = $modules[$module][$chapterName];
} else {
    die('Chapter not found.');
}

// Fetch video
try {
    $stmt = $pdo->prepare("SELECT * FROM videos WHERE week_number = ? AND module_name = ? AND module_title = ?");
    $stmt->execute([$week, $module, $chapterName]);
    $video = $stmt->fetch();
} catch (PDOException $e) {
    die('Error fetching video: ' . htmlspecialchars($e->getMessage()));
}

// Fetch course notes
try {
    $stmt = $pdo->prepare("SELECT course_notes FROM course_notes WHERE week_number = ? AND module_name = ? AND module_title = ?");
    $stmt->execute([$week, $module, $chapterName]);
    $notes = $stmt->fetchColumn();
} catch (PDOException $e) {
    die('Error fetching course notes: ' . htmlspecialchars($e->getMessage()));
}

// Determine next and previous chapters
$chapterTitles = array_keys($modules[$module]);
$index = array_search($chapterName, $chapterTitles);
$nextChapter = $index < count($chapterTitles) - 1 ? $chapterTitles[$index + 1] : null;
$previousChapter = $index > 0 ? $chapterTitles[$index - 1] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($chapterName); ?> - Week <?= $week; ?> - Class <?= $class; ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@400;700&display=swap">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        header, footer {
            background-color: #007bff;
            color: white;
            padding: 1rem;
            text-align: center;
        }
        header a, footer a {
            color: white;
            text-decoration: none;
        }
        header a:hover, footer a:hover {
            text-decoration: underline;
        }
        .container {
            flex: 1;
            display: flex;
        }
        .sidebar {
            width: 25%;
            background-color: #fff;
            padding: 1rem;
            box-shadow: 2px 0 4px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow-y: auto;
        }
        .sidebar h2 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            margin-top: 0;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar ul li {
            margin-bottom: 1rem;
        }
        .sidebar ul li a {
            text-decoration: none;
            color: #007bff;
            font-weight: 500;
        }
        .sidebar ul li a:hover {
            text-decoration: underline;
        }
        .main-content {
            flex: 3;
            padding: 1rem;
            background-color: #fff;
            overflow-y: auto;
        }
        .video-item {
            margin-bottom: 1.5rem;
        }
        .video-item video {
            width: 100%;
            max-width: 800px; /* Set a standard size for the video */
            height: auto;
            display: block;
        }
        .controls {
            margin: 1rem 0;
        }
        .controls button, .controls select {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 0.5rem;
        }
        .controls button:hover, .controls select:hover {
            background-color: #0056b3;
        }
        .notes, .comments, .exercises {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 1rem;
            margin-top: 1rem;
            border-radius: 4px;
        }
        .exercises {
            margin-bottom: 2rem;
        }
        .hide {
            display: none;
        }
    </style>
    <script>
        function toggleNotes() {
            var notes = document.getElementById('notes');
            var button = document.getElementById('toggleNotesButton');
            if (notes.style.display === 'none' || notes.style.display === '') {
                notes.style.display = 'block';
                button.textContent = 'Hide Course Notes';
            } else {
                notes.style.display = 'none';
                button.textContent = 'View Course Notes';
            }
        }
    </script>
</head>
<body>

<header>
    <h1><a href="index.php">Accounting Course</a></h1>
</header>

<div class="container">
    <!-- Sidebar for weeks and modules -->
    <aside class="sidebar">
        <h2>Weeks</h2>
        <ul>
            <?php foreach ($weeks as $w => $chapters): ?>
                <li><a href="?week=<?= $w; ?>&class=1&module=<?= urlencode($module); ?>&chapter=<?= urlencode($chapters[0]); ?>">Week <?= $w; ?> - Class 1</a></li>
                <li><a href="?week=<?= $w; ?>&class=2&module=<?= urlencode($module); ?>&chapter=<?= urlencode($chapters[1]); ?>">Week <?= $w; ?> - Class 2</a></li>
            <?php endforeach; ?>
        </ul>

        <h2>Modules</h2>
        <?php foreach ($modules as $mod => $chapters): ?>
            <div>
                <h3><?= htmlspecialchars($mod); ?></h3>
                <?php foreach ($chapters as $chapterTitle => $subtitles): ?>
                    <div>
                        <strong><?= htmlspecialchars($chapterTitle); ?></strong>
                        <ul>
                            <?php foreach ($subtitles as $subtitle): ?>
                                <li><a href="?week=<?= $week; ?>&module=<?= urlencode($mod); ?>&chapter=<?= urlencode($chapterTitle); ?>"><?= htmlspecialchars($subtitle); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </aside>

    <!-- Main content area -->
    <div class="main-content">
        <h1><?= htmlspecialchars($module); ?> - <?= htmlspecialchars($chapterName); ?></h1>
        <div class="video-item">
            <video controls>
                <source src="<?= htmlspecialchars($video['video_url']); ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        <button id="toggleNotesButton" onclick="toggleNotes()">View Course Notes</button>
        <div id="notes" class="notes hide">
            <h2>Course Notes</h2>
            <p><?= nl2br(htmlspecialchars($notes)); ?></p>
        </div>

        <div class="controls">
            <?php if ($previousChapter): ?>
                <button onclick="window.location.href='?week=<?= $week; ?>&module=<?= urlencode($module); ?>&chapter=<?= urlencode($previousChapter); ?>'">Previous Chapter</button>
            <?php endif; ?>
            <?php if ($nextChapter): ?>
                <button onclick="window.location.href='?week=<?= $week; ?>&module=<?= urlencode($module); ?>&chapter=<?= urlencode($nextChapter); ?>'">Next Chapter</button>
            <?php endif; ?>
        </div>
    </div>
</div>

<footer>
    <p>&copy; <?= date('Y'); ?> Accounting Course</p>
</footer>

</body>
</html>

