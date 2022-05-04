<?php
    include 'style.html';
    require_once("config.php");

    $db_link = ConnectDB();

    if(isset($_POST['view_one_post']))
    {
        //get the avatar from db
        $username = $_POST["view_one_post_user_name"];
        $sql = "SELECT `avatar_id` FROM `users_info` WHERE `username`='$username';";
        $avatar_id_result = mysqli_query($db_link, $sql);
        $avatar_id_row = mysqli_fetch_assoc($avatar_id_result);
        
        $post_time = str_replace("*", " ", $_POST["view_one_post_time"]);
        $sql = "SELECT * FROM `users_blog` WHERE `user_name`='$username' and `post_time`='$post_time'";
        $result = mysqli_query($db_link, $sql);
        $row = mysqli_fetch_assoc($result);

        //check bbcode
        require_once("check_bbcode.php");
        $blog_content = check_bbcode($username, $post_time);

        echo "<br><img src=".$avatar_id_row ["avatar_id"]." width='100'>";
        echo "<br>Poster Name：" . $row['user_name'];
        echo "<br>Title：" . $row['blog_title'];
        echo "<br>Content：" . nl2br($blog_content) . "<br>";
        echo "Time：" . $row['post_time'] . "<br><br>";
    }
?>