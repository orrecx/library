<?php
    session_start();

    if (isset($_SESSION['assistant_logged_in']) && $_SESSION['assistant_logged_in']) {
        header("Location: ../assistant/index.php");
        exit;
    }

    if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
        header("Location: ../assistant/index.php");
        exit;
    }
    
    if (! isset($_SESSION['user_logged_in']) || ! $_SESSION['user_logged_in']) {
        header("Location: ../pub/login.php");
        exit;
    }
    
    if (isset($_POST['submit_logout'])) {
        unset($_SESSION['user_logged_in']);
        session_destroy();
        header("Location: ../pub/index.php");
        exit;
    }
    
    include_once '../services/user_settings.php';
    include_once '../services/db_common.php';

    $usr_name = $_SESSION['user_name'];
    $usr_id = $_SESSION['user_id'];

    $rnd = isset($_SESSION['header_left_pic']) ? $_SESSION['header_left_pic'] : '../pics/user_header_' . rand(1, 3) . '.jpg';
    $header_left_div = '<div class="header_left" style="background-image: url('. $rnd .'); background-size: 100%; background-repeat: no-repeat; background-position: right bottom;"></div>';
    $_SESSION['header_left_pic'] = $rnd;

    $header_right_left_div = '<div class="header_right_left" style="background-image: url('.get_avatar($usr_id, LIBRARIAN).'); background-size: 70%; background-repeat: no-repeat;"></div>';
?>

<!DOCTYPE html>
<html>
<head>
<title>Library at KingsRoot</title>
<link rel="stylesheet" type="text/css" href="../assets/user_styles.css">
</head>
<body>
	<div class="main_t">
		<div class="header_t">
		<?php echo $header_left_div; ?>
			<div class="header_right">
				<div class="header_right_ct">
					<?php echo $header_right_left_div; ?>
					<div class="header_right_right">
					<span class="header_right_right_dv">Welcome</span><br>
					<span class="header_right_right_dv"><?php echo $usr_name;?></span>
					</div>
				</div>
			</div>
		</div>
		<div class="content">
			<div class="content_header">
			<div class="content_header_main">
			<div class="content_header_main_left">
			<form action="" method="post">
			<span>Title:</span> <input type="text" class="usr_content_header_input" name="chl_input_title">&nbsp;
			<span>Author:</span> <input type="text" class="usr_content_header_input" name="chl_input_author">&nbsp;&nbsp;
			<input type="Submit" class="usr_content_header_submit" value="Search">
			</form>
			</div>
			<div class="content_header_main_right">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<input type="hidden" value="logout" name="user_logout">			
			<input type="submit" value="Logout" name="submit_logout" class="usr_content_header_submit">
			</form>
			</div>
			</div>
			</div>
			<div class="content_main">
			<div class="content_main_left">
					<?php 
					if((isset($_POST['chl_input_title']) && $_POST['chl_input_title'] != '')|| 
					    isset($_POST['chl_input_author']) && $_POST['chl_input_author'] != '')
					{
					    //search
					    echo '<div class="table_header">Search Results</div>';
					    search_books_2($_POST['chl_input_title'], $_POST['chl_input_author'], -1, "td_srch_style", "book_description", BORROWER);
					}
					else 
					{
					    //20 newest books
					    echo '<div class="table_header">The 20 newest books in the library </div>';
					    search_last_20_books("td_srch_style", "book_description", BORROWER);
					}
					?>
			</div>
			<div class="content_main_right">
			<div class="content_main_right_top">
			<?php 
			
			if(isset($_POST['user_book_selection']) && count($_POST) > 1)
			{
			    //handle selection
			    reserve_books($_POST, $usr_id,  LIBRARIAN);
			}
			
			echo '<div class="table_header" style="background-color: #00b900">Reservation buckets</div>';
			echo '<div>';
			get_user_reserved_books($usr_id, "td_srch_style", "book_description", LIBRARIAN);
			echo '</div>';
			?>
			</div>
			<div class="content_main_right_bottom">
			<?php 
			 echo '<div class="table_header" style="background-color: #0000ff;">Books in my possession</div>';
			 get_my_borrowed_books($usr_id, "td_srch_style", "book_description", LIBRARIAN);
			?>
			</div>
			</div>
			</div>
		</div>
		<div class="footer_t"></div>
	</div>
</body>
</html>
