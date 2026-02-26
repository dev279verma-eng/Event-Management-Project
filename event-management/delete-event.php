<?php
require_once 'config/database.php';
require_once 'config/session.php';

requireLogin();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $event_id = intval($_GET['id']);
    $user_id = getCurrentUser()['id'];
    
    $conn = getDBConnection();
    
    // Verify event belongs to user before deleting
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $event_id, $user_id);
    $stmt->execute();
    
    $stmt->close();
    $conn->close();
}

header('Location: my-events.php');
exit();
?>
