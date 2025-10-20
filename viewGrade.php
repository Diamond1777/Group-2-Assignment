<?php
include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Grades</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>All Recorded Grades</h2>

  <!-- Filter Form -->
  <form method="GET" action="">
    <label>Filter by Course Code:</label>
    <input type="text" name="course_code" placeholder="e.g. COS102"
           value="<?php echo isset($_GET['course_code']) ? htmlspecialchars($_GET['course_code']) : ''; ?>">

    <label>Semester:</label>
    <select name="semester">
      <option value="">All</option>
      <option value="First" <?php if (!empty($_GET['semester']) && $_GET['semester'] == 'First') echo 'selected'; ?>>First</option>
      <option value="Second" <?php if (!empty($_GET['semester']) && $_GET['semester'] == 'Second') echo 'selected'; ?>>Second</option>
    </select>

    <button type="submit">Search</button>
  </form>

  <table>
    <tr>
      <th>Student Name</th>
      <th>Course Code</th>
      <th>Course Title</th>
      <th>Semester</th>
      <th>Score</th>
      <th>Grade</th>
      <th>GPA Point</th>
    </tr>

<?php
// ✅ Simple filtering logic
$filter = '';

if (!empty($_GET['course_code']) && !empty($_GET['semester'])) {
    // Both filters used
    $course_code = $conn->real_escape_string($_GET['course_code']);
    $semester = $conn->real_escape_string($_GET['semester']);
    $filter = "WHERE grades.course_code LIKE '%$course_code%' AND courses.semester = '$semester'";
}
else if (!empty($_GET['course_code'])) {
    // Only course code filter
    $course_code = $conn->real_escape_string($_GET['course_code']);
    $filter = "WHERE grades.course_code LIKE '%$course_code%'";
}
else if (!empty($_GET['semester'])) {
    // Only semester filter
    $semester = $conn->real_escape_string($_GET['semester']);
    $filter = "WHERE courses.semester = '$semester'";
}

// ✅ Query to fetch grades (joining by course_code)
$sql = "SELECT students.name AS student_name,
               grades.course_code AS course_code,
               courses.course_title AS course_title,
               courses.semester AS semester,
               grades.score, grades.grade, grades.gpa_point
        FROM grades
        JOIN students ON grades.student_id = students.id
        JOIN courses ON grades.course_code = courses.course_code
        $filter
        ORDER BY students.name";

$result = $conn->query($sql);

// ✅ Display results
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['student_name']}</td>
                <td>{$row['course_code']}</td>
                <td>{$row['course_title']}</td>
                <td>{$row['semester']}</td>
                <td>{$row['score']}</td>
                <td>{$row['grade']}</td>
                <td>{$row['gpa_point']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='7'>No grades found for the selected filters.</td></tr>";
}
?>
  </table>
</body>
</html>
