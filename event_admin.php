<?php
include 'config.php';  // Assuming this file establishes the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form is submitted for event creation
    if (isset($_POST['create_event'])) {
        // Get form data
        $eventData = array(
            'UIN' => $_POST['uin'],
            'Program_Num' => $_POST['program_num'],
            'Start_Date' => $_POST['start_date'],
            'Time' => $_POST['time'],
            'Location' => $_POST['location'],
            'End_Date' => $_POST['end_date'],
            'Event_Type' => $_POST['event_type']
        );

        // Call the insertEvent function
        insertEvent($mysql, $eventData);

        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }

    if (isset($_POST['updated_event'])) {
        // Get form data
        $eventIDToUpdate = $_POST['updated_event_id'];

        // Initialize updatedEventData array
        $updatedEventData = array();

        // Function to check if a field should be updated and add it to the array
        function checkAndUpdateField($fieldName) {
            global $updatedEventData;
            if (isset($_POST[$fieldName]) && $_POST[$fieldName] !== '') {
                $updatedEventData[$fieldName] = $_POST[$fieldName];
            }
        }

        // Check and update each field
        checkAndUpdateField('updated_start_date');
        checkAndUpdateField('updated_time');
        checkAndUpdateField('updated_location');
        checkAndUpdateField('attendee_uin');

        // Call the updateEvent function
        updateEvent($mysql, $eventIDToUpdate, $updatedEventData);

        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }


    // Check if the form is submitted for event deletion
    if (isset($_POST['delete_event'])) {
        // Get form data
        $eventIDToDelete = $_POST['update_event_id'];

        // Call the deleteEvent function
        deleteEvent($mysql, $eventIDToDelete);

        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

// Function to insert an event
function insertEvent($mysql, $eventData) {
    // Validate and sanitize data
    $uin = intval($eventData['UIN']);
    $programNum = intval($eventData['Program_Num']);
    $startDate = $eventData['Start_Date'];
    $time = $eventData['Time'];
    $location = $eventData['Location'];
    $endDate = $eventData['End_Date'];
    $eventType = $eventData['Event_Type'];

    // Prepare a statement
    $stmt = $mysql->prepare("INSERT INTO events (UIN, Program_Num, Start_Date, Time, Location, End_Date, Event_Type) 
                             VALUES (?, ?, ?, ?, ?, ?, ?)");

    // Check if the prepare operation is successful
    if (!$stmt) {
        die("Error: " . $mysql->error);
    }

    // Bind parameters
    $stmt->bind_param("iisssss", $uin, $programNum, $startDate, $time, $location, $endDate, $eventType);

    // Execute the query
    if ($stmt->execute()) {
        echo "Event inserted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

function getAllEventIDs($mysql) {
    $eventIDs = array();

    $sql = "SELECT Event_ID FROM events";
    $result = $mysql->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $eventIDs[] = $row['Event_ID'];
        }
        $result->free_result();
    }

    return $eventIDs;
}

function updateEventDropdown($mysql) {
    $allEventIDs = getAllEventIDs($mysql);

    echo '<select name="update_event_id">';
    foreach ($allEventIDs as $id) {
        echo "<option value=\"$id\">$id</option>";
    }
    echo '</select>';
}

function addAttendee($mysql, $eventID, $attendeeUIN) {
    // Check if the attendee is already registered for the event
    $checkQuery = "SELECT * FROM event_tracking WHERE Event_ID = ? AND UIN = ?";
    $checkStmt = $mysql->prepare($checkQuery);
    $checkStmt->bind_param("ii", $eventID, $attendeeUIN);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo "Attendee is already registered for the event.";
        $checkStmt->close();
        return;
    }

    $checkStmt->close();

    // Insert a new record into the event_tracking table
    $insertQuery = "INSERT INTO event_tracking (Event_ID, UIN) VALUES (?, ?)";
    $insertStmt = $mysql->prepare($insertQuery);
    $insertStmt->bind_param("ii", $eventID, $attendeeUIN);

    if ($insertStmt->execute()) {
        echo "Attendee added successfully!";
    } else {
        echo "Error: " . $insertStmt->error;
    }

    $insertStmt->close();
}

// Function to update an event
function updateEvent($mysql, $eventID, $updatedEventData) {
    // Initialize setClause to an empty string
    $setClause = '';

    // Check and add Start_Date
    if (isset($updatedEventData['updated_start_date'])) {
        $setClause .= "Start_Date = ?, ";
    }

    // Check and add Time
    if (isset($updatedEventData['updated_time'])) {
        $setClause .= "Time = ?, ";
    }

    // Check and add Location
    if (isset($updatedEventData['updated_location'])) {
        $setClause .= "Location = ?, ";
    }

    // Add Attendee
    if (isset($updatedEventData['attendee_uin'])) {
        addAttendee($mysql, $eventID, $updatedEventData['attendee_uin']);
    }

    // Remove the trailing comma and space if the setClause is not empty
    if (!empty($setClause)) {
        $setClause = rtrim($setClause, ', ');

        // Construct the SQL query
        $sql = "UPDATE events SET $setClause WHERE Event_ID = ?";

        // Prepare a statement
        $stmt = $mysql->prepare($sql);

        // Check if the prepare operation is successful
        if (!$stmt) {
            die("Error: " . $mysql->error);
        }

        // Dynamically bind parameters based on provided fields
        $bindTypes = ''; // String to hold bind types
        $bindParams = array(); // Array to hold bind parameters

        // Check and bind Start_Date
        if (isset($updatedEventData['updated_start_date'])) {
            $bindTypes .= 's';
            $bindParams[] = $updatedEventData['updated_start_date'];
        }

        // Check and bind Time
        if (isset($updatedEventData['updated_time'])) {
            $bindTypes .= 's';
            $bindParams[] = $updatedEventData['updated_time'];
        }

        // Check and bind Location
        if (isset($updatedEventData['updated_location'])) {
            $bindTypes .= 's';
            $bindParams[] = $updatedEventData['updated_location'];
        }

        // Add the Event_ID parameter
        $bindTypes .= 'i';
        $bindParams[] = $eventID;

        // Bind parameters dynamically
        $stmt->bind_param($bindTypes, ...$bindParams);

        // Execute the query
        if ($stmt->execute()) {
            echo "Event updated successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        // If setClause is empty, no fields to update
        echo "No fields to update.";
    }
}

function deleteEvent($mysql, $eventID) {
    // Delete from the event_tracking table
    $deleteEventTrackingQuery = "DELETE FROM event_tracking WHERE Event_ID = ?";
    $deleteEventTrackingStmt = $mysql->prepare($deleteEventTrackingQuery);
    $deleteEventTrackingStmt->bind_param("i", $eventID);

    // Execute the query
    if ($deleteEventTrackingStmt->execute()) {
        echo "Event deleted successfully from the event_tracking table!";
    } else {
        echo "Error: " . $deleteEventTrackingStmt->error;
    }

    // Close the statement
    $deleteEventTrackingStmt->close();

    // Delete from the events table
    $deleteEventQuery = "DELETE FROM events WHERE Event_ID = ?";
    $deleteEventStmt = $mysql->prepare($deleteEventQuery);
    $deleteEventStmt->bind_param("i", $eventID);

    // Execute the query
    if ($deleteEventStmt->execute()) {
        echo "Event deleted successfully from the events table!";
    } else {
        echo "Error: " . $deleteEventStmt->error;
    }

    // Close the statement
    $deleteEventStmt->close();
}


function viewEvent($mysql, $eventToView) {
    // Validate and sanitize the input
    $eventID = intval($eventToView);

    // Prepare the SQL query with a parameter for Event_ID
    $sql = "SELECT * FROM event_information WHERE Event_ID = ?";
    
    // Prepare a statement
    $stmt = $mysql->prepare($sql);

    // Bind the Event_ID parameter
    $stmt->bind_param("i", $eventToView);

    // Execute the query
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo '<br><b>Event Information</b>';
            echo '<table style="margin-top: 20px; border-collapse: collapse; width: 100%;"> 
                  <tr> 
                      <th style="padding: 10px; border: 1px solid #ddd;">Event ID</th> 
                      <th style="padding: 10px; border: 1px solid #ddd;">Start Date</th>
                      <th style="padding: 10px; border: 1px solid #ddd;">Time</th> 
                      <th style="padding: 10px; border: 1px solid #ddd;">Location</th> 
                      <th style="padding: 10px; border: 1px solid #ddd;">Creator UIN</th>
                      <th style="padding: 10px; border: 1px solid #ddd;">Event Type</th>
                      <th style="padding: 10px; border: 1px solid #ddd;">Attendee UIN</th>
                  </tr>';
        
            while ($row = $result->fetch_assoc()) {
                $eventID = $row['Event_ID'];
                $startDate = $row['Start_Date'];
                $time = $row['Time'];
                $location = $row['Location'];
                $creatorUIN = $row['UIN'];
                $eventType = $row['Event_Type'];
                $attendeeUIN = $row['Attendee_UIN'];
        
                echo '<tr> 
                    <td style="padding: 10px; border: 1px solid #ddd;">'.$eventID.'</td> 
                    <td style="padding: 10px; border: 1px solid #ddd;">'.$startDate.'</td> 
                    <td style="padding: 10px; border: 1px solid #ddd;">'.$time.'</td> 
                    <td style="padding: 10px; border: 1px solid #ddd;">'.$location.'</td> 
                    <td style="padding: 10px; border: 1px solid #ddd;">'.$creatorUIN.'</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">'.$eventType.'</td> 
                    <td style="padding: 10px; border: 1px solid #ddd;">'.$attendeeUIN.'</td>
                </tr>';
            }
        
            echo '</table>';
            $result->free_result();
        } else {
            echo "No results found for the given Event ID.";
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management</title>
</head>
<body>
    <h2>Create Event</h2>
    <form action="" method="post">
        <!-- Input fields for event creation -->
        <!-- Adjust the input names and types based on your actual form fields -->
        UIN: <input type="text" name="uin"><br>
        Program Num: <input type="text" name="program_num"><br>
        Start Date: <input type="date" name="start_date"><br>
        Time: <input type="time" name="time"><br>
        Location: <input type="text" name="location"><br>
        End Date: <input type="date" name="end_date"><br>
        Event Type: <input type="text" name="event_type"><br>
        <input type="submit" name="create_event" value="Create Event">
    </form>

    <?php
    // Check if the form is submitted for event creation
    if (isset($_POST['create_event'])) {
        // Get form data
        $eventData = array(
            'UIN' => $_POST['uin'],
            'Program_Num' => $_POST['program_num'],
            'Start_Date' => $_POST['start_date'],
            'Time' => $_POST['time'],
            'Location' => $_POST['location'],
            'End_Date' => $_POST['end_date'],
            'Event_Type' => $_POST['event_type']
        );

        // Call the insertEvent function
        insertEvent($mysql, $eventData);
    }
    ?>

    <h2>Update Event</h2>
    <form action="" method="post">
        <!-- Dropdown list for selecting Event_ID to update -->
        Event ID to Update: <?php updateEventDropdown($mysql); ?><br>
        Updated Start_Date: <input type="date" name="updated_start_date"><br>
        Updated Time: <input type="time" name="updated_time"><br>
        Updated Location: <input type="text" name="updated_location"><br>
        Add Attendee UIN: <input type="text" name="attendee_uin"><br>
        <input type="submit" name="update_event" value="Update Event">
    </form>

    <?php
    // Check if the form is submitted for event update
    if (isset($_POST['update_event'])) {
        // Get form data
        $eventIDToUpdate = $_POST['update_event_id'];

        // Initialize updatedEventData array
        $updatedEventData = array();

        // Check if each field should be updated and add it to the array
        if ($_POST['updated_start_date'] != '') {
            $updatedEventData['updated_start_date'] = $_POST['updated_start_date'];
        }

        if ($_POST['updated_time'] != '') {
            $updatedEventData['updated_time'] = $_POST['updated_time'];
        }

        if ($_POST['updated_location'] != '') {
            $updatedEventData['updated_location'] = $_POST['updated_location'];
        }
        if ($_POST['attendee_uin'] != '') {
            $updatedEventData['attendee_uin'] = $_POST['attendee_uin'];
        }

        // Call the updateEvent function
        updateEvent($mysql, $eventIDToUpdate, $updatedEventData);
    }
    ?>


    <h2>Delete Event</h2>
    <form action="" method="post">
        <!-- Input field for event deletion -->
        <!-- Adjust the input names and types based on your actual form fields -->
        Event ID to Delete: <?php updateEventDropdown($mysql); ?><br>
        <input type="submit" name="delete_event" value="Delete Event">
    </form>

    <?php
    // Check if the form is submitted for event deletion
    if (isset($_POST['delete_event'])) {
        // Get form data
        $eventIDToDelete = $_POST['update_event_id'];

        // Call the deleteEvent function
        deleteEvent($mysql, $eventIDToDelete);
    }
    ?>

    <h2>Event Information</h2>
    <form action="" method="post">
        Event to View: <?php updateEventDropdown($mysql); ?><br>
        <input type="submit" name="view_event" value="View Event">
    </form>

    <?php
    // Check if the form is submitted for event deletion
    if (isset($_POST['view_event'])) {
        // Get form data
        $eventToView = $_POST['update_event_id'];

        // Call the deleteEvent function
        viewEvent($mysql, $eventToView);
    }
    ?>  

</body>
</html>

<?php
$mysql->close();
?>