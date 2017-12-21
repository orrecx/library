<?php

    session_start();
    $msg = "choose...";
    $colors = array("Pink" => "f0d0d0", "Violet" => "cda8ef", "Blue" => "a8c1ef","Green" => "a8efab","Yellow" => "efee7b");
    
    if(isset($_POST['color_chooser']) && $_POST['color_chooser'] != $msg)
    {
        $_SESSION['color_preferences'] = $_POST['color_chooser'];
        header("location: booksearch.php");
        exit;
    }
    else
    {
?>

<!doctype html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>color chooser</title>
    <style>
    body { background-color: white;}

    .main_t {
    	width: 50%;
    	background-color: #b3d9ff;
    }    
    
    input, option  {
    	width: 50%;
    	background-color: #99ff99;
    	font-size: 16px; 
    }
    
    option { background-color: white;}
    
    td, tr, th, table {
    	font-size: 16px;
    }        
    </style>
    
  </head>
  <body>
  <div>
  <h1>Colour Preferences</h1>
  <hr>
  	<form action="colourchooser.php" method="post">
  	<table>
  	<tr>
  	<td>Choose your color:</td>
  	<td>
  	<select name="color_chooser">
  	<option value="<?php echo $msg;?>" selected><?php echo $msg;?></option>
  	<?php  	
  	 foreach ($colors as $color_name => $color_hex)
  	 {
  	     echo '<option value="'.$color_name .'">' . $color_name .'</option>';
  	 }
  	?>  	
  	</select>  	
  	</td>
  	</tr>
  	<tr>
  	<td colspan="2"><input type="submit" name="color_chooser_submit" value="Save"></td>
  	</tr>
  	</table>
  	</form>
  </div>
  </body>
</html>
<?php } ?>