<?php

    include_once 'db_access_mgr.php';
    include_once 'error_handling.php';

    function get_avatar($user_id, $db_user)
    {
        $db_conn = db_getConnection($db_user);
        try 
        {
           $rows = $db_conn->query('SELECT borrower_id, borrower_pic FROM tb_borrowers WHERE borrower_id = ' . $user_id);
           $row = $rows->fetch(PDO::FETCH_ASSOC);
           return $row['borrower_pic'];
        }
        catch(PDOException $ex)
        {
            error_message($ex);
            exit;
        }
    }
?>