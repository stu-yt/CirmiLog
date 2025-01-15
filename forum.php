<?php
// Simple PHP Forum - No user accounts needed

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

// Handle new post submission
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
    header('Location: forum.php');
    exit;
}

$status = $_GET['status'] ?? 'open';
$posts = get_posts($status);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple PHP Forum</title>
</head>
<body>
    <h1>Simple PHP Forum</h1>

    <nav>
        <a href="forum.php?status=open">Open Posts</a> |
        <a href="forum.php?status=closed">Closed Posts</a> |
        <a href="forum.php?status=all">All Posts</a>
    </nav>

    <h2>Create a New Post</h2>
    <form method="POST" action="forum.php">
        <input type="hidden" name="action" value="add_post">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" required><br>
        <label for="content">Content:</label><br>
        <textarea id="content" name="content" rows="5" cols="40" required></textarea><br>
        <button type="submit">Add Post</button>
    </form>

    <h2>Posts</h2>
    <?php if (empty($posts)): ?>
        <p>No posts found.</p>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <div style="border: 1px solid #ccc; padding: 10px; margin: 10px 0;">
                <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                <p>Status: <?php echo $post['status']; ?></p>

                <form method="POST" action="forum.php" style="display:inline;">
                    <input type="hidden" name="action" value="change_status">
                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                    <input type="hidden" name="new_status" value="<?php echo $post['status'] === 'open' ? 'closed' : 'open'; ?>">
                    <button type="submit"><?php echo $post['status'] === 'open' ? 'Close' : 'Reopen'; ?> Post</button>
                </form>

                <h4>Comments</h4>
                <?php if (empty($post['comments'])): ?>
                    <p>No comments yet.</p>
                <?php else: ?>
                    <ul>
                        <?php foreach ($post['comments'] as $comment): ?>
                            <li><?php echo nl2br(htmlspecialchars($comment)); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <h4>Add a Comment</h4>
                <form method="POST" action="forum.php">
                    <input type="hidden" name="action" value="add_comment">
                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                    <textarea name="comment" rows="3" cols="40" required></textarea><br>
                    <button type="submit">Add Comment</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>