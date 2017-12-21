<?php
include_once 'config.php';
include_once 'error_handling.php';

$pr_stmt_addbook;
$pr_stmt_adduser;
$pr_stmt_deletebook;
$pr_stmt_deleteuser;

function db_getConnection($db_user)
{
    $db_conn;
    try {
        
        switch ($db_user) {
            case LIBRARIAN:
                {
                    // ------------------------------------------------------------------------------
                    // Librarian
                    // ------------------------------------------------------------------------------
                    $db_conn = new PDO("mysql:host=localhost;dbname=library", LIBRARIAN, LIBRARIAN_PSSD);
                    break;
                }
            case ASSISTANT:
                {
                    // ------------------------------------------------------------------------------
                    // Assistant
                    // ------------------------------------------------------------------------------
                    $db_conn = new PDO("mysql:host=localhost;dbname=library", ASSISTANT, ASSISTANT_PSSD);
                    break;
                }
            case BORROWER:
                {
                    // ------------------------------------------------------------------------------
                    // borrower
                    // ------------------------------------------------------------------------------
                    $db_conn = new PDO("mysql:host=localhost;dbname=library", BORROWER, BORROWER_PSSD);
                    break;
                }
            default:
                {
                    // ------------------------------------------------------------------------------
                    // Pub
                    // ------------------------------------------------------------------------------
                    $db_conn = new PDO("mysql:host=localhost;dbname=library", PUBL, PUBL_PSSD);
                    break;
                }
        }
        $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $db_conn;
    } catch (PDOException $pdo_ex) {
        error_message($pdo_ex->getMessage());
    }
}