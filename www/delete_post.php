<?php
    ob_start();
    include("config.php");
    $db_link = ConnectDB();

    if(isset($_POST['delete_post']))# && ($_POST['user_name'] == $_GET['username'])
    {
        $post_time = str_replace("*", " ", $_POST["post_time"]);
        $username = $_POST["user_name"];
        $post_id = $_POST['post_id'];
        $sql_query_delete = "DELETE FROM `users_blog` WHERE `post_time` = '$post_time' and `user_name` = '$username' and `post_id` = '$post_id';";
        mysqli_query($db_link, $sql_query_delete);
        echo $post_time;
        echo "success";
    }

    header("Location: board.php");
?>
<?ob_end_flush();?>