<?php
    // include 'style.html';
    // session_start();
?>
<head>
    <?php include("website_head.php")?>
    <link href="./css/board.css" rel="stylesheet">
    <title>Board</title>
    <script src="./js/bootstrap.bundle.min.js"></script>
</head>
<body class="container container-adjust text-white bg-dark">
    <!-- design header -->
    <nav class="navbar navbar-dark bg-primary mt-2 border-adjust" style="background-color: rgba(37, 163, 33, 1)!important">
        <div class="container-fluid">
            <a class="navbar-brand text-color-adjust" href="index.php"><b>Board</b></a>
            <button class="navbar-toggler button-color-adjust" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample01" aria-controls="navbarsExample01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarsExample01">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active text-color-adjust" href="signup.php"><b>Register</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-color-adjust" href="logout.php"><b>Logout</b></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="padding-adjust">
        <?php
            //從資料庫中撈留言紀錄並顯示出來 --> design post typography and the content
            include "config.php";
            $db_link = ConnectDB();
            $sql = "SELECT * from `users_blog` order by `post_time`";
            $result = mysqli_query($db_link, $sql);
            // $_SESSION['name'] = $name = $_GET['name'];
            
            while ($row = mysqli_fetch_assoc($result))
            {
                //exchange the post_time string to let "post" method can bring the whole string to delete_post.php
                $post_time = explode(" ", $row['post_time']);
                $post_time = $post_time[0]."*".$post_time[1];

                //get the avatar from db
                $username = $row['user_name'];
                $sql = "SELECT `avatar_id` FROM `users_info` WHERE `username`='$username';";
                $avatar_id_result = mysqli_query($db_link, $sql);
                $avatar_id_row = mysqli_fetch_assoc($avatar_id_result);

                //check bbcode
                require_once("check_bbcode.php");
                $blog_content = check_bbcode($username, $post_time);

                //change post time(month)
                $post_time_mon = month_list(explode("-", $row['post_time'])[1]);
                $post_time_day = explode("-", $row['post_time'])[2];
                $post_time_day = explode(" ", $post_time_day)[0];

                echo "
                <div class='alert alert-success margin-adjust board-color position-adjust' role='alert'>
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
                        <form action='view_one_post.php' method='POST' enctype='multipart/form-data'>
                            <input type='hidden' name='view_one_post_time' value=".$post_time.">
                            <input type='hidden' name='view_one_post_user_name' value=".$row['user_name'].">
                            <input type='submit' name='view_one_post' value=".$row['blog_title']." class='title-adjust'>
                        </form>
                        <div class='content-adjust'>
                            ".nl2br($blog_content)."
                        </div>
                    </div>
                    <div class='time-right-adjust'>
                        ".$post_time_mon." ".$post_time_day."
                    </div>";

                echo "
                </div>";
                echo "<hr>";
            }
        ?>
    </div>
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