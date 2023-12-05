<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }

    if (isset($_POST['updated_event'])) {
        $eventIDToUpdate = $_POST['updated_event_id'];

        $updatedEventData = array();

        function checkAndUpdateField($fieldName) {
            global $updatedEventData;
            if (isset($_POST[$fieldName]) && $_POST[$fieldName] !== '') {
                $updatedEventData[$fieldName] = $_POST[$fieldName];
            }
        }

        checkAndUpdateField('updated_start_date');
        checkAndUpdateField('updated_time');
        checkAndUpdateField('updated_location');
        checkAndUpdateField('attendee_uin');

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

function insertEvent($mysql, $eventData) {
    $uin = intval($eventData['UIN']);
    $programNum = intval($eventData['Program_Num']);
    $startDate = $eventData['Start_Date'];
    $time = $eventData['Time'];
    $location = $eventData['Location'];
    $endDate = $eventData['End_Date'];
    $eventType = $eventData['Event_Type'];

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

function updateEvent($mysql, $eventID, $updatedEventData) {
    $setClause = '';

    if (isset($updatedEventData['updated_start_date'])) {
        $setClause .= "Start_Date = ?, ";
    }

    if (isset($updatedEventData['updated_time'])) {
        $setClause .= "Time = ?, ";
    }

    if (isset($updatedEventData['updated_location'])) {
        $setClause .= "Location = ?, ";
    }
    //Add Attendee
    if (isset($updatedEventData['attendee_uin'])) {
        addAttendee($mysql, $eventID, $updatedEventData['attendee_uin']);
    }

    if (!empty($setClause)) {
        $setClause = rtrim($setClause, ', ');

        $sql = "UPDATE events SET $setClause WHERE Event_ID = ?";

        $stmt = $mysql->prepare($sql);

        if (!$stmt) {
            die("Error: " . $mysql->error);
        }

        $bindTypes = ''; 
        $bindParams = array(); // Array to hold bind parameters

        if (isset($updatedEventData['updated_start_date'])) {
            $bindTypes .= 's';
            $bindParams[] = $updatedEventData['updated_start_date'];
        }

        if (isset($updatedEventData['updated_time'])) {
            $bindTypes .= 's';
            $bindParams[] = $updatedEventData['updated_time'];
        }

        if (isset($updatedEventData['updated_location'])) {
            $bindTypes .= 's';
            $bindParams[] = $updatedEventData['updated_location'];
        }

        $bindTypes .= 'i';
        $bindParams[] = $eventID;

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

function deleteEvent($mysql, $eventID) {
    $deleteEventTrackingQuery = "DELETE FROM event_tracking WHERE Event_ID = ?";
    $deleteEventTrackingStmt = $mysql->prepare($deleteEventTrackingQuery);
    $deleteEventTrackingStmt->bind_param("i", $eventID);

    if ($deleteEventTrackingStmt->execute()) {
        echo "Event deleted successfully from the event_tracking table!";
    } else {
        echo "Error: " . $deleteEventTrackingStmt->error;
    }

    $deleteEventTrackingStmt->close();

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


function viewEvent($mysql, $eventToView) {
    $eventID = intval($eventToView);

    $sql = "SELECT * FROM event_information WHERE Event_ID = ?";
    
    $stmt = $mysql->prepare($sql);

    $stmt->bind_param("i", $eventToView);

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
                      <th style="padding: 10px; border: 1px solid #ddd;">Attendee Count</th>
                  </tr>';
        
            while ($row = $result->fetch_assoc()) {
                $eventID = $row['Event_ID'];
                $startDate = $row['Start_Date'];
                $time = $row['Time'];
                $location = $row['Location'];
                $creatorUIN = $row['UIN'];
                $eventType = $row['Event_Type'];
                $attendeeCount = $row['Attendee_Count'];
            
                echo '<tr> 
                    <td style="padding: 10px; border: 1px solid #ddd;">'.$eventID.'</td> 
                    <td style="padding: 10px; border: 1px solid #ddd;">'.$startDate.'</td> 
                    <td style="padding: 10px; border: 1px solid #ddd;">'.$time.'</td> 
                    <td style="padding: 10px; border: 1px solid #ddd;">'.$location.'</td> 
                    <td style="padding: 10px; border: 1px solid #ddd;">'.$creatorUIN.'</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">'.$eventType.'</td> 
                    <td style="padding: 10px; border: 1px solid #ddd;">'.$attendeeCount.'</td>
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
    <h2>Create Event</h2>
    <form action="" method="post">
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

    <h2>Update Event</h2>
    <form action="" method="post">

        Event ID to Update: <?php updateEventDropdown($mysql); ?><br>
        Updated Start_Date: <input type="date" name="updated_start_date"><br>
        Updated Time: <input type="time" name="updated_time"><br>
        Updated Location: <input type="text" name="updated_location"><br>
        Add Attendee UIN: <input type="text" name="attendee_uin"><br>
        <input type="submit" name="update_event" value="Update Event">
    </form>

    <?php
    if (isset($_POST['update_event'])) {
        $eventIDToUpdate = $_POST['update_event_id'];

        $updatedEventData = array();

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

        updateEvent($mysql, $eventIDToUpdate, $updatedEventData);
    }
    ?>


    <h2>Delete Event</h2>
    <form action="" method="post">
        Event ID to Delete: <?php updateEventDropdown($mysql); ?><br>
        <input type="submit" name="delete_event" value="Delete Event">
    </form>

    <?php
    if (isset($_POST['delete_event'])) {
        $eventIDToDelete = $_POST['update_event_id'];

        deleteEvent($mysql, $eventIDToDelete);
    }
    ?>

    <h2>Event Information</h2>
    <form action="" method="post">
        Event to View: <?php updateEventDropdown($mysql); ?><br>
        <input type="submit" name="view_event" value="View Event">
    </form>

    <?php
    if (isset($_POST['view_event'])) {
        $eventToView = $_POST['update_event_id'];

        viewEvent($mysql, $eventToView);
    }
    ?>  

</body>
</html>

<?php
$mysql->close();
?>