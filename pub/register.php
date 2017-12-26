<?php
session_start();
if(
    isset($_POST['registration_name']) && $_POST['registration_name'] != "" && 
    isset($_POST['registration_email']) && $_POST['registration_email'] != "" &&
    isset($_POST['registration_address']) && $_POST['registration_address'] != "" &&
    isset($_POST['registration_psswd']) && $_POST['registration_psswd'] != "")     
{
    $_SESSION['registration_name'] = $_POST['registration_name'];
    $_SESSION['registration_email'] = $_POST['registration_email'];
    $_SESSION['registration_address'] = $_POST['registration_address'];
    $_SESSION['registration_psswd'] = $_POST['registration_psswd'];
    $_SESSION['is_registration_data'] = true;
    header("Location: ../services/check_usr_data.php");
    exit;
}

$input_style_error_usr = "";
$input_style_error_eml = "";
$input_style_error_adr = "";
$input_style_error_pss = "";

$input_usr_val="";
$input_eml_val="";
$input_adr_val="";
$input_pss_val="";

$reg_email_failed="";

if(isset($_POST['registration_data_submit']))
{
    if(!isset($_POST['registration_name']) || $_POST['registration_name'] == "")
    {
        $input_style_error_usr = 'input_error';
    }
    else
    {
        $input_usr_val=$_POST['registration_name'];
    }

    if(!isset($_POST['registration_email']) || $_POST['registration_email'] == "")
    {
        $input_style_error_eml = 'input_error';
    }
    else
    {
        $input_eml_val= 'value="'. $_POST['registration_email'] .'"';
    }

    if(!isset($_POST['registration_address']) || $_POST['registration_address'] == "")
    {
        $input_style_error_adr = 'input_error';
    }
    else
    {
        $input_adr_val=$_POST['registration_address'];
    }

    if(!isset($_POST['registration_psswd']) || $_POST['registration_psswd'] == "")
    {
        $input_style_error_pss = 'input_error';
    }
    else
    {
        $input_pss_val=$_POST['registration_psswd'];
    }
    
}
elseif (isset($_SESSION['registration_succeeded']) && !$_SESSION['registration_succeeded'])
{
    include_once '../services/config.php';
    
    //we are here because registration has failed
    $input_usr_val = $_SESSION['registration_name'];
    $input_eml_val = $_SESSION['registration_email'];
    $input_adr_val = $_SESSION['registration_address'];    
    
    unset($_SESSION['registration_name']);
    unset($_SESSION['registration_address']);
    unset($_SESSION['registration_email']);
    unset($_SESSION['registration_psswd']);
    
    if(isset($_SESSION['registration_email_failed'])) 
    {
        $reg_email_failed= '<span style="color: red; font-size: 22px;">&#9877;</span>';
        $input_style_error_eml = 'input_error';
        switch ($_SESSION['registration_email_failed'])
        {
            case ERROR_DUPLICATE:
            {
                $input_eml_val= 'value="email in use. choose another one" style="color: #b8b8b8;"';
                break;
            }
            case ERROR_WRONG_FORMAT:
            {
                $input_eml_val= 'value="expected@format.com" style="color: #b8b8b8;"';
                break;
            }
            default:
            {
                $input_eml_val= 'value="unexpected error" style="color: #b8b8b8;"';
                break;
            }
        }
        
        unset($_SESSION['registration_email_failed']);
    }
    
    unset($_SESSION['registration_succeeded']);
}

include '../shelves/header.php';
?>
<div class="content">
	<div class="usr_nav">
		<a href="index.php"><span class="rel_buttom">&#9830; Home</span></a> 
		<a href="login.php"><span class="rel_buttom">Login &#10149;</span></a>
	</div>
	<div class="content_main">
	<div class="content_main_left">
		<?php
		$rnd = isset($_POST['img_id']) ? $_POST['img_id']: rand(1, 6);		    
		echo '<div class="content_main_left_in"><img src="../pics/login_page_img_'.$rnd.'.jpg" alt="picture" width="100%" height="100%"></div>';		
		?>
		</div>		
		<div class="content_main_right" style="overflow: hidden;">
					<div class="registration_form_header"><span>Fill up the form below &#9660;</span></div>
					<div class="registration_form">
						<form action="" method="post">
							<table class="centered">
								<tr>
									<td>Name:</td>
									<td><input type="text" name="registration_name" class="registration_data_input <?php echo $input_style_error_usr;?>" value="<?php echo $input_usr_val;?>"></td>
								</tr>
								<tr>
									<td><?php echo $reg_email_failed; ?>Email:</td>
									<td><input type="text" name="registration_email" class="registration_data_input <?php echo $input_style_error_eml;?>" <?php echo $input_eml_val;?>></td>
								</tr>								
								<tr>
									<td>Address:</td>
									<td><input type="text" name="registration_address" class="registration_data_input <?php echo $input_style_error_adr;?>" value="<?php echo $input_adr_val;?>"></td>
								</tr>
								<tr>
									<td>password:</td>
									<td><input type="password" name="registration_psswd" class="registration_data_input <?php echo $input_style_error_pss;?>" value="<?php echo $input_pss_val;?>"></td>
								</tr>								
								<tr>
									<td></td>
									<td><input type="submit" name="registration_data_submit" value="Submit" class="registration_submit"><input type="reset" name="registration_data_submit" value="Reset" class="registration_submit"></td>
								</tr>
							</table>
							<input type="hidden" name="img_id" value="<?php echo $rnd;?>">
						</form>
					</div>		
		</div>
	</div>
</div>
<?php include '../shelves/footer.php';?>