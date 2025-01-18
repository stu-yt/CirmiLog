<?php
require 'forum.php';

if (basename($_SERVER['PHP_SELF']) == 'index.php' && empty($_SERVER['QUERY_STRING'])) {
    // Redirect to the default URL with status=open
    header("Location: index.php?status=open");
    exit; // Make sure the script stops after the redirect
}

$status = $_GET['status'] ?? 'open';
$posts = get_posts($status);

include 'templates/header.php';
?>
<script src="test.js"></script>

<div class="container">
    <h1>Projects</h1>
    <nav class="type_selector">
        <a href="index.php?status=open" id="ts_open">Open Posts</a> 
        <a href="index.php?status=closed" id="ts_closed">Closed Posts</a> 
        <a href="index.php?status=all" id="ts_all">All Posts</a>
    </nav>

    
    <h2>Create a New Project</h2>
    <div class="post_creation">
    <form method="POST" action="forum.php">
        <input type="hidden" name="action" value="add_post">
        
        <input type="text" id="title" name="title" placeholder="Title" required><br>
        
        <textarea id="content" name="content" placeholder="Content" rows="5" cols="40" required></textarea><br>
        <button type="submit">Add Post</button>
    </form>
    </div>

    <h2>Posts</h2>
    <?php if (empty($posts)): ?>
        <p>No posts found.</p>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                <p>Status: <?php echo $post['status']; ?></p>

                <form method="POST" action="forum.php">
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
</div>

<?php include 'templates/footer.php'; ?>
