<?php
// Include the configuration file to establish the database mysql
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Student Tracking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h1>Welcome to Your Student Tracking System</h1>

        <!-- Basic navigation links -->
        <nav>
            <ul class="nav">
                <li class="nav-item"><a class="nav-link" href="#admin-functionalities">Admin Functionalities</a></li>
                <li class="nav-item"><a class="nav-link" href="#student-functionalities">Student Functionalities</a></li>
            </ul>
        </nav>

        <!-- Student Functionalities Section -->
        <section id="student-functionalities" class="mt-4">
            <h2>Student Functionalities</h2>

            <!-- Add your HTML content for student functionalities here -->

            <?php
            // Example: Display list of students
            $studentQuery = "SELECT * FROM users";
            $studentResult = mysqli_query($mysql, $studentQuery);
            ?>
            <h3>List of Students</h3>
            <ul>
                <?php
                while ($studentRow = mysqli_fetch_assoc($studentResult)) {
                    echo "<li>{$studentRow['First_Name']} (ID: {$studentRow['UIN']})</li>";
                }
                ?>
            </ul>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>

<?php
// Close the database mysql when done
mysqli_close($mysql);
?>
