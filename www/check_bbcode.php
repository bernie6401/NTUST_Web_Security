<?php
    function check_bbcode($username, $post_time)
    {
        require_once("config.php");
        $db_link = ConnectDB();
        $post_time = str_replace("*", " ", $post_time);

        $sql = "SELECT `blog_content` FROM `users_blog` WHERE `user_name`='$username' and `post_time`='$post_time';";
        $blog_content_result = mysqli_query($db_link, $sql);
        $blog_content_row = mysqli_fetch_assoc($blog_content_result);

        //normal transform
        $blog_content_row["blog_content"] = str_replace("[b]", "<b>", $blog_content_row["blog_content"]);
        $blog_content_row["blog_content"] = str_replace("[/b]", "</b>", $blog_content_row["blog_content"]);
        $blog_content_row["blog_content"] = str_replace("[i]", "<i>", $blog_content_row["blog_content"]);
        $blog_content_row["blog_content"] = str_replace("[/i]", "</i>", $blog_content_row["blog_content"]);
        $blog_content_row["blog_content"] = str_replace("[u]", "<u>", $blog_content_row["blog_content"]);
        $blog_content_row["blog_content"] = str_replace("[/u]", "</u>", $blog_content_row["blog_content"]);
        //img
        $blog_content_row["blog_content"] = str_replace("[img]", '<img src="', $blog_content_row["blog_content"]);
        $blog_content_row["blog_content"] = str_replace("[/img]", '">', $blog_content_row["blog_content"]);
        
        //color
        $blog_content_row["blog_content"] = str_replace("r=", "r: ", $blog_content_row["blog_content"]);
        $blog_content_row["blog_content"] = str_replace("[c", '<span style="c', $blog_content_row["blog_content"]);
        $blog_content_row["blog_content"] = str_replace("[/color]", "</span>", $blog_content_row["blog_content"]);
        $blog_content_row["blog_content"] = str_replace("]", ';">', $blog_content_row["blog_content"]);
    
        return $blog_content_row["blog_content"];
    }
?>