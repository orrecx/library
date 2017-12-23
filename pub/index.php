	<?php 
	session_start();
	include '../shelves/header.php';
	
	?>
	
		<div class="content">
			<div class="usr_nav">
				<a href="login.php"><span class="rel_buttom">Login</span></a> <a
					href="register.php"><span class="rel_buttom">Register</span></a>
			</div>
			<div class="content_main">
				<div class="content_main_left">
					<div class="book_catalog">
						<span>Book Catalog</span>
					</div>
					<div class="book_search">
						<form action="" method="post">
							<table>
								<tr>
									<td>Title:</td>
									<td><input type="text" name="book_title" class="input_search"></td>
								</tr>
								<tr>
									<td>Author:</td>
									<td><input type="text" name="book_author" class="input_search"></td>
								</tr>
								<tr>
									<td></td>
									<td><input type="submit" name="book_search_submit"
										value="Search" class="input_search_submit"></td>
								</tr>
							</table>
						</form>
					</div>
				</div>
				<div class="content_main_right">
					<?php					
    if (isset($_POST['book_search_submit'])) {
        // search for book and return table
        printf('<div class="content_main_right_content_header"><span>Search Result</span></div>');
        printf('<div class="content_main_right_content">');
        
        search_books($_POST['book_title'], $_POST['book_author'], "td_out", "tr_out", "book_description", PUBL);
    } else {
        printf('<div class="content_main_right_content_header"><span>Recommanded Books</span></div>');
        printf('<div class="content_main_right_content">');
        
        // deliver recommanded book
        books_of_the_day("td_out", "tr_out", "book_description", PUBL);
    }
    printf('</div>');
    
    ?>
				</div>
			</div>
		</div>
		
<?php include '../shelves/footer.php';?>

</html>