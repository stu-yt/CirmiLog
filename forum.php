<?php
define('POSTS_DIR', 'posts');

// Ensure posts directory exists
if (!is_dir(POSTS_DIR)) {
    mkdir(POSTS_DIR);
}

// Utility function to load posts
function get_posts($status = 'open') {
    $posts = [];
    foreach (glob(POSTS_DIR . '/*.json') as $file) {
        $post = json_decode(file_get_contents($file), true);
        if ($status === 'all' || $post['status'] === $status) {
            $posts[] = $post;
        }
    }
    return $posts;
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_post') {
        $post = [
            'id' => uniqid(),
            'title' => htmlspecialchars($_POST['title']),
            'content' => htmlspecialchars($_POST['content']),
            'status' => 'open',
            'comments' => []
        ];
        file_put_contents(POSTS_DIR . '/' . $post['id'] . '.json', json_encode($post));
    } elseif ($_POST['action'] === 'add_comment') {
        $postId = $_POST['post_id'];
        $postFile = POSTS_DIR . '/' . $postId . '.json';
        if (file_exists($postFile)) {
            $post = json_decode(file_get_contents($postFile), true);
            $post['comments'][] = htmlspecialchars($_POST['comment']);
            file_put_contents($postFile, json_encode($post));
        }
    } elseif ($_POST['action'] === 'change_status') {
        $postId = $_POST['post_id'];
        $postFile = POSTS_DIR . '/' . $postId . '.json';
        if (file_exists($postFile)) {
            $post = json_decode(file_get_contents($postFile), true);
            $post['status'] = $_POST['new_status'];
            file_put_contents($postFile, json_encode($post));
        }
    }
    header('Location: index.php');
    exit;
}
