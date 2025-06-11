<?php
$db = new PDO('sqlite:booking.db');

// Create table if it doesn't exist
$db->exec("CREATE TABLE IF NOT EXISTS bookings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    walk_date TEXT NOT NULL,
    time TEXT NOT NULL,
    package TEXT NOT NULL,
    name TEXT NOT NULL,
    contact TEXT NOT NULL,
    email TEXT NOT NULL,
    city TEXT NOT NULL,
    state TEXT NOT NULL,
    country TEXT NOT NULL,
    citizen TEXT NOT NULL,
    guests INTEGER NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Count existing bookings for selected date
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $stmt = $db->prepare("SELECT COUNT(*) FROM bookings WHERE walk_date = ?");
    $stmt->execute([$date]);
    $count = $stmt->fetchColumn();

    if ($count >= 30) {
        echo json_encode(['status' => 'full']);
        exit;
    }

    // Save booking (for now — payment integration comes next)
    $stmt = $db->prepare("INSERT INTO bookings (walk_date, time, package, name, contact, email, city, state, country, citizen, guests)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['date'], $_POST['time'], $_POST['package'],
        $_POST['name'], $_POST['contact'], $_POST['email'],
        $_POST['city'], $_POST['state'], $_POST['country'],
        $_POST['citizen'], $_POST['guests']
    ]);

    echo json_encode(['status' => 'success']);
}
?>
