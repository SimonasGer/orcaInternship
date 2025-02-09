<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: http://simonas.kesug.com/");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
include "comment.php";

$comment = new Comment();
$comments = $comment->getComments();

header('Content-Type: application/json');
echo json_encode($comments, JSON_PRETTY_PRINT);