<?php
session_start();

if (! isset($_SESSION['error_state'])) {
    header('Location: index.php');
    exit();
} else {
    ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Library at KingsRoot</title>
<link rel="stylesheet" type="text/css" href="../assets/styles.css">
</head>
<body>
	<div class="main_t">
		<div class="header_t">
			<div class="header_left"></div>
			<div class="header_right">
				<div class="header_right_content_main">
					<div class="header_right_content">
						<h1>your Library</h1>
					</div>
				</div>
			</div>
		</div>

		<div class="general_error_main">
			<div class="general_error_main_left"></div>
			<div class="general_error_main_right">
<?php
    echo '<div class="general_error_text"><span >';
    if (isset($_SESSION['error_state']))
        echo $_SESSION['error_state'];
    else
        echo 'GENERAL ERROR STATE';
    echo '</span></div>';
    ?>
</div>
		</div>
		<div class="footer_t">
			<div class="footer_main_top"></div>
			<div class="footer_main_bottom"></div>
		</div>
	</div>
</body>	
<?php
    unset($_SESSION['error_state']);
    exit();
}
?>

