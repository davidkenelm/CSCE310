<?php
include 'config.php';

//Checks to ensure the UIN is set to make sure the user is logged in

if (!isset($_SESSION['UIN'])) {
    header("Location: login.php");
    exit();
}

//storing UIN variable for use in insertion
$uin = $_SESSION['UIN'];

//Logic for event creation, uses uin from session variable
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['create_event'])) {
        $eventData = array(
            'UIN' => $uin,
            'Program_Num' => $_POST['program_num'],
            'Start_Date' => $_POST['start_date'],
            'Time' => $_POST['time'],
            'Location' => $_POST['location'],
            'End_Date' => $_POST['end_date'],
            'Event_Type' => $_POST['event_type']
        );

        insertEvent($mysql, $eventData, $uin);

        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }

    //Variable query event updating
    if (isset($_POST['updated_event'])) {
        $eventIDToUpdate = $_POST['updated_event_id'];

        $updatedEventData = array();

        function checkAndUpdateField($fieldName) {
            global $updatedEventData;
            if (isset($_POST[$fieldName]) && $_POST[$fieldName] !== '') {
                $updatedEventData[$fieldName] = $_POST[$fieldName];
            }
        }

        //Selective updates
        checkAndUpdateField('update_program_num');
        checkAndUpdateField('updated_start_date');
        checkAndUpdateField('updated_time');
        checkAndUpdateField('updated_location');
        checkAndUpdateField('attendee_uin');
        checkAndUpdateField('remove_attendee_uin');

        updateEvent($mysql, $eventIDToUpdate, $updatedEventData);

        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }


    if (isset($_POST['delete_event'])) {
        $eventIDToDelete = $_POST['update_event_id'];

        deleteEvent($mysql, $eventIDToDelete);

        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

//Inserting event into database
function insertEvent($mysql, $eventData, $uin) {
    //Checking if fields have data, otherwise the data is null
    $programNum = isset($eventData['Program_Num']) ? intval($eventData['Program_Num']) : null;
    $startDate = !empty($eventData['Start_Date']) ? $eventData['Start_Date'] : null;
    $time = isset($eventData['Time']) ? $eventData['Time'] : null;
    $location = isset($eventData['Location']) ? $eventData['Location'] : null;
    $endDate = !empty($eventData['End_Date']) ? $eventData['End_Date'] : null;
    $eventType = isset($eventData['Event_Type']) ? $eventData['Event_Type'] : null;

    //preparing statement for bind to prevent injeciton
    $stmt = $mysql->prepare("INSERT INTO events (UIN, Program_Num, Start_Date, Time, Location, End_Date, Event_Type) 
                             VALUES (?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Error: " . $mysql->error);
    }

    $stmt->bind_param("iisssss", $uin, $programNum, $startDate, $time, $location, $endDate, $eventType);

    if ($stmt->execute()) {
        echo "Event inserted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
//getAllEventIDs and updateEventDropdown are used so that the user can see all events that can be...
//maniuplated or viewed
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

    echo '<select name="update_event_id" class="form-select" aria-label="Select Event">';
    foreach ($allEventIDs as $id) {
        echo "<option value=\"$id\">$id</option>";
    }
    echo '</select>';
}

//Gets all available programs for easier event creation
function getAllProgramNums($mysql) {
    $programNums = array();

    $sql = 'SELECT Program_Num FROM programs';
    $result = $mysql->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $programNums[] = $row['Program_Num'];
        }
        $result->free_result();
    }

    return $programNums;
}
//Creates dropdown of available programs
function updateProgramDropdown($mysql) {
    $allPrograms = getAllProgramNums($mysql);

    echo '<select name="program_num" class="form-select" aria-label="Program Num">';
    echo "<option value=\"''\">----</option>";
    foreach ($allPrograms as $num) {
        echo "<option value=\"$num\">$num</option>";
    }
    echo '</select>';
}

//Adding attendees to events
function addAttendee($mysql, $eventID, $attendeeUIN) {
    //checking if an attendee is already registered for an event
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

    //adding unique attendee to event
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

//removing attendee from event tracking
function removeAttendee($mysql, $eventID, $attendeeUIN) {
    // Checking if an attendee is registered for an event
    $checkQuery = "SELECT * FROM event_tracking WHERE Event_ID = ? AND UIN = ?";
    $checkStmt = $mysql->prepare($checkQuery);
    $checkStmt->bind_param("ii", $eventID, $attendeeUIN);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows == 0) {
        echo "Attendee is not registered for the event.";
        $checkStmt->close();
        return;
    }

    $checkStmt->close();

    // Removing the attendee from the event
    $deleteQuery = "DELETE FROM event_tracking WHERE Event_ID = ? AND UIN = ?";
    $deleteStmt = $mysql->prepare($deleteQuery);
    $deleteStmt->bind_param("ii", $eventID, $attendeeUIN);

    if ($deleteStmt->execute()) {
        echo "Attendee removed successfully!";
    } else {
        echo "Error: " . $deleteStmt->error;
    }

    $deleteStmt->close();
}

//updating event data
function updateEvent($mysql, $eventID, $updatedEventData) {
    $setClause = '';
    $bindTypes = ''; 
    $bindParams = array(); // Array to hold bind parameters
    //Building query from array built earlier
    if (isset($updatedEventData['update_program_num'])) {
        $setClause .= "Program_Num = ?, ";
        $bindTypes .= 'i';
        $bindParams[] = $updatedEventData['update_program_num'];
    }

    if (isset($updatedEventData['updated_start_date'])) {
        $setClause .= "Start_Date = ?, ";
        $bindTypes .= 's';
        $bindParams[] = $updatedEventData['updated_start_date'];
    }

    if (isset($updatedEventData['updated_time'])) {
        $setClause .= "Time = ?, ";
        $bindTypes .= 's';
        $bindParams[] = $updatedEventData['updated_time'];
    }

    if (isset($updatedEventData['updated_location'])) {
        $setClause .= "Location = ?, ";
        $bindTypes .= 's';
        $bindParams[] = $updatedEventData['updated_location'];
    }
    //Add Attendee, independent of update statement
    if (isset($updatedEventData['attendee_uin'])) {
        addAttendee($mysql, $eventID, $updatedEventData['attendee_uin']);
    }
    //Remove attendee, independent of update statement
    if (isset($updatedEventData['remove_attendee_uin'])) {
        removeAttendee($mysql, $eventID, $updatedEventData['remove_attendee_uin']);
    }

    if (!empty($setClause)) {
        $setClause = rtrim($setClause, ', ');

        $sql = "UPDATE events SET $setClause WHERE Event_ID = ?";

        $stmt = $mysql->prepare($sql);

        if (!$stmt) {
            die("Error: " . $mysql->error);
        }

        $bindTypes .= 'i';
        $bindParams[] = &$eventID;

        $stmt->bind_param($bindTypes, ...$bindParams);

        if ($stmt->execute()) {
            echo "Event updated successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "No fields to update.";
    }
}

//Deletes an event from both events and event_tracking
function deleteEvent($mysql, $eventID) {
    //Deleting event_tracking first because it is a child element of events
    $deleteEventTrackingQuery = "DELETE FROM event_tracking WHERE Event_ID = ?";
    $deleteEventTrackingStmt = $mysql->prepare($deleteEventTrackingQuery);
    $deleteEventTrackingStmt->bind_param("i", $eventID);

    if ($deleteEventTrackingStmt->execute()) {
        echo "Event deleted successfully from the event_tracking table!";
    } else {
        echo "Error: " . $deleteEventTrackingStmt->error;
    }

    $deleteEventTrackingStmt->close();
    //now event is deleted
    $deleteEventQuery = "DELETE FROM events WHERE Event_ID = ?";
    $deleteEventStmt = $mysql->prepare($deleteEventQuery);
    $deleteEventStmt->bind_param("i", $eventID);

    if ($deleteEventStmt->execute()) {
        echo "Event deleted successfully from the events table!";
    } else {
        echo "Error: " . $deleteEventStmt->error;
    }

    $deleteEventStmt->close();
}

//view pertenant event information using event_information view to reduce complexity of query
function viewEvent($mysql, $eventToView) {
    $eventID = intval($eventToView);

    $sql = "SELECT * FROM event_information WHERE Event_ID = ?";
    
    $stmt = $mysql->prepare($sql);

    $stmt->bind_param("i", $eventToView);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        //setting up table to display event information
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
                      <th style="padding: 10px; border: 1px solid #ddd;">Attendee Count</th>
                  </tr>';
        
            $row = $result->fetch_assoc();
            //iputing data into table
            echo '<tr> 
                <td style="padding: 10px; border: 1px solid #ddd;">'.$row['Event_ID'].'</td> 
                <td style="padding: 10px; border: 1px solid #ddd;">'.$row['Start_Date'].'</td> 
                <td style="padding: 10px; border: 1px solid #ddd;">'.$row['Time'].'</td> 
                <td style="padding: 10px; border: 1px solid #ddd;">'.$row['Location'].'</td> 
                <td style="padding: 10px; border: 1px solid #ddd;">'.$row['UIN'].'</td>
                <td style="padding: 10px; border: 1px solid #ddd;">'.$row['Event_Type'].'</td> 
                <td style="padding: 10px; border: 1px solid #ddd;">'.$row['Attendee_Count'].'</td>
            </tr>';
        
            echo '</table>';
            $result->free_result();
        } else {
            echo "No results found for the given Event ID.";
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>Event Management</title>
</head>
<body>
    <?php 
        include 'navbar.php';
    ?>
    <!--Create Event Section -->
    <div class="container mt-3">
        <h2>Create Event</h2>
        <form action="" method="post">
            <div class="mb-3">
                <label for="program_num" class="form-label">Program Num:</label>
                <?php updateProgramDropdown($mysql); ?>
            </div>
            <div class="mb-3">
                <label for="start_date" class="form-label">Start Date:</label>
                <input type="date" class="form-control" name="start_date" required>
            </div>
            <div class="mb-3">
                <label for="time" class="form-label">Time:</label>
                <input type="time" class="form-control" name="time" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Location:</label>
                <input type="text" class="form-control" name="location" required>
            </div>
            <div class="mb-3">
                <label for="end_date" class="form-label">End Date:</label>
                <input type="date" class="form-control" name="end_date">
            </div>
            <div class="mb-3">
                <label for="event_type" class="form-label">Event Type:</label>
                <input type="text" class="form-control" name="event_type" required>
            </div>
            <button type="submit" name="create_event" class="btn btn-primary">Create Event</button>
        </form>

    <?php

    if (isset($_POST['create_event'])) {
        $eventData = array(
            'UIN' => $_POST['uin'],
            'Program_Num' => $_POST['program_num'],
            'Start_Date' => $_POST['start_date'],
            'Time' => $_POST['time'],
            'Location' => $_POST['location'],
            'End_Date' => $_POST['end_date'],
            'Event_Type' => $_POST['event_type']
        );

        insertEvent($mysql, $eventData);
    }
    ?>
    <div class="container mt-3">
        <!-- Update Event Section -->
        <h2>Update Event</h2>
        <form action="" method="post" class="mb-3">

            <div class="mb-3">
                <label for="update_event_id" class="form-label">Event ID to Update:</label>
                <?php updateEventDropdown($mysql); ?>
            </div>

            <div class="mb-3">
                <label for="program_num" class="form-label">Program Num to Update:</label>
                <?php updateProgramDropdown($mysql); ?>
            </div>

            <div class="mb-3">
                <label for="updated_start_date" class="form-label">Updated Start Date:</label>
                <input type="date" name="updated_start_date" class="form-control">
            </div>

            <div class="mb-3">
                <label for="updated_time" class="form-label">Updated Time:</label>
                <input type="time" name="updated_time" class="form-control">
            </div>

            <div class="mb-3">
                <label for="updated_location" class="form-label">Updated Location:</label>
                <input type="text" name="updated_location" class="form-control">
            </div>

            <div class="mb-3">
                <label for="attendee_uin" class="form-label">Add Attendee UIN:</label>
                <input type="text" name="attendee_uin" class="form-control">
            </div>

            <div class="mb-3">
                <label for="remove_attendee_uin" class="form-label">Remove Attendee UIN:</label>
                <input type="text" name="remove_attendee_uin" class="form-control">
            </div>

            <button type="submit" name="update_event" class="btn btn-primary">Update Event</button>
        </form>

        <?php
        if (isset($_POST['update_event'])) {
            $eventIDToUpdate = $_POST['update_event_id'];

            $updatedEventData = array();

            if ($_POST['program_num'] != '') {
                $updatedEventData['program_num'] = $_POST['program_num'];
            }

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

            if ($_POST['remove_attendee_uin'] != '') {
                $updatedEventData['remove_attendee_uin'] = $_POST['remove_attendee_uin'];
            }

            updateEvent($mysql, $eventIDToUpdate, $updatedEventData);
        }
        ?>
    </div>

    <div class="container mt-3">
        <!-- Delete Event Section -->
        <h2>Delete Event</h2>
        <form action="" method="post" class="mb-3">
            <div class="mb-3">
                <label for="delete_event_id" class="form-label">Event ID to Delete:</label>
                <?php updateEventDropdown($mysql); ?>
            </div>

            <button type="submit" name="delete_event" class="btn btn-danger">Delete Event</button>
        </form>

        <?php
        if (isset($_POST['delete_event'])) {
            $eventIDToDelete = $_POST['delete_event_id'];

            deleteEvent($mysql, $eventIDToDelete);
        }
        ?>
    </div>

    <div class="container mt-3">
        <!-- Event Information Section -->
        <h2>Event Information</h2>
        <form action="" method="post" class="mb-3">
            <div class="mb-3">
                <label for="view_event_id" class="form-label">Event to View:</label>
                <?php updateEventDropdown($mysql); ?>
            </div>

            <button type="submit" name="view_event" class="btn btn-primary">View Event</button>
        </form>

        <?php
        if (isset($_POST['view_event'])) {
            $eventToView = $_POST['update_event_id'];

            viewEvent($mysql, $eventToView);
        }
        ?>  
    </div>
</body>
</html>

<?php
$mysql->close();
?>