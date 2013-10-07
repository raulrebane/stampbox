-- Table: ds.t_stamps
-- DROP TABLE ds.t_stamps;
CREATE TABLE ds.t_stamps
(
  stamp_id character varying(128) NOT NULL,
  customer_id bigint NOT NULL,
  sender character varying(100),
  receiver character varying(100),
  e_mail_hash character varying(100),
  status character(1),
  last_updated timestamp without time zone,
  stamppic_url character varying(255),
  CONSTRAINT t_stamps_pkey PRIMARY KEY (stamp_id),
  CONSTRAINT stamp_cust_fk FOREIGN KEY (customer_id)
      REFERENCES ds.t_customer (customer_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
ALTER TABLE ds.t_stamps
  OWNER TO postgres;
GRANT ALL ON TABLE ds.t_stamps TO postgres;
GRANT SELECT, UPDATE, INSERT ON TABLE ds.t_stamps TO ds_user;
-- Index: ds.fki_stamp_cust_fk
-- DROP INDEX ds.fki_stamp_cust_fk;
CREATE INDEX fki_stamp_cust_fk
  ON ds.t_stamps
  USING btree
  (customer_id);

