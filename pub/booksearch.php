<?php 
    session_start();
    
    $hit_count = isset($_COOKIE['hit_count']) ? $_COOKIE['hit_count'] + 1 : 1;
    setcookie('hit_count', $hit_count, time() + 24*3600, '/');
    
    $colors = array("Pink" => "f0d0d0", "Violet" => "cda8ef", "Blue" => "a8c1ef","Green" => "a8efab","Yellow" => "efee7b");
    $color_pref_name="";
    $color_pref_value="#b3d9ff";
    $color_pref ="";    
    
    if (isset($_SESSION['color_preferences']))
    {
        $color_pref_name = $_SESSION['color_preferences'];
        unset($_SESSION['color_preferences']);        
    }
    elseif (isset($_COOKIE['color_preferences']))
    {
        $color_pref_name = $_COOKIE['color_preferences'];
    }
    
    if($color_pref_name != "")
    {
        $color_pref = '<a href="colourchooser.php"> Color preference: '. $color_pref_name . '</a> , ';
        $color_pref_value = "#". $colors[$color_pref_name];
        setcookie("color_preferences", $color_pref_name, time() + 24*3600);        
    }
    else 
    {
        $color_pref = '<a href="colourchooser.php">To color preferences</a>, ';
    }    
?>
<!doctype html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library at KingsRoot</title>
    <style>
    body { background-color: white;}

    .main_t {
    	width: 50%;
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
    
    input {
    	width: 100%;
    	background-color: #F5F5F5;
    	font-size: 16px; 
    }
    
    td, tr, th, table {
    	font-size: 16px;
    }    
    
    .tr_out_0 { background-color: #e6f2ff;}
    .tr_out_1 { background-color: #FFFFFF;}
    
    .input_error { border-color: red; }
    
    </style>
  </head>  
  <body>
   <?php 
   $css_input_error_deco = '';   
   $error_state = false;
   $qr = '';
   
   //------------------------------------------------------------------------------
   // save book reservation
   //------------------------------------------------------------------------------
    if(isset($_POST['submit_book_reservation']))
    {        
        $db_conn = new PDO("mysql:host=localhost;dbname=library", "orex", "unevie");
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

    //------------------------------------------------------------------------------
    // Show result from book search
    //------------------------------------------------------------------------------
    if(isset($_POST['submit_booksearch']))
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
  
   <h1>Book Catalog</h1>
  <hr>
  <div class="info_header"><h3><?php echo $color_pref; ?>hit count [<?php echo $hit_count; ?>]</h3></div>  
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
				<td>Do not show books onloan:</td><td><input type="checkbox" name="no_onloan"></td>
			</tr>			
			<tr>
				<td></td><td><input type="submit" name="submit_booksearch" value="Search"></td>
			</tr>			
		</table>
	</form>
</div>
</body>
</html>

