<?php
session_start();

header("Content-Type: application/json; charset=UTF-8");

if (! isset($_SESSION['admin_logged_in']) || ! $_SESSION['admin_logged_in'] || !isset($_POST['admin_req'])) {
    echo '{"data":"Error: Request info not found"}';
    exit;
}

include_once '../services/user_settings.php';
include_once '../services/db_common.php';

switch ($_POST['admin_req'])
{
    case 'Req_Actv_Usr':
    {
        get_users(false, BORROWER_ROLE , LIBRARIAN);
        break;        
    }
    case 'Req_Deactv_Usr':
    case 'Req_Promote_Asst':
        {
            get_users(true, BORROWER_ROLE, LIBRARIAN);            
            break;
        }
    case 'Req_Degreade_Asst':
    case 'Req_Promote_Adm':        
        {
            get_users(true, ASSISTANT_ROLE, LIBRARIAN);
            break;
        }
    case 'Req_Degreade_Adm':
        {
            get_users(true, LIBRARIAN_ROLE, LIBRARIAN);
            break;
        }
    case 'Req_Add_Book':
        {
            break;
        }
    case 'Req_Delete_Book':
        {
            break;
        }
    default:
        {
            break;
        }
        
}
?>