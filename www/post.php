<html>
    <head>
        <?php include("website_head.php") ?>
        <title>Post</title>
        <link href="./css/post.css" rel="stylesheet">
    </head>
    <body>
        <?php
            // if (password_verify($password, $row_result['password']))// || $_COOKIE["user_name"] == $username
            // {
                //design comment blog
                echo '            
                <div class="m-b-md content" style="padding-left: 30px; padding-top: 15px; margin-left: 170px; padding-right: 20px;">
                    <form name="form1" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="name" value='.$username.'>
                        <p><strong>Hi, there. Write your post below.</strong></p>
                        <p>Title(Max:200)</p><input type="text" name="subject" class="blog_title">
                        <p>Content(Max:2000)</p><textarea class=textarea type="" name="content"></textarea></br>
                        <input type="file" name="attach_file_submit" value="Attach File">
                        <input type="submit" name="send_submit" value="SEND">
                        <input type="reset" name="Reset" value="RESET">
                    </form>
                </div>';
            // }
        ?>
    </body>
</html>

<?php
    if (isset($_POST['send_submit']) && $_POST['subject']!="" && $_POST['content']!="")
    {
        //upload attach file
        if(isset($_FILES['attach_file_submit']) && is_readable($_FILES['attach_file_submit']['tmp_name']))
        {
            $prefix = bin2hex(random_bytes(4));
            $attach_file_addr = $_FILES['attach_file_submit']["name"];
            $attach_file_addr = str_replace("_", " ", $attach_file_addr);
            $attach_file_addr = "../upload_attach/".$prefix."_".$attach_file_addr;
            if(move_uploaded_file($_FILES['attach_file_submit']['tmp_name'], $attach_file_addr))
                echo '<div class="success">Upload Attachment Success!</br></div>';
        }
        // else if(isset($_FILES['attach_file_submit']) && !is_readable($_FILES['attach_file_submit']['tmp_name']))
        //     echo '<div class="success">Upload Attachment Unsuccess!</br>You can not upload the file size > 2M!</div>';
        else
            $attach_file_addr = '';
        

        $subject = $_POST['subject'];
        $content = $_POST['content'];
        $username = $_POST['name'];

        //sqli detection
        $subject = sqli_detect_blog($subject);
        $content = sqli_detect_blog($content);

        //create post id
        require_once('check_post_id.php');
        $post_id = check_post_id($db_link);

        $sql = "INSERT into `users_blog`(`post_id`, `user_name`, `blog_title`, `blog_content`, `post_time`, `attach_file_addr`) VALUES ('$post_id', '$username', '$subject', '$content', now(), '$attach_file_addr');";

        if (!mysqli_query($db_link, $sql))
        {
            die(mysqli_error());
        }
        else
        {
            //若成功將留言存進資料庫，會自動跳轉到顯示留言的頁面
            // echo"
            //     <script>
            //     setTimeout(function(){window.location.href='board.php?username=".$username."';},500);
            //     </script>";
            echo '<div class="success">Added successfully !</br>Wait for redirection !</div>';
            header("refresh:2; url=board.php?username=$username");
        }
    }
    else if(password_verify($password, $row_result['password']))
    {
        echo '<div class="success">Click <strong>Send</strong> when you\'re done.</div>';
    }
    else if($_POST['subject']!="" || $_POST['content']!="")
    {
        echo '<div class="flex-center">You can not let the field blank!</div>';
        header("refresh:2; url=index.php");
    }
?>