use library;
drop function if exists 'count_overdue_books';

DELIMITER $$
use library $$
create function 'count_overdue_books' (days integer) returns integer
begin
	return (select count(*) from tb_books where book_duedate < date_sub(current_date(), interval days day));
end $$

DELIMITER ;