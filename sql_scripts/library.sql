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
    borrower_role int unsigned not null default 0,
    borrower_validation boolean not null default false
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
    FOREIGN KEY (borrower_id) REFERENCES tb_borrowers (borrower_id)
)engine = innodb default charset=utf8;

CREATE TABLE tb_reservations (
	reservation_id int unsigned not null primary key auto_increment,
	book_id int unsigned not null,
	borrower_id int unsigned not null,
	FOREIGN KEY (book_id) REFERENCES tb_books (book_id),
	FOREIGN KEY (borrower_id) REFERENCES tb_borrowers (borrower_id)
)engine = innodb default charset=utf8;

CREATE TABLE tb_book_tracker(
    tracker_id int unsigned not null primary key auto_increment,
    book_id int unsigned not null,
    borrower_id int unsigned not null,
    start_date date not null,
    end_date date not null,
	FOREIGN KEY (book_id) REFERENCES tb_books (book_id),
	FOREIGN KEY (borrower_id) REFERENCES tb_borrowers (borrower_id)
)engine = innodb default charset=utf8;


CREATE TABLE tb_book_tracker_archives(
    tracker_archives_id int unsigned not null primary key auto_increment,
    book_id int unsigned not null,
    borrower_id int unsigned not null,
    start_date date not null,
    end_date date not null,
	FOREIGN KEY (book_id) references tb_books (book_id),
	FOREIGN KEY (borrower_id) references tb_borrowers (borrower_id)
)engine = innodb default charset=utf8;

#populate database with sample data. the default password is simply 'default'
INSERT INTO tb_borrowers (borrower_id, borrower_name, borrower_address, borrower_email, borrower_password) VALUES
(null, 'Atangana', 'Yaounde, olezoa', 'at@atango.com', '$2y$10$C8w7kNdDNuEbJVF5WR5HuO5.XglOo.gMyAPWHeXLES5NZB9Y4Ei0u'),
(null, 'Madiba', 'Yaound, byiemassi', 'ma@madiba.com', '$2y$10$C8w7kNdDNuEbJVF5WR5HuO5.XglOo.gMyAPWHeXLES5NZB9Y4Ei0u'),
(null, 'Simo', 'Doula, Cite verte', 'sim@simo.com', '$2y$10$C8w7kNdDNuEbJVF5WR5HuO5.XglOo.gMyAPWHeXLES5NZB9Y4Ei0u'),
(null, 'Ndjee', 'Edea, sous le Pont', 'ndj@ndjee.com', '$2y$10$C8w7kNdDNuEbJVF5WR5HuO5.XglOo.gMyAPWHeXLES5NZB9Y4Ei0u'),
(null, 'Salif', 'Garoua, Njamboutou', 'sal@salig.com', '$2y$10$C8w7kNdDNuEbJVF5WR5HuO5.XglOo.gMyAPWHeXLES5NZB9Y4Ei0u'),
(null, 'Nguenang', 'Kribi, Plage', 'ngue@nguenang.com', '$2y$10$C8w7kNdDNuEbJVF5WR5HuO5.XglOo.gMyAPWHeXLES5NZB9Y4Ei0u'),
(null, 'Ngo bell', 'Douala, Ndokoti', 'ngo.bell@bell.com', '$2y$10$C8w7kNdDNuEbJVF5WR5HuO5.XglOo.gMyAPWHeXLES5NZB9Y4Ei0u'),
(null, 'test', 'Test city', 'test', '$2y$10$C8w7kNdDNuEbJVF5WR5HuO5.XglOo.gMyAPWHeXLES5NZB9Y4Ei0u');

INSERT INTO tb_borrowers (borrower_id, borrower_name, borrower_address, borrower_email, borrower_password, borrower_pic, borrower_role, borrower_validation) VALUES
(null, 'admin', 'Librarian Test Address city', 'admin', '$2y$10$C8w7kNdDNuEbJVF5WR5HuO5.XglOo.gMyAPWHeXLES5NZB9Y4Ei0u', '../pics/admin-stempel.jpg', 3, true),
(null, 'assistant', 'ASSISTANT Test Address', 'assistant', '$2y$10$C8w7kNdDNuEbJVF5WR5HuO5.XglOo.gMyAPWHeXLES5NZB9Y4Ei0u', '../pics/blpanther.png', 2, true);

INSERT INTO tb_books (book_id, book_title, book_author) VALUES
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
(null, 'Angular 5: From Theory To Practice', 'Asim Hussain'),
(null, 'al-Mawt&nbsp; &#39;ala al as-falt', 'Abnudy Abd al-Rahman'),
(null, 'Arrow of God', 'Achebe Chinua'),
(null, 'Anowa', 'Aidoo Ama Ata'),
(null, 'Zayni Barakat', 'Al Ghitani Gamal'),
(null, 'The beautiful ones are not yet born', 'Armah Ayi Kwei'),
(null, 'Une si longue lettre' , 'B&acirc; Mariama'),
(null, 'La nuit sacr&eacute;e', 'Ben Jelloun Tahar'),
(null, 'Le pauvre Christ de Bomba (English version)', 'Beti Mongo'),
(null, 'A dry white season', 'Brink Andre'),
(null, 'Riwan', 'Bugul Ken'),
(null, 'The last harmattan of Alusine Dunbar', 'Cheney-Coker Syl'),
(null, 'Le pass&eacute; simple', 'Chraibi Driss'),
(null, 'Life and times of Michael K', 'Coetzee J.M'),
(null, 'Terra son&acirc;mbula', 'Couto Mia'),
(null, 'Karingana ua Karingana', 'Craveirinha Jos&eacute'),
(null, 'Nervous conditions', 'Dangarembga Tsitsi'),
(null, 'Murambi ou le livre des ossements', 'Diop Boubacar Boris'),
(null, 'The joys of motherhood', 'Emecheta Buchi'),
(null, 'Ogboju ode nimu Igbo irunmale (English version)', 'Fagunwa D.O.'),
(null, 'Maps', 'Farah Nuruddin'),
(null, 'The blood knot', 'Fugard Athol'),
(null, 'A question of power', 'Head Bessie'),
(null, 'Bones', 'Hove Chenjerai'),
(null, 'Abessijnse kronieken', 'Isegawa Moses'),
(null, 'Ingqumbo yeminyanya (Eng. version)', 'Jordan A.C.'),
(null, 'Die swerfjare van Poppie Nongena', 'Joubert Elsa');

INSERT INTO tb_reservations (book_id, borrower_id) VALUES
(11, 1),
(32, 1),
(23, 4),
(25, 4),
(2,  4),
(17, 3),
(26, 3),
(8,  6),
(12, 5),
(22, 5),
(39, 5),
(41, 5);


UPDATE tb_books SET book_onloan = true, book_duedate = '2017-12-30', borrower_id = 8 WHERE book_id = 5;
UPDATE tb_books SET book_onloan = true, book_duedate = '2017-12-30', borrower_id = 8 WHERE book_id = 14;
UPDATE tb_books SET book_onloan = true, book_duedate = '2018-01-30', borrower_id = 8 WHERE book_id = 16;
UPDATE tb_books SET book_onloan = true, book_duedate = '2018-01-05', borrower_id = 2 WHERE book_id = 10;
UPDATE tb_books SET book_onloan = true, book_duedate = '2018-02-12', borrower_id = 2 WHERE book_id = 24;
UPDATE tb_books SET book_onloan = true, book_duedate = '2017-12-30', borrower_id = 2 WHERE book_id = 33;

UPDATE tb_books SET book_onloan = true, book_duedate = '2017-09-03', borrower_id = 6 WHERE book_id = 27;
UPDATE tb_books SET book_onloan = true, book_duedate = '2017-12-20', borrower_id = 6 WHERE book_id = 30;
UPDATE tb_books SET book_onloan = true, book_duedate = '2017-12-15', borrower_id = 1 WHERE book_id = 21;
UPDATE tb_books SET book_onloan = true, book_duedate = '2017-12-19', borrower_id = 5 WHERE book_id = 2;

INSERT INTO tb_book_tracker (book_id, borrower_id, start_date, end_date) VALUES
(5,  8, '2017-09-30', '2017-12-30'),
(14, 8, '2017-09-30', '2017-12-30'),
(16, 8, '2017-09-30', '2018-01-30'),
(10, 2, '2017-10-05', '2018-01-05'),
(24, 2, '2017-10-12', '2018-02-12'),
(33, 2, '2017-09-30', '2017-12-30'),
(27, 6, '2017-06-03', '2017-09-03'),
(30, 6, '2017-09-20', '2017-12-20'),
(21, 1, '2017-09-15', '2017-12-15'),
(2,  5, '2017-09-19', '2017-12-19');

INSERT INTO tb_book_tracker_archives (book_id, borrower_id, start_date, end_date) VALUES
(24, 2, '2017-07-13', '2018-08-12'),
(33, 2, '2017-07-09', '2017-10-30'),
(27, 6, '2017-08-05', '2017-10-03'),
(30, 6, '2017-08-12', '2017-11-26'),
(21, 1, '2017-09-15', '2017-11-10'),
(2,  5, '2017-10-29', '2017-12-03');


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
GRANT select, update, insert, delete ON library.* to 'librarian'@'localhost';

CREATE USER 'assistant'@'localhost' IDENTIFIED  BY 'assistant_psswd';
GRANT SELECT ON library.* to 'assistant'@'localhost';
GRANT UPDATE (book_onloan, book_duedate, borrower_id) ON library.tb_books to 'assistant'@'localhost';

CREATE USER 'borrower'@'localhost' IDENTIFIED  BY 'borrower_psswd';
GRANT SELECT ON library.tb_books to 'borrower'@'localhost';

CREATE USER 'publ'@'localhost' IDENTIFIED  BY 'publ_psswd';
GRANT SELECT (book_id, book_title, book_author, book_description, book_img) ON library.tb_books to 'publ'@'localhost';

FLUSH PRIVILEGES;

#revoke delete, update on library.* from 'librarian'@'host'; #for host '%' means any host
#GRANT ALL PRIVILEGES ON *.* to 'newuser'@'localhost';
