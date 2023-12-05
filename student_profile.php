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
        <div class="form-group">
            <label>Gender</label>
            <input type="text" class="form-control" name="gender_input" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="submit" name="gender_button">Update</button>
        </div>
        <label>Race</label>
        <select class="form-select" aria-label="Default select example" name="race_select">
            <option selected>Select</option>
            <option value="American Indian or Alaska Native">American Indian or Alaska Native</option>
            <option value="Asian">Asian</option>
            <option value="Black or African American">Black or African American</option>
            <option value="Native Hawaiian or Other Pacific Islander">Native Hawaiian or Other Pacific Islander</option>
            <option value="White">White</option>
        </select>
        <button class="btn btn-outline-secondary" type="submit" name="race_button">Update</button>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" name="hispanic_check">
            <label class="form-check-label" for="flexCheckDefault">
                Hispanic or Latino?
            </label>
            <button class="btn btn-outline-secondary" type="submit" name="hispanic_button">Update</button>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" name="citizen_check">
            <label class="form-check-label" for="flexCheckChecked">
                US Citizen?
            </label>
            <button class="btn btn-outline-secondary" type="submit" name="citizen_button">Update</button>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" name="generation_check">
            <label class="form-check-label" for="flexCheckChecked">
                First Generation?
            </label>
            <button class="btn btn-outline-secondary" type="submit" name="generation_button">Update</button>
        </div>
        <div class="form-group">
            <label>Date of Birth</label>
            <input type="date" name="dob_input"
            placeholder="dd-mm-yyyy" value=""
            min="1950-01-01" max="2030-12-31" class="input-group date"> 
            <button class="btn btn-outline-secondary" type="submit" name="dob_button">Update</button>
        </div>
        <div class="form-group">
            <label>GPA</label>
            <input type="number" min="0" max="4" step="0.001" class="form-control" name="gpa_input" aria-label="Recipient's username" aria-describedby="button-addon2">
        </div>
        <div class="form-group">
            <label>Major</label>
            <input type="text" class="form-control" name="major_input" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="submit" name="major_button">Update</button>
        </div>
        <div class="form-group">
            <label>Minor #1</label>
            <input type="text" class="form-control" name="minor1_input" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="submit" name="minor1_button">Update</button>
        </div>
        <div class="form-group">
            <label>Minor #2</label>
            <input type="text" class="form-control" name="minor2_input" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="submit" name="minor2_button">Update</button>
        </div>
        <div class="form-group">
            <label>Expected Graduation Year</label>
            <input type="number" min="2023" max="2099" step="1" class="form-control" name="grad_year_input" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="submit" name="grad_year_button">Update</button>
        </div>
        <div class="form-group">
            <label>School</label>
            <input type="text" class="form-control" name="school_input" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="submit" name="school_button">Update</button>
        </div>
        <div class="form-group">
            <label>Classification</label>
            <input type="text" class="form-control" name="classification_input" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="submit" name="classification_button">Update</button>
        </div>
        <div class="form-group">
            <label>Phone # (format 1234567890)</label>
            <input type="number" min="1000000000" max="9999999999" class="form-control" name="phone_input" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="submit" name="phone_button">Update</button>
        </div>
        <div class="form-group">
            <label>Student Type</label>
            <input type="text" class="form-control" name="student_type_input" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="submit" name="student_type_button">Update</button>
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
        if(array_key_exists('gender_button', $_POST)) {
            $value = $_POST['gender_input'];
            update_user($sql, $UIN, "Gender", "$value"); 
        }
        if(array_key_exists('race_button', $_POST)) {
            $value = $_POST['race_select'];
            update_user($sql, $UIN, "Race", "$value"); 
        }
        if(array_key_exists('hispanic_button', $_POST)) {
            $value = $_POST['hispanic_check'];
            update_user($sql, $UIN, "Hispanic_Latino", "$value"); 
        }
        if(array_key_exists('citizen_button', $_POST)) {
            $value = $_POST['citizen_check'];
            update_user($sql, $UIN, "US_Citizen", "$value"); 
        }
        if(array_key_exists('generation_button', $_POST)) {
            $value = $_POST['generation_check'];
            update_user($sql, $UIN, "First_Generation", "$value"); 
        }
        if(array_key_exists('dob_button', $_POST)) {
            $value = $_POST['dob_input'];
            update_user($sql, $UIN, "DOB", "$value"); 
        }
        if(array_key_exists('gpa_buton', $_POST)) {
            $value = $_POST['gpa_input'];
            update_user($sql, $UIN, "GPA", "$value"); 
        }
        if(array_key_exists('major_button', $_POST)) {
            $value = $_POST['major_input'];
            update_user($sql, $UIN, "Major", "$value"); 
        }
        if(array_key_exists('minor1_button', $_POST)) {
            $value = $_POST['minor1_input'];
            update_user($sql, $UIN, "Minor #1", "$value"); 
        }
        if(array_key_exists('minor2_button', $_POST)) {
            $value = $_POST['minor2_input'];
            update_user($sql, $UIN, "Minor #2", "$value"); 
        }
        if(array_key_exists('grad_year_button', $_POST)) {
            $value = $_POST['grad_year_input'];
            update_user($sql, $UIN, "Expected_Graduation", "$value"); 
        }
        if(array_key_exists('school_button', $_POST)) {
            $value = $_POST['school_input'];
            update_user($sql, $UIN, "Discord_Name", "$value"); 
        }
        if(array_key_exists('classification_button', $_POST)) {
            $value = $_POST['classification_input'];
            update_user($sql, $UIN, "Classification", "$value"); 
        }
        if(array_key_exists('phone_button', $_POST)) {
            $value = $_POST['phone_input'];
            update_user($sql, $UIN, "Phone", "$value"); 
        }
        if(array_key_exists('student_type_button', $_POST)) {
            $value = $_POST['student_type_input'];
            update_user($sql, $UIN, "Student Type", "$value"); 
        }
        if(array_key_exists('delete_button', $_POST)) {
            delete_user($sql, $UIN);
        }
        function update_user($sql, $uin, $column, $value) {
            $updateQuery = "UPDATE users SET $column = \"$value\" WHERE UIN = $uin";
            if ($sql->query($updateQuery)) {
                echo "successful update";
                header("Location: http://localhost/CSCE310/student_profile.php");
            }
            else {
                echo "error while updating";
                echo "Reason: ", $sql->error;
            }
        }
        function update_student($sql, $uin, $column, $value) {
            $updateQuery = "UPDATE college_student SET $column = \"$value\" WHERE UIN = $uin";
            if ($sql->query($updateQuery)) {
                echo "successful update";
                header("Location: http://localhost/CSCE310/student_profile.php");
            }
            else {
                echo "error while updating";
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
                echo "error while deleting\n";
                echo "Reason: ", $sql->error;
            }
        }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>