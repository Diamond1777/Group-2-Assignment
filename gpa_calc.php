<?php
include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student GPA Calculator</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Calculate Student GPA</h2>

  <form method="get" action="">
    <label>Enter Student ID:</label>
    <input type="number" name="student_id" required>
    <button type="submit">View Result</button>
  </form>

<?php
if (isset($_GET['student_id'])) {
    $student_id = intval($_GET['student_id']);

    // 1️⃣ Fetch student name
    $student_sql = "SELECT name FROM students WHERE id = $student_id";
    $student_result = $conn->query($student_sql);

    if ($student_result && $student_result->num_rows > 0) {
        $student_row = $student_result->fetch_assoc();
        $student_name = $student_row['name'];

        echo "<h3>Result for: <strong>$student_name (ID: $student_id)</strong></h3>";

        // 2️⃣ Fetch all grades with course info
        $sql = "SELECT courses.course_code, courses.course_title, courses.credit_unit,
                       grades.score, grades.grade, grades.gpa_point
                FROM grades
                JOIN courses ON grades.course_code = courses.course_code
                WHERE grades.student_id = $student_id";

        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            echo "<table>
                    <tr>
                      <th>Course Code</th>
                      <th>Course Title</th>
                      <th>Credit Unit</th>
                      <th>Score</th>
                      <th>Grade</th>
                      <th>GPA Point</th>
                      <th>Weighted Point</th>
                    </tr>";

            $total_weighted = 0;
            $total_credits = 0;

            while ($row = $result->fetch_assoc()) {
                $weighted = $row['gpa_point'] * $row['credit_unit'];
                $total_weighted += $weighted;
                $total_credits += $row['credit_unit'];

                echo "<tr>
                        <td>{$row['course_code']}</td>
                        <td>{$row['course_title']}</td>
                        <td>{$row['credit_unit']}</td>
                        <td>{$row['score']}</td>
                        <td>{$row['grade']}</td>
                        <td>{$row['gpa_point']}</td>
                        <td>" . number_format($weighted, 2) . "</td>
                      </tr>";
            }

            $gpa = ($total_credits > 0) ? $total_weighted / $total_credits : 0;

            echo "<tr>
                    <th colspan='6' style='text-align:right;'>Total GPA:</th>
                    <th>" . number_format($gpa, 2) . "</th>
                  </tr>
                  </table>";
        } else {
            echo "<p class='message error'>No grades found for this student.</p>";
        }
    } else {
        echo "<p class='message error'>Student not found!</p>";
    }
}
?>
</body>
</html>
