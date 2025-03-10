<?php
// redirect.php - Redirect to the original URL and increment the click count

// Load database configuration from environment variables
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: '';
$db   = getenv('DB_NAME') ?: 'url';

// Connect to the database
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection error: " . $conn->connect_error);
}

// Check if the short code is provided
if (isset($_GET['c'])) {
    $code = $_GET['c'];

    // 1) Look up the original URL
    $stmt = $conn->prepare("SELECT url FROM urls WHERE code = ?");
    if ($stmt) {
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $stmt->bind_result($originalUrl);

        if ($stmt->fetch() && $originalUrl) {
            $stmt->close();

            // 2) Increment the clicks for this code
            $updateStmt = $conn->prepare("UPDATE urls SET clicks = clicks + 1 WHERE code = ?");
            if ($updateStmt) {
                $updateStmt->bind_param("s", $code);
                $updateStmt->execute();
                $updateStmt->close();
            }

            // 3) Redirect to the original URL
            header("Location: $originalUrl");
            exit;
        } else {
            // No matching URL found
            echo "URL not found!";
            $stmt->close();
        }
    } else {
        echo "Database query error!";
    }
} else {
    echo "No URL code provided.";
}

$conn->close();
?>
