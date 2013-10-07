-- Table: ds.t_customer_mailbox
-- DROP TABLE ds.t_customer_mailbox;
CREATE TABLE ds.t_customer_mailbox
(
  customer_id bigint NOT NULL,
  e_mail character varying(100) NOT NULL,
  e_mail_type character varying(10),
  e_mail_username character varying(100),
  e_mail_password character varying(32),
  status character(1),
  CONSTRAINT mailbox_cust_fk FOREIGN KEY (customer_id)
      REFERENCES ds.t_customer (customer_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
ALTER TABLE ds.t_customer_mailbox
  OWNER TO postgres;
GRANT ALL ON TABLE ds.t_customer_mailbox TO postgres;
GRANT SELECT, UPDATE, INSERT ON TABLE ds.t_customer_mailbox TO ds_user;
-- Index: ds.fki_mailbox_cust_fk
-- DROP INDEX ds.fki_mailbox_cust_fk;
CREATE INDEX fki_mailbox_cust_fk
  ON ds.t_customer_mailbox
  USING btree
  (customer_id);

