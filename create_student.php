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
            <label>Username</label>
            <input type="text" class="form-control" name="username_input" aria-label="Recipient's username" aria-describedby="button-addon2">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="text" class="form-control" name="password_input" aria-label="Recipient's username" aria-describedby="button-addon2">
        </div>
        <div class="form-group">
            <label>First Name</label>
            <input type="text" class="form-control" name="fname_input" aria-label="Recipient's username" aria-describedby="button-addon2">
        </div>
        <div class="form-group">
            <label>Middle Initial</label>
            <input type="text" class="form-control" name="middle_input" aria-label="Recipient's username" aria-describedby="button-addon2">
        </div>
        <div class="form-group">
            <label>Last Name</label>
            <input type="text" class="form-control" name="lname_input" aria-label="Recipient's username" aria-describedby="button-addon2">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" name="email_input" aria-label="Recipient's username" aria-describedby="button-addon2">
        </div>
        <div class="form-group">
            <label>Discord</label>
            <input type="text" class="form-control" name="discord_input" aria-label="Recipient's username" aria-describedby="button-addon2">
        </div>
        <button type="submit" class="btn btn-primary" name="new_user_button" value="Login Button">Create User</button>
    </form>

    <?php
        if(array_key_exists('new_user_button', $_POST)) {
            $sql = $_SESSION['sql'];
            $username = $_POST['username_input'];
            $password = $_POST['password_input'];
            $role = "student";
            $fname = $_POST['fname_input'];
            $middle_initial = $_POST['middle_input'];
            $lname = $_POST['lname_input'];
            $email = $_POST['email_input'];
            $discord = $_POST['discord_input'];
            create_user($sql, $username, $password, $role, $fname, $middle_initial, $lname, $email, $discord);
        }
        function create_user($sql, $username, $password, $role, $fname, $middle_initial, $lname, $email, $discord) {
            $uinQuery = "SELECT max(UIN) FROM users;";
            $uinResult = mysqli_query($sql, $uinQuery);
            $uinRow = mysqli_fetch_assoc($uinResult);
            $newUIN = $uinRow['max(UIN)'] + 1;
            $insertQuery =  "INSERT INTO users VALUES ($newUIN, '$fname', '$middle_initial', '$lname', '$username', '$password', '$role', '$email', '$discord')";
            if ($sql->query($insertQuery)) {
                echo "New user successfully created\n";
                echo "Please navigate to the home page to log in";
            }
        }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>