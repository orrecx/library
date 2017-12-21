<?php

?>

<!doctype html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library at KingsRoot</title>
    <link rel="stylesheet" type="text/css" href="../assets/styles.css"> 
  </head>
  <body>
   <h1>Library at KingsRoot</h1>
  <hr>  
  <?php 
  
    $sendmail = false;
    $email;
    $message;
    $name = "Mr X";
    $css_emailinput_deco = '';
    
    if(isset($_GET['message']) && $_GET['message'] != "")
    {
        $message = $_GET['message'];
        if(isset($_GET['email']) &&  $_GET['email'] != "")
        {
            $email = $_GET['email'];
            if(isset($_GET['reply']))
            {
                $sendmail = true;
            }
        }
        else 
        {
            $css_emailinput_deco = 'class="emailerror"';
        }
        
        if(isset($_GET['name']))
        {
            $name = $_GET['name'];
        }
    }
        
    if($sendmail)
    {
        $message = "Message from [".$name."; ".$email."]: \n \n" .$message;
        if(mail($email, "Library web visitor request", $message))
        {
            echo ' have a look into your mail account '. $email .': a confirmation mail has been sent to you.';
        }
        else 
        {
            echo 'it was not possible to send you a confirmation email... mailserver issue';
            
        }
        echo "Dear " .$name ." it's a pleasure to hear about you... you request is been processed..";
    }
    else 
    {
  ?>
  <div class="main_t">
	<form action="" method="get">

		<table>
			<tr>
				<td>name:</td><td><input type="text" name="name"></td>
			</tr>
			<tr>
				<td>email:</td><td><input type="text" name="email" <?php echo $css_emailinput_deco; ?>></td>
			</tr>
			<tr>
				<td>Message:</td><td><textarea name="message"></textarea></td>
			</tr>
			<tr>
				<td>Do you want a reply:</td><td><input type="checkbox" name="reply"></td>
			</tr>			
			<tr>
				<td></td><td><input type="submit" name="submit_contact" value="submit"></td>
			</tr>			
		</table>
	</form>
</div>
<?php } ?>

</body>
</html>