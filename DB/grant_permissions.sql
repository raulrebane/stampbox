-- Grant use of schema to web users
GRANT USAGE ON SCHEMA ds TO sbweb;

-- Grant access to all sequences
GRANT USAGE ON ALL SEQUENCES IN SCHEMA ds to sbweb;

-- Grant access to all tables (TO-DO!!! Better would be of course to grant all tables and permissions separately)
GRANT SELECT, INSERT, UPDATE ON ALL TABLES IN SCHEMA ds TO sbweb;

-- do not allow to manage stamp definitions;
REVOKE INSERT, UPDATE ON TABLE ds.t_stamp_definition FROM sbweb;

GRANT ALL ON TABLE websessions TO sbweb;

GRANT DELETE ON TABLE ds.t_whitelist TO sbweb;
GRANT DELETE ON TABLE ds.t_shoppingcart TO sbweb;


