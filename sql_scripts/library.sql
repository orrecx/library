#login as a root using 'mysql.exe -u root -p'
#enter your passwd
# run this script with 'source library_init.sql'

#initilisation script: init database
#start from the very beginning
DROP DATABASE IF EXISTS library;
CREATE DATABASE library;
USE library;


#create the tables
#Role: 0 account_user; 1 account_assistant; 2 account_admin
CREATE TABLE tb_borrowers ( 
    borrower_id int unsigned not null primary key auto_increment,
    borrower_name varchar(128) not null,
    borrower_address varchar(512) not null,
    borrower_email varchar(512) not null,
    borrower_password varchar(2048) not null,
    borrower_pic varchar(1024) not null default '../pics/avatar_guy.png',
    borrower_role int unsigned not null default 0
)engine = innodb default charset=utf8;


CREATE TABLE tb_books (
    book_id int unsigned not null primary key auto_increment,
    book_title varchar(128) not null,
    book_author varchar(128) not null,
    book_description varchar(2048) default 'This right here is a sample book description. Provide one',
    book_img varchar(1024) not null default '../book_imgs/book_red.png',
    book_onloan boolean default false,
    book_duedate date default null,
    borrower_id int unsigned default null, 
    foreign key (borrower_id) references tb_borrowers (borrower_id)
)engine = innodb default charset=utf8;

CREATE TABLE tb_reservations (
	reservation_id int unsigned not null primary key auto_increment,
	book_id int unsigned not null,
	borrower_id int unsigned not null,
	FOREIGN KEY (book_id) references tb_books (book_id),
	FOREIGN KEY (borrower_id) references tb_borrowers (borrower_id)
)engine = innodb default charset=utf8;

#populate database with sample data. the default password is simply 'default'
insert into tb_borrowers (borrower_id, borrower_name, borrower_address, borrower_email, borrower_password) values
(null, 'Atangana', 'Yaounde, olezoa', 'at@atango.com', '$2y$10$C8w7kNdDNuEbJVF5WR5HuO5.XglOo.gMyAPWHeXLES5NZB9Y4Ei0u'),
(null, 'Madiba', 'Yaound, byiemassi', 'ma@madiba.com', '$2y$10$C8w7kNdDNuEbJVF5WR5HuO5.XglOo.gMyAPWHeXLES5NZB9Y4Ei0u'),
(null, 'Simo', 'Doula, Cite verte', 'sim@simo.com', '$2y$10$C8w7kNdDNuEbJVF5WR5HuO5.XglOo.gMyAPWHeXLES5NZB9Y4Ei0u'),
(null, 'Ndjee', 'Edea, sous le Pont', 'ndj@ndjee.com', '$2y$10$C8w7kNdDNuEbJVF5WR5HuO5.XglOo.gMyAPWHeXLES5NZB9Y4Ei0u'),
(null, 'Salif', 'Garoua, Njamboutou', 'sal@salig.com', '$2y$10$C8w7kNdDNuEbJVF5WR5HuO5.XglOo.gMyAPWHeXLES5NZB9Y4Ei0u'),
(null, 'Nguenang', 'Kribi, Plage', 'ngue@nguenang.com', '$2y$10$C8w7kNdDNuEbJVF5WR5HuO5.XglOo.gMyAPWHeXLES5NZB9Y4Ei0u'),
(null, 'Ngo bell', 'Douala, Ndokoti', 'ngo.bell@bell.com', '$2y$10$C8w7kNdDNuEbJVF5WR5HuO5.XglOo.gMyAPWHeXLES5NZB9Y4Ei0u');

insert into tb_books (book_id, book_title, book_author) values
(null, 'Harry Potter and the G Fire', 'J. K. Rowling'),
(null, 'Harry Potter and Half-Blood Prince', 'J. K. Rowling'),
(null, 'Wind in the willows', 'Kenneth Grahame'),
(null, 'Great Expectations', 'Charles Dickens'),
(null, 'A christmas carol', 'Charles Dickens'),
(null, 'Knots and crosses', 'Ian Rankin'),
(null, 'The hanging garden', 'Ian Rankin'),
(null, 'Linux System Programming', 'Robert Love'),
(null, 'Suse Linux', 'Chris Brown'),
(null, 'PHP and MySQL', 'Welling and Thomson'),
(null, 'High Performance MySQL', 'Schwarts et al'),
(null, 'Computer Security', 'Stallings and Brown'),
(null, 'MySQL', 'Paul Dubois'),
(null, 'PHP 7 und MySQL', 'Thomas Theis'),
(null, 'Modern PHP: New Features and Good Practices', 'Josh Lockhart'),
(null, 'HTML5 und CSS3', 'Juergen Wolf'),
(null, 'Angular: Grundlagen', 'G. Woiwode and F. Malcher'),
(null, 'Angular 5: From Theory To Practice', 'Asim Hussain');

#change to database mysql
USE mysql;
DROP USER IF EXISTS 'librarian'@'localhost';
DROP USER IF EXISTS 'borrower'@'localhost';
DROP USER IF EXISTS 'assistant'@'localhost';
DROP USER IF EXISTS 'publ'@'localhost';

#change back to database library
USE library;

#create user accounts
CREATE USER 'librarian'@'localhost' IDENTIFIED  BY 'librarian_psswd';
GRANT select, update, insert, delete on library.* to 'librarian'@'localhost';

CREATE USER 'assistant'@'localhost' IDENTIFIED  BY 'assitant_psswd';
GRANT SELECT ON library.* to 'assistant'@'localhost';
GRANT UPDATE (book_onloan, book_duedate, borrower_id) ON library.tb_books to 'assistant'@'localhost';

CREATE USER 'borrower'@'localhost' IDENTIFIED  BY 'borrower_psswd';
GRANT SELECT ON library.tb_books to 'borrower'@'localhost';

CREATE USER 'publ'@'localhost' IDENTIFIED  BY 'publ_psswd';
GRANT SELECT (book_id, book_title, book_author, book_description, book_img) ON library.tb_books to 'publ'@'localhost';

FLUSH PRIVILEGES;

#revoke delete, update on library.* from 'librarian'@'host'; #for host '%' means any host
#GRANT ALL PRIVILEGES ON *.* to 'newuser'@'localhost';




