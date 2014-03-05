-- Table: ds.t_customer_mailbox

-- DROP TABLE ds.t_customer_mailbox;

CREATE TABLE ds.t_customer_mailbox
(
  customer_id bigint NOT NULL,
  e_mail character varying(100) NOT NULL,
  e_mail_username character varying(100),
  e_mail_password character varying(32),
  status character(1) NOT NULL,
  maildomain character varying(100) NOT NULL,
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
ALTER TABLE ds.t_customer_mailbox
  OWNER TO postgres;
GRANT ALL ON TABLE ds.t_customer_mailbox TO postgres;
GRANT SELECT, UPDATE, INSERT ON TABLE ds.t_customer_mailbox TO ds_user;


