<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
$company_id = 'YNT4363'; // Change to session-based if needed

include 'db.php';
        // Risk Data Champions
        $assign_to = $_GET['assign_to'] ?? '';
        $start_date = $_GET['start_date'] ?? '';
        $end_date = $_GET['end_date'] ?? '';       
         $sql = "
            SELECT 
                ra.id,
                ra.company_id,
                ra.reference_number AS ref_no,
                ra.risk_title AS risk,
                ra.risk_action AS associated_item,
                ra.assign_to AS assigned_to,
                ra.due_date,
                ra.action_status AS status,
                ra.created_at AS created,
                ra.updated_at
            FROM risk_action ra
            WHERE ra.company_id = ?
            AND ra.assign_to = ?
            AND ra.created_at BETWEEN ? AND ?
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $company_id, $assign_to, $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        echo json_encode($data);
?>