<?php
include "db.php"; // Include the Database connection class
class Comment {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->conn;
    }

    // Add a new comment
    public function addComment($name, $email, $content, $parent_id = NULL) {
        // Trim whitespace from input
        $name = trim($name);
        $email = trim($email);
        $content = trim($content);
    
        // Check for Empty Fields
        if (empty($name) || empty($email) || empty($content)) {
            return ["status" => "error", "message" => "All fields are required."];
        }
    
        // Validate Email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["status" => "error", "message" => "Invalid email format."];
        }
    
        // Check Length Limits
        if (strlen($name) > 20) {
            return ["status" => "error", "message" => "Name cannot exceed 20 characters."];
        }
        if (strlen($name) < 4) {
            return ["status" => "error", "message" => "Name cannot be less than 4 characters."];
        }
        if (strlen($email) > 30) {
            return ["status" => "error", "message" => "Email cannot exceed 30 characters."];
        }
        if (strlen($content) > 200) {
            return ["status" => "error", "message" => "Comment cannot exceed 200 characters."];
        }
    
        // Prevent XSS Attacks
        $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
        $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
    
        // Convert Parent ID to NULL if it's Empty
        $parent_id = !empty($parent_id) ? (int) $parent_id : NULL;
    
        // Prepare and Execute SQL Statement
        $stmt = $this->conn->prepare("INSERT INTO comments (name, email, content, parent_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $name, $email, $content, $parent_id);
    
        if ($stmt->execute()) {
            return ["status" => "success"];
        } else {
            return ["status" => "error", "message" => $this->conn->error];
        }
    }
    
    
    // Fetch all comments and format them as a nested JSON
    public function getComments() {
        $sql = "SELECT * FROM comments ORDER BY created_at DESC";
        $result = $this->conn->query($sql);
        $comments = [];
    
        while ($row = $result->fetch_assoc()) {
            $comments[$row['id']] = $row;
            $comments[$row['id']]['replies'] = [];
        }
    
        $nestedComments = [];
    
        foreach ($comments as $id => &$comment) {
            if ($comment['parent_id'] !== NULL) {
                $comments[$comment['parent_id']]['replies'][] = &$comment;
            } else {
                $nestedComments[] = &$comment;
            }
        }
    
        return $nestedComments; // Return an array (NOT JSON encoded)
    }
}