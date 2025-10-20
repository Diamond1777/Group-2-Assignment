<?php
include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Student Grade</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Add a Student Grade</h2>

  <!-- Simple form where you manually type course code -->
  <form method="post">
    <label>Student ID:</label>
    <input type="number" name="student_id" required>

    <label>Course Code (e.g. COS102):</label>
    <input type="text" name="course_code" required>

    <label>Score (0 – 100):</label>
    <input type="number" name="score" min="0" max="100" required>

    <button type="submit" name="submit">Save Grade</button>
  </form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // sanitize input
    $student_id  = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);
    $course_code = isset($_POST['course_code']) ? strtoupper(trim($_POST['course_code'])) : ''; // make uppercase (optional)
    $score       = filter_input(INPUT_POST, 'score', FILTER_VALIDATE_FLOAT);

    // validate
    if ($student_id === false || $course_code === '' || $score === false || $score < 0 || $score > 100) {
        echo "<p class='message error'>❌ Please enter valid values for all fields.</p>";
    } else {
        // grading logic
        if ($score >= 70) { $grade = "A"; $gpa = 4.0; }
        elseif ($score >= 60) { $grade = "B"; $gpa = 3.0; }
        elseif ($score >= 50) { $grade = "C"; $gpa = 2.0; }
        elseif ($score >= 45) { $grade = "D"; $gpa = 1.0; }
        else { $grade = "F"; $gpa = 0.0; }

        // insert into database
        $stmt = $conn->prepare("INSERT INTO grades (student_id, course_code, score, grade, gpa_point) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isdss", $student_id, $course_code, $score, $grade, $gpa);

        if ($stmt->execute()) {
            echo "<p class='message success'>✅ Grade saved successfully!</p>";
        } else {
            echo "<p class='message error'>❌ Database error: " . htmlspecialchars($conn->error) . "</p>";
        }

        $stmt->close();
    }
}
?>
</body>
</html>
