<head>
    <?php include("website_head.php")?>
    <link href="./css/board.css" rel="stylesheet">
    <title>Board</title>
    <script src="./js/bootstrap.bundle.min.js"></script>
</head>
<body class="container container-adjust text-white bg-dark">
    <?php
        if(isset($_POST['view_one_post']))
        {
            //connect to db
            require_once("config.php");
            $db_link = ConnectDB();

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

            //change post time(month)
            $post_time_mon = month_list(explode("-", $row['post_time'])[1]);
            $post_time_day = explode("-", $row['post_time'])[2];
            $post_time_day = explode(" ", $post_time_day)[0];

            echo "
            <div class='alert alert-success margin-adjust board-color position-adjust view-one-post-margin-adjust' role='alert'>
                <span  class='avatar-adjust'>
                    <img src=".$avatar_id_row ["avatar_id"]." width='30' height='30' class='img-circle' title='Your avatar icon'>
                </span>
                <div class='username-adjust font-size'>
                    ".$row['user_name']."
                </div>";

            if ($_COOKIE['user_name'] == $row['user_name'])
            {
                //若登入者名稱和留言者名稱一致，顯示出編輯和刪除的連結
                echo '
                <div class="content-float-adjust">
                    <form action="delete_post.php" method="POST" enctype="multipart/form-data" class="delete-adjust">
                        <input type="hidden" name="post_time" value='.$post_time.'>
                        <input type="hidden" name="user_name" value='.$username.'>
                        <input type="hidden" name="post_id" value='.$row['post_id'].'>
                        <input type="image" name="delete_post" src="./img/trash_can.svg" width="30" height="30">
                    </form>
                </div>';
            }

            if(is_file($row['attach_file_addr']) && is_readable($row['attach_file_addr']))
            {
                echo "
                <div class='content-float-adjust'>
                    <form action='download_file.php' method='POST' enctype='multipart/form-data'>
                        <input type='hidden' name='download_file_post_time' value=".$post_time.">
                        <input type='image' name='download_file'  src='./img/attachments.svg' width='30' height='30'>
                    </form>
                </div>";
            }
                
            echo "
                <div class='margin-left-adjust content-border-adjust word-wrap-adjust'>
                    <p class='title-adjust'>".$row['blog_title']."</p>

                    <div class='content-adjust'>
                        ".nl2br($blog_content)."
                    </div>
                </div>
                <div class='time-right-adjust'>
                    ".$post_time_mon." ".$post_time_day."
                </div>";

            echo "
            </div>";
        }

        else
            header('Location: index.php')
    ?>
</body>

<?php
    function month_list($mon)
    {
        $mon_array = array(
            "01" => "Jan",
            "02" => "Fab",
            "03" => "Mar",
            "04" => "Apr",
            "05" => "May",
            "06" => "Jun",
            "07" => "Jul",
            "08" => "Aug",
            "09" => "Sep",
            "10" => "Oct",
            "11" => "Nov",
            "12" => "Dec"
        );

        return $mon_array[$mon];
    }
?>