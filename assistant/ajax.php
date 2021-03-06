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
    case "req_book_checkin":
        {
            if(isset($_POST['query']) && $_POST['query'] == 'set' && isset($_POST['vals']))
            {
                //$_POST['vals'] = q-checkbox_1-checkbox_23-checkbox_15
                $vals = explode('-', $_POST['vals']);
                for($i = 1 ; $i < count($vals); $i++)
                {
                    $v = explode("_", $vals[$i]); //checkbox_nr
                    checkin_book($v[1], ASSISTANT);
                }
            }
            else 
            {
                get_book_tracker("tr_zebra", (isset($_POST['query']) ? $_POST['query'] : null), ASSISTANT);
            }
            
            break;
        }
    case "req_book_checkout":
        {
            if(isset($_POST['query']) && $_POST['query'] == 'set' && isset($_POST['vals']))
            {
                //$_POST['vals'] = q-checkbox_1-checkbox_23-checkbox_15
                $vals = explode('-', $_POST['vals']);
                for($i = 1 ; $i < count($vals); $i++)
                {
                    $v = explode("_", $vals[$i]); //checkbox_nr
                    checkout_book($v[1], ASSISTANT);
                }                
                
            }
            else
            {
                get_book_reservations("tr_zebra", (isset($_POST['query']) ? $_POST['query'] : null), ASSISTANT);
            }
            
            break;
            
            
            break;
        }
    case "req_book_search":
        {
            //print_r($_POST);
            if(isset($_POST['query']))
            {
                search_books_2($_POST['query'], $_POST['query'], -2, "tr_zebra", null, ASSISTANT, true);
            }
            break;
        }
    default:
        {
            echo "<h1>Assistant Request [".$_POST['assistant_req']."] not supported yet</h1>";
        }
}

?>