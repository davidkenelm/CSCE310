<?php
include 'config.php';

function displayDocuments($mysql, $appNum) {
    $sql = "SELECT * FROM documentation WHERE App_Num = '$appNum'";
    $result = $mysql->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Uploaded Documents</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Document Type</th><th>File</th><th>Edit</th><th>Delete</th></tr>";

        while ($row = $result->fetch_assoc()) {
            $filename = basename($row['Link']);

            echo "<tr>";
            echo "<td>" . $row['Doc_Type'] . "</td>";
            echo "<td><a href='download.php?file=" . $filename . "' target='_blank'>" . $filename . "</a></td>";
            echo "<td><a href='javascript:void(0);' onclick='editDocument(" . $row['Doc_Num'] . "," . $row['App_Num'] . ",\"" . $row['Doc_Type'] . "\")'>Edit</a></td>";
            echo "<td><a href='document_student.php?action=delete&docNum=" . $row['Doc_Num'] . "'>Delete</a></td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No documents uploaded for this application.";
    }
}

function deleteDocument($mysql, $docNum) {
    $sql = "DELETE FROM documentation WHERE Doc_Num = '$docNum'";
    
    if ($mysql->query($sql) === TRUE) {
        echo "Document deleted successfully.";
    } else {
        echo "Error deleting document: " . $mysql->error;
    }
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == 'delete' && isset($_GET['docNum'])) {
        $docNum = $_GET['docNum'];
        deleteDocument($mysql, $docNum);
    }
}

if (isset($_POST['submit'])) {
    $appNum = $_POST['appNum'];

    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["file"]["name"]);

    $extension = pathinfo($targetFile, PATHINFO_EXTENSION);

    if (!in_array($extension, ['pdf', 'docx'])) {
        echo "You file extension must be .pdf or .docx";
    } else if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
        $link = $targetFile;

        $sql = "INSERT INTO documentation (App_Num, Link, Doc_Type) VALUES ('$appNum', '$link', '$extension')";

        if ($mysql->query($sql) === TRUE) {
            echo "File uploaded successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $mysql->error;
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newAppNum = isset($_POST['field1']) ? $_POST['field1'] : '';
    $newDocType = isset($_POST['field2']) ? $_POST['field2'] : '';
    $docNum = isset($_POST['docNum']) ? $_POST['docNum'] : '';

    if (isset($_FILES['fileInput']) && $_FILES['fileInput']['error'] == 0) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["fileInput"]["name"]);

        $extension = pathinfo($targetFile, PATHINFO_EXTENSION);

        if (!in_array($extension, ['pdf', 'docx'])) {
            echo "You file extension must be .pdf or .docx";
            exit;
        }

        if (move_uploaded_file($_FILES["fileInput"]["tmp_name"], $targetFile)) {
            $link = $targetFile;

            $updateSql = "UPDATE documentation SET App_Num = '$newAppNum', Doc_Type = '$newDocType', Link = '$link' WHERE Doc_Num = '$docNum'";
            if ($mysql->query($updateSql) === TRUE) {
                echo "Document updated successfully.";
            } else {
                echo "Error updating document: " . $mysql->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        $updateSql = "UPDATE documentation SET App_Num = '$newAppNum', Doc_Type = '$newDocType' WHERE Doc_Num = '$docNum'";
        if ($mysql->query($updateSql) === TRUE) {
            echo "Document updated successfully.";
        } else {
            echo "Error updating document: " . $mysql->error;
        }
    }

    echo "<script>closePopup();</script>";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Upload and Management</title>

    <style>
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            z-index: 9999;
        }

        .close-btn {
            position: absolute;
            top: 5px;
            right: 10px;
            cursor: pointer;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }
    </style>

    <script>
        function editDocument(docNum, appNum, docType) {
            var popup = document.getElementById('popup');
            var field1 = document.getElementById('field1');
            var field2 = document.getElementById('field2');
            var docNumInput = document.getElementById('docNumInput');
            var fileInput = document.getElementById('fileInput');

            field1.value = appNum;
            field2.value = docType;
            docNumInput.value = docNum;
            fileInput.value = '';

            popup.style.display = 'block';
        }

        function closePopup() {
            var popup = document.getElementById('popup');
            popup.style.display = 'none';
        }
    </script>

</head>
<body>
    <h1>Document Upload and Management</h1>

    <h2>Upload Document</h2>
    <form action="document_student.php" method="post" enctype="multipart/form-data">
        <label for="appNum">Application Number:</label>
        <input type="text" name="appNum" required>
        
        <label for="file">Choose File:</label>
        <input type="file" name="file" required>
        
        <button type="submit" name="submit">Upload</button>
    </form>

    <h2>View Documents</h2>
    <form action="" method="get">
        <label for="appNumView">Application Number:</label>
        <input type="text" name="appNum" required>
        <button type="submit">View Documents</button>
    </form>

    <?php
    if (isset($_GET['appNum'])) {
        $appNum = $_GET['appNum'];
        displayDocuments($mysql, $appNum);
    }
    ?>

    <div class="popup" id="popup">
        <span class="close-btn" onclick="closePopup()">X</span>
        <form id="editForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <label for="field1">Application Number:</label>
            <input type="text" id="field1" name="field1" required>

            <label for="field2">Document Type:</label>
            <input type="text" id="field2" name="field2" required>

            <label for="fileInput">Choose New File:</label>
            <input type="file" id="fileInput" name="fileInput">

            <input type="hidden" id="docNumInput" name="docNum" value="">
            <button type="submit">Submit</button>
        </form>
    </div>

</body>
</html>

<?php
$mysql->close();
?>
