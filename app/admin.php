<?php
// admin.php - Simple Admin Panel to view and delete shortened URLs

$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: '';
$db   = getenv('DB_NAME') ?: 'url';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection error: " . $conn->connect_error);
}

$message = '';

// Handle deletion if a delete request is present
if (isset($_GET['delete'])) {
    $codeToDelete = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM urls WHERE code = ?");
    if ($stmt) {
        $stmt->bind_param("s", $codeToDelete);
        if ($stmt->execute()) {
            $message = "Short URL with code " . htmlspecialchars($codeToDelete) . " has been deleted.";
        } else {
            $message = "Error: Could not delete the URL.";
        }
        $stmt->close();
    } else {
        $message = "Error: Could not prepare the deletion query.";
    }
}

// Fetch all shortened URLs
$result = $conn->query("SELECT * FROM urls ORDER BY id DESC");
$urls = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $urls[] = $row;
    }
    $result->free();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel - URL Shortener</title>
</head>
<body>
  <h1>Admin Panel</h1>
  <?php if ($message): ?>
    <p><?php echo htmlspecialchars($message); ?></p>
  <?php endif; ?>

  <?php if (count($urls) > 0): ?>
    <table border="1" cellpadding="5">
      <thead>
        <tr>
          <th>ID</th>
          <th>Short Code</th>
          <th>Original URL</th>
          <th>Short URL</th>
          <th>Clicks</th> <!-- NEW COLUMN -->
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($urls as $url): ?>
          <tr>
            <td><?php echo htmlspecialchars($url['id']); ?></td>
            <td><?php echo htmlspecialchars($url['code']); ?></td>
            <td><?php echo htmlspecialchars($url['url']); ?></td>
            <td><?php echo "http://" . $_SERVER['HTTP_HOST'] . "/" . htmlspecialchars($url['code']); ?></td>
            <td><?php echo (int)$url['clicks']; ?></td> <!-- DISPLAY THE CLICKS -->
            <td>
              <a href="?delete=<?php echo urlencode($url['code']); ?>"
                 onclick="return confirm('Are you sure you want to delete this URL?');">
                 Delete
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No URLs found.</p>
  <?php endif; ?>
</body>
</html>
