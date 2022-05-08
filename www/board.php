<?php
    include 'style.html';
    // session_start();
?>
<head>
    <?php include("website_head.php")?>
    <link href="./css/board.css" rel="stylesheet">
    <title>All messages</title>
</head>
<body class="container container-adjust text-white bg-dark">
    <div>
        <?php
            echo '<div class="top-right home "><a href="index.php">Login</a></div>';
            echo '
            <div class="note full-height">';
                include "config.php";
                $db_link = ConnectDB();
                $sql = "SELECT * from `users_blog`";
                $result = mysqli_query($db_link, $sql);
                // $_SESSION['name'] = $name = $_GET['name'];
                //從資料庫中撈留言紀錄並顯示出來
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

                    echo "
                    <div class='post_content'>
                        <br><img src=".$avatar_id_row ["avatar_id"]." width='10'  class='img-circle' alt='You should not upload ilegal img' title='Your avatar icon'>
                        <br>Poster Name：" . $row['user_name']."
                        <br>Title：" . $row['blog_title']."
                        <br>Content：" . nl2br($blog_content) . "<br>
                    </div>";
                    
                    if(is_file($row['attach_file_addr']) && is_readable($row['attach_file_addr']))
                    {
                        // echo '<a href="download_file.php?filename='.$row['attach_file_addr'].'">Doanload Attachment</a></br>';
                        echo "
                        <form action='download_file.php' method='POST' enctype='multipart/form-data'>
                            <input type='hidden' name='download_file_post_time' value=".$post_time.">
                            <input type='submit' name='download_file' value='Doanload File'>
                        </form>";
                    }
                    
                    echo "Time：" . $row['post_time'] . "<br><br>";
                    echo '
                        <form action="view_one_post.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="view_one_post_time" value='.$post_time.'>
                            <input type="hidden" name="view_one_post_user_name" value='.$row['user_name'].'>
                            <input type="submit" name="view_one_post" value="View" class="delete_sw">
                        </form>
                    ';

                    if ($_COOKIE['user_name'] == $row['user_name'])
                    {
                        //若登入者名稱和留言者名稱一致，顯示出編輯和刪除的連結
                        echo '
                        <form action="delete_post.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="post_time" value='.$post_time.'>
                            <input type="hidden" name="user_name" value='.$username.'>
                            <input type="hidden" name="post_id" value='.$row['post_id'].'>
                            <input type="submit" name="delete_post" value="Delete" class="delete_sw">
                        </form>
                        ';
                    }
                    echo "<hr>";
                }
                echo "<br>";
                echo '<div class="bottom left position-abs content">';
                echo "There are " . mysqli_num_rows($result) . " messages.";
            echo '
            </div>';
        ?>
    </div>
</body>
</html>

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