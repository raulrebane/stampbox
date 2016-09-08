CREATE SEQUENCE ds.t_customer_customer_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;

CREATE TABLE ds.t_customer
(
  customer_id bigint NOT NULL DEFAULT nextval('ds.t_customer_customer_id_seq'::regclass),
  username character varying(128) NOT NULL,
  firstname character varying(100),
  lastname character varying(100),
  password character varying(64) NOT NULL,
  registered_date timestamp without time zone,
  status character(1),
  preferred_lang character(3),
  bad_logins integer,
  customer_type character(1), -- I - individual...
  country character(2),
  CONSTRAINT t_customer_pkey PRIMARY KEY (customer_id),
  CONSTRAINT "UC_USERNAME" UNIQUE (username)
)
WITH (
  OIDS=FALSE
);

CREATE UNIQUE INDEX username_idx
  ON ds.t_customer
  USING btree
  (username COLLATE pg_catalog."default");

CREATE TABLE ds.t_account
(
  customer_id bigint NOT NULL,
  points_bal integer,
  stamps_bal integer,
  CONSTRAINT pk_customer_account PRIMARY KEY (customer_id),
  CONSTRAINT fk_customer_account FOREIGN KEY (customer_id)
      REFERENCES ds.t_customer (customer_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);

CREATE TABLE ds.t_customer_mailbox
(
  customer_id bigint NOT NULL,
  e_mail character varying(128) NOT NULL,
  e_mail_username character varying(128),
  e_mail_password character varying(200),
  status character(1) NOT NULL,
  maildomain character varying(100),
  worker_ip inet,
  worker_type character varying(20),
  last_seen timestamp(6) without time zone,
  CONSTRAINT pk_customer_mailbox PRIMARY KEY (e_mail),
  CONSTRAINT "FK_t_customer_mailbox" FOREIGN KEY (customer_id)
      REFERENCES ds.t_customer (customer_id) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);

CREATE UNIQUE INDEX e_mail_idx
  ON ds.t_customer_mailbox
  USING btree
  (e_mail COLLATE pg_catalog."default");

CREATE TABLE ds.t_mailbox_config
(
  maildomain character varying(100) NOT NULL,
  mailtype character varying(10) NOT NULL,
  incoming_hostname character varying(255),
  incoming_port integer,
  incoming_socket_type character varying(10),
  incoming_auth character varying(10),
  outgoing_hostname character varying(255),
  outgoing_port integer,
  outgoing_socket_type character varying(10),
  outgoing_auth character varying(10),
  CONSTRAINT pk_mailbox_config PRIMARY KEY (maildomain)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE ds.t_passwdresets
(
  customer_id bigint NOT NULL,
  e_mail character varying(100),
  token character varying(32),
  sent timestamp without time zone,
  CONSTRAINT pk_passwd_reset PRIMARY KEY (customer_id),
  CONSTRAINT fk_passwd_reset_customer FOREIGN KEY (customer_id)
      REFERENCES ds.t_customer (customer_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);

CREATE TABLE ds.t_contacts
(
  customer_id bigint NOT NULL,
  e_mail character varying(128) NOT NULL,
  contact_email character varying(100) NOT NULL,
  name character varying(100),
  from_count integer,
  to_count integer,
  last_email_date timestamp without time zone,
  CONSTRAINT pk_contacts PRIMARY KEY (customer_id, e_mail, contact_email),
  CONSTRAINT fk_contacts_t_customer FOREIGN KEY (customer_id)
      REFERENCES ds.t_customer (customer_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT fk_contacts_t_customer_mailbox FOREIGN KEY (e_mail)
      REFERENCES ds.t_customer_mailbox (e_mail) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
ALTER TABLE ds.t_contacts
  OWNER TO sbadmin;
GRANT ALL ON TABLE ds.t_contacts TO sbadmin;
GRANT SELECT, UPDATE, INSERT ON TABLE ds.t_contacts TO sbweb;

CREATE TABLE ds.t_invitations
(
  customer_id bigint NOT NULL,
  invited_email character varying(100) NOT NULL,
  invited_when timestamp without time zone,
  from_count integer,
  to_count integer,
  invite character(1),
  name character varying(100),
  last_email_date timestamp without time zone,
  CONSTRAINT pk_invitations PRIMARY KEY (customer_id, invited_email),
  CONSTRAINT fk_invitations_t_customer FOREIGN KEY (customer_id)
      REFERENCES ds.t_customer (customer_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);

CREATE SEQUENCE ds.t_stamp_definition_batch_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;

CREATE TABLE ds.t_stamp_definition
(
  batch_id bigint NOT NULL DEFAULT nextval('ds.t_stamp_definition_batch_id_seq'::regclass),
  cost numeric(6,2) NOT NULL,
  currency character(3) NOT NULL,
  pts_earned numeric(6,3) NOT NULL,
  stamp_pic character varying(255),
  status character(1) NOT NULL,
  changed_by bigint NOT NULL,
  added timestamp without time zone NOT NULL,
  CONSTRAINT t_stamp_definition_pkey PRIMARY KEY (batch_id),
  CONSTRAINT "FK_t_stamp_definition_customer" FOREIGN KEY (changed_by)
      REFERENCES ds.t_customer (customer_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);

CREATE SEQUENCE ds.t_stamp_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 20;

CREATE TABLE ds.t_stamps_issued
(
  stamp_id bigint NOT NULL DEFAULT nextval('ds.t_stamp_id_seq'::regclass),
  batch_id bigint NOT NULL,
  customer_id bigint NOT NULL,
  status character(1) NOT NULL,
  issued timestamp(5) without time zone NOT NULL,
  from_email character varying(100),
  to_email character varying(100),
  email_id character varying(255),
  subject character varying(255),
  CONSTRAINT t_stamps_issued_pkey PRIMARY KEY (stamp_id),
  CONSTRAINT "FK_t_stamps_batch" FOREIGN KEY (batch_id)
      REFERENCES ds.t_stamp_definition (batch_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT "FK_t_stamps_issued" FOREIGN KEY (customer_id)
      REFERENCES ds.t_customer (customer_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);

CREATE TABLE ds.t_stamps_transactions
(
  transaction_id bigserial NOT NULL,
  customer_id bigint,
  transaction_code character(5),
  amount numeric(6,3),
  stamp_id bigint,
  description character varying(500),
  transaction_date timestamp without time zone,
  CONSTRAINT pk_transaction_id PRIMARY KEY (transaction_id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE ds.t_whitelist
(
  e_mail character varying(100) NOT NULL,
  customer_id bigint NOT NULL,
  CONSTRAINT pk_whitelist_email PRIMARY KEY (e_mail, customer_id)
)
WITH (
  OIDS=FALSE
);

CREATE SEQUENCE ds.t_shop_offers_offer_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;

CREATE TABLE ds.t_shop_offers
(
  offer_id bigint NOT NULL DEFAULT nextval('ds.t_shop_offers_offer_id_seq'::regclass),
  batch_id bigint,
  start_from date,
  end_date date,
  status character(1), -- A - active...
  offer_amount integer,
  offer_price numeric(6,2),
  entered_by bigint,
  entered_when timestamp without time zone,
  CONSTRAINT pk_offers PRIMARY KEY (offer_id),
  CONSTRAINT fk_stamp_batch FOREIGN KEY (batch_id)
      REFERENCES ds.t_stamp_definition (batch_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);

CREATE TABLE ds.t_shoppingcart
(
  customer_id bigint NOT NULL,
  batch_id bigint,
  stamp_amount integer,
  price numeric(6,2),
  paypal_token character varying(30),
  paypal_timestamp timestamp without time zone,
  paypal_correlation_id character varying(30),
  CONSTRAINT pk_customer_cart PRIMARY KEY (customer_id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE ds.t_processing
(
  customer_id bigint NOT NULL,
  action character varying(30),
  percent_complete smallint,
  task_id character varying(100) NOT NULL,
  CONSTRAINT pk_processing_tasks PRIMARY KEY (customer_id, task_id)
)
WITH (
  OIDS=FALSE
);

CREATE OR REPLACE VIEW ds.v_registered_email AS 
 SELECT t_customer.customer_id,
    t_customer.username
   FROM ds.t_customer
UNION
 SELECT t_customer_mailbox.customer_id,
    t_customer_mailbox.e_mail AS username
   FROM ds.t_customer_mailbox;

CREATE OR REPLACE VIEW ds.v_transactions AS 
 SELECT tran.transaction_id,
    tran.customer_id,
    tran.transaction_code,
    tran.amount,
    tran.description,
    tran.transaction_date,
        CASE
            WHEN tran.amount > 0::numeric THEN stamps.from_email
            ELSE stamps.to_email
        END AS e_mail,
    stamps.subject,
    stamps.email_id
   FROM ds.t_stamps_transactions tran
     LEFT JOIN ds.t_stamps_issued stamps ON tran.stamp_id = stamps.stamp_id;

CREATE TABLE websessions
(
  id character(32) NOT NULL,
  expire integer,
  data bytea,
  CONSTRAINT websessions_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);


