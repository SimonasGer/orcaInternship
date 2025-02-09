<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: http://simonas.kesug.com/");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
include "comment.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $content = $_POST['content'];
    $parent_id = isset($_POST['parent_id']) ? $_POST['parent_id'] : NULL;

    $comment = new Comment();
    $response = $comment->addComment($name, $email, $content, $parent_id);

    echo json_encode($response);
}