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

function books_of_the_day($tr_base_style, $td_img_style, $db_user)
{
    $nb_bks = number_books();
    if ($nb_bks > 1) {
        $half = round($nb_bks / 2);
        $bid_1 = rand(1, $half);
        $bid_2 = rand($half + 1, $nb_bks);
        $db_conn = db_getConnection($db_user);
        $sth = $db_conn->query('SELECT book_title, book_author, book_description, book_img FROM tb_books WHERE book_id = ' . $bid_1 . ' OR book_id = ' . $bid_2);
        printf('<table>');
        $i = 1;
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            
            printf('<tr class="%s_%d"><td class="%s"><img src="%s" alt="default_img" style="display: block; margin-left: auto; margin-right: auto; width:50%%; height:50%%;"></td><td><strong>%s</strong><br><em style="font-size=12px;">by %s</em></td><td>%s</td><td>',
                $tr_base_style, ($i % 2), $td_img_style, $row["book_img"], $row["book_title"], $row["book_author"], $row["book_description"]);
            
            $i ++;
        }
        
        printf('</table>');
    }
}

// select book_title, book_author from tb_books where book_title like $title and book_author like $author
function search_books_2($title, $author, $nb_res, $td_base_style, $td_img_style, $db_user)
{
    $qr_select = '';
    $qr = '';
    
    switch ($db_user) {
        case LIBRARIAN:
            {
                $qr_select = 'SELECT * FROM tb_books ';
                break;
            }
        case ASSISTANT:
            {
                $qr = 'SELECT * FROM tb_books ';
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
    
    if (isset($title) && $title != '' && $nb_res != -1) {
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
        //get last $nb_res books: nb_res != -1
        $qr = 'SELECT book_id, book_title, book_author, book_description, book_img, book_onloan, book_duedate FROM tb_books ORDER BY book_id DESC LIMIT ' . $nb_res;
    }
    
    if ($qr != '') {
        $db_conn = db_getConnection($db_user);
        
        try {
            $sth = $db_conn->query($qr);
            $i = 1;
            $n = 0;
            $printf_form = $sth->rowCount() > 0 && $db_user == BORROWER ? true : false;
            
            if ($printf_form) printf('<form method="post" action="">');
            
            printf('<table class="db_list">');
            
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
                            $tr_style = 'class="' . $tr_style . '" '; 
                            $tr_trail = '';
                            if($row['book_onloan'])
                            {
                                $tr_trail = '<td style="text-align: center;">'.$row['book_duedate'].'<br><span style="font-style: italic; font-size: 14px;">- '.$row['borrower_id'].' -</span></td>';
                                $datetime = new DateTime($row['book_duedate']);
                                if(time() > $datetime->getTimestamp())
                                {
                                    $tr_style = $tr_style . ' style="background-color: #ffa2a2;" ';
                                }
                                else 
                                {
                                    $tr_style = $tr_style . ' style="background-color: #b9ffb9;" ';
                                }
                            }
                            else 
                            {
                                $tr_trail = '<td></td>';
                            }
                            
                            printf('<tr %s>
                                    <td style="text-align: center; width:10%%;">[%s]</td>
                                    <td style="text-align: center; width:10%%;"><img src="%s" alt="default_img" style="display: block; margin-left: auto; margin-right: auto; width:60%%; height:10%%;"></td>
                                    <td style="width: 30%%;"><span style="font-weight: bold;">%s</span><br><span style="font-style: italic; font-size: 14px;">by %s</span></td>
                                    <td style="width: 40%%;">%s</td>
                                    %s</tr>',
                                $tr_style, $row["book_id"], $row["book_img"], $row["book_title"], $row["book_author"], $row["book_description"], $tr_trail) ;                            
                            
                            break;
                        }
                    case BORROWER:
                        {
                            printf('<tr class="%s"><td class="%s"><img src="%s" alt="default_img" style="display: block; margin-left: auto; margin-right: auto; width:50%%; height:50%%;"></td><td><strong>%s</strong><br><em style="font-size=12px;">by %s</em></td><td>%s</td><td>', 
                                $tr_style, $td_img_style, $row["book_img"], $row["book_title"], $row["book_author"], $row["book_description"]);
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
                            printf('<tr class="%s"><td class="%s"><img src="%s" alt="default_img" style="display: block; margin-left: auto; margin-right: auto; width:50%%; height:50%%;"></td><td><strong>%s</strong><br><em style="font-size=12px;">by %s</em></td><td>%s</td><td>',
                                $tr_style, $td_img_style, $row["book_img"], $row["book_title"], $row["book_author"], $row["book_description"]);
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
            error_message($pdo_ex->getMessage());
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

function reserve_books($user_selection, $user_id, $db_user)
{
    $db_conn = db_getConnection($db_user);
    
    try 
    {
        foreach ($user_selection as $key => $value)
        {
            if(!($key == 'user_book_selection'))
            {
                $slct = explode('_', $key);
                $qr = 'INSERT INTO tb_reservations (book_id, borrower_id) Values ('.$slct[2].', '.$user_id.')';
                $db_conn->query($qr);
            }
        }
    } 
    catch (PDOException $ex) 
    {
        error_message($ex->getMessage());
        exit;
    }
}

function get_user_reserved_books($user_id, $td_base_style, $td_img_style, $db_user)
{
    $db_conn = db_getConnection($db_user);
    
    try
    {
        $qr = 'SELECT book_id, borrower_id from tb_reservations WHERE borrower_id = ' . $user_id;
        $rows = $db_conn->query($qr);
        if($rows->rowCount() > 0)
        {
            $books;
            while($row = $rows->fetch(PDO::FETCH_ASSOC))
            {
                $books["".$row['book_id'].""] = $row['book_id'];                
            }
            
            printf('<table>');
            $i = 1;
            foreach($books as $key => $val)
            {
                $rows = $db_conn->query('SELECT book_id, book_title, book_author, book_img FROM tb_books WHERE book_id = ' . $val);
                $row = $rows->fetch(PDO::FETCH_ASSOC);
                $tr_style = $td_base_style . '_' . ($i % 2);
                printf('<tr class="%s"><td class="%s"><img src="%s" alt="default_img" style="display: block; margin-left: auto; margin-right: auto; width:50%%; height:50%%;"></td><td><strong>%s</strong><br><em style="font-size=12px;">by %s</em></td></tr>', 
                            $tr_style, $td_img_style, $row["book_img"], $row["book_title"], $row["book_author"]);
                $i++;
            }
            
            printf('</table>');
            return true;
        }
        else 
        {
            return false;
        }
    }
    catch (PDOException $ex)
    {
        error_message($ex->getMessage());
        exit;
    }    
    
    return false;
}

function get_my_borrowed_books($user_id, $td_base_style, $td_img_style, $db_user)
{
    $db_conn = db_getConnection($db_user);
    
    try
    {
        $qr = 'SELECT book_id, book_title, book_author, book_img, borrower_id, book_onloan, book_duedate FROM tb_books WHERE borrower_id = ' . $user_id . ' AND book_onloan = true';
        $rows = $db_conn->query($qr);
        if($rows->rowCount() > 0)
        {
            $books;
            printf('<table>');
            $i = 1;
            
            while($row = $rows->fetch(PDO::FETCH_ASSOC))
            {
                $tr_style = $td_base_style . '_' . ($i % 2);
                printf('<tr class="%s"><td class="%s"><img src="%s" alt="default_img" style="display: block; margin-left: auto; margin-right: auto; width:50%%; height:50%%;"></td><td><strong>%s</strong><br><em style="font-size=12px;">by %s</em></td><td>%s</td></tr>',
                    $tr_style, $td_img_style, $row["book_img"], $row["book_title"], $row["book_author"], $row["book_duedate"]);
                $i++;
            }
            
            printf('</table>');
            return true;
        }
        else
        {
            return false;
        }
    }
    catch (PDOException $ex)
    {
        error_message($ex->getMessage());
        exit;
    }
    
    return false;
    
}


function get_book_request($td_base_style, $nb_res, $db_user)
{
    $db_conn = db_getConnection($db_user);
    
    try
    {
        $qr = 'SELECT reservation_id, book_id, borrower_id FROM tb_reservations ORDER BY reservation_id DESC ' . ($nb_res > 0 ? 'LIMIT ' .$nb_res : '');
        $rows = $db_conn->query($qr);
        if($rows->rowCount() > 0)
        {
            printf('<table class="db_list">');
            $i = 1;
            while($row = $rows->fetch(PDO::FETCH_ASSOC))
            {
                $db_conn2 = db_getConnection($db_user);
                $db_conn3 = db_getConnection($db_user);
                
                $qr2 = 'SELECT book_id, book_img, book_author, book_title FROM tb_books WHERE book_id =' .$row['book_id'];
                $qr3 = 'SELECT borrower_id, borrower_name FROM tb_borrowers WHERE borrower_id =' .$row['borrower_id'];
                
                $rows2 = $db_conn2->query($qr2);
                $rows3 = $db_conn3->query($qr3);
                
                if($rows2->rowCount() != 1 || $rows2->rowCount() != 1)
                {
                    return false;    
                }
                else 
                {
                    $row2 = $rows2->fetch(PDO::FETCH_ASSOC);
                    $row3 = $rows3->fetch(PDO::FETCH_ASSOC);
                    
                    printf('<tr class="%s_%d";">
                                <td style=" width:20%%; text-align: center;"><img src="%s" alt="default_img" width="60%%" height="60%%"></td>
                                <td style="width:50%%;"><span style="font-weight: bold;">%s</span><br><span style="font-size: 14px; font-style: italic;">by %s</span></td>
                                <td style="width=30%%; text-align: center;">%s<br><span style="font-size: 14px; font-style: italic;">[id:%s]</span></td>
                            </tr>',
                        $td_base_style, ($i % 2), $row2["book_img"], $row2["book_title"], $row2["book_author"], $row3["borrower_name"], $row3["borrower_id"]);
                    $i++;                    
                }
            }
            
            printf('</table>');            
            
            return true;
        }
        else
        {
            return false;
        }
    }
    catch (PDOException $ex)
    {
        error_message($ex->getMessage());
        exit;
    }    
}


function get_overdue_books($td_base_style, $nb_res, $db_user)
{
    $db_conn = db_getConnection($db_user);
    
    try
    {
        $qr = 'SELECT book_id, book_title, book_author, book_img, book_onloan, book_duedate, borrower_id FROM tb_books WHERE book_onloan = true ORDER BY book_duedate ASC ' . ($nb_res > 0 ? 'LIMIT ' .$nb_res : '');
        $rows = $db_conn->query($qr);
        if($rows->rowCount() > 0)
        {
            printf('<table class="db_list">');
            $i = 1;
            while($row = $rows->fetch(PDO::FETCH_ASSOC))
            {
                if($row['book_onloan'])
                {
                    $datetime = new DateTime($row['book_duedate']);
                    if(time() > $datetime->getTimestamp())
                    {
                        $db_conn3 = db_getConnection($db_user);                
                        $qr3 = 'SELECT borrower_id, borrower_name FROM tb_borrowers WHERE borrower_id =' .$row['borrower_id'];                
                        $rows3 = $db_conn3->query($qr3);
                        $row3 = $rows3->fetch(PDO::FETCH_ASSOC);
                    
                        printf('<tr class="%s_%d";">
                                    <td style=" width:20%%; text-align: center;"><img src="%s" alt="default_img" width="60%%" height="60%%"></td>
                                    <td style="width:50%%;"><span style="font-weight: bold;">%s</span><br><span style="font-size: 14px; font-style: italic;">by %s</span></td>
                                    <td style="width=30%%; text-align: center;">%s<br><span style="font-size: 14px; font-style: italic;">by [%s]</span></td>
                                </tr>',
                            $td_base_style, ($i % 2), $row["book_img"], $row["book_title"], $row["book_author"], $row["book_duedate"], $row3["borrower_id"]);
                        $i++;
                    }
                }
            }
            
            printf('</table>');
            
            return true;
        }
        else
        {
            return false;
        }
    }
    catch (PDOException $ex)
    {
        error_message($ex->getMessage());
        exit;
    }
}
?>