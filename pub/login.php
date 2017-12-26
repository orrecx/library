<?php
session_start();
if (isset($_POST['login_usrname']) && $_POST['login_usrname'] != "" && isset($_POST['login_usrpsswd']) && $_POST['login_usrpsswd'] != "") {
    $_SESSION['login_usrname'] = $_POST['login_usrname'];
    $_SESSION['login_usrpsswd'] = $_POST['login_usrpsswd'];
    $_SESSION['is_login_data'] = true;
    header("Location: ../services/check_usr_data.php");
    exit();
}

$login_failed = false;
$input_style_error_usr = "";
$input_style_error_pss = "";
$input_usr_val = "";
$input_pss_val = "";
$registration_succeeded = false;

if (isset($_POST['login_data_submit'])) {
    if (! isset($_POST['login_usrname']) || $_POST['login_usrname'] == "") {
        $input_style_error_usr = 'input_error';
    } else {
        $input_usr_val = $_POST['login_usrname'];
    }
    
    if (! isset($_POST['login_usrpsswd']) || $_POST['login_usrpsswd'] == "") {
        $input_style_error_pss = 'input_error';
    } else {
        $input_pss_val = $_POST['login_usrpsswd'];
    }
} elseif (isset($_SESSION['login_usrname'])) {
    // we are here because login has failed
    $login_failed = true;
    $input_usr_val = $_SESSION['login_usrname'];
    unset($_SESSION['login_usrname']);
    unset($_SESSION['login_usrpsswd']);
} elseif (isset($_SESSION['registration_succeeded']) && $_SESSION['registration_succeeded']) {
    // we are here because we just got registered
    $input_usr_val = $_SESSION['registration_email'];    
    $registration_succeeded = true;
    
    unset($_SESSION['registration_succeeded']);
    unset($_SESSION['registration_email']);
}

include '../shelves/header.php';
?>
<div class="content">
	<div class="usr_nav">
		<a href="index.php"><span class="rel_buttom">&#9830; Home</span></a> 
		<a href="register.php"><span class="rel_buttom">Register &#10149;</span></a>
	</div>
	<div class="content_main">
	<div class="content_main_left">
		<?php
$rnd = isset($_POST['img_id']) ? $_POST['img_id'] : rand(1, 6);
echo '<div class="content_main_left_in"><img src="../pics/login_page_img_' . $rnd . '.jpg" alt="Picture" width="100%" height="100%"></div>';
?>		
	</div>
		<div class="content_main_right" style="overflow: hidden;">
			<div class="login_form_header">
					<?php
    if ($registration_succeeded) 
    {
        echo '<span>&#9786; Login into your account &#9660;</span>';        
    } 
    else 
    {        
        echo '<a href="register.php"><span>no account yet ? then register</span></a>';
    }
    ?>
				 </div>

			<div class="login_form">
				<form action="" method="post">
					<table class="centered">
						<?php
                            if ($login_failed) {
                                printf('<tr><td></td><td class="input_login" style="background-color: #a7adba;"><span style="color: red;">Email or Password wrong</span></td></tr>');
                            }
                        ?>
						<tr>
							<td>Email:</td>
							<td><input type="text" name="login_usrname"
								class="input_login <?php echo $input_style_error_usr;?>" value="<?php echo $input_usr_val;?>"></td>
						</tr>
						<tr>
							<td>Password:</td>
							<td><input type="password" name="login_usrpsswd"
								class="input_login <?php echo $input_style_error_pss;?>" value="<?php echo $input_pss_val;?>"></td>
						</tr>
						<tr>
							<td></td>
							<td><input type="submit" name="login_data_submit" value="Login" class="input_login_submit"></td>
						</tr>
					</table>
					<input type="hidden" name="img_id" value="<?php echo $rnd;?>">
				</form>
			</div>
		</div>
	</div>
</div>
<?php include '../shelves/footer.php';?>