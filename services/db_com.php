<?php
    $title;
    $author;

    $db = new PDO("mysql:host=localhost; dbname=library", "orex", "unevie");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //prepare statement: binding by position, value
    $db_searchbook_title_only = $db->prepare("select book_title, book_author from tb_books where book_title like ?");
    $db_searchbook_author_only = $db->prepare("select book_title, book_author from tb_books where book_author like ?");
    $db_searchbook = $db->prepare("select book_title, book_author from tb_books where book_author like ? and book_author like ? ");
    $db_searchbook_no_onloan = $db->prepare("select book_title, book_author from tb_books where book_author like ? and book_author like ? 
        and book_onloan=false");
    
    //run: $db_searchbook->execute(array("%".$title."%","%".$author."%"));
    
    //prepare statement: binding by position, reference
    $db_searchbook_ref = $db->prepare("select book_title, book_author from tb_books where book_author regexp ? and book_author regexp ? ");
    $db_searchbook->bindParam(1, $title);
    $db_searchbook->bindParam(2, $author);
    
    //run:
    // $title="Har";
    // $author="dick";
    //$db_searchbook->execute();
    
    
?>