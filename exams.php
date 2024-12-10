<?php
session_start();
include('db.php'); // Include database connection
include('header_loggedin.php');

// Assume user is logged in, otherwise redirect
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id']; // Current user's ID

// Check if the user has already taken the exam
try {
    $stmt = $pdo->prepare("SELECT * FROM exam_attempts WHERE id = ?");
    $stmt->execute([$userId]);
    $attempt = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($attempt) {
        // If the user has already taken the exam, redirect to results page
        header('Location: exam_results.php');
        exit;
    }

    // Fetch exam questions
    $sql = "SELECT exam_id, question, option_a, option_b, option_c, option_d, Answer FROM exam_questions";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$questions) {
        echo "<div class='alert alert-danger text-center'>No questions found.</div>";
    } else {
        ?>
        <div class="container mt-5" id="quiz-container">
            <h2 class="text-center text-primary">Online Exam</h2>
            <div id="timer" class="timer text-danger text-center mb-4">Time Remaining: <span id="time">20:00</span></div>

            <form id="quizForm" method="POST" action="exam_results.php">
            <?php
            foreach ($questions as $index => $question) {
                ?>
                <div class="question-block" id="question_<?php echo $index; ?>" <?php if ($index !== 0) echo 'style="display: none;"'; ?>>
                    <h4 class="text-info">Question <?php echo $index + 1; ?>:</h4>
                    <p><?php echo htmlspecialchars($question['question']); ?></p>
                    <input type="hidden" name="correct_answer_<?php echo $index; ?>" value="<?php echo $question['Answer']; ?>">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" name="answer_<?php echo $index; ?>" value="A" class="form-check-input"> A) <?php echo htmlspecialchars($question['option_a']); ?>
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" name="answer_<?php echo $index; ?>" value="B" class="form-check-input"> B) <?php echo htmlspecialchars($question['option_b']); ?>
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" name="answer_<?php echo $index; ?>" value="C" class="form-check-input"> C) <?php echo htmlspecialchars($question['option_c']); ?>
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" name="answer_<?php echo $index; ?>" value="D" class="form-check-input"> D) <?php echo htmlspecialchars($question['option_d']); ?>
                        </label>
                    </div>
                    <hr>
                </div>
                <?php
            }
            ?>
            <div id="navigation-buttons" class="text-center">
                <button type="button" class="btn btn-secondary" onclick="previousQuestion()" id="prevButton" style="display: none;">Previous</button>
                <button type="button" class="btn btn-secondary" onclick="nextQuestion()" id="nextButton">Next</button>
                <button type="submit" class="btn btn-success" id="submitButton" style="display: none;">Submit Exam</button>
            </div>
            </form>
        </div>

        <!-- JavaScript for Quiz Functionality -->
        <script>
        let currentQuestion = 0;
        let totalQuestions = <?php echo count($questions); ?>;

        function nextQuestion() {
            if (currentQuestion < totalQuestions - 1) {
                document.getElementById('question_' + currentQuestion).style.display = 'none';
                currentQuestion++;
                document.getElementById('question_' + currentQuestion).style.display = 'block';
                updateNavigationButtons();
            }
        }

        function previousQuestion() {
            if (currentQuestion > 0) {
                document.getElementById('question_' + currentQuestion).style.display = 'none';
                currentQuestion--;
                document.getElementById('question_' + currentQuestion).style.display = 'block';
                updateNavigationButtons();
            }
        }

        function updateNavigationButtons() {
            document.getElementById('prevButton').style.display = (currentQuestion === 0) ? 'none' : 'inline-block';
            document.getElementById('nextButton').style.display = (currentQuestion === totalQuestions - 1) ? 'none' : 'inline-block';
            document.getElementById('submitButton').style.display = (currentQuestion === totalQuestions - 1) ? 'inline-block' : 'none';
        }
        </script>
        <?php
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

include('footer.php');
?>
