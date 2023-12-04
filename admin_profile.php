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
        $UIN = $_SESSION['UIN'];
        $sql = $_SESSION['sql'];
        $profileQuery = "SELECT * FROM users WHERE UIN='$UIN'";
        $profileResult = mysqli_query($_SESSION['sql'], $profileQuery);
        if (mysqli_num_rows($profileResult) == 0) {
            echo "Incorrect username or password";
        }
        else if (mysqli_num_rows($profileResult) == 1){
            $profileRow = mysqli_fetch_assoc($profileResult);
            $username = $profileRow['Username'];
            $password = $profileRow['Password'];
            $role = $profileRow['User_Type'];
            $UIN = $profileRow['UIN'];
            $fname = $profileRow['First_Name'];
            $middle_initial = $profileRow['M_Initial'];
            $lname = $profileRow['Last_Name'];
            $email = $profileRow['Email'];
            $discord = $profileRow['Discord_Name'];
        }
        else {
            echo "duplicate user detected";
        }
    ?>
    <form method='post'>
        <div class="form-group">
            <label>UIN</label>
            <input type="text" class="form-control" placeholder="<?php echo $UIN; ?>" name="UIN_field" disabled>
        </div>
        <div class="form-group">
            <label>Username</label>
            <input type="text" class="form-control" name="username_input" placeholder="<?php echo $username; ?>" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="submit" name="username_button">Update</button>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="text" class="form-control" name="password_input" placeholder="<?php echo $password; ?>" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="submit" name="password_button">Update</button>
        </div>
        <div class="form-group">
            <label>Role</label>
            <input type="text" class="form-control" placeholder="<?php echo $role; ?>" name="role_field" disabled>
        </div>
        <div class="form-group">
            <label>First Name</label>
            <input type="text" class="form-control" name="fname_input" placeholder="<?php echo $fname; ?>" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="submit" name="fname_button">Update</button>
        </div>
        <div class="form-group">
            <label>Middle Initial</label>
            <input type="text" class="form-control" name="middle_input" placeholder="<?php echo $middle_initial; ?>" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="submit" name="middle_button">Update</button>
        </div>
        <div class="form-group">
            <label>Last Name</label>
            <input type="text" class="form-control" name="lname_input" placeholder="<?php echo $lname; ?>" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="submit" name="lname_button">Update</button>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" name="email_input" placeholder="<?php echo $email; ?>" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="submit" name="email_button">Update</button>
        </div>
        <div class="form-group">
            <label>Discord</label>
            <input type="text" class="form-control" name="discord_input" placeholder="<?php echo $discord; ?>" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="submit" name="discord_button">Update</button>
        </div>
        <button type="submit" class="btn btn-primary" name="delete_button">Delete User</button>
    </form>

    <?php
        if(array_key_exists('username_button', $_POST)) {
            $value = $_POST['username_input'];
            update_user($sql, $UIN, "Username", "$value"); 
        }
        if(array_key_exists('password_button', $_POST)) {
            $value = $_POST['password_input'];
            update_user($sql, $UIN, "Password", "$value"); 
        }
        if(array_key_exists('fname_button', $_POST)) {
            $value = $_POST['fname_input'];
            update_user($sql, $UIN, "First_Name", "$value"); 
        }
        if(array_key_exists('middle_button', $_POST)) {
            $value = $_POST['middle_input'];
            update_user($sql, $UIN, "M_Initial", "$value"); 
        }
        if(array_key_exists('lname_button', $_POST)) {
            $value = $_POST['lname_input'];
            update_user($sql, $UIN, "Last_Name", "$value"); 
        }
        if(array_key_exists('email_button', $_POST)) {
            $value = $_POST['email_input'];
            update_user($sql, $UIN, "Email", "$value"); 
        }
        if(array_key_exists('discord_button', $_POST)) {
            $value = $_POST['discord_input'];
            update_user($sql, $UIN, "Discord_Name", "$value"); 
        }
        if(array_key_exists('delete_button', $_POST)) {
            delete_user($sql, $UIN);
        }
        function update_user($sql, $uin, $column, $value) {
            $updateQuery = "UPDATE users SET $column = \"$value\" WHERE UIN = $uin";
            if ($sql->query($updateQuery)) {
                echo "successful update\n";
                header("Location: http://localhost/CSCE310/student_profile.php");
            }
            else {
                echo "error while updating\n";
                echo "Reason: ", $sql->error;
            }
        }
        function delete_user($sql, $uin) {
            $updateQuery = "DELETE FROM users WHERE UIN = $uin";
            if ($sql->query($updateQuery)) {
                echo "successful delete\n";
                echo "please navigate to the hompage\n";
                session_destroy();
                //header("Location: http://localhost/CSCE310/student_profile.php");
            }
            else {
                echo "error while deleting";
                echo "Reason: ", $sql->error;
            }
        }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>