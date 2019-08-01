--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.14
-- Dumped by pg_dump version 9.3.14
-- Started on 2019-08-01 20:57:59 EEST

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 8 (class 2615 OID 16693)
-- Name: ds; Type: SCHEMA; Schema: -; Owner: sbadmin
--

CREATE SCHEMA ds;


ALTER SCHEMA ds OWNER TO sbadmin;

--
-- TOC entry 1 (class 3079 OID 11789)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2152 (class 0 OID 0)
-- Dependencies: 1
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = ds, pg_catalog;

--
-- TOC entry 210 (class 1255 OID 16957)
-- Name: clear_data(); Type: FUNCTION; Schema: ds; Owner: sbadmin
--

CREATE FUNCTION clear_data() RETURNS boolean
    LANGUAGE plpgsql SECURITY DEFINER
    AS $$begin
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
end;$$;


ALTER FUNCTION ds.clear_data() OWNER TO sbadmin;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 174 (class 1259 OID 16705)
-- Name: t_account; Type: TABLE; Schema: ds; Owner: raulr; Tablespace: 
--

CREATE TABLE t_account (
    customer_id bigint NOT NULL,
    points_bal integer,
    stamps_bal integer
);


ALTER TABLE ds.t_account OWNER TO raulr;

--
-- TOC entry 172 (class 1259 OID 16694)
-- Name: t_customer_customer_id_seq; Type: SEQUENCE; Schema: ds; Owner: raulr
--

CREATE SEQUENCE t_customer_customer_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE ds.t_customer_customer_id_seq OWNER TO raulr;

--
-- TOC entry 173 (class 1259 OID 16696)
-- Name: t_customer; Type: TABLE; Schema: ds; Owner: raulr; Tablespace: 
--

CREATE TABLE t_customer (
    customer_id bigint DEFAULT nextval('t_customer_customer_id_seq'::regclass) NOT NULL,
    username character varying(128) NOT NULL,
    firstname character varying(100),
    lastname character varying(100),
    password character varying(64) NOT NULL,
    registered_date timestamp without time zone,
    status character(1),
    preferred_lang character(3),
    bad_logins integer,
    customer_type character(1),
    country character(2)
);


ALTER TABLE ds.t_customer OWNER TO raulr;

--
-- TOC entry 175 (class 1259 OID 16715)
-- Name: t_customer_mailbox; Type: TABLE; Schema: ds; Owner: raulr; Tablespace: 
--

CREATE TABLE t_customer_mailbox (
    customer_id bigint NOT NULL,
    e_mail character varying(128) NOT NULL,
    e_mail_username character varying(128),
    e_mail_password character varying(500),
    status character(1) NOT NULL,
    maildomain character varying(100),
    extended_service boolean
);


ALTER TABLE ds.t_customer_mailbox OWNER TO raulr;

--
-- TOC entry 197 (class 1259 OID 17099)
-- Name: t_ignored_emailaddresses; Type: TABLE; Schema: ds; Owner: sbadmin; Tablespace: 
--

CREATE TABLE t_ignored_emailaddresses (
    e_mail character varying(128) NOT NULL
);


ALTER TABLE ds.t_ignored_emailaddresses OWNER TO sbadmin;

--
-- TOC entry 178 (class 1259 OID 16747)
-- Name: t_invitations; Type: TABLE; Schema: ds; Owner: raulr; Tablespace: 
--

CREATE TABLE t_invitations (
    customer_id bigint NOT NULL,
    invited_email character varying(100) NOT NULL,
    invited_when timestamp without time zone,
    from_count integer,
    to_count integer,
    invite character(1),
    name character varying(100),
    last_email_date timestamp without time zone
);


ALTER TABLE ds.t_invitations OWNER TO raulr;

--
-- TOC entry 190 (class 1259 OID 16882)
-- Name: t_log_line; Type: TABLE; Schema: ds; Owner: sbadmin; Tablespace: 
--

CREATE TABLE t_log_line (
    log_datetime timestamp without time zone,
    log_ip inet,
    log_session character varying(32),
    log_customer_id bigint,
    log_path character varying(100),
    log_data character varying(4000)
);


ALTER TABLE ds.t_log_line OWNER TO sbadmin;

--
-- TOC entry 176 (class 1259 OID 16729)
-- Name: t_mailbox_config; Type: TABLE; Schema: ds; Owner: raulr; Tablespace: 
--

CREATE TABLE t_mailbox_config (
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
    status character(1)
);


ALTER TABLE ds.t_mailbox_config OWNER TO raulr;

--
-- TOC entry 191 (class 1259 OID 16941)
-- Name: t_messages_message_id_seq; Type: SEQUENCE; Schema: ds; Owner: sbadmin
--

CREATE SEQUENCE t_messages_message_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE ds.t_messages_message_id_seq OWNER TO sbadmin;

--
-- TOC entry 192 (class 1259 OID 16959)
-- Name: t_messages; Type: TABLE; Schema: ds; Owner: sbadmin; Tablespace: 
--

CREATE TABLE t_messages (
    message_id bigint DEFAULT nextval('t_messages_message_id_seq'::regclass) NOT NULL,
    customer_id bigint,
    showcount smallint,
    message_type character varying(10),
    page_id character varying(30),
    message character varying(5000)
);


ALTER TABLE ds.t_messages OWNER TO sbadmin;

--
-- TOC entry 177 (class 1259 OID 16737)
-- Name: t_passwdresets; Type: TABLE; Schema: ds; Owner: raulr; Tablespace: 
--

CREATE TABLE t_passwdresets (
    customer_id bigint NOT NULL,
    e_mail character varying(100),
    token character varying(32),
    sent timestamp without time zone
);


ALTER TABLE ds.t_passwdresets OWNER TO raulr;

--
-- TOC entry 196 (class 1259 OID 17094)
-- Name: t_processed_emails; Type: TABLE; Schema: ds; Owner: raulr; Tablespace: 
--

CREATE TABLE t_processed_emails (
    customer_id bigint NOT NULL,
    e_mail character varying(128) NOT NULL,
    email_id character varying(255) NOT NULL,
    processed_timestamp timestamp without time zone
);


ALTER TABLE ds.t_processed_emails OWNER TO raulr;

--
-- TOC entry 187 (class 1259 OID 16825)
-- Name: t_processing; Type: TABLE; Schema: ds; Owner: raulr; Tablespace: 
--

CREATE TABLE t_processing (
    customer_id bigint NOT NULL,
    action character varying(30),
    percent_complete smallint,
    task_id character varying(100) NOT NULL
);


ALTER TABLE ds.t_processing OWNER TO raulr;

--
-- TOC entry 184 (class 1259 OID 16807)
-- Name: t_shop_offers_offer_id_seq; Type: SEQUENCE; Schema: ds; Owner: raulr
--

CREATE SEQUENCE t_shop_offers_offer_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE ds.t_shop_offers_offer_id_seq OWNER TO raulr;

--
-- TOC entry 185 (class 1259 OID 16809)
-- Name: t_shop_offers; Type: TABLE; Schema: ds; Owner: raulr; Tablespace: 
--

CREATE TABLE t_shop_offers (
    offer_id bigint DEFAULT nextval('t_shop_offers_offer_id_seq'::regclass) NOT NULL,
    batch_id bigint,
    start_from date,
    end_date date,
    status character(1),
    offer_amount integer,
    offer_price numeric(6,2),
    entered_by bigint,
    entered_when timestamp without time zone
);


ALTER TABLE ds.t_shop_offers OWNER TO raulr;

--
-- TOC entry 186 (class 1259 OID 16820)
-- Name: t_shoppingcart; Type: TABLE; Schema: ds; Owner: raulr; Tablespace: 
--

CREATE TABLE t_shoppingcart (
    customer_id bigint NOT NULL,
    batch_id bigint,
    stamp_amount integer,
    price numeric(6,2),
    paypal_token character varying(30),
    paypal_timestamp timestamp without time zone,
    paypal_correlation_id character varying(30)
);


ALTER TABLE ds.t_shoppingcart OWNER TO raulr;

--
-- TOC entry 179 (class 1259 OID 16757)
-- Name: t_stamp_definition_batch_id_seq; Type: SEQUENCE; Schema: ds; Owner: raulr
--

CREATE SEQUENCE t_stamp_definition_batch_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE ds.t_stamp_definition_batch_id_seq OWNER TO raulr;

--
-- TOC entry 180 (class 1259 OID 16759)
-- Name: t_stamp_definition; Type: TABLE; Schema: ds; Owner: raulr; Tablespace: 
--

CREATE TABLE t_stamp_definition (
    batch_id bigint DEFAULT nextval('t_stamp_definition_batch_id_seq'::regclass) NOT NULL,
    cost numeric(6,2) NOT NULL,
    currency character(3) NOT NULL,
    pts_earned numeric(6,3) NOT NULL,
    stamp_pic character varying(255),
    status character(1) NOT NULL,
    changed_by bigint NOT NULL,
    added timestamp without time zone NOT NULL
);


ALTER TABLE ds.t_stamp_definition OWNER TO raulr;

--
-- TOC entry 181 (class 1259 OID 16770)
-- Name: t_stamp_id_seq; Type: SEQUENCE; Schema: ds; Owner: raulr
--

CREATE SEQUENCE t_stamp_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 20;


ALTER TABLE ds.t_stamp_id_seq OWNER TO raulr;

--
-- TOC entry 182 (class 1259 OID 16772)
-- Name: t_stamps_issued; Type: TABLE; Schema: ds; Owner: raulr; Tablespace: 
--

CREATE TABLE t_stamps_issued (
    stamp_id bigint DEFAULT nextval('t_stamp_id_seq'::regclass) NOT NULL,
    batch_id bigint NOT NULL,
    customer_id bigint NOT NULL,
    status character(1) NOT NULL,
    issued timestamp(5) without time zone NOT NULL,
    from_email character varying(100),
    to_email character varying(100),
    email_id character varying(255),
    subject character varying(255)
);


ALTER TABLE ds.t_stamps_issued OWNER TO raulr;

--
-- TOC entry 194 (class 1259 OID 17072)
-- Name: t_stamps_transactions; Type: TABLE; Schema: ds; Owner: sbadmin; Tablespace: 
--

CREATE TABLE t_stamps_transactions (
    transaction_id bigint NOT NULL,
    transaction_date timestamp without time zone,
    customer_id bigint,
    transaction_code character(5),
    amount numeric(6,3),
    stamp_id bigint,
    email_id character varying(255),
    description character varying(500)
);


ALTER TABLE ds.t_stamps_transactions OWNER TO sbadmin;

--
-- TOC entry 193 (class 1259 OID 17070)
-- Name: t_stamps_transactions_transaction_id_seq; Type: SEQUENCE; Schema: ds; Owner: sbadmin
--

CREATE SEQUENCE t_stamps_transactions_transaction_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE ds.t_stamps_transactions_transaction_id_seq OWNER TO sbadmin;

--
-- TOC entry 2175 (class 0 OID 0)
-- Dependencies: 193
-- Name: t_stamps_transactions_transaction_id_seq; Type: SEQUENCE OWNED BY; Schema: ds; Owner: sbadmin
--

ALTER SEQUENCE t_stamps_transactions_transaction_id_seq OWNED BY t_stamps_transactions.transaction_id;


--
-- TOC entry 183 (class 1259 OID 16802)
-- Name: t_whitelist; Type: TABLE; Schema: ds; Owner: raulr; Tablespace: 
--

CREATE TABLE t_whitelist (
    e_mail character varying(100) NOT NULL,
    customer_id bigint NOT NULL
);


ALTER TABLE ds.t_whitelist OWNER TO raulr;

--
-- TOC entry 188 (class 1259 OID 16830)
-- Name: v_registered_email; Type: VIEW; Schema: ds; Owner: raulr
--

CREATE VIEW v_registered_email AS
 SELECT t_customer.customer_id,
    t_customer.username
   FROM t_customer
UNION
 SELECT t_customer_mailbox.customer_id,
    t_customer_mailbox.e_mail AS username
   FROM t_customer_mailbox;


ALTER TABLE ds.v_registered_email OWNER TO raulr;

--
-- TOC entry 195 (class 1259 OID 17081)
-- Name: v_transactions; Type: VIEW; Schema: ds; Owner: sbadmin
--

CREATE VIEW v_transactions AS
 SELECT tran.transaction_id,
    tran.customer_id,
    tran.transaction_code,
    tran.amount,
    tran.description,
    tran.transaction_date,
        CASE
            WHEN (tran.amount >= (0)::numeric) THEN stamps.from_email
            ELSE stamps.to_email
        END AS e_mail,
    stamps.subject,
    tran.email_id
   FROM (t_stamps_transactions tran
     LEFT JOIN t_stamps_issued stamps ON ((tran.stamp_id = stamps.stamp_id)));


ALTER TABLE ds.v_transactions OWNER TO sbadmin;

SET search_path = public, pg_catalog;

--
-- TOC entry 189 (class 1259 OID 16868)
-- Name: websessions; Type: TABLE; Schema: public; Owner: sbweb; Tablespace: 
--

CREATE TABLE websessions (
    id character(32) NOT NULL,
    expire integer,
    data bytea
);


ALTER TABLE public.websessions OWNER TO sbweb;

SET search_path = ds, pg_catalog;

--
-- TOC entry 1961 (class 2604 OID 17075)
-- Name: transaction_id; Type: DEFAULT; Schema: ds; Owner: sbadmin
--

ALTER TABLE ONLY t_stamps_transactions ALTER COLUMN transaction_id SET DEFAULT nextval('t_stamps_transactions_transaction_id_seq'::regclass);


--
-- TOC entry 2122 (class 0 OID 16705)
-- Dependencies: 174
-- Data for Name: t_account; Type: TABLE DATA; Schema: ds; Owner: raulr
--

COPY t_account (customer_id, points_bal, stamps_bal) FROM stdin;
101	0	400
\.


--
-- TOC entry 2121 (class 0 OID 16696)
-- Dependencies: 173
-- Data for Name: t_customer; Type: TABLE DATA; Schema: ds; Owner: raulr
--

COPY t_customer (customer_id, username, firstname, lastname, password, registered_date, status, preferred_lang, bad_logins, customer_type, country) FROM stdin;
1	support@stampbox.email	\N	\N	$2a$13$myeXXjLLTKrQKVzo2B9VAeqXjrqfUS8bKvFkjtr3nfT6Ydto0V3W6	2016-04-18 08:51:53.305889	A	\N	\N	\N	EE
101	stampboxdemo@yahoo.com	\N	\N	$2a$13$HD8qNZMaYLcRouKEVYHPQel6r1yjdjk1wtZfiE/CP4N/7gA28yZqu	2017-03-10 15:48:46	A	\N	0	\N	EE
\.


--
-- TOC entry 2180 (class 0 OID 0)
-- Dependencies: 172
-- Name: t_customer_customer_id_seq; Type: SEQUENCE SET; Schema: ds; Owner: raulr
--

SELECT pg_catalog.setval('t_customer_customer_id_seq', 101, true);


--
-- TOC entry 2123 (class 0 OID 16715)
-- Dependencies: 175
-- Data for Name: t_customer_mailbox; Type: TABLE DATA; Schema: ds; Owner: raulr
--

COPY t_customer_mailbox (customer_id, e_mail, e_mail_username, e_mail_password, status, maildomain, extended_service) FROM stdin;
101	stampboxdemo@yahoo.com	\N	\N	V	yahoo.com	f
\.


--
-- TOC entry 2143 (class 0 OID 17099)
-- Dependencies: 197
-- Data for Name: t_ignored_emailaddresses; Type: TABLE DATA; Schema: ds; Owner: sbadmin
--

COPY t_ignored_emailaddresses (e_mail) FROM stdin;
raulrebane71@gmail.com
jukkaj@icloud.com
\.


--
-- TOC entry 2126 (class 0 OID 16747)
-- Dependencies: 178
-- Data for Name: t_invitations; Type: TABLE DATA; Schema: ds; Owner: raulr
--

COPY t_invitations (customer_id, invited_email, invited_when, from_count, to_count, invite, name, last_email_date) FROM stdin;
\.


--
-- TOC entry 2124 (class 0 OID 16729)
-- Dependencies: 176
-- Data for Name: t_mailbox_config; Type: TABLE DATA; Schema: ds; Owner: raulr
--

COPY t_mailbox_config (maildomain, mailtype, incoming_hostname, incoming_port, incoming_socket_type, incoming_auth, outgoing_hostname, outgoing_port, outgoing_socket_type, outgoing_auth, status) FROM stdin;
icloud.com	IMAP	imap.mail.me.com	993	SSL	USERNAME	\N	\N	\N	\N	A
feelings.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
financier.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
fireman.net	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
florida.usa.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
footballer.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
gardener.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
geologist.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
playful.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
workmail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
bitnisse.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
city.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
cool.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
cyberdude.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
cyberjunkie.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
dk2net.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
dk-online.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
elinstallatoer.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
elsker.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
elvis.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
fald.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
fedt.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
feminin.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
forening.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
gadefejer.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
gason.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
grin.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
grov.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
hardworking.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
heaven.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
hemmelig.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
huleboer.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
image.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
inbound.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
indbakke.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
infile.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
jyde.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
klog.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
knus.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
krudt.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
kulturel.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
larsen.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
lazy.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
lystig.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
mail.dia.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
maskulin.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
min-postkasse.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
musling.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
natteliv.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
netbruger.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
pedal.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
pengemand.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
pokerface.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
post.dia.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
postman.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
privat.dia.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
quake.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
ready.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
secret.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
sleepy.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
sporty.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
superbruger.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
talent.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
tanke.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
taxidriver.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
teens.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
teknik.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
tjekket.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
traceroute.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
tv.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
ugenstilbud.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
ungdom.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
video.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
vittig.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
126.com	IMAP	imap.126.com	993	SSL	EMAIL	\N	\N	\N	\N	A
163.com	IMAP	imap.163.com	993	SSL	EMAIL	\N	\N	\N	\N	A
a1.net	IMAP	securemail.a1.net	993	SSL	EMAIL	\N	\N	\N	\N	A
aon.at	IMAP	securemail.a1.net	993	SSL	EMAIL	\N	\N	\N	\N	A
active24.com	IMAP	email.active24.com	993	SSL	EMAIL	\N	\N	\N	\N	A
smtp.cz	IMAP	email.active24.com	993	SSL	EMAIL	\N	\N	\N	\N	A
aol.com	IMAP	imap.aol.com	993	SSL	EMAIL	\N	\N	\N	\N	A
netscape.net	IMAP	imap.aol.com	993	SSL	EMAIL	\N	\N	\N	\N	A
alice.it	IMAP	in.alice.it	143	\N	EMAIL	\N	\N	\N	\N	A
arcor.de	IMAP	imap.arcor.de	993	SSL	USERNAME	\N	\N	\N	\N	A
pec.it	IMAP	imaps.pec.aruba.it	993	SSL	EMAIL	\N	\N	\N	\N	A
arubapec.it	IMAP	imaps.pec.aruba.it	993	SSL	EMAIL	\N	\N	\N	\N	A
mypec.eu	IMAP	imaps.pec.aruba.it	993	SSL	EMAIL	\N	\N	\N	\N	A
gigapec.it	IMAP	imaps.pec.aruba.it	993	SSL	EMAIL	\N	\N	\N	\N	A
ingpec.eu	IMAP	imaps.pec.aruba.it	993	SSL	EMAIL	\N	\N	\N	\N	A
skynet.be	IMAP	imap.proximus.be	993	SSL	EMAIL	\N	\N	\N	\N	A
proximus.be	IMAP	imap.proximus.be	993	SSL	EMAIL	\N	\N	\N	\N	A
belgacom.net	IMAP	imap.proximus.be	993	SSL	EMAIL	\N	\N	\N	\N	A
kidcity.be	IMAP	imap.proximus.be	993	SSL	EMAIL	\N	\N	\N	\N	A
bell.net	IMAP	imap.bell.net	993	SSL	EMAIL	\N	\N	\N	\N	A
sympatico.ca	IMAP	imap.bell.net	993	SSL	EMAIL	\N	\N	\N	\N	A
bigpond.com	IMAP	imap.telstra.com	993	SSL	EMAIL	\N	\N	\N	\N	A
bigpond.net.au	IMAP	imap.telstra.com	993	SSL	EMAIL	\N	\N	\N	\N	A
telstra.com	IMAP	imap.telstra.com	993	SSL	EMAIL	\N	\N	\N	\N	A
bigpond.net	IMAP	imap.telstra.com	993	SSL	EMAIL	\N	\N	\N	\N	A
mail.ru	IMAP	imap.mail.ru	993	SSL	EMAIL	\N	\N	\N	\N	A
inbox.ru	IMAP	imap.mail.ru	993	SSL	EMAIL	\N	\N	\N	\N	A
list.ru	IMAP	imap.mail.ru	993	SSL	EMAIL	\N	\N	\N	\N	A
bk.ru	IMAP	imap.mail.ru	993	SSL	EMAIL	\N	\N	\N	\N	A
corp.mail.ru	IMAP	imap.mail.ru	993	SSL	EMAIL	\N	\N	\N	\N	A
libero.it	IMAP	imapmail.libero.it	993	SSL	EMAIL	\N	\N	\N	\N	A
iol.it	IMAP	imapmail.libero.it	993	SSL	EMAIL	\N	\N	\N	\N	A
blu.it	IMAP	imapmail.libero.it	993	SSL	EMAIL	\N	\N	\N	\N	A
inwind.it	IMAP	imapmail.libero.it	993	SSL	EMAIL	\N	\N	\N	\N	A
giallo.it	IMAP	imapmail.libero.it	993	SSL	EMAIL	\N	\N	\N	\N	A
bluemail.ch	IMAP	imaps.bluewin.ch	993	SSL	EMAIL	\N	\N	\N	\N	A
bluewin.ch	IMAP	imaps.bluewin.ch	993	SSL	USERNAME	\N	\N	\N	\N	A
blueyonder.co.uk	IMAP	imap4.blueyonder.co.uk	993	SSL	EMAIL	\N	\N	\N	\N	A
bol.com.br	IMAP	imap.bol.com.br	993	SSL	USERNAME	\N	\N	\N	\N	A
btinternet.com	IMAP	mail.btinternet.com	993	SSL	EMAIL	\N	\N	\N	\N	A
btopenworld.com	IMAP	mail.btinternet.com	993	SSL	EMAIL	\N	\N	\N	\N	A
talk21.com	IMAP	mail.btinternet.com	993	SSL	EMAIL	\N	\N	\N	\N	A
hahah.nl	IMAP	imap.ziggo.nl	993	SSL	EMAIL	\N	\N	\N	\N	A
ziggomail.com	IMAP	imap.ziggo.nl	993	SSL	EMAIL	\N	\N	\N	\N	A
casema.nl	IMAP	imap.ziggo.nl	993	SSL	EMAIL	\N	\N	\N	\N	A
zinders.nl	IMAP	imap.ziggo.nl	993	SSL	EMAIL	\N	\N	\N	\N	A
ziggo.nl	IMAP	imap.ziggo.nl	993	SSL	EMAIL	\N	\N	\N	\N	A
zeggis.nl	IMAP	imap.ziggo.nl	993	SSL	EMAIL	\N	\N	\N	\N	A
zeggis.com	IMAP	imap.ziggo.nl	993	SSL	EMAIL	\N	\N	\N	\N	A
razcall.nl	IMAP	imap.ziggo.nl	993	SSL	EMAIL	\N	\N	\N	\N	A
razcall.com	IMAP	imap.ziggo.nl	993	SSL	EMAIL	\N	\N	\N	\N	A
upcmail.nl	IMAP	imap.ziggo.nl	993	SSL	EMAIL	\N	\N	\N	\N	A
chello.nl	IMAP	imap.ziggo.nl	993	SSL	EMAIL	\N	\N	\N	\N	A
multiweb.nl	IMAP	imap.ziggo.nl	993	SSL	EMAIL	\N	\N	\N	\N	A
home.nl	IMAP	imap.ziggo.nl	993	SSL	EMAIL	\N	\N	\N	\N	A
quicknet.nl	IMAP	imap.ziggo.nl	993	SSL	EMAIL	\N	\N	\N	\N	A
centurylink.net	IMAP	mail.centurylink.net	993	SSL	EMAIL	\N	\N	\N	\N	A
embarqmail.com	IMAP	mail.centurylink.net	993	SSL	EMAIL	\N	\N	\N	\N	A
cgl.ucsf.edu	IMAP	plato.cgl.ucsf.edu	993	SSL	USERNAME	\N	\N	\N	\N	A
charter.net	IMAP	mobile.charter.net	993	SSL	EMAIL	\N	\N	\N	\N	A
charter.com	IMAP	mobile.charter.net	993	SSL	EMAIL	\N	\N	\N	\N	A
sfr.fr	IMAP	imap.sfr.fr	993	SSL	EMAIL	\N	\N	\N	\N	A
neuf.fr	IMAP	imap.sfr.fr	993	SSL	EMAIL	\N	\N	\N	\N	A
club-internet.fr	IMAP	imap.sfr.fr	993	SSL	EMAIL	\N	\N	\N	\N	A
clustermail.de	IMAP	mail.clustermail.de	993	SSL	EMAIL	\N	\N	\N	\N	A
versatel.de	IMAP	mx.versatel.de	143	\N	EMAIL	\N	\N	\N	\N	A
versanet.de	IMAP	mx.versatel.de	143	\N	EMAIL	\N	\N	\N	\N	A
foni.net	IMAP	mx.versatel.de	143	\N	EMAIL	\N	\N	\N	\N	A
gelsennet.de	IMAP	mx.versatel.de	143	\N	EMAIL	\N	\N	\N	\N	A
telebel.de	IMAP	mx.versatel.de	143	\N	EMAIL	\N	\N	\N	\N	A
telelev.de	IMAP	mx.versatel.de	143	\N	EMAIL	\N	\N	\N	\N	A
cneweb.de	IMAP	mx.versatel.de	143	\N	EMAIL	\N	\N	\N	\N	A
ruhrnet-online.de	IMAP	mx.versatel.de	143	\N	EMAIL	\N	\N	\N	\N	A
comcast.net	IMAP	imap.comcast.net	993	SSL	EMAIL	\N	\N	\N	\N	A
cox.net	IMAP	imap.cox.net	993	SSL	USERNAME	\N	\N	\N	\N	A
dondominio.com	IMAP	imap.dondominio.com	993	SSL	EMAIL	\N	\N	\N	\N	A
earthlink.net	IMAP	imap.earthlink.net	143	\N	EMAIL	\N	\N	\N	\N	A
mindspring.com	IMAP	imap.earthlink.net	143	\N	EMAIL	\N	\N	\N	\N	A
ix.netcom.com	IMAP	imap.earthlink.net	143	\N	EMAIL	\N	\N	\N	\N	A
elpasotel.net	IMAP	mail.elpasotel.net	993	SSL	EMAIL	\N	\N	\N	\N	A
seznam.cz	IMAP	imap.seznam.cz	993	SSL	EMAIL	\N	\N	\N	\N	A
email.cz	IMAP	imap.seznam.cz	993	SSL	EMAIL	\N	\N	\N	\N	A
post.cz	IMAP	imap.seznam.cz	993	SSL	EMAIL	\N	\N	\N	\N	A
spoluzaci.cz	IMAP	imap.seznam.cz	993	SSL	EMAIL	\N	\N	\N	\N	A
email.it	IMAP	imapmail.email.it	993	SSL	EMAIL	\N	\N	\N	\N	A
emailsrvr.com	IMAP	secure.emailsrvr.com	993	SSL	EMAIL	\N	\N	\N	\N	A
fastwebnet.it	IMAP	imap.fastwebnet.it	143	TLS	EMAIL	\N	\N	\N	\N	A
free.fr	IMAP	imap.free.fr	993	SSL	USERNAME	\N	\N	\N	\N	A
freenet.de	IMAP	mx.freenet.de	993	SSL	EMAIL	\N	\N	\N	\N	A
gandi.net	IMAP	mail.gandi.net	993	SSL	EMAIL	\N	\N	\N	\N	A
gigahost.dk	IMAP	mail.gigahost.dk	993	SSL	EMAIL	\N	\N	\N	\N	A
gmail.com	IMAP	imap.gmail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
googlemail.com	IMAP	imap.gmail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
google.com	IMAP	imap.gmail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
jazztel.es	IMAP	imap.gmail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.net	IMAP	imap.gmx.net	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.de	IMAP	imap.gmx.net	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.at	IMAP	imap.gmx.net	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.ch	IMAP	imap.gmx.net	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.eu	IMAP	imap.gmx.net	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.biz	IMAP	imap.gmx.net	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.org	IMAP	imap.gmx.net	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.info	IMAP	imap.gmx.net	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.com	IMAP	imap.gmx.com	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.tm	IMAP	imap.gmx.com	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.us	IMAP	imap.gmx.com	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.co.uk	IMAP	imap.gmx.com	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.es	IMAP	imap.gmx.com	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.fr	IMAP	imap.gmx.com	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.ca	IMAP	imap.gmx.com	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.cn	IMAP	imap.gmx.com	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.co.in	IMAP	imap.gmx.com	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.com.br	IMAP	imap.gmx.com	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.com.my	IMAP	imap.gmx.com	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.hk	IMAP	imap.gmx.com	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.ie	IMAP	imap.gmx.com	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.ph	IMAP	imap.gmx.com	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.pt	IMAP	imap.gmx.com	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.ru	IMAP	imap.gmx.com	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.se	IMAP	imap.gmx.com	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.sg	IMAP	imap.gmx.com	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.tw	IMAP	imap.gmx.com	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.com.tr	IMAP	imap.gmx.com	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.it	IMAP	imap.gmx.com	993	SSL	EMAIL	\N	\N	\N	\N	A
gmx.li	IMAP	imap.gmx.com	993	SSL	EMAIL	\N	\N	\N	\N	A
1and1.de	IMAP	imap.1und1.de	993	SSL	EMAIL	\N	\N	\N	\N	A
online.de	IMAP	imap.1und1.de	993	SSL	EMAIL	\N	\N	\N	\N	A
goneo.de	IMAP	imap.goneo.de	993	SSL	EMAIL	\N	\N	\N	\N	A
gransy.com	IMAP	imap.gransy.com	993	SSL	EMAIL	\N	\N	\N	\N	A
internetserver.cz	IMAP	imap.gransy.com	993	SSL	EMAIL	\N	\N	\N	\N	A
hotmail.com	IMAP	imap-mail.outlook.com	993	SSL	EMAIL	\N	\N	\N	\N	A
hotmail.co.uk	IMAP	imap-mail.outlook.com	993	SSL	EMAIL	\N	\N	\N	\N	A
hotmail.co.jp	IMAP	imap-mail.outlook.com	993	SSL	EMAIL	\N	\N	\N	\N	A
hotmail.com.br	IMAP	imap-mail.outlook.com	993	SSL	EMAIL	\N	\N	\N	\N	A
hotmail.de	IMAP	imap-mail.outlook.com	993	SSL	EMAIL	\N	\N	\N	\N	A
hotmail.fr	IMAP	imap-mail.outlook.com	993	SSL	EMAIL	\N	\N	\N	\N	A
hotmail.it	IMAP	imap-mail.outlook.com	993	SSL	EMAIL	\N	\N	\N	\N	A
hotmail.es	IMAP	imap-mail.outlook.com	993	SSL	EMAIL	\N	\N	\N	\N	A
live.com	IMAP	imap-mail.outlook.com	993	SSL	EMAIL	\N	\N	\N	\N	A
live.co.uk	IMAP	imap-mail.outlook.com	993	SSL	EMAIL	\N	\N	\N	\N	A
live.co.jp	IMAP	imap-mail.outlook.com	993	SSL	EMAIL	\N	\N	\N	\N	A
live.de	IMAP	imap-mail.outlook.com	993	SSL	EMAIL	\N	\N	\N	\N	A
live.fr	IMAP	imap-mail.outlook.com	993	SSL	EMAIL	\N	\N	\N	\N	A
live.it	IMAP	imap-mail.outlook.com	993	SSL	EMAIL	\N	\N	\N	\N	A
live.jp	IMAP	imap-mail.outlook.com	993	SSL	EMAIL	\N	\N	\N	\N	A
msn.com	IMAP	imap-mail.outlook.com	993	SSL	EMAIL	\N	\N	\N	\N	A
outlook.com	IMAP	imap-mail.outlook.com	993	SSL	EMAIL	\N	\N	\N	\N	A
i.softbank.jp	IMAP	imap.softbank.jp	993	SSL	USERNAME	\N	\N	\N	\N	A
internode.on.net	IMAP	mail.internode.on.net	993	SSL	EMAIL	\N	\N	\N	\N	A
ipax.at	IMAP	mail.ipax.at	993	SSL	EMAIL	\N	\N	\N	\N	A
ispgateway.de	IMAP	sslmailpool.ispgateway.de	993	SSL	EMAIL	\N	\N	\N	\N	A
jet.ne.jp	IMAP	imap.jet.ne.jp	993	SSL	USERNAME	\N	\N	\N	\N	A
laposte.net	IMAP	imap.laposte.net	993	SSL	USERNAME	\N	\N	\N	\N	A
mac.com	IMAP	imap.mail.me.com	993	SSL	USERNAME	\N	\N	\N	\N	A
me.com	IMAP	imap.mail.me.com	993	SSL	USERNAME	\N	\N	\N	\N	A
messagingengine.com	IMAP	imap.fastmail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
mopera.net	IMAP	mail.mopera.net	993	SSL	USERNAME	\N	\N	\N	\N	A
mozilla.com	IMAP	imap.googlemail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
mozillafoundation.org	IMAP	imap.googlemail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
yandex.ru	IMAP	imap.yandex.com	993	SSL	EMAIL	\N	\N	\N	\N	A
yandex.com	IMAP	imap.yandex.com	993	SSL	EMAIL	\N	\N	\N	\N	A
yandex.net	IMAP	imap.yandex.com	993	SSL	EMAIL	\N	\N	\N	\N	A
yandex.by	IMAP	imap.yandex.com	993	SSL	EMAIL	\N	\N	\N	\N	A
yandex.kz	IMAP	imap.yandex.com	993	SSL	EMAIL	\N	\N	\N	\N	A
yandex.ua	IMAP	imap.yandex.com	993	SSL	EMAIL	\N	\N	\N	\N	A
ya.ru	IMAP	imap.yandex.com	993	SSL	EMAIL	\N	\N	\N	\N	A
narod.ru	IMAP	imap.yandex.com	993	SSL	EMAIL	\N	\N	\N	\N	A
ntlworld.com	IMAP	imap.ntlworld.com	993	SSL	EMAIL	\N	\N	\N	\N	A
one.com	IMAP	imap.one.com	993	SSL	EMAIL	\N	\N	\N	\N	A
one.cz	IMAP	imap.registrator.cz	993	SSL	EMAIL	\N	\N	\N	\N	A
orange.fr	IMAP	imap.orange.fr	993	SSL	EMAIL	\N	\N	\N	\N	A
wanadoo.fr	IMAP	imap.orange.fr	993	SSL	EMAIL	\N	\N	\N	\N	A
ovh.net	IMAP	ssl0.ovh.net	993	SSL	EMAIL	\N	\N	\N	\N	A
pdx.edu	IMAP	psumail.pdx.edu	993	SSL	EMAIL	\N	\N	\N	\N	A
peoplepc.com	IMAP	imap.peoplepc.com	143	\N	EMAIL	\N	\N	\N	\N	A
pobox.com	IMAP	mail.pobox.com	993	SSL	EMAIL	\N	\N	\N	\N	A
posteo.de	IMAP	posteo.de	143	TLS	EMAIL	\N	\N	\N	\N	A
posteo.at	IMAP	posteo.de	143	TLS	EMAIL	\N	\N	\N	\N	A
posteo.ch	IMAP	posteo.de	143	TLS	EMAIL	\N	\N	\N	\N	A
posteo.org	IMAP	posteo.de	143	TLS	EMAIL	\N	\N	\N	\N	A
posteo.eu	IMAP	posteo.de	143	TLS	EMAIL	\N	\N	\N	\N	A
ptd.net	IMAP	promail.ptd.net	993	SSL	USERNAME	\N	\N	\N	\N	A
q.com	IMAP	mail.q.com	993	SSL	EMAIL	\N	\N	\N	\N	A
qq.com	IMAP	imap.qq.com	993	SSL	EMAIL	\N	\N	\N	\N	A
rambler.ru	IMAP	mail.rambler.ru	993	SSL	EMAIL	\N	\N	\N	\N	A
yahoo.com	IMAP	imap.mail.yahoo.com	993	SSL	EMAIL	\N	\N	\N	\N	A
yahoo.de	IMAP	imap.mail.yahoo.com	993	SSL	EMAIL	\N	\N	\N	\N	A
yahoo.it	IMAP	imap.mail.yahoo.com	993	SSL	EMAIL	\N	\N	\N	\N	A
yahoo.fr	IMAP	imap.mail.yahoo.com	993	SSL	EMAIL	\N	\N	\N	\N	A
yahoo.es	IMAP	imap.mail.yahoo.com	993	SSL	EMAIL	\N	\N	\N	\N	A
yahoo.se	IMAP	imap.mail.yahoo.com	993	SSL	EMAIL	\N	\N	\N	\N	A
yahoo.co.uk	IMAP	imap.mail.yahoo.com	993	SSL	EMAIL	\N	\N	\N	\N	A
yahoo.co.nz	IMAP	imap.mail.yahoo.com	993	SSL	EMAIL	\N	\N	\N	\N	A
yahoo.com.au	IMAP	imap.mail.yahoo.com	993	SSL	EMAIL	\N	\N	\N	\N	A
mail.ee	IMAP	mail.ee	993	ssl	OTHER	\N	\N	\N	\N	A
onlinehome.de	IMAP	imap.1und1.de	993	SSL	EMAIL	\N	\N	\N	\N	A
sofortstart.de	IMAP	imap.1und1.de	993	SSL	EMAIL	\N	\N	\N	\N	A
sofort-start.de	IMAP	imap.1und1.de	993	SSL	EMAIL	\N	\N	\N	\N	A
sofortsurf.de	IMAP	imap.1und1.de	993	SSL	EMAIL	\N	\N	\N	\N	A
sofort-surf.de	IMAP	imap.1und1.de	993	SSL	EMAIL	\N	\N	\N	\N	A
go4more.de	IMAP	imap.1und1.de	993	SSL	EMAIL	\N	\N	\N	\N	A
kundenserver.de	IMAP	imap.1und1.de	993	SSL	EMAIL	\N	\N	\N	\N	A
schlund.de	IMAP	imap.1und1.de	993	SSL	EMAIL	\N	\N	\N	\N	A
1and1.com	IMAP	imap.1und1.de	993	SSL	EMAIL	\N	\N	\N	\N	A
1and1.fr	IMAP	imap.1und1.de	993	SSL	EMAIL	\N	\N	\N	\N	A
1and1.co.uk	IMAP	imap.1und1.de	993	SSL	EMAIL	\N	\N	\N	\N	A
1and1.es	IMAP	imap.1und1.de	993	SSL	EMAIL	\N	\N	\N	\N	A
mail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
mail.org	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
email.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
post.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
usa.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
accountant.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
consultant.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
dr.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
engineer.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
cheerful.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
techie.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
linuxmail.org	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
europe.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
london.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
uymail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
myself.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
iname.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
writeme.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
2die4.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
activist.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
adexec.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
africamail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
aircraftmail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
alabama.usa.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
alaska.usa.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
allergist.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
alumni.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
alumnidirector.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
americamail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
amorous.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
angelic.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
archaeologist.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
arizona.usa.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
artlover.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
asia-mail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
atheist.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
australiamail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
aim.com	IMAP	imap.aol.com	993	SSL	EMAIL	\N	\N	\N	\N	A
bartender.net	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
berlin.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
bigger.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
bikerider.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
birdlover.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
blader.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
boardermail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
brazilmail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
brew-master.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
california.usa.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
californiamail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
caress.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
catlover.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
chef.net	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
chemist.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
chinamail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
clerk.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
cliffhanger.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
collector.org	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
columnist.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
comic.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
computer4u.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
contractor.net	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
coolsite.net	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
counsellor.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
count.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
couple.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
cutey.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
cyberdude.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
cybergal.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
cyber-wizard.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
dallasmail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
dbzmail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
deliveryman.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
diplomats.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
disciples.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
doctor.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
doglover.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
doramail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
dublin.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
earthling.net	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
elvisfan.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
englandmail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
europemail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
execs.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
fan.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
germanymail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
graduate.org	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
graphic-designer.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
hackermail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
hairdresser.net	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
hilarious.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
hockeymail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
homemail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
hot-shot.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
hour.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
humanoid.net	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
illinois.usa.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
innocent.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
inorbit.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
instruction.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
instructor.net	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
insurer.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
irelandmail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
italymail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
japan.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
journalist.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
keromail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
kittymail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
koreamail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
lawyer.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
legislator.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
loveable.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
lovecat.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
mad.scientist.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
madonnafan.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
madrid.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
marchmail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
mexicomail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
mindless.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
minister.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
mobsters.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
monarchy.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
moscowmail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
munich.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
musician.org	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
muslim.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
newyork.usa.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
null.net	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
nycmail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
oath.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
optician.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
pacificwest.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
petlover.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
photographer.net	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
poetic.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
politician.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
popstar.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
presidency.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
priest.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
programmer.net	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
publicist.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
realtyagent.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
reborn.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
reggaefan.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
religious.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
repairman.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
representative.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
rescueteam.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
revenue.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
rocketship.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
rockfan.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
rome.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
royal.net	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
saintly.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
salesperson.net	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
sanfranmail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
scientist.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
scotlandmail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
secretary.net	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
seductive.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
singapore.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
snakebite.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
songwriter.net	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
soon.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
spainmail.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
teachers.org	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
technologist.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
texas.usa.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
thegame.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
therapist.net	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
toke.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
tokyo.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
toothfairy.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
tvstar.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
umpire.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
wallet.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
webname.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
weirdness.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
who.net	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
whoever.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
winning.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
witty.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
worker.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
yours.com	IMAP	imap.mail.com	993	SSL	EMAIL	\N	\N	\N	\N	A
mail.telenor.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
vip.cybercity.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
post.cybercity.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
email.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
mobil.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
privatmail.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
info.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
io.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
it.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
film.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
worldonline.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
wol.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
1031.inord.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
123mail.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
12fuel.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
12mail.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
12move.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
2senior.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
anarki.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
anderledes.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
begavet.dk	IMAP	mail.telenor.dk	143	TLS	EMAIL	\N	\N	\N	\N	A
yahoo.com.ar	IMAP	imap.mail.yahoo.com	993	SSL	EMAIL	\N	\N	\N	\N	A
yahoo.com.br	IMAP	imap.mail.yahoo.com	993	SSL	EMAIL	\N	\N	\N	\N	A
yahoo.com.mx	IMAP	imap.mail.yahoo.com	993	SSL	EMAIL	\N	\N	\N	\N	A
ymail.com	IMAP	imap.mail.yahoo.com	993	SSL	EMAIL	\N	\N	\N	\N	A
rocketmail.com	IMAP	imap.mail.yahoo.com	993	SSL	EMAIL	\N	\N	\N	\N	A
yahoodns.net	IMAP	imap.mail.yahoo.com	993	SSL	EMAIL	\N	\N	\N	\N	A
rr.com	IMAP	mail.twc.com	993	SSL	EMAIL	\N	\N	\N	\N	A
strato.de	IMAP	imap.strato.de	993	SSL	EMAIL	\N	\N	\N	\N	A
rzone.de	IMAP	imap.strato.de	993	SSL	EMAIL	\N	\N	\N	\N	A
secureserver.net	IMAP	imap.secureserver.net	993	SSL	EMAIL	\N	\N	\N	\N	A
sky.com	IMAP	imap.tools.sky.com	993	SSL	EMAIL	\N	\N	\N	\N	A
so.wind.ne.jp	IMAP	so.wind.ne.jp	143	\N	USERNAME	\N	\N	\N	\N	A
so.wind.jp	IMAP	so.wind.ne.jp	143	\N	USERNAME	\N	\N	\N	\N	A
studenti.univr.it	IMAP	univr.mail.cineca.it	993	SSL	EMAIL	\N	\N	\N	\N	A
t-online.de	IMAP	secureimap.t-online.de	993	SSL	EMAIL	\N	\N	\N	\N	A
terra.es	IMAP	imap4.terra.es	143	\N	EMAIL	\N	\N	\N	\N	A
thinline.cz	IMAP	mail.cesky-hosting.cz	993	SSL	EMAIL	\N	\N	\N	\N	A
tiscali.it	IMAP	imap.tiscali.it	993	SSL	EMAIL	\N	\N	\N	\N	A
umich.edu	IMAP	mail.umich.edu	993	SSL	USERNAME	\N	\N	\N	\N	A
uol.com.br	IMAP	imap.uol.com.br	993	SSL	USERNAME	\N	\N	\N	\N	A
virgin.net	IMAP	imap4.virgin.net	993	SSL	EMAIL	\N	\N	\N	\N	A
virginmedia.com	IMAP	imap.virginmedia.com	993	SSL	EMAIL	\N	\N	\N	\N	A
web.de	IMAP	imap.web.de	993	SSL	USERNAME	\N	\N	\N	\N	A
webhuset.no	IMAP	imap.webhuset.no	993	SSL	EMAIL	\N	\N	\N	\N	A
wp.pl	IMAP	imap.wp.pl	993	SSL	USERNAME	\N	\N	\N	\N	A
yeah.net	IMAP	imap.yeah.net	993	SSL	EMAIL	\N	\N	\N	\N	A
zeelandnet.nl	IMAP	mail.zeelandnet.nl	993	SSL	USERNAME	\N	\N	\N	\N	A
zoho.com	IMAP	imap.zoho.com	993	SSL	EMAIL	\N	\N	\N	\N	A
zohomail.com	IMAP	imap.zoho.com	993	SSL	EMAIL	\N	\N	\N	\N	A
\.


--
-- TOC entry 2139 (class 0 OID 16959)
-- Dependencies: 192
-- Data for Name: t_messages; Type: TABLE DATA; Schema: ds; Owner: sbadmin
--

COPY t_messages (message_id, customer_id, showcount, message_type, page_id, message) FROM stdin;
56	101	0	success	site/index	<h4>We recommend you to sign up for the extended service which automatically filters e-mails with digital stamps to your inbox and those without stamps into no-stamp-box email folder.<br>With the extended service you also earn money for every stamped e-mail you receive. To be able to give you this service we need your email password. <a class="alert-link" style="text-decoration: underline;" href="/index.php?r=usermailbox/update&email=stampboxdemo@yahoo.com">Upgrade to extended service here</a></h4>
\.


--
-- TOC entry 2181 (class 0 OID 0)
-- Dependencies: 191
-- Name: t_messages_message_id_seq; Type: SEQUENCE SET; Schema: ds; Owner: sbadmin
--

SELECT pg_catalog.setval('t_messages_message_id_seq', 56, true);


--
-- TOC entry 2125 (class 0 OID 16737)
-- Dependencies: 177
-- Data for Name: t_passwdresets; Type: TABLE DATA; Schema: ds; Owner: raulr
--

COPY t_passwdresets (customer_id, e_mail, token, sent) FROM stdin;
\.



--
-- TOC entry 2133 (class 0 OID 16809)
-- Dependencies: 185
-- Data for Name: t_shop_offers; Type: TABLE DATA; Schema: ds; Owner: raulr
--

COPY t_shop_offers (offer_id, batch_id, start_from, end_date, status, offer_amount, offer_price, entered_by, entered_when) FROM stdin;
1	2	\N	\N	A	100	9.90	1	2016-04-18 08:51:53.338263
2	2	\N	\N	A	1000	89.90	1	2016-04-18 08:51:53.349108
3	2	\N	\N	A	100	9.90	1	2016-04-18 09:53:38.219802
4	2	\N	\N	A	1000	89.90	1	2016-04-18 09:53:38.230743
\.


--
-- TOC entry 2182 (class 0 OID 0)
-- Dependencies: 184
-- Name: t_shop_offers_offer_id_seq; Type: SEQUENCE SET; Schema: ds; Owner: raulr
--

SELECT pg_catalog.setval('t_shop_offers_offer_id_seq', 4, true);


--
-- TOC entry 2134 (class 0 OID 16820)
-- Dependencies: 186
-- Data for Name: t_shoppingcart; Type: TABLE DATA; Schema: ds; Owner: raulr
--

COPY t_shoppingcart (customer_id, batch_id, stamp_amount, price, paypal_token, paypal_timestamp, paypal_correlation_id) FROM stdin;
\.


--
-- TOC entry 2128 (class 0 OID 16759)
-- Dependencies: 180
-- Data for Name: t_stamp_definition; Type: TABLE DATA; Schema: ds; Owner: raulr
--

COPY t_stamp_definition (batch_id, cost, currency, pts_earned, stamp_pic, status, changed_by, added) FROM stdin;
1	0.00	EUR	0.000	\N	A	1	2016-04-18 08:51:53.316122
2	0.10	EUR	0.071	\N	A	1	2016-04-18 08:51:53.328105
3	0.00	EUR	0.000	\N	A	1	2016-04-18 09:53:38.179192
4	0.10	EUR	0.071	\N	A	1	2016-04-18 09:53:38.208767
\.


--
-- TOC entry 2183 (class 0 OID 0)
-- Dependencies: 179
-- Name: t_stamp_definition_batch_id_seq; Type: SEQUENCE SET; Schema: ds; Owner: raulr
--

SELECT pg_catalog.setval('t_stamp_definition_batch_id_seq', 4, true);


--
-- TOC entry 2184 (class 0 OID 0)
-- Dependencies: 181
-- Name: t_stamp_id_seq; Type: SEQUENCE SET; Schema: ds; Owner: raulr
--

SELECT pg_catalog.setval('t_stamp_id_seq', 8900, true);


--
-- TOC entry 2130 (class 0 OID 16772)
-- Dependencies: 182
-- Data for Name: t_stamps_issued; Type: TABLE DATA; Schema: ds; Owner: raulr
--

COPY t_stamps_issued (stamp_id, batch_id, customer_id, status, issued, from_email, to_email, email_id, subject) FROM stdin;
8501	1	101	A	2017-03-10 15:48:47.8455	\N	\N	\N	\N
8502	1	101	A	2017-03-10 15:48:47.855	\N	\N	\N	\N
8503	1	101	A	2017-03-10 15:48:47.86654	\N	\N	\N	\N
8504	1	101	A	2017-03-10 15:48:47.87817	\N	\N	\N	\N
8505	1	101	A	2017-03-10 15:48:47.88884	\N	\N	\N	\N
8506	1	101	A	2017-03-10 15:48:47.89978	\N	\N	\N	\N
8507	1	101	A	2017-03-10 15:48:47.91093	\N	\N	\N	\N
8508	1	101	A	2017-03-10 15:48:47.92177	\N	\N	\N	\N
8509	1	101	A	2017-03-10 15:48:47.93293	\N	\N	\N	\N
8510	1	101	A	2017-03-10 15:48:47.94416	\N	\N	\N	\N
8511	1	101	A	2017-03-10 15:48:47.95488	\N	\N	\N	\N
8512	1	101	A	2017-03-10 15:48:47.96622	\N	\N	\N	\N
8513	1	101	A	2017-03-10 15:48:47.97674	\N	\N	\N	\N
8514	1	101	A	2017-03-10 15:48:47.98795	\N	\N	\N	\N
8515	1	101	A	2017-03-10 15:48:47.99916	\N	\N	\N	\N
8516	1	101	A	2017-03-10 15:48:48.01025	\N	\N	\N	\N
8517	1	101	A	2017-03-10 15:48:48.02081	\N	\N	\N	\N
8518	1	101	A	2017-03-10 15:48:48.03302	\N	\N	\N	\N
8519	1	101	A	2017-03-10 15:48:48.04336	\N	\N	\N	\N
8520	1	101	A	2017-03-10 15:48:48.05437	\N	\N	\N	\N
8521	1	101	A	2017-03-10 15:48:48.06539	\N	\N	\N	\N
8522	1	101	A	2017-03-10 15:48:48.07628	\N	\N	\N	\N
8523	1	101	A	2017-03-10 15:48:48.0872	\N	\N	\N	\N
8524	1	101	A	2017-03-10 15:48:48.0981	\N	\N	\N	\N
8525	1	101	A	2017-03-10 15:48:48.10942	\N	\N	\N	\N
8526	1	101	A	2017-03-10 15:48:48.12022	\N	\N	\N	\N
8527	1	101	A	2017-03-10 15:48:48.13127	\N	\N	\N	\N
8528	1	101	A	2017-03-10 15:48:48.1421	\N	\N	\N	\N
8529	1	101	A	2017-03-10 15:48:48.15488	\N	\N	\N	\N
8530	1	101	A	2017-03-10 15:48:48.16313	\N	\N	\N	\N
8531	1	101	A	2017-03-10 15:48:48.17422	\N	\N	\N	\N
8532	1	101	A	2017-03-10 15:48:48.18521	\N	\N	\N	\N
8533	1	101	A	2017-03-10 15:48:48.1962	\N	\N	\N	\N
8534	1	101	A	2017-03-10 15:48:48.20718	\N	\N	\N	\N
8535	1	101	A	2017-03-10 15:48:48.21825	\N	\N	\N	\N
8536	1	101	A	2017-03-10 15:48:48.22924	\N	\N	\N	\N
8537	1	101	A	2017-03-10 15:48:48.24018	\N	\N	\N	\N
8538	1	101	A	2017-03-10 15:48:48.25114	\N	\N	\N	\N
8539	1	101	A	2017-03-10 15:48:48.2622	\N	\N	\N	\N
8540	1	101	A	2017-03-10 15:48:48.27327	\N	\N	\N	\N
8541	1	101	A	2017-03-10 15:48:48.28509	\N	\N	\N	\N
8542	1	101	A	2017-03-10 15:48:48.29744	\N	\N	\N	\N
8543	1	101	A	2017-03-10 15:48:48.30731	\N	\N	\N	\N
8544	1	101	A	2017-03-10 15:48:48.31835	\N	\N	\N	\N
8545	1	101	A	2017-03-10 15:48:48.3297	\N	\N	\N	\N
8546	1	101	A	2017-03-10 15:48:48.34059	\N	\N	\N	\N
8547	1	101	A	2017-03-10 15:48:48.35148	\N	\N	\N	\N
8548	1	101	A	2017-03-10 15:48:48.36232	\N	\N	\N	\N
8549	1	101	A	2017-03-10 15:48:48.37331	\N	\N	\N	\N
8550	1	101	A	2017-03-10 15:48:48.38444	\N	\N	\N	\N
8551	1	101	A	2017-03-10 15:48:48.3967	\N	\N	\N	\N
8552	1	101	A	2017-03-10 15:48:48.40669	\N	\N	\N	\N
8553	1	101	A	2017-03-10 15:48:48.41743	\N	\N	\N	\N
8554	1	101	A	2017-03-10 15:48:48.42834	\N	\N	\N	\N
8555	1	101	A	2017-03-10 15:48:48.43931	\N	\N	\N	\N
8556	1	101	A	2017-03-10 15:48:48.45025	\N	\N	\N	\N
8557	1	101	A	2017-03-10 15:48:48.46126	\N	\N	\N	\N
8558	1	101	A	2017-03-10 15:48:48.47222	\N	\N	\N	\N
8559	1	101	A	2017-03-10 15:48:48.48484	\N	\N	\N	\N
8560	1	101	A	2017-03-10 15:48:48.49459	\N	\N	\N	\N
8561	1	101	A	2017-03-10 15:48:48.50572	\N	\N	\N	\N
8562	1	101	A	2017-03-10 15:48:48.51668	\N	\N	\N	\N
8563	1	101	A	2017-03-10 15:48:48.52738	\N	\N	\N	\N
8564	1	101	A	2017-03-10 15:48:48.53859	\N	\N	\N	\N
8565	1	101	A	2017-03-10 15:48:48.54937	\N	\N	\N	\N
8566	1	101	A	2017-03-10 15:48:48.56073	\N	\N	\N	\N
8567	1	101	A	2017-03-10 15:48:48.57156	\N	\N	\N	\N
8568	1	101	A	2017-03-10 15:48:48.58243	\N	\N	\N	\N
8569	1	101	A	2017-03-10 15:48:48.59381	\N	\N	\N	\N
8570	1	101	A	2017-03-10 15:48:48.60427	\N	\N	\N	\N
8571	1	101	A	2017-03-10 15:48:48.61586	\N	\N	\N	\N
8572	1	101	A	2017-03-10 15:48:48.6266	\N	\N	\N	\N
8573	1	101	A	2017-03-10 15:48:48.63834	\N	\N	\N	\N
8574	1	101	A	2017-03-10 15:48:48.6482	\N	\N	\N	\N
8575	1	101	A	2017-03-10 15:48:48.65935	\N	\N	\N	\N
8576	1	101	A	2017-03-10 15:48:48.67111	\N	\N	\N	\N
8577	1	101	A	2017-03-10 15:48:48.68188	\N	\N	\N	\N
8578	1	101	A	2017-03-10 15:48:48.69252	\N	\N	\N	\N
8579	1	101	A	2017-03-10 15:48:48.70368	\N	\N	\N	\N
8580	1	101	A	2017-03-10 15:48:48.71478	\N	\N	\N	\N
8581	1	101	A	2017-03-10 15:48:48.72597	\N	\N	\N	\N
8582	1	101	A	2017-03-10 15:48:48.73664	\N	\N	\N	\N
8583	1	101	A	2017-03-10 15:48:48.74754	\N	\N	\N	\N
8584	1	101	A	2017-03-10 15:48:48.75888	\N	\N	\N	\N
8585	1	101	A	2017-03-10 15:48:48.76998	\N	\N	\N	\N
8586	1	101	A	2017-03-10 15:48:48.7805	\N	\N	\N	\N
8587	1	101	A	2017-03-10 15:48:48.79197	\N	\N	\N	\N
8588	1	101	A	2017-03-10 15:48:48.80288	\N	\N	\N	\N
8589	1	101	A	2017-03-10 15:48:48.81398	\N	\N	\N	\N
8590	1	101	A	2017-03-10 15:48:48.82462	\N	\N	\N	\N
8591	1	101	A	2017-03-10 15:48:48.83697	\N	\N	\N	\N
8592	1	101	A	2017-03-10 15:48:48.84693	\N	\N	\N	\N
8593	1	101	A	2017-03-10 15:48:48.85775	\N	\N	\N	\N
8594	1	101	A	2017-03-10 15:48:48.86897	\N	\N	\N	\N
8595	1	101	A	2017-03-10 15:48:48.87973	\N	\N	\N	\N
8596	1	101	A	2017-03-10 15:48:48.891	\N	\N	\N	\N
8597	1	101	A	2017-03-10 15:48:48.90186	\N	\N	\N	\N
8598	1	101	A	2017-03-10 15:48:48.91272	\N	\N	\N	\N
8599	1	101	A	2017-03-10 15:48:48.92366	\N	\N	\N	\N
8600	1	101	A	2017-03-10 15:48:48.93507	\N	\N	\N	\N
8601	2	101	A	2017-03-10 15:50:05.84151	\N	\N	\N	\N
8602	2	101	A	2017-03-10 15:50:05.86195	\N	\N	\N	\N
8603	2	101	A	2017-03-10 15:50:05.87163	\N	\N	\N	\N
8604	2	101	A	2017-03-10 15:50:05.88274	\N	\N	\N	\N
8605	2	101	A	2017-03-10 15:50:05.89261	\N	\N	\N	\N
8606	2	101	A	2017-03-10 15:50:05.9043	\N	\N	\N	\N
8607	2	101	A	2017-03-10 15:50:05.91526	\N	\N	\N	\N
8608	2	101	A	2017-03-10 15:50:05.92646	\N	\N	\N	\N
8609	2	101	A	2017-03-10 15:50:05.93745	\N	\N	\N	\N
8610	2	101	A	2017-03-10 15:50:05.9484	\N	\N	\N	\N
8611	2	101	A	2017-03-10 15:50:05.95966	\N	\N	\N	\N
8612	2	101	A	2017-03-10 15:50:05.97074	\N	\N	\N	\N
8613	2	101	A	2017-03-10 15:50:05.98158	\N	\N	\N	\N
8614	2	101	A	2017-03-10 15:50:05.9928	\N	\N	\N	\N
8615	2	101	A	2017-03-10 15:50:06.00349	\N	\N	\N	\N
8616	2	101	A	2017-03-10 15:50:06.01479	\N	\N	\N	\N
8617	2	101	A	2017-03-10 15:50:06.02582	\N	\N	\N	\N
8618	2	101	A	2017-03-10 15:50:06.03669	\N	\N	\N	\N
8619	2	101	A	2017-03-10 15:50:06.04777	\N	\N	\N	\N
8620	2	101	A	2017-03-10 15:50:06.05877	\N	\N	\N	\N
8621	2	101	A	2017-03-10 15:50:06.06982	\N	\N	\N	\N
8622	2	101	A	2017-03-10 15:50:06.08081	\N	\N	\N	\N
8623	2	101	A	2017-03-10 15:50:06.09169	\N	\N	\N	\N
8624	2	101	A	2017-03-10 15:50:06.10284	\N	\N	\N	\N
8625	2	101	A	2017-03-10 15:50:06.11356	\N	\N	\N	\N
8626	2	101	A	2017-03-10 15:50:06.12476	\N	\N	\N	\N
8627	2	101	A	2017-03-10 15:50:06.13591	\N	\N	\N	\N
8628	2	101	A	2017-03-10 15:50:06.14683	\N	\N	\N	\N
8629	2	101	A	2017-03-10 15:50:06.15774	\N	\N	\N	\N
8630	2	101	A	2017-03-10 15:50:06.16895	\N	\N	\N	\N
8631	2	101	A	2017-03-10 15:50:06.17987	\N	\N	\N	\N
8632	2	101	A	2017-03-10 15:50:06.19116	\N	\N	\N	\N
8633	2	101	A	2017-03-10 15:50:06.2018	\N	\N	\N	\N
8634	2	101	A	2017-03-10 15:50:06.21281	\N	\N	\N	\N
8635	2	101	A	2017-03-10 15:50:06.22403	\N	\N	\N	\N
8636	2	101	A	2017-03-10 15:50:06.23502	\N	\N	\N	\N
8637	2	101	A	2017-03-10 15:50:06.24607	\N	\N	\N	\N
8638	2	101	A	2017-03-10 15:50:06.25667	\N	\N	\N	\N
8639	2	101	A	2017-03-10 15:50:06.26806	\N	\N	\N	\N
8640	2	101	A	2017-03-10 15:50:06.27871	\N	\N	\N	\N
8641	2	101	A	2017-03-10 15:50:06.28974	\N	\N	\N	\N
8642	2	101	A	2017-03-10 15:50:06.30101	\N	\N	\N	\N
8643	2	101	A	2017-03-10 15:50:06.31155	\N	\N	\N	\N
8644	2	101	A	2017-03-10 15:50:06.32275	\N	\N	\N	\N
8645	2	101	A	2017-03-10 15:50:06.33411	\N	\N	\N	\N
8646	2	101	A	2017-03-10 15:50:06.34511	\N	\N	\N	\N
8647	2	101	A	2017-03-10 15:50:06.35569	\N	\N	\N	\N
8648	2	101	A	2017-03-10 15:50:06.36704	\N	\N	\N	\N
8649	2	101	A	2017-03-10 15:50:06.37784	\N	\N	\N	\N
8650	2	101	A	2017-03-10 15:50:06.38899	\N	\N	\N	\N
8651	2	101	A	2017-03-10 15:50:06.40064	\N	\N	\N	\N
8652	2	101	A	2017-03-10 15:50:06.41102	\N	\N	\N	\N
8653	2	101	A	2017-03-10 15:50:06.42157	\N	\N	\N	\N
8654	2	101	A	2017-03-10 15:50:06.43247	\N	\N	\N	\N
8655	2	101	A	2017-03-10 15:50:06.44381	\N	\N	\N	\N
8656	2	101	A	2017-03-10 15:50:06.45491	\N	\N	\N	\N
8657	2	101	A	2017-03-10 15:50:06.46563	\N	\N	\N	\N
8658	2	101	A	2017-03-10 15:50:06.47686	\N	\N	\N	\N
8659	2	101	A	2017-03-10 15:50:06.48799	\N	\N	\N	\N
8660	2	101	A	2017-03-10 15:50:06.49869	\N	\N	\N	\N
8661	2	101	A	2017-03-10 15:50:06.50969	\N	\N	\N	\N
8662	2	101	A	2017-03-10 15:50:06.52133	\N	\N	\N	\N
8663	2	101	A	2017-03-10 15:50:06.53198	\N	\N	\N	\N
8664	2	101	A	2017-03-10 15:50:06.54273	\N	\N	\N	\N
8665	2	101	A	2017-03-10 15:50:06.55372	\N	\N	\N	\N
8666	2	101	A	2017-03-10 15:50:06.56457	\N	\N	\N	\N
8667	2	101	A	2017-03-10 15:50:06.57595	\N	\N	\N	\N
8668	2	101	A	2017-03-10 15:50:06.58713	\N	\N	\N	\N
8669	2	101	A	2017-03-10 15:50:06.59811	\N	\N	\N	\N
8670	2	101	A	2017-03-10 15:50:06.60945	\N	\N	\N	\N
8671	2	101	A	2017-03-10 15:50:06.62013	\N	\N	\N	\N
8672	2	101	A	2017-03-10 15:50:06.63156	\N	\N	\N	\N
8673	2	101	A	2017-03-10 15:50:06.64253	\N	\N	\N	\N
8674	2	101	A	2017-03-10 15:50:06.65295	\N	\N	\N	\N
8675	2	101	A	2017-03-10 15:50:06.66416	\N	\N	\N	\N
8676	2	101	A	2017-03-10 15:50:06.67478	\N	\N	\N	\N
8677	2	101	A	2017-03-10 15:50:06.68531	\N	\N	\N	\N
8678	2	101	A	2017-03-10 15:50:06.69641	\N	\N	\N	\N
8679	2	101	A	2017-03-10 15:50:06.70725	\N	\N	\N	\N
8680	2	101	A	2017-03-10 15:50:06.71834	\N	\N	\N	\N
8681	2	101	A	2017-03-10 15:50:06.72959	\N	\N	\N	\N
8682	2	101	A	2017-03-10 15:50:06.74105	\N	\N	\N	\N
8683	2	101	A	2017-03-10 15:50:06.75191	\N	\N	\N	\N
8684	2	101	A	2017-03-10 15:50:06.76296	\N	\N	\N	\N
8685	2	101	A	2017-03-10 15:50:06.77405	\N	\N	\N	\N
8686	2	101	A	2017-03-10 15:50:06.78558	\N	\N	\N	\N
8687	2	101	A	2017-03-10 15:50:06.79654	\N	\N	\N	\N
8688	2	101	A	2017-03-10 15:50:06.80696	\N	\N	\N	\N
8689	2	101	A	2017-03-10 15:50:06.81825	\N	\N	\N	\N
8690	2	101	A	2017-03-10 15:50:06.82895	\N	\N	\N	\N
8691	2	101	A	2017-03-10 15:50:06.83986	\N	\N	\N	\N
8692	2	101	A	2017-03-10 15:50:06.85099	\N	\N	\N	\N
8693	2	101	A	2017-03-10 15:50:06.86235	\N	\N	\N	\N
8694	2	101	A	2017-03-10 15:50:06.87297	\N	\N	\N	\N
8695	2	101	A	2017-03-10 15:50:06.88417	\N	\N	\N	\N
8696	2	101	A	2017-03-10 15:50:06.89513	\N	\N	\N	\N
8697	2	101	A	2017-03-10 15:50:06.90633	\N	\N	\N	\N
8698	2	101	A	2017-03-10 15:50:06.91722	\N	\N	\N	\N
8699	2	101	A	2017-03-10 15:50:06.92857	\N	\N	\N	\N
8700	2	101	A	2017-03-10 15:50:06.93916	\N	\N	\N	\N
8701	2	101	A	2017-03-10 15:57:50.59122	\N	\N	\N	\N
8702	2	101	A	2017-03-10 15:57:50.61225	\N	\N	\N	\N
8703	2	101	A	2017-03-10 15:57:50.62394	\N	\N	\N	\N
8704	2	101	A	2017-03-10 15:57:50.63484	\N	\N	\N	\N
8705	2	101	A	2017-03-10 15:57:50.64588	\N	\N	\N	\N
8706	2	101	A	2017-03-10 15:57:50.65677	\N	\N	\N	\N
8707	2	101	A	2017-03-10 15:57:50.66808	\N	\N	\N	\N
8708	2	101	A	2017-03-10 15:57:50.67904	\N	\N	\N	\N
8709	2	101	A	2017-03-10 15:57:50.68938	\N	\N	\N	\N
8710	2	101	A	2017-03-10 15:57:50.70142	\N	\N	\N	\N
8711	2	101	A	2017-03-10 15:57:50.71254	\N	\N	\N	\N
8712	2	101	A	2017-03-10 15:57:50.72311	\N	\N	\N	\N
8713	2	101	A	2017-03-10 15:57:50.73441	\N	\N	\N	\N
8714	2	101	A	2017-03-10 15:57:50.74515	\N	\N	\N	\N
8715	2	101	A	2017-03-10 15:57:50.75601	\N	\N	\N	\N
8716	2	101	A	2017-03-10 15:57:50.76722	\N	\N	\N	\N
8717	2	101	A	2017-03-10 15:57:50.77847	\N	\N	\N	\N
8718	2	101	A	2017-03-10 15:57:50.78904	\N	\N	\N	\N
8719	2	101	A	2017-03-10 15:57:50.80042	\N	\N	\N	\N
8720	2	101	A	2017-03-10 15:57:50.81135	\N	\N	\N	\N
8721	2	101	A	2017-03-10 15:57:50.82193	\N	\N	\N	\N
8722	2	101	A	2017-03-10 15:57:50.83322	\N	\N	\N	\N
8723	2	101	A	2017-03-10 15:57:50.84449	\N	\N	\N	\N
8724	2	101	A	2017-03-10 15:57:50.85497	\N	\N	\N	\N
8725	2	101	A	2017-03-10 15:57:50.86658	\N	\N	\N	\N
8726	2	101	A	2017-03-10 15:57:50.8777	\N	\N	\N	\N
8727	2	101	A	2017-03-10 15:57:50.88856	\N	\N	\N	\N
8728	2	101	A	2017-03-10 15:57:50.89939	\N	\N	\N	\N
8729	2	101	A	2017-03-10 15:57:50.91066	\N	\N	\N	\N
8730	2	101	A	2017-03-10 15:57:50.92159	\N	\N	\N	\N
8731	2	101	A	2017-03-10 15:57:50.93264	\N	\N	\N	\N
8732	2	101	A	2017-03-10 15:57:50.94354	\N	\N	\N	\N
8733	2	101	A	2017-03-10 15:57:50.9544	\N	\N	\N	\N
8734	2	101	A	2017-03-10 15:57:50.96569	\N	\N	\N	\N
8735	2	101	A	2017-03-10 15:57:50.97666	\N	\N	\N	\N
8736	2	101	A	2017-03-10 15:57:50.98737	\N	\N	\N	\N
8737	2	101	A	2017-03-10 15:57:50.99838	\N	\N	\N	\N
8738	2	101	A	2017-03-10 15:57:51.0097	\N	\N	\N	\N
8739	2	101	A	2017-03-10 15:57:51.02065	\N	\N	\N	\N
8740	2	101	A	2017-03-10 15:57:51.03108	\N	\N	\N	\N
8741	2	101	A	2017-03-10 15:57:51.0427	\N	\N	\N	\N
8742	2	101	A	2017-03-10 15:57:51.05379	\N	\N	\N	\N
8743	2	101	A	2017-03-10 15:57:51.06471	\N	\N	\N	\N
8744	2	101	A	2017-03-10 15:57:51.07555	\N	\N	\N	\N
8745	2	101	A	2017-03-10 15:57:51.08629	\N	\N	\N	\N
8746	2	101	A	2017-03-10 15:57:51.09753	\N	\N	\N	\N
8747	2	101	A	2017-03-10 15:57:51.10875	\N	\N	\N	\N
8748	2	101	A	2017-03-10 15:57:51.12262	\N	\N	\N	\N
8749	2	101	A	2017-03-10 15:57:51.1299	\N	\N	\N	\N
8750	2	101	A	2017-03-10 15:57:51.14074	\N	\N	\N	\N
8751	2	101	A	2017-03-10 15:57:51.15254	\N	\N	\N	\N
8752	2	101	A	2017-03-10 15:57:51.16353	\N	\N	\N	\N
8753	2	101	A	2017-03-10 15:57:51.17457	\N	\N	\N	\N
8754	2	101	A	2017-03-10 15:57:51.18575	\N	\N	\N	\N
8755	2	101	A	2017-03-10 15:57:51.23034	\N	\N	\N	\N
8756	2	101	A	2017-03-10 15:57:51.27445	\N	\N	\N	\N
8757	2	101	A	2017-03-10 15:57:51.28455	\N	\N	\N	\N
8758	2	101	A	2017-03-10 15:57:51.29674	\N	\N	\N	\N
8759	2	101	A	2017-03-10 15:57:51.30692	\N	\N	\N	\N
8760	2	101	A	2017-03-10 15:57:51.31798	\N	\N	\N	\N
8761	2	101	A	2017-03-10 15:57:51.32864	\N	\N	\N	\N
8762	2	101	A	2017-03-10 15:57:51.33888	\N	\N	\N	\N
8763	2	101	A	2017-03-10 15:57:51.35018	\N	\N	\N	\N
8764	2	101	A	2017-03-10 15:57:51.36195	\N	\N	\N	\N
8765	2	101	A	2017-03-10 15:57:51.37265	\N	\N	\N	\N
8766	2	101	A	2017-03-10 15:57:51.3836	\N	\N	\N	\N
8767	2	101	A	2017-03-10 15:57:51.39495	\N	\N	\N	\N
8768	2	101	A	2017-03-10 15:57:51.40565	\N	\N	\N	\N
8769	2	101	A	2017-03-10 15:57:51.41699	\N	\N	\N	\N
8770	2	101	A	2017-03-10 15:57:51.42881	\N	\N	\N	\N
8771	2	101	A	2017-03-10 15:57:51.43884	\N	\N	\N	\N
8772	2	101	A	2017-03-10 15:57:51.44969	\N	\N	\N	\N
8773	2	101	A	2017-03-10 15:57:51.46085	\N	\N	\N	\N
8774	2	101	A	2017-03-10 15:57:51.47197	\N	\N	\N	\N
8775	2	101	A	2017-03-10 15:57:51.4827	\N	\N	\N	\N
8776	2	101	A	2017-03-10 15:57:51.49516	\N	\N	\N	\N
8777	2	101	A	2017-03-10 15:57:51.50534	\N	\N	\N	\N
8778	2	101	A	2017-03-10 15:57:51.51615	\N	\N	\N	\N
8779	2	101	A	2017-03-10 15:57:51.52765	\N	\N	\N	\N
8780	2	101	A	2017-03-10 15:57:51.5384	\N	\N	\N	\N
8781	2	101	A	2017-03-10 15:57:51.54856	\N	\N	\N	\N
8782	2	101	A	2017-03-10 15:57:51.55951	\N	\N	\N	\N
8783	2	101	A	2017-03-10 15:57:51.57083	\N	\N	\N	\N
8784	2	101	A	2017-03-10 15:57:51.58196	\N	\N	\N	\N
8785	2	101	A	2017-03-10 15:57:51.59263	\N	\N	\N	\N
8786	2	101	A	2017-03-10 15:57:51.60387	\N	\N	\N	\N
8787	2	101	A	2017-03-10 15:57:51.61518	\N	\N	\N	\N
8788	2	101	A	2017-03-10 15:57:51.62503	\N	\N	\N	\N
8789	2	101	A	2017-03-10 15:57:51.63588	\N	\N	\N	\N
8790	2	101	A	2017-03-10 15:57:51.64691	\N	\N	\N	\N
8791	2	101	A	2017-03-10 15:57:51.65795	\N	\N	\N	\N
8792	2	101	A	2017-03-10 15:57:51.66996	\N	\N	\N	\N
8793	2	101	A	2017-03-10 15:57:51.68132	\N	\N	\N	\N
8794	2	101	A	2017-03-10 15:57:51.69228	\N	\N	\N	\N
8795	2	101	A	2017-03-10 15:57:51.70292	\N	\N	\N	\N
8796	2	101	A	2017-03-10 15:57:51.71395	\N	\N	\N	\N
8797	2	101	A	2017-03-10 15:57:51.72465	\N	\N	\N	\N
8798	2	101	A	2017-03-10 15:57:51.7359	\N	\N	\N	\N
8799	2	101	A	2017-03-10 15:57:51.74709	\N	\N	\N	\N
8800	2	101	A	2017-03-10 15:57:51.75769	\N	\N	\N	\N
8801	2	101	A	2017-03-10 15:58:24.90898	\N	\N	\N	\N
8802	2	101	A	2017-03-10 15:58:24.91521	\N	\N	\N	\N
8803	2	101	A	2017-03-10 15:58:24.92654	\N	\N	\N	\N
8804	2	101	A	2017-03-10 15:58:24.938	\N	\N	\N	\N
8805	2	101	A	2017-03-10 15:58:24.94957	\N	\N	\N	\N
8806	2	101	A	2017-03-10 15:58:24.96043	\N	\N	\N	\N
8807	2	101	A	2017-03-10 15:58:24.97123	\N	\N	\N	\N
8808	2	101	A	2017-03-10 15:58:24.98251	\N	\N	\N	\N
8809	2	101	A	2017-03-10 15:58:24.99343	\N	\N	\N	\N
8810	2	101	A	2017-03-10 15:58:25.00462	\N	\N	\N	\N
8811	2	101	A	2017-03-10 15:58:25.01531	\N	\N	\N	\N
8812	2	101	A	2017-03-10 15:58:25.02629	\N	\N	\N	\N
8813	2	101	A	2017-03-10 15:58:25.03722	\N	\N	\N	\N
8814	2	101	A	2017-03-10 15:58:25.04828	\N	\N	\N	\N
8815	2	101	A	2017-03-10 15:58:25.06026	\N	\N	\N	\N
8816	2	101	A	2017-03-10 15:58:25.0694	\N	\N	\N	\N
8817	2	101	A	2017-03-10 15:58:25.08063	\N	\N	\N	\N
8818	2	101	A	2017-03-10 15:58:25.09147	\N	\N	\N	\N
8819	2	101	A	2017-03-10 15:58:25.10351	\N	\N	\N	\N
8820	2	101	A	2017-03-10 15:58:25.1148	\N	\N	\N	\N
8821	2	101	A	2017-03-10 15:58:25.12572	\N	\N	\N	\N
8822	2	101	A	2017-03-10 15:58:25.13683	\N	\N	\N	\N
8823	2	101	A	2017-03-10 15:58:25.14767	\N	\N	\N	\N
8824	2	101	A	2017-03-10 15:58:25.15966	\N	\N	\N	\N
8825	2	101	A	2017-03-10 15:58:25.16989	\N	\N	\N	\N
8826	2	101	A	2017-03-10 15:58:25.18021	\N	\N	\N	\N
8827	2	101	A	2017-03-10 15:58:25.19169	\N	\N	\N	\N
8828	2	101	A	2017-03-10 15:58:25.20259	\N	\N	\N	\N
8829	2	101	A	2017-03-10 15:58:25.21319	\N	\N	\N	\N
8830	2	101	A	2017-03-10 15:58:25.22469	\N	\N	\N	\N
8831	2	101	A	2017-03-10 15:58:25.23664	\N	\N	\N	\N
8832	2	101	A	2017-03-10 15:58:25.24663	\N	\N	\N	\N
8833	2	101	A	2017-03-10 15:58:25.25681	\N	\N	\N	\N
8834	2	101	A	2017-03-10 15:58:25.26866	\N	\N	\N	\N
8835	2	101	A	2017-03-10 15:58:25.27978	\N	\N	\N	\N
8836	2	101	A	2017-03-10 15:58:25.29101	\N	\N	\N	\N
8837	2	101	A	2017-03-10 15:58:25.30192	\N	\N	\N	\N
8838	2	101	A	2017-03-10 15:58:25.31254	\N	\N	\N	\N
8839	2	101	A	2017-03-10 15:58:25.32344	\N	\N	\N	\N
8840	2	101	A	2017-03-10 15:58:25.33561	\N	\N	\N	\N
8841	2	101	A	2017-03-10 15:58:25.34664	\N	\N	\N	\N
8842	2	101	A	2017-03-10 15:58:25.35583	\N	\N	\N	\N
8843	2	101	A	2017-03-10 15:58:25.3671	\N	\N	\N	\N
8844	2	101	A	2017-03-10 15:58:25.37803	\N	\N	\N	\N
8845	2	101	A	2017-03-10 15:58:25.38895	\N	\N	\N	\N
8846	2	101	A	2017-03-10 15:58:25.40074	\N	\N	\N	\N
8847	2	101	A	2017-03-10 15:58:25.41211	\N	\N	\N	\N
8848	2	101	A	2017-03-10 15:58:25.42301	\N	\N	\N	\N
8849	2	101	A	2017-03-10 15:58:25.43415	\N	\N	\N	\N
8850	2	101	A	2017-03-10 15:58:25.44448	\N	\N	\N	\N
8851	2	101	A	2017-03-10 15:58:25.45555	\N	\N	\N	\N
8852	2	101	A	2017-03-10 15:58:25.46573	\N	\N	\N	\N
8853	2	101	A	2017-03-10 15:58:25.47688	\N	\N	\N	\N
8854	2	101	A	2017-03-10 15:58:25.4879	\N	\N	\N	\N
8855	2	101	A	2017-03-10 15:58:25.49973	\N	\N	\N	\N
8856	2	101	A	2017-03-10 15:58:25.51056	\N	\N	\N	\N
8857	2	101	A	2017-03-10 15:58:25.52208	\N	\N	\N	\N
8858	2	101	A	2017-03-10 15:58:25.53282	\N	\N	\N	\N
8859	2	101	A	2017-03-10 15:58:25.54384	\N	\N	\N	\N
8860	2	101	A	2017-03-10 15:58:25.55495	\N	\N	\N	\N
8861	2	101	A	2017-03-10 15:58:25.56573	\N	\N	\N	\N
8862	2	101	A	2017-03-10 15:58:25.57677	\N	\N	\N	\N
8863	2	101	A	2017-03-10 15:58:25.58776	\N	\N	\N	\N
8864	2	101	A	2017-03-10 15:58:25.59872	\N	\N	\N	\N
8865	2	101	A	2017-03-10 15:58:25.61021	\N	\N	\N	\N
8866	2	101	A	2017-03-10 15:58:25.62026	\N	\N	\N	\N
8867	2	101	A	2017-03-10 15:58:25.63106	\N	\N	\N	\N
8868	2	101	A	2017-03-10 15:58:25.64213	\N	\N	\N	\N
8869	2	101	A	2017-03-10 15:58:25.65317	\N	\N	\N	\N
8870	2	101	A	2017-03-10 15:58:25.6643	\N	\N	\N	\N
8871	2	101	A	2017-03-10 15:58:25.67521	\N	\N	\N	\N
8872	2	101	A	2017-03-10 15:58:25.6873	\N	\N	\N	\N
8873	2	101	A	2017-03-10 15:58:25.69867	\N	\N	\N	\N
8874	2	101	A	2017-03-10 15:58:25.70899	\N	\N	\N	\N
8875	2	101	A	2017-03-10 15:58:25.72031	\N	\N	\N	\N
8876	2	101	A	2017-03-10 15:58:25.7312	\N	\N	\N	\N
8877	2	101	A	2017-03-10 15:58:25.74201	\N	\N	\N	\N
8878	2	101	A	2017-03-10 15:58:25.75314	\N	\N	\N	\N
8879	2	101	A	2017-03-10 15:58:25.76411	\N	\N	\N	\N
8880	2	101	A	2017-03-10 15:58:25.77522	\N	\N	\N	\N
8881	2	101	A	2017-03-10 15:58:25.78605	\N	\N	\N	\N
8882	2	101	A	2017-03-10 15:58:25.79704	\N	\N	\N	\N
8883	2	101	A	2017-03-10 15:58:25.80836	\N	\N	\N	\N
8884	2	101	A	2017-03-10 15:58:25.8191	\N	\N	\N	\N
8885	2	101	A	2017-03-10 15:58:25.83034	\N	\N	\N	\N
8886	2	101	A	2017-03-10 15:58:25.84137	\N	\N	\N	\N
8887	2	101	A	2017-03-10 15:58:25.85232	\N	\N	\N	\N
8888	2	101	A	2017-03-10 15:58:25.86332	\N	\N	\N	\N
8889	2	101	A	2017-03-10 15:58:25.87439	\N	\N	\N	\N
8890	2	101	A	2017-03-10 15:58:25.88544	\N	\N	\N	\N
8891	2	101	A	2017-03-10 15:58:25.8962	\N	\N	\N	\N
8892	2	101	A	2017-03-10 15:58:25.90694	\N	\N	\N	\N
8893	2	101	A	2017-03-10 15:58:25.91837	\N	\N	\N	\N
8894	2	101	A	2017-03-10 15:58:25.92894	\N	\N	\N	\N
8895	2	101	A	2017-03-10 15:58:25.93987	\N	\N	\N	\N
8896	2	101	A	2017-03-10 15:58:25.95118	\N	\N	\N	\N
8897	2	101	A	2017-03-10 15:58:25.96197	\N	\N	\N	\N
8898	2	101	A	2017-03-10 15:58:25.97335	\N	\N	\N	\N
8899	2	101	A	2017-03-10 15:58:25.98455	\N	\N	\N	\N
8900	2	101	A	2017-03-10 15:58:25.99527	\N	\N	\N	\N
\.


--
-- TOC entry 2141 (class 0 OID 17072)
-- Dependencies: 194
-- Data for Name: t_stamps_transactions; Type: TABLE DATA; Schema: ds; Owner: sbadmin
--

COPY t_stamps_transactions (transaction_id, transaction_date, customer_id, transaction_code, amount, stamp_id, email_id, description) FROM stdin;
41	2017-03-10 15:48:48.945851	101	SCR  	100.000	\N	\N	Free stamps for joining
42	2017-03-10 15:50:06.950296	101	SCR  	100.000	\N	\N	Paypal transaction: 5MN70213L65198307
43	2017-03-10 15:57:51.7693	101	SCR  	100.000	\N	\N	Paypal transaction: 0JR08459YK319682R
44	2017-03-10 15:58:26.006236	101	SCR  	100.000	\N	\N	Paypal transaction: 3C750250N82751139
\.


--
-- TOC entry 2185 (class 0 OID 0)
-- Dependencies: 193
-- Name: t_stamps_transactions_transaction_id_seq; Type: SEQUENCE SET; Schema: ds; Owner: sbadmin
--

SELECT pg_catalog.setval('t_stamps_transactions_transaction_id_seq', 44, true);


--
-- TOC entry 2131 (class 0 OID 16802)
-- Dependencies: 183
-- Data for Name: t_whitelist; Type: TABLE DATA; Schema: ds; Owner: raulr
--

COPY t_whitelist (e_mail, customer_id) FROM stdin;
\.


SET search_path = public, pg_catalog;

--
-- TOC entry 2136 (class 0 OID 16868)
-- Dependencies: 189
-- Data for Name: websessions; Type: TABLE DATA; Schema: public; Owner: sbweb
--

COPY websessions (id, expire, data) FROM stdin;
26ehajd1u3196b7le0cv9ufs50      	1501587719	\\x
\.


SET search_path = ds, pg_catalog;

--
-- TOC entry 1963 (class 2606 OID 16703)
-- Name: UC_USERNAME; Type: CONSTRAINT; Schema: ds; Owner: raulr; Tablespace: 
--

ALTER TABLE ONLY t_customer
    ADD CONSTRAINT "UC_USERNAME" UNIQUE (username);


--
-- TOC entry 1969 (class 2606 OID 16709)
-- Name: pk_customer_account; Type: CONSTRAINT; Schema: ds; Owner: raulr; Tablespace: 
--

ALTER TABLE ONLY t_account
    ADD CONSTRAINT pk_customer_account PRIMARY KEY (customer_id);


--
-- TOC entry 1988 (class 2606 OID 16824)
-- Name: pk_customer_cart; Type: CONSTRAINT; Schema: ds; Owner: raulr; Tablespace: 
--

ALTER TABLE ONLY t_shoppingcart
    ADD CONSTRAINT pk_customer_cart PRIMARY KEY (customer_id);


--
-- TOC entry 1972 (class 2606 OID 16722)
-- Name: pk_customer_mailbox; Type: CONSTRAINT; Schema: ds; Owner: raulr; Tablespace: 
--

ALTER TABLE ONLY t_customer_mailbox
    ADD CONSTRAINT pk_customer_mailbox PRIMARY KEY (e_mail);


--
-- TOC entry 2001 (class 2606 OID 17105)
-- Name: pk_ingored_emails; Type: CONSTRAINT; Schema: ds; Owner: sbadmin; Tablespace: 
--

ALTER TABLE ONLY t_ignored_emailaddresses
    ADD CONSTRAINT pk_ingored_emails PRIMARY KEY (e_mail);


--
-- TOC entry 1978 (class 2606 OID 16751)
-- Name: pk_invitations; Type: CONSTRAINT; Schema: ds; Owner: raulr; Tablespace: 
--

ALTER TABLE ONLY t_invitations
    ADD CONSTRAINT pk_invitations PRIMARY KEY (customer_id, invited_email);


--
-- TOC entry 1974 (class 2606 OID 16736)
-- Name: pk_mailbox_config; Type: CONSTRAINT; Schema: ds; Owner: raulr; Tablespace: 
--

ALTER TABLE ONLY t_mailbox_config
    ADD CONSTRAINT pk_mailbox_config PRIMARY KEY (maildomain);


--
-- TOC entry 1995 (class 2606 OID 16967)
-- Name: pk_messages; Type: CONSTRAINT; Schema: ds; Owner: sbadmin; Tablespace: 
--

ALTER TABLE ONLY t_messages
    ADD CONSTRAINT pk_messages PRIMARY KEY (message_id);


--
-- TOC entry 1986 (class 2606 OID 16814)
-- Name: pk_offers; Type: CONSTRAINT; Schema: ds; Owner: raulr; Tablespace: 
--

ALTER TABLE ONLY t_shop_offers
    ADD CONSTRAINT pk_offers PRIMARY KEY (offer_id);


--
-- TOC entry 1976 (class 2606 OID 16741)
-- Name: pk_passwd_reset; Type: CONSTRAINT; Schema: ds; Owner: raulr; Tablespace: 
--

ALTER TABLE ONLY t_passwdresets
    ADD CONSTRAINT pk_passwd_reset PRIMARY KEY (customer_id);


--
-- TOC entry 1999 (class 2606 OID 17098)
-- Name: pk_processed_emails; Type: CONSTRAINT; Schema: ds; Owner: raulr; Tablespace: 
--

ALTER TABLE ONLY t_processed_emails
    ADD CONSTRAINT pk_processed_emails PRIMARY KEY (customer_id, e_mail, email_id);


--
-- TOC entry 1990 (class 2606 OID 16829)
-- Name: pk_processing_tasks; Type: CONSTRAINT; Schema: ds; Owner: raulr; Tablespace: 
--

ALTER TABLE ONLY t_processing
    ADD CONSTRAINT pk_processing_tasks PRIMARY KEY (customer_id, task_id);


--
-- TOC entry 1997 (class 2606 OID 17080)
-- Name: pk_transaction_id; Type: CONSTRAINT; Schema: ds; Owner: sbadmin; Tablespace: 
--

ALTER TABLE ONLY t_stamps_transactions
    ADD CONSTRAINT pk_transaction_id PRIMARY KEY (transaction_id);


--
-- TOC entry 1984 (class 2606 OID 16806)
-- Name: pk_whitelist_email; Type: CONSTRAINT; Schema: ds; Owner: raulr; Tablespace: 
--

ALTER TABLE ONLY t_whitelist
    ADD CONSTRAINT pk_whitelist_email PRIMARY KEY (e_mail, customer_id);


--
-- TOC entry 1966 (class 2606 OID 16701)
-- Name: t_customer_pkey; Type: CONSTRAINT; Schema: ds; Owner: raulr; Tablespace: 
--

ALTER TABLE ONLY t_customer
    ADD CONSTRAINT t_customer_pkey PRIMARY KEY (customer_id);


--
-- TOC entry 1980 (class 2606 OID 16764)
-- Name: t_stamp_definition_pkey; Type: CONSTRAINT; Schema: ds; Owner: raulr; Tablespace: 
--

ALTER TABLE ONLY t_stamp_definition
    ADD CONSTRAINT t_stamp_definition_pkey PRIMARY KEY (batch_id);


--
-- TOC entry 1982 (class 2606 OID 16780)
-- Name: t_stamps_issued_pkey; Type: CONSTRAINT; Schema: ds; Owner: raulr; Tablespace: 
--

ALTER TABLE ONLY t_stamps_issued
    ADD CONSTRAINT t_stamps_issued_pkey PRIMARY KEY (stamp_id);


SET search_path = public, pg_catalog;

--
-- TOC entry 1992 (class 2606 OID 16875)
-- Name: websessions_pkey; Type: CONSTRAINT; Schema: public; Owner: sbweb; Tablespace: 
--

ALTER TABLE ONLY websessions
    ADD CONSTRAINT websessions_pkey PRIMARY KEY (id);


SET search_path = ds, pg_catalog;

--
-- TOC entry 1970 (class 1259 OID 16728)
-- Name: e_mail_idx; Type: INDEX; Schema: ds; Owner: raulr; Tablespace: 
--

CREATE UNIQUE INDEX e_mail_idx ON t_customer_mailbox USING btree (e_mail);


--
-- TOC entry 1967 (class 1259 OID 17000)
-- Name: idx_account_customer_id; Type: INDEX; Schema: ds; Owner: raulr; Tablespace: 
--

CREATE INDEX idx_account_customer_id ON t_account USING btree (customer_id);


--
-- TOC entry 1964 (class 1259 OID 16704)
-- Name: idx_customer_username; Type: INDEX; Schema: ds; Owner: raulr; Tablespace: 
--

CREATE UNIQUE INDEX idx_customer_username ON t_customer USING btree (username);


--
-- TOC entry 1993 (class 1259 OID 16999)
-- Name: idx_log_line; Type: INDEX; Schema: ds; Owner: sbadmin; Tablespace: 
--

CREATE INDEX idx_log_line ON t_log_line USING btree (log_customer_id, log_datetime);


--
-- TOC entry 2003 (class 2606 OID 16723)
-- Name: FK_t_customer_mailbox; Type: FK CONSTRAINT; Schema: ds; Owner: raulr
--

ALTER TABLE ONLY t_customer_mailbox
    ADD CONSTRAINT "FK_t_customer_mailbox" FOREIGN KEY (customer_id) REFERENCES t_customer(customer_id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 2006 (class 2606 OID 16765)
-- Name: FK_t_stamp_definition_customer; Type: FK CONSTRAINT; Schema: ds; Owner: raulr
--

ALTER TABLE ONLY t_stamp_definition
    ADD CONSTRAINT "FK_t_stamp_definition_customer" FOREIGN KEY (changed_by) REFERENCES t_customer(customer_id);


--
-- TOC entry 2007 (class 2606 OID 16781)
-- Name: FK_t_stamps_batch; Type: FK CONSTRAINT; Schema: ds; Owner: raulr
--

ALTER TABLE ONLY t_stamps_issued
    ADD CONSTRAINT "FK_t_stamps_batch" FOREIGN KEY (batch_id) REFERENCES t_stamp_definition(batch_id);


--
-- TOC entry 2008 (class 2606 OID 16786)
-- Name: FK_t_stamps_issued; Type: FK CONSTRAINT; Schema: ds; Owner: raulr
--

ALTER TABLE ONLY t_stamps_issued
    ADD CONSTRAINT "FK_t_stamps_issued" FOREIGN KEY (customer_id) REFERENCES t_customer(customer_id);


--
-- TOC entry 2002 (class 2606 OID 16710)
-- Name: fk_customer_account; Type: FK CONSTRAINT; Schema: ds; Owner: raulr
--

ALTER TABLE ONLY t_account
    ADD CONSTRAINT fk_customer_account FOREIGN KEY (customer_id) REFERENCES t_customer(customer_id);


--
-- TOC entry 2010 (class 2606 OID 16968)
-- Name: fk_customer_message; Type: FK CONSTRAINT; Schema: ds; Owner: sbadmin
--

ALTER TABLE ONLY t_messages
    ADD CONSTRAINT fk_customer_message FOREIGN KEY (customer_id) REFERENCES t_customer(customer_id);


--
-- TOC entry 2005 (class 2606 OID 16752)
-- Name: fk_invitations_t_customer; Type: FK CONSTRAINT; Schema: ds; Owner: raulr
--

ALTER TABLE ONLY t_invitations
    ADD CONSTRAINT fk_invitations_t_customer FOREIGN KEY (customer_id) REFERENCES t_customer(customer_id);


--
-- TOC entry 2004 (class 2606 OID 16742)
-- Name: fk_passwd_reset_customer; Type: FK CONSTRAINT; Schema: ds; Owner: raulr
--

ALTER TABLE ONLY t_passwdresets
    ADD CONSTRAINT fk_passwd_reset_customer FOREIGN KEY (customer_id) REFERENCES t_customer(customer_id);


--
-- TOC entry 2009 (class 2606 OID 16815)
-- Name: fk_stamp_batch; Type: FK CONSTRAINT; Schema: ds; Owner: raulr
--

ALTER TABLE ONLY t_shop_offers
    ADD CONSTRAINT fk_stamp_batch FOREIGN KEY (batch_id) REFERENCES t_stamp_definition(batch_id);


--
-- TOC entry 2149 (class 0 OID 0)
-- Dependencies: 8
-- Name: ds; Type: ACL; Schema: -; Owner: sbadmin
--

REVOKE ALL ON SCHEMA ds FROM PUBLIC;
REVOKE ALL ON SCHEMA ds FROM sbadmin;
GRANT ALL ON SCHEMA ds TO sbadmin;
GRANT USAGE ON SCHEMA ds TO sbweb;


--
-- TOC entry 2151 (class 0 OID 0)
-- Dependencies: 6
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- TOC entry 2153 (class 0 OID 0)
-- Dependencies: 210
-- Name: clear_data(); Type: ACL; Schema: ds; Owner: sbadmin
--

REVOKE ALL ON FUNCTION clear_data() FROM PUBLIC;
REVOKE ALL ON FUNCTION clear_data() FROM sbadmin;
GRANT ALL ON FUNCTION clear_data() TO sbadmin;
GRANT ALL ON FUNCTION clear_data() TO sbweb;


--
-- TOC entry 2154 (class 0 OID 0)
-- Dependencies: 174
-- Name: t_account; Type: ACL; Schema: ds; Owner: raulr
--

REVOKE ALL ON TABLE t_account FROM PUBLIC;
REVOKE ALL ON TABLE t_account FROM raulr;
GRANT ALL ON TABLE t_account TO raulr;
GRANT ALL ON TABLE t_account TO sbweb;
GRANT ALL ON TABLE t_account TO sbadmin;


--
-- TOC entry 2155 (class 0 OID 0)
-- Dependencies: 172
-- Name: t_customer_customer_id_seq; Type: ACL; Schema: ds; Owner: raulr
--

REVOKE ALL ON SEQUENCE t_customer_customer_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE t_customer_customer_id_seq FROM raulr;
GRANT ALL ON SEQUENCE t_customer_customer_id_seq TO raulr;
GRANT ALL ON SEQUENCE t_customer_customer_id_seq TO sbweb;
GRANT ALL ON SEQUENCE t_customer_customer_id_seq TO sbadmin;


--
-- TOC entry 2156 (class 0 OID 0)
-- Dependencies: 173
-- Name: t_customer; Type: ACL; Schema: ds; Owner: raulr
--

REVOKE ALL ON TABLE t_customer FROM PUBLIC;
REVOKE ALL ON TABLE t_customer FROM raulr;
GRANT ALL ON TABLE t_customer TO raulr;
GRANT ALL ON TABLE t_customer TO sbweb;
GRANT ALL ON TABLE t_customer TO sbadmin;


--
-- TOC entry 2157 (class 0 OID 0)
-- Dependencies: 175
-- Name: t_customer_mailbox; Type: ACL; Schema: ds; Owner: raulr
--

REVOKE ALL ON TABLE t_customer_mailbox FROM PUBLIC;
REVOKE ALL ON TABLE t_customer_mailbox FROM raulr;
GRANT ALL ON TABLE t_customer_mailbox TO raulr;
GRANT ALL ON TABLE t_customer_mailbox TO sbweb;
GRANT ALL ON TABLE t_customer_mailbox TO sbadmin;


--
-- TOC entry 2158 (class 0 OID 0)
-- Dependencies: 197
-- Name: t_ignored_emailaddresses; Type: ACL; Schema: ds; Owner: sbadmin
--

REVOKE ALL ON TABLE t_ignored_emailaddresses FROM PUBLIC;
REVOKE ALL ON TABLE t_ignored_emailaddresses FROM sbadmin;
GRANT ALL ON TABLE t_ignored_emailaddresses TO sbadmin;
GRANT ALL ON TABLE t_ignored_emailaddresses TO sbweb;


--
-- TOC entry 2159 (class 0 OID 0)
-- Dependencies: 178
-- Name: t_invitations; Type: ACL; Schema: ds; Owner: raulr
--

REVOKE ALL ON TABLE t_invitations FROM PUBLIC;
REVOKE ALL ON TABLE t_invitations FROM raulr;
GRANT ALL ON TABLE t_invitations TO raulr;
GRANT ALL ON TABLE t_invitations TO sbweb;
GRANT ALL ON TABLE t_invitations TO sbadmin;


--
-- TOC entry 2160 (class 0 OID 0)
-- Dependencies: 190
-- Name: t_log_line; Type: ACL; Schema: ds; Owner: sbadmin
--

REVOKE ALL ON TABLE t_log_line FROM PUBLIC;
REVOKE ALL ON TABLE t_log_line FROM sbadmin;
GRANT ALL ON TABLE t_log_line TO sbadmin;
GRANT ALL ON TABLE t_log_line TO sbweb;


--
-- TOC entry 2161 (class 0 OID 0)
-- Dependencies: 176
-- Name: t_mailbox_config; Type: ACL; Schema: ds; Owner: raulr
--

REVOKE ALL ON TABLE t_mailbox_config FROM PUBLIC;
REVOKE ALL ON TABLE t_mailbox_config FROM raulr;
GRANT ALL ON TABLE t_mailbox_config TO raulr;
GRANT ALL ON TABLE t_mailbox_config TO sbweb;
GRANT ALL ON TABLE t_mailbox_config TO sbadmin;


--
-- TOC entry 2162 (class 0 OID 0)
-- Dependencies: 191
-- Name: t_messages_message_id_seq; Type: ACL; Schema: ds; Owner: sbadmin
--

REVOKE ALL ON SEQUENCE t_messages_message_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE t_messages_message_id_seq FROM sbadmin;
GRANT ALL ON SEQUENCE t_messages_message_id_seq TO sbadmin;
GRANT ALL ON SEQUENCE t_messages_message_id_seq TO sbweb;


--
-- TOC entry 2163 (class 0 OID 0)
-- Dependencies: 192
-- Name: t_messages; Type: ACL; Schema: ds; Owner: sbadmin
--

REVOKE ALL ON TABLE t_messages FROM PUBLIC;
REVOKE ALL ON TABLE t_messages FROM sbadmin;
GRANT ALL ON TABLE t_messages TO sbadmin;
GRANT ALL ON TABLE t_messages TO sbweb;


--
-- TOC entry 2164 (class 0 OID 0)
-- Dependencies: 177
-- Name: t_passwdresets; Type: ACL; Schema: ds; Owner: raulr
--

REVOKE ALL ON TABLE t_passwdresets FROM PUBLIC;
REVOKE ALL ON TABLE t_passwdresets FROM raulr;
GRANT ALL ON TABLE t_passwdresets TO raulr;
GRANT ALL ON TABLE t_passwdresets TO sbweb;
GRANT ALL ON TABLE t_passwdresets TO sbadmin;


--
-- TOC entry 2165 (class 0 OID 0)
-- Dependencies: 196
-- Name: t_processed_emails; Type: ACL; Schema: ds; Owner: raulr
--

REVOKE ALL ON TABLE t_processed_emails FROM PUBLIC;
REVOKE ALL ON TABLE t_processed_emails FROM raulr;
GRANT ALL ON TABLE t_processed_emails TO raulr;
GRANT ALL ON TABLE t_processed_emails TO sbweb;


--
-- TOC entry 2166 (class 0 OID 0)
-- Dependencies: 187
-- Name: t_processing; Type: ACL; Schema: ds; Owner: raulr
--

REVOKE ALL ON TABLE t_processing FROM PUBLIC;
REVOKE ALL ON TABLE t_processing FROM raulr;
GRANT ALL ON TABLE t_processing TO raulr;
GRANT ALL ON TABLE t_processing TO sbweb;
GRANT ALL ON TABLE t_processing TO sbadmin;


--
-- TOC entry 2167 (class 0 OID 0)
-- Dependencies: 184
-- Name: t_shop_offers_offer_id_seq; Type: ACL; Schema: ds; Owner: raulr
--

REVOKE ALL ON SEQUENCE t_shop_offers_offer_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE t_shop_offers_offer_id_seq FROM raulr;
GRANT ALL ON SEQUENCE t_shop_offers_offer_id_seq TO raulr;
GRANT ALL ON SEQUENCE t_shop_offers_offer_id_seq TO sbweb;
GRANT ALL ON SEQUENCE t_shop_offers_offer_id_seq TO sbadmin;


--
-- TOC entry 2168 (class 0 OID 0)
-- Dependencies: 185
-- Name: t_shop_offers; Type: ACL; Schema: ds; Owner: raulr
--

REVOKE ALL ON TABLE t_shop_offers FROM PUBLIC;
REVOKE ALL ON TABLE t_shop_offers FROM raulr;
GRANT ALL ON TABLE t_shop_offers TO raulr;
GRANT ALL ON TABLE t_shop_offers TO sbweb;
GRANT ALL ON TABLE t_shop_offers TO sbadmin;


--
-- TOC entry 2169 (class 0 OID 0)
-- Dependencies: 186
-- Name: t_shoppingcart; Type: ACL; Schema: ds; Owner: raulr
--

REVOKE ALL ON TABLE t_shoppingcart FROM PUBLIC;
REVOKE ALL ON TABLE t_shoppingcart FROM raulr;
GRANT ALL ON TABLE t_shoppingcart TO raulr;
GRANT ALL ON TABLE t_shoppingcart TO sbweb;
GRANT ALL ON TABLE t_shoppingcart TO sbadmin;


--
-- TOC entry 2170 (class 0 OID 0)
-- Dependencies: 179
-- Name: t_stamp_definition_batch_id_seq; Type: ACL; Schema: ds; Owner: raulr
--

REVOKE ALL ON SEQUENCE t_stamp_definition_batch_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE t_stamp_definition_batch_id_seq FROM raulr;
GRANT ALL ON SEQUENCE t_stamp_definition_batch_id_seq TO raulr;
GRANT ALL ON SEQUENCE t_stamp_definition_batch_id_seq TO sbweb;
GRANT ALL ON SEQUENCE t_stamp_definition_batch_id_seq TO sbadmin;


--
-- TOC entry 2171 (class 0 OID 0)
-- Dependencies: 180
-- Name: t_stamp_definition; Type: ACL; Schema: ds; Owner: raulr
--

REVOKE ALL ON TABLE t_stamp_definition FROM PUBLIC;
REVOKE ALL ON TABLE t_stamp_definition FROM raulr;
GRANT ALL ON TABLE t_stamp_definition TO raulr;
GRANT ALL ON TABLE t_stamp_definition TO sbweb;
GRANT ALL ON TABLE t_stamp_definition TO sbadmin;


--
-- TOC entry 2172 (class 0 OID 0)
-- Dependencies: 181
-- Name: t_stamp_id_seq; Type: ACL; Schema: ds; Owner: raulr
--

REVOKE ALL ON SEQUENCE t_stamp_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE t_stamp_id_seq FROM raulr;
GRANT ALL ON SEQUENCE t_stamp_id_seq TO raulr;
GRANT ALL ON SEQUENCE t_stamp_id_seq TO sbweb;
GRANT ALL ON SEQUENCE t_stamp_id_seq TO sbadmin;


--
-- TOC entry 2173 (class 0 OID 0)
-- Dependencies: 182
-- Name: t_stamps_issued; Type: ACL; Schema: ds; Owner: raulr
--

REVOKE ALL ON TABLE t_stamps_issued FROM PUBLIC;
REVOKE ALL ON TABLE t_stamps_issued FROM raulr;
GRANT ALL ON TABLE t_stamps_issued TO raulr;
GRANT ALL ON TABLE t_stamps_issued TO sbweb;
GRANT ALL ON TABLE t_stamps_issued TO sbadmin;


--
-- TOC entry 2174 (class 0 OID 0)
-- Dependencies: 194
-- Name: t_stamps_transactions; Type: ACL; Schema: ds; Owner: sbadmin
--

REVOKE ALL ON TABLE t_stamps_transactions FROM PUBLIC;
REVOKE ALL ON TABLE t_stamps_transactions FROM sbadmin;
GRANT ALL ON TABLE t_stamps_transactions TO sbadmin;
GRANT ALL ON TABLE t_stamps_transactions TO sbweb;


--
-- TOC entry 2176 (class 0 OID 0)
-- Dependencies: 193
-- Name: t_stamps_transactions_transaction_id_seq; Type: ACL; Schema: ds; Owner: sbadmin
--

REVOKE ALL ON SEQUENCE t_stamps_transactions_transaction_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE t_stamps_transactions_transaction_id_seq FROM sbadmin;
GRANT ALL ON SEQUENCE t_stamps_transactions_transaction_id_seq TO sbadmin;
GRANT ALL ON SEQUENCE t_stamps_transactions_transaction_id_seq TO sbweb;


--
-- TOC entry 2177 (class 0 OID 0)
-- Dependencies: 183
-- Name: t_whitelist; Type: ACL; Schema: ds; Owner: raulr
--

REVOKE ALL ON TABLE t_whitelist FROM PUBLIC;
REVOKE ALL ON TABLE t_whitelist FROM raulr;
GRANT ALL ON TABLE t_whitelist TO raulr;
GRANT ALL ON TABLE t_whitelist TO sbweb;
GRANT ALL ON TABLE t_whitelist TO sbadmin;


--
-- TOC entry 2178 (class 0 OID 0)
-- Dependencies: 188
-- Name: v_registered_email; Type: ACL; Schema: ds; Owner: raulr
--

REVOKE ALL ON TABLE v_registered_email FROM PUBLIC;
REVOKE ALL ON TABLE v_registered_email FROM raulr;
GRANT ALL ON TABLE v_registered_email TO raulr;
GRANT ALL ON TABLE v_registered_email TO sbweb;
GRANT ALL ON TABLE v_registered_email TO sbadmin;


--
-- TOC entry 2179 (class 0 OID 0)
-- Dependencies: 195
-- Name: v_transactions; Type: ACL; Schema: ds; Owner: sbadmin
--

REVOKE ALL ON TABLE v_transactions FROM PUBLIC;
REVOKE ALL ON TABLE v_transactions FROM sbadmin;
GRANT ALL ON TABLE v_transactions TO sbadmin;
GRANT ALL ON TABLE v_transactions TO sbweb;


-- Completed on 2019-08-01 20:58:00 EEST

--
-- PostgreSQL database dump complete
--

