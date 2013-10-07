-- Table: ds.t_customer
-- DROP TABLE ds.t_customer;
CREATE TABLE ds.t_customer
(
  customer_id bigserial NOT NULL,
  username character varying(128) NOT NULL,
  firstname character varying(100) NOT NULL,
  lastname character varying(100) NOT NULL,
  password character varying(64) NOT NULL,
  last_seen timestamp without time zone,
  status character(1),
  preferred_lang character(3),
  bad_logins integer,
  CONSTRAINT t_customer_pkey PRIMARY KEY (customer_id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE ds.t_customer
  OWNER TO postgres;
GRANT ALL ON TABLE ds.t_customer TO postgres;
GRANT SELECT, UPDATE, INSERT ON TABLE ds.t_customer TO ds_user;
-- Index: ds.username
-- DROP INDEX ds.username;
CREATE UNIQUE INDEX username
  ON ds.t_customer
  USING btree
  (username COLLATE pg_catalog."default");

