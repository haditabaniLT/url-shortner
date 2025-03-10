<?php
// index.php - Simple URL Shortener Landing Page

// Load environment variables for DB
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: '';
$db   = getenv('DB_NAME') ?: 'url';

// Connect to DB
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection error: " . $conn->connect_error);
}

// Function to generate a short code
function generateCode($length = 6) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

$message = '';
$shortUrl = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['url'])) {
    $url = trim($_POST['url']);
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        $code = generateCode();
        $stmt = $conn->prepare("INSERT INTO urls (code, url) VALUES (?, ?)");
        if ($stmt) {
            $stmt->bind_param("ss", $code, $url);
            if ($stmt->execute()) {
                $shortUrl = "http://" . $_SERVER['HTTP_HOST'] . "/" . $code;
                $message = "Short URL generated successfully!";
            } else {
                $message = "Error: Could not save URL.";
            }
            $stmt->close();
        } else {
            $message = "Error: Could not prepare database query.";
        }
    } else {
        $message = "Please enter a valid URL.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Simple URL Shortener</title>
</head>
<body>
    <h1>Simple URL Shortener</h1>
    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <?php if ($shortUrl): ?>
        <p>Your short URL: <a href="<?php echo htmlspecialchars($shortUrl); ?>"><?php echo htmlspecialchars($shortUrl); ?></a></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label for="url">Enter URL:</label>
        <input type="url" name="url" id="url" required>
        <button type="submit">Shorten</button>
    </form>
</body>
</html>
