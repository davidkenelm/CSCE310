<?php
    session_start();
    if(!isset($_SESSION['role'])){ //if login in session is not set
        header("Location: http://localhost/CSCE310/login.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Student Tracking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body style="background-color: rgb(240, 241, 245);">
    <!-- Basic navigation links -->
    <?php 
            include 'navbar.php';
        ?>
    <div class="container mt-5">
        <h1>Home Page</h1>
        <?php if(isset($_SESSION['role'])) : ?>
            <form method='post'>
                <button type="submit" class="btn btn-primary" name="logout_button" value="Logout Button">Logout</button>
            </form>
            <?php
                if(array_key_exists('logout_button', $_POST)) {
                    session_destroy();
                    header("Location: http://localhost/CSCE310/login.php");
                }
            ?>
        <?php endif; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
