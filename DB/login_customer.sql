-- Function: ds.login_customer(character varying)

-- DROP FUNCTION ds.login_customer(character varying);

CREATE OR REPLACE FUNCTION ds.login_customer(p_username character varying, p_password character varying, p_password_encrypted character varying)
  RETURNS boolean AS
$BODY$
DECLARE
  found_customer bigint;
begin
  select customer_id into found_customer from ds.t_customer where username = p_username and password = p_password_encrypted;
  IF NOT FOUND THEN
    select customer_id into found_customer from ds.t_customer_mailbox where e_mail = p_username and e_mail_password = p_password;
    IF NOT FOUND THEN
      RETURN FALSE;
    END IF;
  END IF;
  RETURN TRUE;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

