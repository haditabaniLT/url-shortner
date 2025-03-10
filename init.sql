CREATE TABLE IF NOT EXISTS urls (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(255) NOT NULL,
  url TEXT NOT NULL,
  clicks INT NOT NULL DEFAULT 0
);
