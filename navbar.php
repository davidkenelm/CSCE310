<?php
    session_start();
    include 'config.php';
?>
<html>
    <nav class="navbar navbar-light navbar-expand-lg" style="background-color: rgb(80,0,0);">
    <div class="container-fluid">
        <a class="navbar-brand text-light"  href="#">Cyber Security Center</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link active text-light" aria-current="page" href="./index.php">Home</a>
            </li>
            <?php if($_SESSION['role'] == 'admin') : ?>
                <li class="nav-item">
                <a class="nav-link text-light" href="./admin_profile.php">Admin Profile</a>
            <?php endif; ?>
            <?php if($_SESSION['role'] == 'student') : ?>
                <li class="nav-item">
                <a class="nav-link text-light" href="./student_profile.php">Student Profile</a>
            <?php endif; ?>
        </ul>
        </div>
    </div>
    </nav>
</html>