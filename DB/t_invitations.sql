-- Table: ds.t_invitations

-- DROP TABLE ds.t_invitations;

CREATE TABLE ds.t_invitations
(
  customer_id bigint NOT NULL,
  invited_email character varying(100) NOT NULL,
  invited_when timestamp without time zone,
  from_count integer,
  to_count integer,
  invite character(1),
  name character varying(100),
  CONSTRAINT pk_invitations PRIMARY KEY (customer_id, invited_email),
  CONSTRAINT fk_invitations_t_customer FOREIGN KEY (customer_id)
      REFERENCES ds.t_customer (customer_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
ALTER TABLE ds.t_invitations
  OWNER TO postgres;
GRANT ALL ON TABLE ds.t_invitations TO postgres;
GRANT SELECT, UPDATE, INSERT ON TABLE ds.t_invitations TO ds_user;

