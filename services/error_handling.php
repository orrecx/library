<?php

function error_message($excp_msg)
{
    $_SESSION['error_state'] = "General database Error<br>". $excp_msg;    
    //header('Location: http://orrecx.com/error.php');
    echo $excp_msg;
    exit;
}
?>
