<?php
session_start();

if ((isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in']) || (isset($_SESSION['assistant_logged_in']) && $_SESSION['assistant_logged_in'])) {
    unset($_SESSION['user_logged_in']);
    session_destroy();
    header("Location: ../pub/login.php");
    exit;
}

if (! isset($_SESSION['admin_logged_in']) || ! $_SESSION['admin_logged_in']) {
    header("Location: ../pub/login.php");
    exit;
}


if (isset($_GET['admin_logout'])) {
    unset($_SESSION['admin_logged_in']);
    session_destroy();
    header("Location: ../pub/index.php");
    exit;
}

include_once '../services/user_settings.php';
include_once '../services/db_common.php';

$usr_name = $_SESSION['user_name'];
$usr_id = $_SESSION['user_id'];

$rnd = isset($_SESSION['header_left_pic']) ? $_SESSION['header_left_pic'] : '../pics/user_header_' . rand(1, 3) . '.jpg';
$header_left_div = '<div id="header_left" style="background-image: url('. $rnd .'); background-size: 100%; background-repeat: no-repeat; background-position: right bottom;"></div>';
$_SESSION['header_left_pic'] = $rnd;

$header_right_item_pic = '<div class="header_right_item"><img src="'.get_avatar($usr_id, LIBRARIAN).'" alt="user pic" width="100%" height="100%"></div>';

?>

<!DOCTYPE html>
<html>
<head>
<title>Library at KingsRoot</title>
<link rel="stylesheet" type="text/css" href="../assets/admin_styles.css">
<script src="../assets/jquery-3.2.1.js"></script>
<script>

function ajax_request(jq_elt)
{	
	jq_elt.click(function(){
		
		var dt = "admin_req=" + jq_elt.attr('id');
		$('#db_result').html('loading...');
		
		$.ajax
		({
			type: 'POST',
			url: 'adm_ajax.php',
			data: dt,
			dataType: 'json',
			success: function(result)
			{
				//alert(JSON.stringify(result));
				var data = result.data;
				var dbres = $('#db_result');
				
				if(data == "Empty")
				{
					dbres.html('');
				}
				else
				{
					dbres.html('<table id="svr_data">');
					for(val in data)
					{
						
						var txt = '<tr style="width: 100%;"><td style="width:10%;">' + data[val].borrower_id + '</td><td style="width:20%;"><img src="' + data[val].borrower_pic + '"style="width: 40%; border-radius: 4px;"></td><td style="width:20%;">' + data[val].borrower_name + '<br>' + data[val].borrower_address + '</td><td style="width:20%;">' + data[val].borrower_email 
						+ '</td><td><input type="checkbox" name="checkbox_'+data[val].borrower_id+'" id="checkbox_id_'+data[val].borrower_id+'" </td></tr>' ; 
						dbres.append(txt);
					}
					dbres.append('</table>');
					$( "#db_result tr:odd" ).css( "background-color", "#d6d6d6" );					
				}
				
				//alert(data);
				//$('#db_result').html(result['msg']);				
			},
			error: function(jqXHR, status, errorThrown)
			{
				alert(errorThrown);
			}
		});

	});
}

$(document).ready(function(){

	$('#left_sidebar_items ul li').click(function(){
		var insd = $('#left_sidebar_main_inside');
		var insd_top = -1;
		
		if( insd.length )
		{
			insd_top = insd.position().top;
			insd.remove();
			$('.arrow').remove();			
			$(".sidebar_nav_list").css("background-color", "black");
		}

		var pos = $(this).position();
		
		if(insd_top != pos.top)
		{						
			$(this).prepend('<span class="arrow">&#x21D0; &nbsp;</span>');
			switch($(this).attr('id'))
			{
				case 'users_mgr':
				{
					$(this).after('<div id="left_sidebar_main_inside"><ul><li id="Req_Actv_Usr">Activate user</li><li id="Req_Deactv_Usr">Deactivate user</li></ul></div>');
					ajax_request($('#Req_Actv_Usr'));
					ajax_request($('#Req_Deactv_Usr'));
					break;
				}
				case 'assistants_mgr':
				{
					$(this).after('<div id="left_sidebar_main_inside"><ul><li id="Req_Promote_Asst">Promote A.</li><li id="Req_Degreade_Asst">Degrade A.</li></ul></div>');
					ajax_request($('#Req_Promote_Asst'));
					ajax_request($('#Req_Degreade_Asst'));
					break;					
				}
				case 'admins_mgr':
				{
					$(this).after('<div id="left_sidebar_main_inside"><ul><li id="Req_Promote_Adm">Promote Adm</li><li id="Req_Degreade_Adm">Degrade Adm</li></ul></div>');
					ajax_request($('#Req_Promote_Adm'));
					ajax_request($('#Req_Degreade_Adm'));					
					break;					
				}
				case 'books_mgr':
				{
					$(this).after('<div id="left_sidebar_main_inside"><ul><li id="Req_Add_Book">Add book</li><li id="Req_Delete_Book">Delete book</li></ul></div>');
					ajax_request($('#Req_Add_Book'));
					ajax_request($('#Req_Delete_Book'));					
					break;					
				}
				default: 
				{
					alert("not supported yet");
					return;				
				}
			}
						
			$('#left_sidebar_main_inside').css("top", pos.top);
			$(this).css("background-color", "#2b160c");

			$('#left_sidebar_main_inside').mouseleave(function(){				
				$(this).remove();
				$('.arrow').remove();
				$(".sidebar_nav_list").css("background-color", "black");							
			});
		}
	});
});
</script>
</head>
<body>
<div class="main_t">
	<div id="header_t">
		<?php  echo $header_left_div; ?>
		<div id="header_right">
		<div id="header_right_items">
				<div class="header_right_item" style="font-weight: bold;">Welcome <br> <?php echo $usr_name; ?></div>
				<?php  echo $header_right_item_pic; ?>
				<div class="header_right_item">
					<div id="header_nav">
					<div id="header_nav_items">
						<ul>
							<li><a href="index.php?admin_logout=true">Logout</a></li>						
							<li>Settings</li>
						</ul>
					</div>
					</div>			
				</div>
			</div>
		</div>
	</div>
	<div id="content_t">
		<div id="content_main">
			<div id="db_result"></div>
		</div>
		<div id="left_sidebar">
			<div id="left_sidebar_main">
			<div id="left_sidebar_items">
				<ul>
					<li id="users_mgr" class="sidebar_nav_list">Users managment</li>
					<li id="assistants_mgr" class="sidebar_nav_list">Assistants managment</li>
					<li id="admins_mgr" class="sidebar_nav_list">Admins managment</li>
					<li id="books_mgr" class="sidebar_nav_list">Books managment</li>
				</ul>
			</div>
			
			</div>
		</div>
	</div>	
	<div id="footer_t">
		<div class="footer_item">footer_1</div>
		<div class="footer_item">footer_2</div>
		<div class="footer_item">footer_3</div>
	</div>
</div>
</body>
</html> 