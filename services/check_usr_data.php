<?php
session_start();

include_once 'config.php';
include_once 'db_access_mgr.php';

if(isset($_SESSION['is_login_data']) && $_SESSION['is_login_data'])
{
    unset($_SESSION['is_login_data']);    
    //check login data   
    $db_conn = db_getConnection(LIBRARIAN);
    
    try 
    {
        $stmt = $db_conn->prepare("SELECT borrower_id, borrower_name, borrower_email, borrower_password, borrower_role from tb_borrowers WHERE borrower_email = ?");    
        $stmt->execute(array($_SESSION['login_usrname']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC); // only one element is expected
        
        if($row && password_verify($_SESSION['login_usrpsswd'] ,$row['borrower_password']))
        {
            $_SESSION['user_id'] = $row['borrower_id'];
            $_SESSION['user_name'] = $row['borrower_name'];
            $_SESSION['user_role'] = $row['borrower_role'];

            unset($_SESSION['login_usrname']);
            unset($_SESSION['login_usrpsswd']);
            
            switch ($row['borrower_role'])
            {
                case LIBRARIAN_ROLE:
                    {
                        $_SESSION['admin_logged_in'] = true;                        
                        header("Location: ../admin/index.php");
                        break;
                    }
                case ASSISTANT_ROLE:
                    {
                        $_SESSION['assistant_logged_in'] = true;                        
                        header("Location: ../assistant/index.php");
                        break;
                    }
                default:
                    {
                        $_SESSION['user_logged_in'] = true;                        
                        header("Location: ../user/index.php");
                        break;
                    }                    
            }
            
        }
        else 
        {
            header("Location: ../pub/login.php");
        }
        
        exit;
    }
    catch (PDOException $pdo_ex)
    {
        error_message($pdo_ex->getMessage());
        exit;
    }    
}

if(isset($_SESSION['is_registration_data']) && $_SESSION['is_registration_data'])
{
    unset($_SESSION['is_registration_data']);
    //check login email 
    $loc = "Location: ../pub/register.php";
    $_SESSION['registration_succeeded'] = false;
    
    if(preg_match("/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9.-]+.[a-zA-Z]+$/", $_SESSION['registration_email']))
    {

        $db_conn = db_getConnection(LIBRARIAN);
        $rows = $db_conn->query('SELECT borrower_email from tb_borrowers where borrower_email = "'. $_SESSION['registration_email'] .'"');
        
        if($rows->rowCount() == 0)
        {        
            try
            {
                $stmt = $db_conn->prepare("INSERT INTO tb_borrowers (borrower_id, borrower_name, borrower_address, borrower_email, borrower_password, borrower_role) VALUES(null, ?, ?, ?, ?, 0)");                
                $row_count = $stmt->execute(array($_SESSION['registration_name'], $_SESSION['registration_address'], $_SESSION['registration_email'], password_hash($_SESSION['registration_psswd'], PASSWORD_DEFAULT)));        

                if($row_count)
                {
                    $_SESSION['registration_succeeded'] = true;
                    $loc = "Location: ../pub/login.php";

                    unset($_SESSION['registration_name']);
                    unset($_SESSION['registration_address']);
                    unset($_SESSION['registration_psswd']);            
                }
            }
            catch (PDOException $pdo_ex)
            {
                error_message($pdo_ex->getMessage());
                exit;
            }
        }
        else 
        {
            $_SESSION['registration_email_failed'] = ERROR_DUPLICATE;
        }
    }
    else 
    {
        $_SESSION['registration_email_failed'] = ERROR_WRONG_FORMAT;
    }

    header($loc);    
    exit;
}

?>