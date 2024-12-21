use mysql;
CREATE USER 'myMovies'@'localhost' IDENTIFIED BY 'myMovPass';
grant select on sakila.* to 'myMovies'@'localhost';
grant insert on sakila.customer  to 'myMovies'@'localhost';
grant insert on sakila.store  to 'myMovies'@'localhost';
grant insert on sakila.address  to 'myMovies'@'localhost';
