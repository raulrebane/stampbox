-- Function: ds.clear_data()

-- DROP FUNCTION ds.clear_data();

CREATE OR REPLACE FUNCTION ds.clear_data()
  RETURNS boolean AS
$BODY$begin
  delete from ds.t_account where customer_id > 1;
  delete from ds.t_customer_mailbox where customer_id > 1;
  delete from ds.t_invitations where customer_id > 1;
  delete from ds.t_passwdresets where customer_id > 1;
  delete from ds.t_shoppingcart where customer_id > 1;
  delete from ds.t_stamps_issued where customer_id > 1;
  delete from ds.t_whitelist where customer_id > 1;
  delete from ds.t_stamps_issued where customer_id > 1;
  delete from ds.t_stamps_transactions where customer_id > 1;
  delete from ds.t_messages;
  delete from ds.t_customer where customer_id > 1;
  return TRUE;
end;$BODY$
  LANGUAGE plpgsql VOLATILE SECURITY DEFINER
  COST 100;
ALTER FUNCTION ds.clear_data()
  OWNER TO sbadmin;
GRANT EXECUTE ON FUNCTION ds.clear_data() TO sbadmin;
GRANT EXECUTE ON FUNCTION ds.clear_data() TO sbweb;
REVOKE ALL ON FUNCTION ds.clear_data() FROM public;

