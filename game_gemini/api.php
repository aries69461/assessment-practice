<?php
    header('Content-Type: application/json');

    // --- Database Connection (Conceptual) ---
    // Replace with your actual database credentials
    $servername = "localhost";
    $username = "root"; // Your DB username
    $password = "";     // Your DB password
    $dbname = "db_website"; // Your DB name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
        exit();
    }

    // Ensure the 'Answer' column exists in your Quests table.
    // If not, you'd run an ALTER TABLE query:
    // ALTER TABLE Quests ADD COLUMN Answer VARCHAR(255) NOT NULL DEFAULT '';


    $action = $_GET['action'] ?? ''; // Get the requested action

    switch ($action) {
        case 'getQuests':
            // Fetch all quests (including answers for admin panel, but be careful with player side)
            // For the player side, you'd have a separate endpoint or omit 'Answer'
            $sql = "SELECT Quest_ID, Quest_Name, Description, Point_Value, Difficulty_Level, Answer FROM Quests";
            $result = $conn->query($sql);

            $quests = [];
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $quests[] = $row;
                }
            }
            echo json_encode($quests);
            break;

        case 'addQuest':
            $data = json_decode(file_get_contents('php://input'), true);

            $name = $data['name'] ?? null;
            $description = $data['description'] ?? null;
            $answer = $data['answer'] ?? null;
            $points = $data['points'] ?? null;
            $difficulty = $data['difficulty'] ?? null;

            if ($name && $description && $answer && $points && $difficulty) {
                // Use prepared statement to prevent SQL injection
                $stmt = $conn->prepare("INSERT INTO Quests (Quest_Name, Description, Answer, Point_Value, Difficulty_Level) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssis", $name, $description, $answer, $points, $difficulty);

                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Quest added successfully.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to add quest: ' . $stmt->error]);
                }
                $stmt->close();
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing quest data.']);
            }
            break;

        case 'deleteQuest':
            $data = json_decode(file_get_contents('php://input'), true);
            $questId = $data['quest_id'] ?? null;

            if ($questId) {
                // Use prepared statement to prevent SQL injection
                $stmt = $conn->prepare("DELETE FROM Quests WHERE Quest_ID = ?");
                $stmt->bind_param("i", $questId); // "i" for integer

                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        echo json_encode(['success' => true, 'message' => 'Quest deleted successfully.']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Quest not found or already deleted.']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to delete quest: ' . $stmt->error]);
                }
                $stmt->close();
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing quest ID.']);
            }
            break;

        case 'updatePlayerPoints':
            // (Existing logic for updating player points remains)
            $data = json_decode(file_get_contents('php://input'), true);
            $playerId = $data['player_id'] ?? null;
            $pointsAwarded = $data['points_awarded'] ?? 0;
            $currentDateTime = date('Y-m-d H:i:s');

            if ($playerId && $pointsAwarded > 0) {
                // Example SQL (use prepared statements in real app to prevent SQL injection)
                $stmt = $conn->prepare("UPDATE Players SET Total_Points = Total_Points + ?, Last_Played_Date = ? WHERE Player_ID = ?");
                $stmt->bind_param("isi", $pointsAwarded, $currentDateTime, $playerId);
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Points updated successfully.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to update points: ' . $stmt->error]);
                }
                $stmt->close();
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid data for updating points.']);
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Unknown action.']);
            break;
    }

    $conn->close(); // Close database connection
?>