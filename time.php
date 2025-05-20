CREATE TABLE images_tbl( 
images_id INT NOT NULL AUTO_INCREMENT, 
name VARCHAR(50) NOT NULL, 
EMAIL VARCHAR(80) NOT NULL, 
INSTAUSER VARCHAR(50), 
SNAPUSER VARCHAR(50), 
CAPTION VARCHAR(200), 
images_path VARCHAR(300) NOT NULL, 
submission_date DATE, 
PRIMARY KEY (images_id) );







create table products(
products_id INT NOT NULL, 
name VARCHAR(50) NOT NULL,
PRIMARY KEY (products_id) );







alter table images_tbl add column productid int;

alter table images_tbl add foreign KEY (productid)REFERENCES products(products_id);


insert into products VALUES(1,"1 Hour-5 Posts");

insert into products VALUES(2,"3 Hour-3 Posts");

insert into products VALUES(3,"6 Hour-2 Posts");

insert into products VALUES(4,"12 Hour-2 Posts");

insert into products VALUES(5,"24 Hour-2 Posts");

insert into products VALUES(6,"Permanent");



alter table products add COLUMN Amount float;

update products set amount = 9.99 WHERE products_id=1;


update products set amount = 14.99 WHERE products_id=2;


update products set amount = 18.99 WHERE products_id=3;


update products set amount = 22.99 WHERE products_id=4;


update products set amount = 29.99 WHERE products_id=5;

update products set amount = 49.99 WHERE products_id=6;




CREATE TABLE payments( 
PAYMENTS_id INT NOT NULL AUTO_INCREMENT,
PAYPAL_ID VARCHAR(50) , 
EMAIL VARCHAR(80) NOT NULL, 
STATE VARCHAR(50), 
FAILURE_REASON  VARCHAR(50), 
CREATE_TIME VARCHAR(200), 
PAYMENT_METHOD VARCHAR(300) NOT NULL, 
PRIMARY KEY (PAYMENTS_id)
);



alter table images_tbl add column PAYPAL_ID VARCHAR(50) ;


ALTER TABLE Payments ADD UNIQUE(`PAYPAL_ID`);



alter table images_tbl add foreign KEY (PAYPAL_ID)REFERENCES Payments(PAYPAL_ID);






UPDATE `images_tbl` SET PAYMENTS_id = "PAYPA_ID" WHERE images_path = "";





