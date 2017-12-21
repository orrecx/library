<?php
    session_start();

    if (! isset($_SESSION['logged_in']) || ! $_SESSION['logged_in']) {
        // header("Location: ../pub/login.php");
    }

    include_once '../services/user_settings.php';
    include_once '../services/db_common.php';

    $usr_name = 'Salif'; //$_SESSION['user_name'];
    $usr_id = 5;   //$_SESSION['user_id'];

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
			<div class="content_header_main_right"></div>
			</div>
			</div>
			<div class="content_main">
				<div class="content_main_left">
					<div class="content_main_left_top">
					<div class="content_main_left_top_left">
					<?php 
					if((isset($_POST['chl_input_title']) && $_POST['chl_input_title'] != '')|| 
					    isset($_POST['chl_input_author']) && $_POST['chl_input_author'] != '')
					{
					    //search
					    search_books_2($_POST['chl_input_title'], $_POST['chl_input_author'], "td_srch_style", "book_description", BORROWER);
					}
					else 
					{
					    //10 newest books
					}
					?>
					</div>
					<div class="content_main_left_top_right"></div>
					</div>
					<div class="content_main_left_bottom"></div>
				</div>
				<div class="content_main_right">
					<div class="content_main_right_top"></div>
					<div class="content_main_right_middle"></div>
					<div class="content_main_right_bottom"></div>
				</div>
			</div>
		</div>
		<div class="footer_t"></div>
	</div>
</body>
</html>
