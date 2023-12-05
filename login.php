<?php
    session_start();
    include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body style="background-color: rgb(240, 241, 245);">
    <!-- Basic navigation links -->
    <?php 
            include 'navbar.php';
        ?>
    <form method='post'>
        <div class="form-group">
            <label for="exampleInputEmail1">Username</label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter username" name="email_input">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Enter password" name="password_input">
        </div>
        <button type="submit" class="btn btn-primary" name="login_button" value="Login Button">Submit</button>
        <button type="submit" class="btn btn-primary" name="new_student_button">Create Student User</button>
        <button type="submit" class="btn btn-primary" name="new_admin_button">Create Admin User</button>
    </form>

    <?php
        if(array_key_exists('login_button', $_POST)) {
            login(); 
        }
        if(array_key_exists('new_student_button', $_POST)) {
            new_student(); 
        }
        if(array_key_exists('new_admin_button', $_POST)) {
            new_admin(); 
        }
        function login() {
            $username = $_POST['email_input'];
            $password = $_POST['password_input'];
            $loginQuery = "SELECT * FROM users WHERE Username='$username' AND Password='$password'";
            $loginResult = mysqli_query($_SESSION['sql'], $loginQuery);
            if (mysqli_num_rows($loginResult) == 0) {
                echo "Incorrect username or password";
            }
            else if (mysqli_num_rows($loginResult) == 1){
                $loginRow = mysqli_fetch_assoc($loginResult);
                $_SESSION['username'] = $loginRow['Username'];
                $_SESSION['role'] = $loginRow['User_Type'];
                $_SESSION['UIN'] = $loginRow['UIN'];
                //echo "<li>Username: {$_SESSION['username']}, Password: {$_SESSION['password']} (Role: {$_SESSION['role']})</li>";
                header("Location: http://localhost/CSCE310/index.php");
            }
            else {
                echo "duplicate user detected";
            }
        }
        function new_student() {
            header("Location: http://localhost/CSCE310/create_student.php");
        }
        function new_admin() {
            header("Location: http://localhost/CSCE310/create_admin.php");
        }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>