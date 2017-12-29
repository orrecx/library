<?php

session_start();

if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in']) {
    unset($_SESSION['user_logged_in']);
    session_destroy();
    echo "Blank!!!";
    exit;
}

if (! isset($_SESSION['assistant_logged_in']) || ! $_SESSION['assistant_logged_in'] || !isset($_POST['assistant_req'])) {
    echo "Blank!!!";
    exit;
}

include_once '../services/user_settings.php';
include_once '../services/db_common.php';

switch ($_POST['assistant_req'])
{
    case "req_book_catalog":
    {
        search_books_2(null, null, -1, "tr_zebra", null, ASSISTANT);
        break;
    }
    case "req_checkin":
        {
            
        }
    case "req_checkout":
        {
            
        }
    case "req_book_search":
        {
            //print_r($_POST);
            if(isset($_POST['book_query']))
            {
                search_books_2($_POST['book_query'], $_POST['book_query'], -2, "tr_zebra", null, ASSISTANT, true);
            }
            break;
        }
    default:
        {
            echo "<h1>Assistant Request [".$_POST['assistant_req']."] not supported yet</h1>";
        }
}

?>