<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dynamic Pop-up Form</title>
    <style>
        /* Styling for the pop-up */
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
        /* Additional style for labels */
        label {
            display: block;
            margin-bottom: 5px;
        }
    </style>
    <script>
        function togglePopup() {
            var popup = document.getElementById('popup');
            popup.style.display = (popup.style.display === 'none') ? 'block' : 'none';
        }

        function submitForm() {
            var popupContent = document.getElementById('popupContent');
            popupContent.innerHTML = "SUBMITTED";
        }
    </script>
</head>
<body>

    <!-- Button to trigger pop-up -->
    <button onclick="togglePopup()">Open Form</button>

    <!-- Pop-up form -->
    <div class="popup" id="popup">
        <span class="close-btn" onclick="togglePopup()">X</span>
        <div id="popupContent">
            <form onsubmit="submitForm(); return false;"> <!-- Prevent default form submission -->
                <label for="field1">Field 1:</label>
                <input type="text" id="field1" name="field1">

                <label for="field2">Field 2:</label>
                <input type="text" id="field2" name="field2">
                <!-- Add other form fields as needed -->

                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

</body>
</html>
