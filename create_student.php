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
        <div class="form-group">
            <label>Gender</label>
            <input type="text" class="form-control" name="gender_input" aria-label="Recipient's username" aria-describedby="button-addon2">
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
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" name="hispanic_check">
            <label class="form-check-label" for="flexCheckDefault">
                Hispanic or Latino?
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" name="citizen_check">
            <label class="form-check-label" for="flexCheckChecked">
                US Citizen?
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" name="generation_check">
            <label class="form-check-label" for="flexCheckChecked">
                First Generation?
            </label>
        </div>
        <div class="form-group">
            <label>Date of Birth</label>
            <input type="date" name="dob_input"
            placeholder="dd-mm-yyyy" value=""
            min="1950-01-01" max="2030-12-31" class="input-group date"> 
        </div>
        <div class="form-group">
            <label>GPA</label>
            <input type="number" min="0" max="4" step="0.001" class="form-control" name="gpa_input" aria-label="Recipient's username" aria-describedby="button-addon2">
        </div>
        <div class="form-group">
            <label>Major</label>
            <input type="text" class="form-control" name="major_input" aria-label="Recipient's username" aria-describedby="button-addon2">
        </div>
        <div class="form-group">
            <label>Minor #1</label>
            <input type="text" class="form-control" name="minor1_input" aria-label="Recipient's username" aria-describedby="button-addon2">
        </div>
        <div class="form-group">
            <label>Minor #2</label>
            <input type="text" class="form-control" name="minor2_input" aria-label="Recipient's username" aria-describedby="button-addon2">
        </div>
        <div class="form-group">
            <label>Expected Graduation Year</label>
            <input type="number" min="2023" max="2099" step="1" class="form-control" name="grad_year_input" aria-label="Recipient's username" aria-describedby="button-addon2">
        </div>
        <div class="form-group">
            <label>School</label>
            <input type="text" class="form-control" name="school_input" aria-label="Recipient's username" aria-describedby="button-addon2">
        </div>
        <div class="form-group">
            <label>Classification</label>
            <input type="text" class="form-control" name="classification_input" aria-label="Recipient's username" aria-describedby="button-addon2">
        </div>
        <div class="form-group">
            <label>Phone # (format 123456789)</label>
            <input type="number" min="1000000000" max="9999999999" class="form-control" name="phone_input" aria-label="Recipient's username" aria-describedby="button-addon2">
        </div>
        <div class="form-group">
            <label>Student Type</label>
            <input type="text" class="form-control" name="student_type_input" aria-label="Recipient's username" aria-describedby="button-addon2">
        </div>
        <button type="submit" class="btn btn-primary" name="new_user_button" value="Login Button">Create User</button>
    </form>

    <?php
        if(array_key_exists('new_user_button', $_POST)) {
            $sql = $_SESSION['sql'];
            $uinQuery = "SELECT max(UIN) FROM users;";
            $uinResult = mysqli_query($sql, $uinQuery);
            $uinRow = mysqli_fetch_assoc($uinResult);
            $newUIN = $uinRow['max(UIN)'] + 1;

            $username = $_POST['username_input'];
            $password = $_POST['password_input'];
            $role = "student";
            $fname = $_POST['fname_input'];
            $middle_initial = $_POST['middle_input'];
            $lname = $_POST['lname_input'];
            $email = $_POST['email_input'];
            $discord = $_POST['discord_input'];

            $gender = $_POST['gender_input'];
            if (isset($_POST['hispanic_check'])) {
                $hispanic = 1;
            }
            else {
                $hispanic = 0;
            }
            if (isset($_POST['citizen_check'])) {
                $citizen = 1;
            }
            else {
                $citizen = 0;
            }
            if (isset($_POST['generation_check'])) {
                $generation = 1;
            }
            else {
                $generation = 0;
            }

            $race = $_POST['race_select'];
            $dob = $_POST['dob_input'];
            $gpa = $_POST['gpa_input'];
            $major = $_POST['major_input'];
            $minor1 = $_POST['minor1_input'];
            $minor2 = $_POST['minor2_input'];
            $grad_year = $_POST['grad_year_input'];
            $school = $_POST['school_input'];
            $classification = $_POST['classification_input'];
            $phone = $_POST['phone_input'];
            $student_type = $_POST['student_type_input'];

            create_user($sql, $newUIN, $username, $password, $role, $fname, $middle_initial, $lname, $email, $discord);
            create_college_student($sql, $newUIN, $gender, $hispanic, $race, $citizen, $generation, $dob, $gpa, $major, $minor1, $minor2, $grad_year, $school, $classification, $phone, $student_type);
        }
        function create_user($sql, $uin, $username, $password, $role, $fname, $middle_initial, $lname, $email, $discord) {
            
            $insertQuery =  "INSERT INTO users VALUES ($uin, '$fname', '$middle_initial', '$lname', '$username', '$password', '$role', '$email', '$discord')";
            if ($sql->query($insertQuery)) {
                echo "New user successfully created\n";
            }
        }
        function create_college_student($sql, $uin, $gender, $hispanic, $race, $citizen, $generation, $dob, $gpa, $major, $minor1, $minor2, $grad_year, $shcool, $classification, $phone, $student_type) {
            $insertQuery =  "INSERT INTO college_student VALUES ($uin, '$gender', 0, '$race', CAST($citizen AS BINARY(1)), CAST($generation AS BINARY(1)), '$dob', $gpa, '$major', '$minor1', '$minor2', $grad_year, '$shcool', '$classification', $phone, '$student_type')";
            if ($sql->query($insertQuery)) {
                echo "New student successfully created\n";
                echo "Please navigate to the home page to log in";
            }
            else {
                echo $sql->error;
            }
        }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>