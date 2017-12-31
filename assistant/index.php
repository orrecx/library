<?php
session_start();

if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in']) {
    unset($_SESSION['user_logged_in']);
    session_destroy();
    header("Location: ../pub/login.php");
    exit;
}

if (! isset($_SESSION['assistant_logged_in']) || ! $_SESSION['assistant_logged_in']) {
    header("Location: ../pub/login.php");
}

if (isset($_GET['assistant_logout'])) {
    unset($_SESSION['assistant_logged_in']);
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

$header_right_item_pic = '<div class="header_right_item"><img src="'.get_avatar($usr_id, ASSISTANT).'" alt="user pic" width="100%" height="100%"></div>';
?>

<!DOCTYPE html>
<html>
<head>
<title>Library at KingsRoot</title>
<link rel="stylesheet" type="text/css" href="../assets/assistant_styles.css">
<script src="../assets/jquery-3.2.1.js"></script>
<script>

function nav_mark_selected_item(elt)
{
	$('#nav_div ul li').css("background-color", "#575757");
	$(elt).css("background-color", "#d07200");
	if(elt == '#checkin' || elt == '#checkout')
	{
		$('#context-filter').remove();		
		$("#content_main_db_result").css({"height":"90%", "overflow-y":"scroll"});
		$("#content_main_content").append
		(
			'<div id="context_filter" style="height: 10%; background-color: #575757; text-align: center; ">'
			+'<input type="text" id="ct_filter_input" placeholder="filter id/email" style="background-color: white; width: 20%; padding: 1%; font-size: 16px; text-align: center; margin-top: 0.5%; border-radius: 2px;">'
			+'</div>'
		);

		$('#ct_filter_input').keydown(function(event)
			{
				//if not ENTER
				if(event.keyCode != 13 || ($(this).val()).trim() == '' || $(this).attr("type") == 'submit')
				{
					return;
				}

				var src_val = ($(this).val()).trim();
				var me = $(this);
				var dt = 'assistant_req=' + ((elt == '#checkin') ? 'req_book_checkin': 'req_book_checkout') + '&query=' + $(this).val() 
				$.ajax({
					type: "POST",
					data: dt,
					url: "ajax.php", success: function(result)
						{
							$("#content_main_db_result").html(result);
							$(me).attr("type", "submit");
							$(me).val("Submit");
							$(me).css({"background-color" : "#d07200", "color": 'white'});
							$(me).click(function(){
								//submit user data
								var selections = $('#content_main_db_result input[type="checkbox"]:checked');
								var query = 'assistant_req=' + ((elt == '#checkin') ? 'req_book_checkin': 'req_book_checkout') + '&query=set&vals=q';
								var i = 0;
																
								for(; i < selections.length; i++)
								{
									query += '-'  + $(selections[i]).attr("name");
								}
								
								if(i > 0)
								{
									$.ajax({
									type: "POST",
									data: query,
									url: "ajax.php",
									success: function(res)
									{
										$("#content_main_db_result").css({"height":"100%", "overflow-y":"scroll"});
										$("#content_main_db_result").html(res);																					
									}										
									});
								}
							});
		    			}
		    		}
		   		);				
			}			
		);				
	}
	else
	{
		$('#context_filter').remove();		
		$("#content_main_db_result").css({"height":"100%", "overflow-y":"scroll"});
	}	
}

$(document).ready(function(){

	$('#book_catalog').click(function()
		{
			$.ajax({
				type: "POST",
				data: 'assistant_req=req_book_catalog',
				url: "ajax.php", success: function(result)
					{
						$('#dfer45').remove();
						$("#content_main_db_result").html(result);							        								
	    			}
	    		}
    		);

			nav_mark_selected_item('#book_catalog');
			$("#content_main_content").append('<h1 id="dfer45">Loading...</h1>');			
		}
	);

	$('#checkin').click(function()
			{
				$.ajax({
					type: "POST",
					data: 'assistant_req=req_book_checkin',
					url: "ajax.php", success: function(result)
						{
							$('#dfer45').remove();
							$("#content_main_db_result").html(result);							        								
		    			}
		    		}
	    		);

				nav_mark_selected_item('#checkin');
				$("#content_main_content").append('<h1 id="dfer45">Loading...</h1>');			
			}
		);

	$('#checkout').click(function()
			{
				$.ajax({
					type: "POST",
					data: 'assistant_req=req_book_checkout',
					url: "ajax.php", success: function(result)
						{
							$('#dfer45').remove();
							$("#content_main_db_result").html(result);							        								
		    			}
		    		}
	    		);

				nav_mark_selected_item('#checkout');
				$("#content_main_content").append('<h1 id="dfer45">Loading...</h1>');			
			}
		);
	
	$('#assistant_input_search').keydown(function(event)
		{
			//if not ENTER
			if(event.keyCode != 13 || ($(this).val()).trim() == '')
			{
				return;
			}

			var src_val = ($(this).val()).trim();
	
			$.ajax({
				type: "POST",
				data: 'assistant_req=req_book_search&query=' + $(this).val(),
				url: "ajax.php", success: function(result)
					{
						$('#dfer45').remove();
						$("#content_main_db_result").css({"height":"100%", "overflow-y":"scroll"});
						$("#content_main_db_result").html(result);
						$('#content_main_db_result table tr td span:contains("' + src_val + '")').css("background-color", "#ffff73");
	    			}
	    		}
    		);				
		}			
	);
	
});
</script>
</head>
<body>
	<div id="main_t">
		<div id="header_t">
			<?php 
			//header left
			 echo $header_left_div; 
			?>
			<div id="header_right">
				<?php 
				    echo $header_right_item_pic; 
				?>
				<div class="header_right_item">
					<div class="centered">
		    			<span class="title_font">Welcome</span><br>
						<span class="title_font" style="color: #ff8c00;"><?php echo $usr_name;?></span>
					</div>			
				</div>
				<div class="header_right_item">
					<div id="user_tools">
						<div id="settings">
							<img alt="" src="../pics/settings.png" width="100%" height="100%">
						</div>
						<div id="header_nav">
							<ul>
								<li>Settings</li>
								<li><a href="index.php?assistant_logout=true">Logout</a></li>
							</ul>
						</div>
					</div>
				</div>			
			</div>
		</div>	
		<div id="content">
			<div id="content_header">
				<div id="content_header_nav">
					<div id="nav_div">
						<ul>
							<li id="checkout">Checkout</li>
							<li id="checkin">Checkin</li>
							<li id="book_catalog">Book catalog</li>
						</ul>
					</div>
				</div>
				<div id="content_header_search">
					<div id="search_div">
						<input type="search" placeholder="Search..." name="assistant_search" id="assistant_input_search">
					</div>
				</div>
			</div>
			<div id="content_main">
				<div id="content_main_content">
				<div id="content_main_db_result"></div>
				</div>
				<div id="content_main_sidebar">
					<div class="sidebar_item">
						<div class="sidebar_item_title"><span>Books requests</span></div>
						<div class="sidebar_item_content">
						<?php 
						  get_book_request("tr_zebra", 3, ASSISTANT);
						?>
						</div>
					</div>
					<div class="sidebar_item">
						<div class="sidebar_item_title"><span>Overdue Books</span></div>
						<div class="sidebar_item_content">
						<?php 
						get_overdue_books("tr_zebra", 3, ASSISTANT);
						?>
						</div>
					</div>
					
				</div>
			</div>
		</div>
		<div id="footer_t"></div>
	</div>
	
</body>
</html> 