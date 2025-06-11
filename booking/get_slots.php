<?php
$db = new PDO('sqlite:booking.db');
$date = $_GET['date'] ?? date('Y-m-d');
$stmt = $db->prepare("SELECT COUNT(*) FROM bookings WHERE walk_date = ?");
$stmt->execute([$date]);
$count = $stmt->fetchColumn();
echo json_encode(['count' => $count]);
?>
