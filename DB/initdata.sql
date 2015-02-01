insert into ds.t_customer (username, password, registered_date, status, country) values ('support@stampbox.email', '$2a$13$myeXXjLLTKrQKVzo2B9VAeqXjrqfUS8bKvFkjtr3nfT6Ydto0V3W6', 'now()', 'A', 'EE');


insert into ds.t_stamp_definition (cost, currency, pts_earned, status, changed_by, added) values (0.0,'EUR',0.0, 'A', 1, 'now()');
insert into ds.t_stamp_definition (cost, currency, pts_earned, status, changed_by, added) values (0.10,'EUR',0.071, 'A', 1, 'now()');

insert into ds.t_shop_offers (batch_id, status, offer_amount, offer_price, entered_by, entered_when) values (2,'A',100,9.90,1,'now()');
insert into ds.t_shop_offers (batch_id, status, offer_amount, offer_price, entered_by, entered_when) values (2,'A',1000,89.90,1,'now()');

