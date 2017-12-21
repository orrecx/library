<?php

$css_input_error_deco = '';
$error_state = false;
$qr = '';

if(isset($_POST['submit_addbook']))
{
    
    //------------------------------------------------------------------------------
    // save book
    //------------------------------------------------------------------------------    
    $db_conn = new PDO("mysql:host=localhost;dbname=library", "librarian", "librarian_psswd");
    $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try
    {
        $n = 0;
        foreach ($_POST as $name => $value)
        {
            if($name != "submit_book_reservation")
            {
                $name = substr($name, 3);
                $qr = 'update tb_books set book_onloan=true, book_duedate=2018-01-12, borrower_id=2 where book_id=' .$name.';';
                $db_conn->query($qr);
                $n++;
            }
        }
        
        printf('<div class="info_header"><h3><a href="booksearch.php">Back to search</a></h3></div>');
        if($n != 0) printf('<div class="main_t"><h3>Your books have been reserved</h3></div>');
    }
    catch (PDOException $ex)
    {
        printf("..... site under maintenance...");
        printf($ex->getMessage());
    }
    
    exit;    
}

include '../services/preferences.php';
    
?>
<!doctype html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library at KingsRoot</title>
    <link rel="stylesheet" type="text/css" href="../assets/styles.css">
    <style>
    
    .main_t {
    	background-color: <?php echo $color_pref_value; ?>;
    }    
    
    .info_header
    {
        background-color: #e6ffe6;
        text-align: right;
        font-size: 14px;
        width: 100%;
        height: 50px;
        margin-bottom: 5px;
        padding-top: 5px;
    }        
    
    </style>
  </head>  
  <body>
   <?php 
   
    //------------------------------------------------------------------------------
    // Show result from book search
    //------------------------------------------------------------------------------
    if(isset($_POST['submit_addbook']))
    {    
        $qr_select = 'select book_id, book_title, book_author, book_onloan from tb_books ';
    
        if(isset($_POST['book_title']) && $_POST['book_title'] != '')
        {
            $qr = $qr_select . 'where book_title like "%'. trim(htmlspecialchars($_POST['book_title'])).'%"';
        }

        if(isset($_POST['book_author']) && $_POST['book_author'] != '')
        {
            if($qr != '')
            {
                $qr = $qr . ' and book_author like "%'. trim(htmlspecialchars($_POST['book_author'])) .'%"';
            }
            else 
            {
                $qr = $qr_select . 'where book_author like "%'. trim(htmlspecialchars($_POST['book_author'])) .'%"';
            }
        }

        if(isset($_POST['no_onloan']))
        {
            if($qr !== '')
                $qr = $qr . ' and book_onloan;';
            else 
                $css_input_error_deco = 'class="input_error"';
        }
    
        if($qr != '')
        {
            $db_conn = new PDO("mysql:host=localhost;dbname=library", "orex", "unevie");
            $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try 
            {
                $sth = $db_conn->query($qr);            

                printf('<div class="main-t">');
            
                printf('<h1>Library at KingsRoot</h1> <hr>');
                printf('<div class="info_header"><h3><a href="booksearch.php">Back to search</a></h3></div>');
                printf('<form action="booksearch.php" method="post">');
                printf('<table style="width:50%%;">');
                printf('<tr style="background-color:#66b0ff;">');
                printf('<th>Title</th><th>Author</th><th>Reserve</th>');
                printf('</tr>');
            
                $i = 1;
                $n = 0;
                while($row = $sth->fetch(PDO::FETCH_ASSOC))
                {
                    printf('<tr class="tr_out_%d ">', $i%2);
                    printf('<td>%s</td><td>%s</td>', $row["book_title"], $row["book_author"]);
                    printf('<td>');
                    if(!$row["book_onloan"])
                    {
                        printf('<input type="checkbox" name="id_%s">', $row['book_id']);
                        $n++;
                    }
                    printf('</td>');
                    printf('</tr>');
                    $i++;
                }               
            
                if($n > 0 )
                {
                    printf('<tr><td colspan="3"><input type="submit" name="submit_book_reservation" value="Save Reservation"></td></tr>');  
                }            
            
                printf('</table>');            
                printf('</form>');
                printf('</div>');
            
            }
            catch (PDOException $pdo_ex)
            {
                $error_state = true;
                printf('SQL Statement: %s \n', $qr);
                printf('General error: %s \n', $pdo_ex->getMessage());            
            }
            
            exit;
        }
        else 
        {
            $css_input_error_deco = 'class="input_error"';
        }        
    }       
  ?>
  
   <h1>Library at KingsRoot</h1>
  <hr>
  <div class="info_header"><h3>Administrative Section: adding books</h3></div>  
  <div class="main_t">
	<form action="" method="post">

		<table>
			<tr>
				<td>Title:</td><td><input type="text" name="book_title" <?php echo $css_input_error_deco; ?>></td>
			</tr>
			<tr>
				<td>Author:</td><td><input type="text" name="book_author" <?php echo $css_input_error_deco; ?>></td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" name="submit_addbook" value="Add"></td>
			</tr>			
		</table>
	</form>
</div>
</body>
</html>

