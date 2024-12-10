
kkkk
<?php
include('db.php');
include('header.php');

// Display timer countdown script here

try {
    $sql = "SELECT question, option_a, option_b, option_c, option_d, answer FROM exam_questions";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$questions) {
        echo "No questions found.";
    } else {
        foreach ($questions as $question) {
            echo "Question: " . htmlspecialchars($question['question']) . "<br>";
            echo "Option A: <input type='radio' name='answer_" . $question['question'] . "' value='A'>" . htmlspecialchars($question['option_a']) . "<br>";
            echo "Option B: <input type='radio' name='answer_" . $question['question'] . "' value='B'>" . htmlspecialchars($question['option_b']) . "<br>";
            echo "Option C: <input type='radio' name='answer_" . $question['question'] . "' value='C'>" . htmlspecialchars($question['option_c']) . "<br>";
            echo "Option D: <input type='radio' name='answer_" . $question['question'] . "' value='D'>" . htmlspecialchars($question['option_d']) . "<br>";
            echo "<button onclick='checkAnswer(\"" . $question['answer'] . "\", \"" . $question['question'] . "\")'>Check Answer</button>";
            echo "<div id='result_" . $question['question'] . "'></div><br><br>";
        }
    }
} catch (PDOException $e) {
    echo "Query failed: " . $e->getMessage();
}
?>

<script>
// Function to check the selected answer
function checkAnswer(correctAnswer, questionId) {
    var selectedAnswer = document.querySelector('input[name="answer_' + question+ '"]:checked');
    if (selectedAnswer) {
        var selectedValue = selectedAnswer.value;
        var resultDiv = document.getElementById('result_' + question);
        if (selectedValue === correctAnswer) {
            resultDiv.innerHTML = "<span style='color:green;'>Correct Answer!</span>";
        } else {
            resultDiv.innerHTML = "<span style='color:red;'>Incorrect. Answer is " + correctAnswer + "</span>";
        }
    } else {
        alert("Please select an answer.");
    }
}
</script>

<?php include('footer.php'); ?>

kkkk
<?php

include('db.php');
include('header.php');
try {
    $sql = "SELECT question, option_a, option_b, option_c, option_d, answer FROM exam_questions";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$questions) {
        echo "No questions found.";
    } else {
        foreach ($questions as $question) {
            echo "Question: " . htmlspecialchars($question['question']) . "<br>";
            echo "Option A: " . htmlspecialchars($question['option_a']) . "<br>";
            echo "Option B: " . htmlspecialchars($question['option_b']) . "<br>";
            echo "Option C: " . htmlspecialchars($question['option_c']) . "<br>";
            echo "Option D: " . htmlspecialchars($question['option_d']) . "<br>";
            echo "correct_answer: " . htmlspecialchars($question['answer']) . "<br><br>";
        }
    }
} catch (PDOException $e) {
    echo "Query failed: " . $e->getMessage();
}
?>
<?php include('footer.php'); ?>