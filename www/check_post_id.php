<?php
    function check_post_id($db_link)
    {
        //verify blog id
        $sql_query_select = "SELECT * FROM `users_blog`;";
        $select_result = mysqli_query($db_link, $sql_query_select);
        $total_records = mysqli_num_rows($select_result);
        // $id = $total_records + 1;   //use (the total member quantity + 1) as its id
        $id = rand(0, 10000);
        do
        {
            $sql_query_select = "SELECT * FROM `users_blog` WHERE `post_id` = '$id';";
            $select_result = mysqli_query($db_link, $sql_query_select);
            $id_repeat = mysqli_fetch_array($select_result);
            if($id_repeat)  //check if id repeat or not
                $id = rand(0, 10000);
        }while($id_repeat);
        
        return $id;
    }
?>