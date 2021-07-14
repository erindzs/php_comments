<!doctype html>
<link rel="stylesheet" href="style.css">

<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include 'DB.php';
    $db = new DB();
    $comment_obj = $db->makeTableObject('comments');

    if (array_key_exists('id', $_GET)) {
        $comment = $comment_obj->getEntry((int) $_GET['id']);
        if (!$comment) {
            header('Location: index.php');
            exit();
        }
    }
    elseif (
        array_key_exists('id', $_POST) &&
        array_key_exists('message', $_POST) &&
        array_key_exists('name', $_POST)
    ) {
        $comment_obj->updateEntry(
            $_POST['id'],
            [
                'name' => $_POST['name'],
                'message' => $_POST['message'],
            ]
        );

        header('Location:index.php');
        exit();
    }
?>

<a href="index.php">back to comments</a>
<div id="app">
    <section class="comments">
        <h1>Update Comment</h1>
        <form class="comments__form" action="update.php" method="post">
            <input type="hidden" name="id" value="<?=$_GET['id']; ?>">
            <div class="form_block">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" value="<?= $comment['name']; ?>">
            </div>

            <div class="form_block">
                <label for="message">Message</label>
                <textarea name="message" id="message" rows="7"><?= $comment['message']; ?></textarea>
            </div>
            <button type="submit">Submit</button>
        </form>
    </section>
</div>