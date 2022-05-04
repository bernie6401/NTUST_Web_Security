<?php
    $filename_post_time=$post_time = str_replace("*", " ", $_POST['download_file_post_time']);

    require_once('config.php');
    $db_link = ConnectDB();
    $sql = "SELECT * from `users_blog` WHERE `post_time` = '$filename_post_time'";
    $result = mysqli_query($db_link, $sql);

    // $sql = "SELECT * FROM `users_blog` WHERE `user_name`='$username' and `post_time`='$post_time'";
    // $result = mysqli_query($db_link, $sql);
    // $row = mysqli_fetch_assoc($result);
    

    if ($row = mysqli_fetch_assoc($result))
    {
        header('content-type:application/octet-stream');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length:'.filesize($row['attach_file_addr']));
        header('Content-Disposition:attachment;filename='.basename($row['attach_file_addr']).';');

        readfile($row['attach_file_addr']);
        exit;
    }

    else 
        echo "File download unsuccessfully!";
?>