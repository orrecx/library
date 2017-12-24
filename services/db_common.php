<?php
include_once 'config.php';
include_once 'error_handling.php';
include_once 'db_access_mgr.php';

function number_books()
{
    $db_conn = db_getConnection(PUBL);
    try {
        $stmt = $db_conn->query('SELECT book_title FROM tb_books');
        return $stmt->rowCount();
    } catch (PDOException $pdo_ex) {
        error_message($pdo_ex->getMessage());
    }
}

function books_of_the_day($td_style, $tr_header_stlye, $td_content_style, $db_user)
{
    $nb_bks = number_books();
    if ($nb_bks > 1) {
        $half = round($nb_bks / 2);
        $bid_1 = rand(1, $half);
        $bid_2 = rand($half + 1, $nb_bks);
        $db_conn = db_getConnection($db_user);
        $sth = $db_conn->query('SELECT book_title, book_author, book_description FROM tb_books WHERE book_id = ' . $bid_1 . ' OR book_id = ' . $bid_2);
        printf('<table><tr class="%s"><th>Title</th><th>Author</th><th>Description</th></tr>', $tr_header_stlye);
        $i = 1;
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            printf('<tr class="%s_%d"><td><div class="%s">%s</div></td><td><div class="%s">%s</div></td><td><div class="%s">%s</div></td></tr>', $td_style, ($i % 2), $td_content_style, $row['book_title'], $td_content_style, $row['book_author'], $td_content_style, $row['book_description']);
            $i ++;
        }
        
        printf('</table>');
    }
}

// select book_title, book_author from tb_books where book_title like $title and book_author like $author
function search_books($title, $author, $td_base_style, $tr_header_style, $td_content_style, $db_user)
{
    $qr_select = '';
    $qr = '';
    
    switch ($db_user) {
        case LIBRARIAN:
        case ASSISTANT:
            {
                $qr_select = 'SELECT * FROM tb_books ';
                break;
            }
        case BORROWER:
            {
                $qr_select = 'SELECT book_id, book_title, book_author, book_onloan, book_duedate FROM tb_books ';
                break;
            }
        default:
            {
                $qr_select = 'SELECT book_id, book_title, book_author, book_description FROM tb_books ';
                break;
            }
    }
    
    if (isset($title) && $title != '') {
        $qr = $qr_select . 'WHERE book_title LIKE "%' . trim(htmlspecialchars($title)) . '%"';
    }
    
    if (isset($author) && $author != '') {
        if ($qr != '') {
            $qr = $qr . ' AND book_author LIKE "%' . trim(htmlspecialchars($author)) . '%"';
        } else {
            $qr = $qr_select . 'WHERE book_author LIKE "%' . trim(htmlspecialchars($author)) . '%"';
        }
    }
    
    if ($qr != '') {
        $db_conn = db_getConnection(PUBL);
        
        try {
            $sth = $db_conn->query($qr);
            
            printf('<table><tr class="%s"><th>Title</th><th>Author</th><th>Description</th></tr>', $tr_header_style);
            
            $i = 1;
            $n = 0;
            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                printf('<tr class="%s_%d "><td><div class="%s">%s</div></td><td><div class="%s">%s</div></td><td><div class="%s">%s</div></td></tr>', $td_base_style, ($i % 2), $td_content_style, $row["book_title"], $td_content_style, $row["book_author"], $td_content_style, $row["book_description"]);
                $i ++;
            }
            
            printf('</table>');
        } catch (PDOException $pdo_ex) {
            error_message($pdo_ex);
            exit();
        }
        
        return SUCCEEDED;
    } else {
        return FAILED;
    }
}

// select book_title, book_author from tb_books where book_title like $title and book_author like $author
function search_books_2($title, $author, $nb_res, $td_base_style, $td_img_style, $db_user)
{
    $qr_select = '';
    $qr = '';
    
    switch ($db_user) {
        case LIBRARIAN:
        case ASSISTANT:
            {
                $qr_select = 'SELECT * FROM tb_books ';
                break;
            }
        case BORROWER:
            {
                $qr_select = 'SELECT book_id, book_title, book_author, book_description, book_img, book_onloan, book_duedate FROM tb_books ';
                break;
            }
        default:
            {
                $qr_select = 'SELECT book_id, book_title, book_author, book_description, book_img FROM tb_books ';
                break;
            }
    }
    
    if (isset($title) && $title != '') {
        $qr = $qr_select . 'WHERE book_title LIKE "%' . trim(htmlspecialchars($title)) . '%"';
    }
    
    if (isset($author) && $author != '') {
        if ($qr != '') {
            $qr = $qr . ' AND book_author LIKE "%' . trim(htmlspecialchars($author)) . '%"';
        } else {
            $qr = $qr_select . 'WHERE book_author LIKE "%' . trim(htmlspecialchars($author)) . '%"';
        }
    }
    
    if(!isset($title) && !isset($author) && $nb_res != -1)
    {
        //get last $nb_res books
        $qr = 'SELECT book_id, book_title, book_author, book_description, book_img, book_onloan, book_duedate FROM tb_books ORDER BY book_id DESC LIMIT ' .$nb_res;
    }
    
    if ($qr != '') {
        $db_conn = db_getConnection($db_user);
        
        try {
            $sth = $db_conn->query($qr);
            $i = 1;
            $n = 0;
            $printf_form = $sth->rowCount() > 0 && $db_user == BORROWER ? true : false;
            
            if ($printf_form)
                printf('<form method="post" action="">');
            printf('<table>');
            
            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                $tr_style = $td_base_style . "_" . ($i % 2);
                switch ($db_user) {
                    case LIBRARIAN:
                        {
                            printf('<tr class="%s"><td class="%s">%s</td><td>%s:<br></td><td>%s</td></tr>', $tr_style, $td_img_style, $row["book_img"], $row["book_title"], $row["book_author"], $row["book_description"]);
                            break;
                        }
                    case ASSISTANT:
                        {
                            printf('<tr class="%s"><td class="%s">%s</td><td>%s:<br>%s</td><td>%s</td></tr>', $tr_style, $td_img_style, $row["book_img"], $row["book_title"], $row["book_author"], $row["book_description"]);
                            break;
                        }
                    case BORROWER:
                        {
                            printf('<tr class="%s"><td class="%s"><img src="%s" alt="default_img" style="display: block; margin-left: auto; margin-right: auto; width:50%%; height:50%%;"></td><td><strong>%s</strong><br><em style="font-size=12px;">by %s</em></td><td>%s</td><td>', $tr_style, $td_img_style, $row["book_img"], $row["book_title"], $row["book_author"], $row["book_description"]);
                            if ($row['book_onloan']) {
                                printf('<td>%s</td></tr>', $row["book_duedate"]);
                            } else {
                                printf('<td><input type="checkbox" name="checkbox_%s_%s"></td></tr>', $n, $row['book_id']);
                                $n ++;
                            }
                            printf("\r\n");
                            break;
                        }
                    default:
                        {
                            printf('<tr class="%s"><td class="%s">%s</td><td>%s:<br>%s</td><td>%s</td></tr>', $tr_style, $td_img_style, $row["book_img"], $row["book_title"], $row["book_author"], $row["book_description"]);
                            break;
                        }
                }
                
                $i ++;
            }
            
            if ($printf_form && $n > 0)
                printf('<tr class="%s_0" style="text-align: center; background-color: #171717; color: white;"><td></td><td colspan="2" ><input type="submit" value="Submit book selection" name="user_book_selection" style="background-color:%s_0; font-weight: bold; width: 30%%;" /></td></tr>', $td_base_style, $td_base_style);
            
            printf('</table>');
            if ($printf_form)
                printf('</form>');
        } catch (PDOException $pdo_ex) {
            error_message($pdo_ex);
            exit();
        }
        
        return SUCCEEDED;
    } else {
        return FAILED;
    }
}

function search_last_20_books($td_base_style, $td_img_style, $db_user)
{
    search_books_2(null, null, 20, $td_base_style, $td_img_style, $db_user);
}

?>