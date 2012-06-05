--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- Name: f_delete_image(); Type: FUNCTION; Schema: public; Owner: gdadmin
--

CREATE FUNCTION f_delete_image() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
BEGIN
IF OLD.image <> 20382 THEN
    PERFORM lo_unlink(OLD.image);
END IF;

RETURN NULL;
END;
$$;


ALTER FUNCTION public.f_delete_image() OWNER TO gdadmin;

--
-- Name: f_delete_info_image(); Type: FUNCTION; Schema: public; Owner: gdadmin
--

CREATE FUNCTION f_delete_info_image() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
BEGIN
IF OLD.image <> 16595 THEN
    PERFORM lo_unlink(OLD.image);
END IF;

RETURN NULL;
END;
$$;


ALTER FUNCTION public.f_delete_info_image() OWNER TO gdadmin;

--
-- Name: f_insertinfo(character varying, character varying, character varying); Type: FUNCTION; Schema: public; Owner: gdadmin
--

CREATE FUNCTION f_insertinfo(title1 character varying, description2 character varying, image3 character varying) RETURNS void
    LANGUAGE plpgsql
    AS $_$BEGIN
  IF $3 = '' THEN
    INSERT INTO tb_info (title, description) VALUES ($1, $2);
  ELSE
    INSERT INTO tb_info (title, description, image) VALUES ($1, $2, lo_import($3));
  END IF;
END;$_$;


ALTER FUNCTION public.f_insertinfo(title1 character varying, description2 character varying, image3 character varying) OWNER TO gdadmin;

--
-- Name: f_insertmemo(character varying, character varying, text); Type: FUNCTION; Schema: public; Owner: gdadmin
--

CREATE FUNCTION f_insertmemo(cc1 character varying, title2 character varying, description3 text) RETURNS void
    LANGUAGE plpgsql
    AS $_$BEGIN
  INSERT INTO tb_memo (cc_owner, title, description) VALUES ($1, $2, $3);
END;$_$;


ALTER FUNCTION public.f_insertmemo(cc1 character varying, title2 character varying, description3 text) OWNER TO gdadmin;

--
-- Name: f_update_image(); Type: FUNCTION; Schema: public; Owner: gdadmin
--

CREATE FUNCTION f_update_image() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
BEGIN
IF OLD.image <> NEW.image AND OLD.image <> 20382 THEN
    PERFORM lo_unlink(OLD.image);
END IF;

RETURN NEW;
END;
$$;


ALTER FUNCTION public.f_update_image() OWNER TO gdadmin;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: tb_image; Type: TABLE; Schema: public; Owner: gdadmin; Tablespace: 
--

CREATE TABLE tb_image (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    content bytea NOT NULL,
    type character varying(4)
);


ALTER TABLE public.tb_image OWNER TO gdadmin;

--
-- Name: tb_image_id_seq; Type: SEQUENCE; Schema: public; Owner: gdadmin
--

CREATE SEQUENCE tb_image_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tb_image_id_seq OWNER TO gdadmin;

--
-- Name: tb_image_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gdadmin
--

ALTER SEQUENCE tb_image_id_seq OWNED BY tb_image.id;


--
-- Name: tb_image_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gdadmin
--

SELECT pg_catalog.setval('tb_image_id_seq', 1, false);


--
-- Name: tb_info; Type: TABLE; Schema: public; Owner: gdadmin; Tablespace: 
--

CREATE TABLE tb_info (
    id integer NOT NULL,
    title character varying(20) NOT NULL,
    description text NOT NULL,
    date timestamp without time zone DEFAULT now(),
    image oid DEFAULT 16595
);


ALTER TABLE public.tb_info OWNER TO gdadmin;

--
-- Name: tb_info_id_seq; Type: SEQUENCE; Schema: public; Owner: gdadmin
--

CREATE SEQUENCE tb_info_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tb_info_id_seq OWNER TO gdadmin;

--
-- Name: tb_info_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gdadmin
--

ALTER SEQUENCE tb_info_id_seq OWNED BY tb_info.id;


--
-- Name: tb_info_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gdadmin
--

SELECT pg_catalog.setval('tb_info_id_seq', 1, false);


--
-- Name: tb_memo; Type: TABLE; Schema: public; Owner: gdadmin; Tablespace: 
--

CREATE TABLE tb_memo (
    id integer NOT NULL,
    cc_owner character varying(10) NOT NULL,
    title character varying(20) NOT NULL,
    description text NOT NULL,
    activated boolean DEFAULT true NOT NULL
);


ALTER TABLE public.tb_memo OWNER TO gdadmin;

--
-- Name: tb_memo_id_seq; Type: SEQUENCE; Schema: public; Owner: gdadmin
--

CREATE SEQUENCE tb_memo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tb_memo_id_seq OWNER TO gdadmin;

--
-- Name: tb_memo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gdadmin
--

ALTER SEQUENCE tb_memo_id_seq OWNED BY tb_memo.id;


--
-- Name: tb_memo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gdadmin
--

SELECT pg_catalog.setval('tb_memo_id_seq', 1, false);


--
-- Name: tb_news; Type: TABLE; Schema: public; Owner: gdadmin; Tablespace: 
--

CREATE TABLE tb_news (
    id integer NOT NULL,
    title character varying(20) NOT NULL,
    header character varying(50) NOT NULL,
    description text,
    cc_owner character varying(10) NOT NULL,
    id_image integer,
    date timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.tb_news OWNER TO gdadmin;

--
-- Name: tb_news_id_seq; Type: SEQUENCE; Schema: public; Owner: gdadmin
--

CREATE SEQUENCE tb_news_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tb_news_id_seq OWNER TO gdadmin;

--
-- Name: tb_news_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gdadmin
--

ALTER SEQUENCE tb_news_id_seq OWNED BY tb_news.id;


--
-- Name: tb_news_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gdadmin
--

SELECT pg_catalog.setval('tb_news_id_seq', 1, true);


--
-- Name: tb_proyect; Type: TABLE; Schema: public; Owner: gdadmin; Tablespace: 
--

CREATE TABLE tb_proyect (
    id integer NOT NULL,
    name character varying(40) NOT NULL,
    description text NOT NULL,
    id_proyecttype integer NOT NULL,
    image oid DEFAULT 20382 NOT NULL,
    cc_client character varying(10)
);


ALTER TABLE public.tb_proyect OWNER TO gdadmin;

--
-- Name: tb_proyect_id_seq; Type: SEQUENCE; Schema: public; Owner: gdadmin
--

CREATE SEQUENCE tb_proyect_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tb_proyect_id_seq OWNER TO gdadmin;

--
-- Name: tb_proyect_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gdadmin
--

ALTER SEQUENCE tb_proyect_id_seq OWNED BY tb_proyect.id;


--
-- Name: tb_proyect_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gdadmin
--

SELECT pg_catalog.setval('tb_proyect_id_seq', 1, false);


--
-- Name: tb_proyecttype; Type: TABLE; Schema: public; Owner: gdadmin; Tablespace: 
--

CREATE TABLE tb_proyecttype (
    id integer NOT NULL,
    name character varying(12)
);


ALTER TABLE public.tb_proyecttype OWNER TO gdadmin;

--
-- Name: tb_proyecttype_id_seq; Type: SEQUENCE; Schema: public; Owner: gdadmin
--

CREATE SEQUENCE tb_proyecttype_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tb_proyecttype_id_seq OWNER TO gdadmin;

--
-- Name: tb_proyecttype_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gdadmin
--

ALTER SEQUENCE tb_proyecttype_id_seq OWNED BY tb_proyecttype.id;


--
-- Name: tb_proyecttype_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gdadmin
--

SELECT pg_catalog.setval('tb_proyecttype_id_seq', 2, true);


--
-- Name: tb_service; Type: TABLE; Schema: public; Owner: gdadmin; Tablespace: 
--

CREATE TABLE tb_service (
    id integer NOT NULL,
    name character varying(20) NOT NULL,
    description text NOT NULL,
    date timestamp without time zone DEFAULT now() NOT NULL,
    cc_owner character varying(10) NOT NULL
);


ALTER TABLE public.tb_service OWNER TO gdadmin;

--
-- Name: tb_service_id_seq; Type: SEQUENCE; Schema: public; Owner: gdadmin
--

CREATE SEQUENCE tb_service_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tb_service_id_seq OWNER TO gdadmin;

--
-- Name: tb_service_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gdadmin
--

ALTER SEQUENCE tb_service_id_seq OWNED BY tb_service.id;


--
-- Name: tb_service_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gdadmin
--

SELECT pg_catalog.setval('tb_service_id_seq', 1, false);


--
-- Name: tb_user; Type: TABLE; Schema: public; Owner: gdadmin; Tablespace: 
--

CREATE TABLE tb_user (
    cc character varying(10) NOT NULL,
    password character varying(40) NOT NULL,
    names character varying(25) NOT NULL,
    lastnames character varying(25),
    telephone character varying(7),
    movil character varying(10),
    id_usertype integer NOT NULL,
    id_image integer,
    activated boolean DEFAULT true NOT NULL
);


ALTER TABLE public.tb_user OWNER TO gdadmin;

--
-- Name: tb_usertype; Type: TABLE; Schema: public; Owner: gdadmin; Tablespace: 
--

CREATE TABLE tb_usertype (
    id integer NOT NULL,
    name character varying(15) NOT NULL
);


ALTER TABLE public.tb_usertype OWNER TO gdadmin;

--
-- Name: tb_usertype_id_seq; Type: SEQUENCE; Schema: public; Owner: gdadmin
--

CREATE SEQUENCE tb_usertype_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tb_usertype_id_seq OWNER TO gdadmin;

--
-- Name: tb_usertype_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gdadmin
--

ALTER SEQUENCE tb_usertype_id_seq OWNED BY tb_usertype.id;


--
-- Name: tb_usertype_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gdadmin
--

SELECT pg_catalog.setval('tb_usertype_id_seq', 3, true);


--
-- Name: v_admin; Type: VIEW; Schema: public; Owner: gdadmin
--

CREATE VIEW v_admin AS
    SELECT tb_user.cc, tb_user.names, tb_user.lastnames, tb_user.telephone, tb_user.movil FROM tb_user WHERE ((tb_user.id_usertype = 1) AND (tb_user.activated = true));


ALTER TABLE public.v_admin OWNER TO gdadmin;

--
-- Name: v_client; Type: VIEW; Schema: public; Owner: gdadmin
--

CREATE VIEW v_client AS
    SELECT tb_user.cc, tb_user.names, tb_user.lastnames, tb_user.telephone, tb_user.movil FROM tb_user WHERE ((tb_user.id_usertype = 2) AND (tb_user.activated = true));


ALTER TABLE public.v_client OWNER TO gdadmin;

--
-- Name: v_developer; Type: VIEW; Schema: public; Owner: gdadmin
--

CREATE VIEW v_developer AS
    SELECT tb_user.cc, tb_user.names, tb_user.lastnames, tb_user.telephone, tb_user.movil FROM tb_user WHERE ((tb_user.id_usertype = 3) AND (tb_user.activated = true));


ALTER TABLE public.v_developer OWNER TO gdadmin;

--
-- Name: v_memo; Type: VIEW; Schema: public; Owner: gdadmin
--

CREATE VIEW v_memo AS
    SELECT tb_memo.id, tb_memo.cc_owner, tb_memo.title, tb_memo.description FROM tb_memo WHERE (tb_memo.activated = true);


ALTER TABLE public.v_memo OWNER TO gdadmin;

--
-- Name: version; Type: TABLE; Schema: public; Owner: gdadmin; Tablespace: 
--

CREATE TABLE version (
    version real NOT NULL
);


ALTER TABLE public.version OWNER TO gdadmin;

--
-- Name: id; Type: DEFAULT; Schema: public; Owner: gdadmin
--

ALTER TABLE ONLY tb_image ALTER COLUMN id SET DEFAULT nextval('tb_image_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: gdadmin
--

ALTER TABLE ONLY tb_info ALTER COLUMN id SET DEFAULT nextval('tb_info_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: gdadmin
--

ALTER TABLE ONLY tb_memo ALTER COLUMN id SET DEFAULT nextval('tb_memo_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: gdadmin
--

ALTER TABLE ONLY tb_news ALTER COLUMN id SET DEFAULT nextval('tb_news_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: gdadmin
--

ALTER TABLE ONLY tb_proyect ALTER COLUMN id SET DEFAULT nextval('tb_proyect_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: gdadmin
--

ALTER TABLE ONLY tb_proyecttype ALTER COLUMN id SET DEFAULT nextval('tb_proyecttype_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: gdadmin
--

ALTER TABLE ONLY tb_service ALTER COLUMN id SET DEFAULT nextval('tb_service_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: gdadmin
--

ALTER TABLE ONLY tb_usertype ALTER COLUMN id SET DEFAULT nextval('tb_usertype_id_seq'::regclass);


--
-- Name: 16595; Type: BLOB; Schema: -; Owner: gdadmin
--

SELECT pg_catalog.lo_create('16595');


ALTER LARGE OBJECT 16595 OWNER TO gdadmin;

--
-- Data for Name: tb_image; Type: TABLE DATA; Schema: public; Owner: gdadmin
--

COPY tb_image (id, name, content, type) FROM stdin;
\.


--
-- Data for Name: tb_info; Type: TABLE DATA; Schema: public; Owner: gdadmin
--

COPY tb_info (id, title, description, date, image) FROM stdin;
\.


--
-- Data for Name: tb_memo; Type: TABLE DATA; Schema: public; Owner: gdadmin
--

COPY tb_memo (id, cc_owner, title, description, activated) FROM stdin;
\.


--
-- Data for Name: tb_news; Type: TABLE DATA; Schema: public; Owner: gdadmin
--

COPY tb_news (id, title, header, description, cc_owner, id_image, date) FROM stdin;
1	Nuenas Cuentas	Administrador: 1<br>Cliente: 2<br>Desarrollador: 3	\N	1	\N	2012-01-30 14:07:11.285038
\.


--
-- Data for Name: tb_proyect; Type: TABLE DATA; Schema: public; Owner: gdadmin
--

COPY tb_proyect (id, name, description, id_proyecttype, image, cc_client) FROM stdin;
\.


--
-- Data for Name: tb_proyecttype; Type: TABLE DATA; Schema: public; Owner: gdadmin
--

COPY tb_proyecttype (id, name) FROM stdin;
1	Open Source
2	Non-free
\.


--
-- Data for Name: tb_service; Type: TABLE DATA; Schema: public; Owner: gdadmin
--

COPY tb_service (id, name, description, date, cc_owner) FROM stdin;
\.


--
-- Data for Name: tb_user; Type: TABLE DATA; Schema: public; Owner: gdadmin
--

COPY tb_user (cc, password, names, lastnames, telephone, movil, id_usertype, id_image, activated) FROM stdin;
2	e285e2e264f407492baeeb10e313981369a35259	Cliente	GfifDev	496	314	2	\N	t
3	e285e2e264f407492baeeb10e313981369a35259	Desarrollador	GfifDev	496	314	3	\N	t
1	e285e2e264f407492baeeb10e313981369a35259	Administrador	GfifDev	496	2314asd	1	\N	t
\.


--
-- Data for Name: tb_usertype; Type: TABLE DATA; Schema: public; Owner: gdadmin
--

COPY tb_usertype (id, name) FROM stdin;
1	Administrador
2	Cliente
3	Desarrollador
\.


--
-- Data for Name: version; Type: TABLE DATA; Schema: public; Owner: gdadmin
--

COPY version (version) FROM stdin;
14
\.


--
-- Data for Name: BLOBS; Type: BLOBS; Schema: -; Owner: 
--

SET search_path = pg_catalog;

BEGIN;

SELECT pg_catalog.lo_open('16595', 131072);
SELECT pg_catalog.lowrite(0, '\x89504e470d0a1a0a0000000d49484452000000c8000000c80806000000ad58ae9e000000097048597300000ec300000ec301c76fa86400000a4f6943435050686f746f73686f70204943432070726f66696c65000078da9d53675453e9163df7def4424b8880944b6f5215082052428b801491262a2109104a8821a1d91551c1114545041bc8a088038e8e808c15512c0c8a0ad807e421a28e83a3888acafbe17ba36bd6bcf7e6cdfeb5d73ee7acf39db3cf07c0080c9648335135800ca9421e11e083c7c4c6e1e42e40810a2470001008b3642173fd230100f87e3c3c2b22c007be000178d30b0800c04d9bc0301c87ff0fea42995c01808401c07491384b08801400407a8e42a600404601809d98265300a0040060cb6362e300502d0060277fe6d300809df8997b01005b94211501a09100201365884400683b00accf568a450058300014664bc43900d82d00304957664800b0b700c0ce100bb200080c00305188852900047b0060c8232378008499001446f2573cf12bae10e72a00007899b23cb9243945815b082d710757572e1e28ce49172b14366102619a402ec27999193281340fe0f3cc0000a0911511e083f3fd78ce0eaecece368eb60e5f2deabf06ff226262e3fee5cfab70400000e1747ed1fe2c2fb31a803b06806dfea225ee04685e0ba075f78b66b20f40b500a0e9da57f370f87e3c3c45a190b9d9d9e5e4e4d84ac4425b61ca577dfe67c25fc057fd6cf97e3cfcf7f5e0bee22481325d814704f8e0c2ccf44ca51ccf92098462dce68f47fcb70bfffc1dd322c44962b9582a14e35112718e449a8cf332a52289429229c525d2ff64e2df2cfb033edf3500b06a3e017b912da85d6303f64b27105874c0e2f70000f2bb6fc1d4280803806883e1cf77ffef3ffd47a02500806649927100005e44242e54cab33fc708000044a0812ab0411bf4c1182cc0061cc105dcc10bfc6036844224c4c24210420a64801c726029ac82422886cdb01d2a602fd4401d34c051688693700e2ec255b80e3d700ffa61089ec128bc81090441c808136121da8801628a58238e08179985f821c14804128b2420c9881451224b91354831528a542055481df23d720239875c46ba913bc8003282fc86bc47319481b2513dd40cb543b9a8371a8446a20bd06474319a8f16a09bd072b41a3d8c36a1e7d0ab680fda8f3e43c730c0e8180733c46c302ec6c342b1382c099363cbb122ac0cabc61ab056ac03bb89f563cfb17704128145c0093604774220611e4148584c584ed848a8201c243411da093709038451c2272293a84bb426ba11f9c4186232318758482c23d6128f132f107b8843c437241289433227b9900249b1a454d212d246d26e5223e92ca99b34481a2393c9da646bb20739942c202bc885e49de4c3e433e41be421f25b0a9d624071a4f853e22852ca6a4a19e510e534e5066598324155a39a52dda8a15411358f5a42ada1b652af5187a81334759a39cd8316494ba5ada295d31a681768f769afe874ba11dd951e4e97d057d2cbe947e897e803f4770c0d861583c7886728199b18071867197718af984ca619d38b19c754303731eb98e7990f996f55582ab62a7c1591ca0a954a9526951b2a2f54a9aaa6aadeaa0b55f355cb548fa95e537dae46553353e3a909d496ab55aa9d50eb531b5367a93ba887aa67a86f543fa47e59fd890659c34cc34f43a451a0b15fe3bcc6200b6319b3782c216b0dab86758135c426b1cdd97c762abb98fd1dbb8b3daaa9a13943334a3357b352f394663f07e39871f89c744e09e728a797f37e8ade14ef29e2291ba6344cb931655c6baa96979658ab48ab51ab47ebbd36aeeda79da6bd45bb59fb810e41c74a275c2747678fce059de753d953dda70aa7164d3d3af5ae2eaa6ba51ba1bb4477bf6ea7ee989ebe5e809e4c6fa7de79bde7fa1c7d2ffd54fd6dfaa7f5470c5806b30c2406db0cce183cc535716f3c1d2fc7dbf151435dc34043a561956197e18491b9d13ca3d5468d460f8c69c65ce324e36dc66dc6a326062621264b4dea4dee9a524db9a629a63b4c3b4cc7cdcccda2cdd699359b3d31d732e79be79bd79bdfb7605a785a2cb6a8b6b86549b2e45aa659eeb6bc6e855a3959a558555a5db346ad9dad25d6bbadbba711a7b94e934eab9ed667c3b0f1b6c9b6a9b719b0e5d806dbaeb66db67d6167621767b7c5aec3ee93bd937dba7d8dfd3d070d87d90eab1d5a1d7e73b472143a563ade9ace9cee3f7dc5f496e92f6758cf10cfd833e3b613cb29c4699d539bd347671767b97383f3888b894b82cb2e973e2e9b1bc6ddc8bde44a74f5715de17ad2f59d9bb39bc2eda8dbafee36ee69ee87dc9fcc349f299e593373d0c3c843e051e5d13f0b9f95306bdfac7e4f434f8167b5e7232f632f9157add7b0b7a577aaf761ef173ef63e729fe33ee33c37de32de595fcc37c0b7c8b7cb4fc36f9e5f85df437f23ff64ff7affd100a78025016703898141815b02fbf87a7c21bf8e3f3adb65f6b2d9ed418ca0b94115418f82ad82e5c1ad2168c8ec90ad21f7e798ce91ce690e85507ee8d6d00761e6618bc37e0c2785878557863f8e7088581ad131973577d1dc4373df44fa449644de9b67314f39af2d4a352a3eaa2e6a3cda37ba34ba3fc62e6659ccd5589d58496c4b1c392e2aae366e6cbedffcedf387e29de20be37b17982fc85d7079a1cec2f485a716a92e122c3a96404c884e3894f041102aa8168c25f21377258e0a79c21dc267222fd136d188d8435c2a1e4ef2482a4d7a92ec91bc357924c533a52ce5b98427a990bc4c0d4cdd9b3a9e169a76206d323d3abd31839291907142aa214d93b667ea67e66676cbac6585b2fec56e8bb72f1e9507c96bb390ac05592d0ab642a6e8545a28d72a07b267655766bfcd89ca3996ab9e2bcdedccb3cadb90379cef9fffed12c212e192b6a5864b572d1d58e6bdac6a39b23c7179db0ae315052b865606ac3cb88ab62a6dd54fabed5797ae7ebd267a4d6b815ec1ca82c1b5016beb0b550ae5857debdcd7ed5d4f582f59dfb561fa869d1b3e15898aae14db1797157fd828dc78e51b876fcabf99dc94b4a9abc4b964cf66d266e9e6de2d9e5b0e96aa97e6970e6e0dd9dab40ddf56b4edf5f645db2f97cd28dbbb83b643b9a3bf3cb8bc65a7c9cecd3b3f54a454f454fa5436eed2ddb561d7f86ed1ee1b7bbcf634ecd5db5bbcf7fd3ec9bedb5501554dd566d565fb49fbb3f73fae89aae9f896fb6d5dad4e6d71edc703d203fd07230eb6d7b9d4d51dd23d54528fd62beb470ec71fbefe9def772d0d360d558d9cc6e223704479e4e9f709dff71e0d3ada768c7bace107d31f761d671d2f6a429af29a469b539afb5b625bba4fcc3ed1d6eade7afc47db1f0f9c343c59794af354c969dae982d39367f2cf8c9d959d7d7e2ef9dc60dba2b67be763cedf6a0f6fefba1074e1d245ff8be73bbc3bce5cf2b874f2b2dbe51357b8579aaf3a5f6dea74ea3cfe93d34fc7bb9cbb9aaeb95c6bb9ee7abdb57b66f7e91b9e37ceddf4bd79f116ffd6d59e393dddbdf37a6ff7c5f7f5df16dd7e7227fdcecbbbd97727eeadbc4fbc5ff440ed41d943dd87d53f5bfedcd8efdc7f6ac077a0f3d1dc47f7068583cffe91f58f0f43058f998fcb860d86eb9e383e3939e23f72fde9fca743cf64cf269e17fea2fecbae17162f7ef8d5ebd7ced198d1a197f29793bf6d7ca5fdeac0eb19afdbc6c2c61ebec97833315ef456fbedc177dc771defa3df0f4fe47c207f28ff68f9b1f553d0a7fb93199393ff040398f3fc63332ddb000000206348524d00007a25000080830000f9ff000080e9000075300000ea6000003a980000176f925fc54600005e6b4944415478daecbd799c5d57712e5a6b9dd3a7bb3575b766b706dbb264cbb62cdb92073c0f0c4e0007c305c223092137f0c210722f2437814072954002e425101ee19a40801b83c92510c0181c6c636cc0c6c6936cc018dbb2064bb2a46ef53cf7397bd5fda3cfdea756adaab5f769cdc0d1af7fea3ef3de7bd5aaaaafbefaca3cf1e41308e9cda4ff19086ee25d26faf8a1dc8c39cc6ff8abdb097f43c4265f407f45f5b1e039e4b1b243d758ec489e68c20f330d0b0a3f147d633287683188d8bcd169e7ef701b2ffcca788fb831c82bb8d8eb30be468abc77fa9c323a948d22661048168a29f025cc2c17191eb6b37d38ac227ef17e753b529652cc880ec12862de25f3209251042e877906cf5a63e119864672bc2db2a8c19ac368acbfbacdda48306e2d853d891aaa09865476ce79f13e358a200f40dfd3187f5bf58d8a2f3e3cb261cf1171e9fce49b5f855ac7e49a60134691f71816371844843222064f480d23bb9f1b84660c2827d7aad19cc8ee9e1dff51391e7342aff6c36b1c0516f76c3c0c7f5dd939171800358c6cf7e70b02cd215d306e7cc7e34d5df42667f76ae67dcd915b642782d160b1cc5b3fcf45c328943d8c6488f4be32228ae8556a1868d0cf4f0cb3409e97280bdf84771cf7173d3d51264ca00e6d73006c78da5ff2dca610749b9783145cec858c857d44e641a4f0892f0ce9bed8fdda0e7ba28557de776f76619b620be397319f6916ca2d9c5ca31e4a453d8bf0bacc8378de03eba19609bd04ad4f68e157de05a7dee7a8c5b9669608167b0f443c228b39770331bf1016512c1cc59c7593771fe61b8bf7ba8807f260de34af082e563dd42aea5172bdcab108afb0b99d8cd780b40b9c9b4f34798cc6985917c97ee142ab9851c5bc468e97e086c00d8abeaf07f3a65645c3086a1812041cbca6480c7fb40dc1cce66d663c65ea4dd5e719f4bde9212251f462fda2865d79b9c4ac3c87e60522c621bd07fd6e01ccebc1bb34ac020c502b440cea27316fe2c5f0787477a6d92e3483a60152e4a059eaf19b59780253c0b3fd221806ce02b98a788aec6fe539e2ef92472186e37b106a18d8c84152b7ef1989108f1709ab0e75879c4d053ee6cea3b992419f56a318c7ac927913f90e78788fff84319ca2453c6d514b9e43c939d2e7470dc5f320cc63d09c445c30245fa15e410d4db0d80e795812f2c22e22b24b1b7917cb5bf84191b58027692aa7f9c5b18ac2489664249a37101372c508f28c234bd2258f911a8441d33020e641f84152a8d88bcd8f54728ec770b753bc4a16aef2e3ce41d8823cef1730318f9e532c6044b17a0762aee7f09e83187d3c7dacec125249273945fa7b769f61f98309911e8fdd8bfa7d4723392fb42b631c61d38c3b5bcc2c3fc9bca65116bad1c99cc14efacb5216c1820623a0566ac2cd4328cd589861f0ff01a9076189779a4fd0bc8227e6858ebfee858e45fcaa2679b17e150c43400e2c643523664499d744dd20c53c25b6709a3875c76b02df0c6ad554ce11d9f9d5f00a9921b0d7f0d766398807dbd64f769a98d370c93318165a05a1969177c7a217b2708e82cdef5a1a3a253205948d213d1fd2f796f298dc3c25e2e58a9cb3132981cf0b25678b5005c6c28c213004d48dc343b1c43c837900ea55782d24e8130199d0a82daaa62f381e7a921f6bf80a8025c148bce3360210817af8160da5503967bf4861579126269c9d17e10b9e7b0ef531d58390059d1606696d8427ef85124fd0f95c87745e110fef2e866c578f783b29e412c32da31801b20d22c7c33413469ed0b6a250d3a306a1e513b33006c9fb645c2c87ce876c817813a1a827e529627875a44f6213ee5adaad03c3e51e8293368defb6f372312d7f114182a2c5c45f300f92b709c4a0dc3cef91de2f1a8c60209a61652856b608c8efd9ff1aa295fe8e8dc7bd859ceeacb35028d192b5236520c1e3c2c24e5fe31d273946ef22d79f1fd0ff85f341d1af66bdec89cc8c2e8c54c58c268644698612f326cc50ca140fe6c4448e647945c1489f392fa6712acb6cc3a7a24653a4ed578ae9795f8bf87d0d6b284301e18be423e2260287989b9c70ce237e1d45ef2041bb91d0484dce25ef817aae32c3e6a5741289b14b92ed742148c97b1696b0cafca1e40f1a26decc09572be60c8ee5547e896fa6923915ea09f544817148a857c4f0d5dc24dfcd1c57e154b390ae8652451129c140b2dfc9639ea77161a895d54168de9141bb9cb0c8441a32233132c5a4482355336e389aac6271bc3d5d8c627d84f4c2d0f3e2a1761251330538849a49f67988218d3ec685344d6e30e630c1e047d950d47c43c839a4e49c337079b21e780fe6353223e1cf4f7310638cb708b28bccf30f92b87bcfa91b8941e3a13ada856e2abc8a75871d2abac3c5288cdc0722e560de3900765ee87b234101c1782dcc22ff8db72f17d85f4ea4f6ddbceb9c4b3054ea1551642a6214defdc27b95a9c568ac5e0fcd51720faf88c89e13bdd045383811e82ff775528e6184e71b561b21bf676125abef44db92d37360185ac57e17fbd39bc94be0c875391e95fc030b78122c1066e58554dc83448c44cd41b871d05082875a12415162f7cec6cdab2e36274ecd75e38277f3da8c8d9f7389341b130734b2fbeae78043e13c5fe18858b659291d8d1aafec844bd60b12110be71c802172856162eefdee50cc47a89144fb41c0425cb48bedae52dfba1653e7e91645e352fe7c9c1d2dc553693128864b9204521a2a79f5209683484694e536a840cc4a71919ebfa6d8bee6f8338859e51f39106eb0c001015cb1502a8ba09c5c0f69a89a18e3d73d0c8071c6af8ff0e7d04524559399611459f09a0b8ec17db3d945a59d3860333396b2978ba05c17a2350deffd88a7a575260904f08e5711ccc83b96e33627c1487fb9166ea19c7c47615cbad8193a95fe8de81b5290aca754131a4250922218d223625948917a0e09d1e1484e017857359848437d54bd22c738bc3a08f316fcb8b46396423049dc428375bd73257468526f14e37105827e2762a1308f4e0228e6242a1a550fa1bcd71263c90c255dffdc8830a59a100f42e3677aa10d98194b33728bad96e407bb9b76b2b040fe91d33a592449d792758a2e89c44d14d03cf0d13c6a303499f7ce2bafc453a3e0399c14f272cf8c46cfb38ef3dc43cd3f0ad047383a95977348c691855442ee418dc6ef0701a5c75c683fcd1590637a59791d73b3ed29ce0dcd040abea7cec2bc0935846cb1d25c8b7a0a23b00d907905214917cf9f0195cca829317af71789f7cdb1378a58d4a0a156312a88f477fa4f5bf4aa51b0bf11d33a880d6b1ddeef3c37e1bb2986d0283d509a5c46738fd84e027a775833de43857c41c929227520f13160f91aaf1919e3e721c6f832aeb4b6223018f882cf15c1385e72921c951231948e140663941169a107f739dd28bc1cc4a19b49c695625e909b700a865451c71cc44adb316286a1bca65c2e8f2d98b7e0b9ee65ddcf747576f5555a2ad52449ecd4d454dbd0e850c7c1be83ab868687564d4c4d2c8a16d9622195b07950d8971a023f6e3557e1de44a2aa50c48b86635a15fe3008d71d936261813e72ad98a741b4d1c54ff310e9b9aef119215991c6ce06e22db820771a6a956135f68ce915459429962f5ebef5e24d17dfd3d5d1351ab91e8f2549629f7ce6c9d39edcf6e4a523a323ab3dd894a34586e424942550ff976d1891f0ca0bc19a48dea36002adc4a36ce487b357e6a81b0ae6f77bd028245a29a7f02d8446426b20de6b9ce641905dd4fac50c7213521dd6c4e46223130a158414588f9e2003064f3fedf4ffbc74f3a50f15b910a552c96d58bfe1a90deb373cf5d8138fad7ff29927af1e1b1f5bc661dd8c4fc6c21f7a6ed2e77a1b486a40c4683cd40b22ec689ad4a706452af85efe66e42262b4927e9cd64262b4f6a69b9da45c03e31e420ab7a4f76a140a51c83d84bf696c1cd403186e1f73f9452aa61abde0f435a77ffbd2cd973e309b6b74ded9e73d71d6bab39efac1833fb86ce7ee9d972549d226d53f82bc0b04a350f2b3e07154b86c1009eb784f09abbe6b395eb4f8781c160c0b470f8ab1443d08ca8895662072925e379060f7ab7b08638d57133156df090b8557584cba258835eb8f2f5eb8f8c7575c7cc57d87728d2a95caf40b2f7fe1ddbbf6ec7ae2de07ef7de5c8d8c8aacc0320ca06c23691c0ab482198410fc8486b4987b2ab73ca7ce1fe12c887dd8f5afd2302edab4a2391a2604a97528d04c2ca390fb3a49fc6f803605c23e3c39bd6d8cc8da39313746fd04e8198584bd063eed51853bd60e3057701803b1c17ede49527ef3f69e9499ffee677be79c381de039bbcda8d05b060435897188097b41b3f69cf42541a96ba06046cac5121df3ce518b1b351f118b3ee21394a7907bdbf59b91e2d840a8c460ba9ea1fe6d0f9093acd419224695c70db48d029ed1d2d46a15e4a47e1f06434c462b9459e344bf7d2ee875775afda7f382f58a552495ef5d2577de9d63b6e1d796eef735765c7e00c38e3b2852c8551a92145c3d1faebbdb054a1f1641e0a43a10c2eac51c41b782dbfc7436e820560de5942bb9e713817d43c3ce388e4203c1ff1512ce7b355b35dd031d62ace2c8c60c74396c0375139a7851ccda3ac3869c59387cb7bf0dbf52fb9fe9bb7de716b6de7ee9d2fa4f9033ad40dc41830aee13dc4fccd353cb28878b1c2aa840e6a1ea41002a619c6b1722439f2a151f62ec804448e44a5efa57a11a83fe622897bfdbe460e624304c65adbb80f4de649a8316508982280adba59c5bd6aea13a55269fcd455a7ee3a5206523792dbbefaadafb6ecdeb7fb4a6fc1831c4e694681869c3b54927781094c0d41321c1e461591283a56497bf4bb2014aaa04bc20b9460281a09f86856163e81ef6162b9070db3b286a9ac584892f10ce132f5300bfd2433359680ea2ec4bfb9c52109db2621d69cb973f62cea5a347ca42fec0d2fbde1eb377fe5e68ebe81be73c186e895e845a8d1d45909986023bca29b0fb2f349c32b9ac4330170cefad59ad772776f73740c831b457486b9908ff2c2a017664101042b1646d1bfebff071e25f520894be4dd10fcd83bfdddceac1a9f0e4ef3102541d40c44f318fc04cd699fb30f0092236d20d658b8e1d76fb8f9e6afdedc39363e7632cf410203b161ce96fd9d225709349e0b3e3dc733b204fd508e5178382f2c586c26324d3856593747d683a806a2d08602862d5f1f0abf8a3742a9f9456a14cee9f987a32db788fe050408d8bb2902e38cf350188927248d6cf34e14e6e41f4232d6d2d2327a24c32b7a9b3f6ffef88bae7cd14db7de7eeb3b93249917f520d8f01a9eb1d4e1f1345f3360c0242640af02548cb51b78b9096296fb5120848f9d0804f09aac4de4acfcd9c1ba7921560cf2d7247aa09e900b0816ad7f50ef90855739106f582834c42808f242ff46c0190f921a9015da6ec19fadae2669121b93f3f8c98e61c14e1f2d030100587bcada7d1bd66ff8cad69f6efddd99cd5bcf395223b0ce36422d6c180a38f0927a2d17019cf16059984bc530d2739a402364634357b30ec6949e722494e10f9529ac31b8050391ea231a959dd7428a18003596ccb09ccfc76a781020d025435ea8e1386c28311ae713e9f27a400a2b6f4b625e80ee681a0800c08baf7af1037bf6ed39fdc0c10397a60bd91a2b43bbbcf332f5222e3fb1a7bfbb19df2e42bfd93e94340ab6bc9ace37ab20c19fcd8e5fc4a09a244e7a9fe7fcfb347106b5655640b30283a186814e4dcef96b677290f45f0ecd84e72541c519e5e254ac622a726c8436c8e9e9e9d2d1c841f8ede52f7af997fff5cbffba6e7a7a7a09587f0a17877b9d758167e1e7d25a1b84635e5dc9faf99cb53654a63490e5355a41d0a3ac1cc53a481109a2bceed158ef7956e360902ea78bd0d04b0db11c665e2340b75c5a077138b3bb39e3150c29d5243b0e5245b7334f0ad4e0b5d1c96a0d244fdcabfeb9d3b5e996a3ed410000962c5e327cee59e77ef9478ffee8ad006052f262b6c0d362a1357e88651a9b0e3dafe911d0c73c083941efbe24491a390e1a919315680583ace2c885ff66997d17af94cfa22e22d2d8957c556a828a8657247ccabc88931fcb92f44cdddd920221857c1dc94988fb76c689f2405a314b1c9e28198993358dc6c7c73b8e8581d443ad879edefef4c57d037d9bbdbcc310c348e16e663c29cc9ba25960a15149d7c22d0c8d86aa5e0632b08862931adfd50f0bcd6436537bb5904e817bf344a9a5103c1652d1eab9875cc53c08ed49cfc20164452e5249e7d87d9aac8b63034098e014ab9e23aae25ea9c18c8c8d2c3b5606020070f945977ff596db6f392b4992766b6712696bea86514fa879de9179600760d17a035073f311f4f95d81ea2584421374b106de2347dd72360afcd1fcc2346f644125bdc03ad1888ae9fd5a5130f0202c716f7810e702ae50b613a671731d59a13948d650c47635ed846b620c126225e1dd63e363cbf6f7ee6f5fbe64f9e8b1309073cf3e77d7c38f3f7ce7ce3d3b7f23135cb03ef294a154d67848a031751682990139b23c84855fa9b7a18f79bd2646c845c06f7356f38e26d77fcc60a21ea2592a4b8e6a499ea48fc8bb720cf2752c8412f290cc989c9f83942ebbe6b22dd209440829d43c74f260c59c93cbc3aa00b1a23b84f38dc73907ceb9d6ae8eae8757af587db0010c1edd9fee65dd3b1f7fe2f10b6ad5da7cb1da1bdb25314c46699dc30b410d0b310cf89299828085047f7ad46e01ed117fd86eae52c1c11748503f27e229bce3e19e42a11e890541c0dcf02a330282606979888762a54fb6603dba042df65967331e960317d0b43d2a04e4f48148e43404b12acadde7f3fb9f3f09007e7eacc2ace54b970faf5fbbfe6b5b7fb2f56d59af8c94904303914a9907193cec4c800ad2e707fded06660a8c2cd4e2fdea1c208936ad813ec434aa360f460de18a6c8c8592fbbc29514a459da34fa24138177a156e3ccc58b21c241b83400da2be081ccc083ba452a4592e62219a87688959e04d3087be5c3f80fd3dfbd702c05d700c6f2fbce2850f3cf1f3276e989a9eeacec22bdbc8c9acb559d8e579d6fab9b3c666e1566664493dc44a1a4d55524e92be0f9de815e8fc4228957a3860de60825611a331c5432ccf3862689622f026093178c9393afdbed4789c6f50de9cf43449a7068106c1a2cd2ae819a2e58cdf00c4c87045e6744807cd0f964b45f6f6f79ebfbf677ffbf2a5cbc78e95812cec5c38beaa7bd59d4f6d7fea77adb1909824ec3fa7220de8eff059bf3fab9564f49dc4efcb096a4d09e36aa50b9149bf066db8a6b8a790bc863a365bab7b34cb1cc61cea099bdbe1d540d8469a790ac963b0645c7b2c0df34b975c79c916daeb91854c268c93a5fcc413e98278eb27a51d075d62d448e84ee11a079e2449bb35f6c9f56bd7ef3d56790800e09a93d7ecfcd1a33fbab45aabce4feb13aa201e179f6074f56cc72c225a008a509a941b282a1f45f8479ef89af01981c4a7947308798d68145800e1d4aae8647de4d540a4ea394dcad3da077f5e5649a7052eaa6ae2fd8b54da033d2c0385aaa585b48cc817dfbe6bfb0600f8d1b10cb33a3b3ac7972d5ef6fd1dbb77bc2e257066e895234445ed77fa3c432486ac709ea918362593d2ea3b65526bf5883cd16be139b94d57ec6fcee86ea6ff3dd05c8e80108108b513a8ee1047a8b2bf5de84568e5bd74f115176ff1fa398c2fac4c47ab5137cb0f287d1e27a079e80df31e457210de0239363eb674eda96befeaeae89a3c965ea46341c7dead3fd97aad73aead99443740f4240f22f452a85ab420cbf96b1e25b611055e447a3ee8cf1543a182ffbcda07fa6b25d60ca5317935248b26e734c4d2c2b19924dd36d0a98c5304f586a97aa1cb5997851296f4db06bd09269feaac35e1f316498fba5cbf3fa9265ddfbbff7be79fbafad4ef1e8a1778e2e74f2c7ce8d187ce1c181c58dad5d9d573ce59e76cdb7cdee603455fbf7eedfa83cb972dbf7df7deddafe54856baa158631bb9849d2916a65e83b60d508a7a5a84f4541a2589219a889b1c3e56818439782d46bc8de041b8a6b088a215993296a3911570b078925e30c4a2493b0fd1e89acba447bdea79ddfdf3c43c0d119c751937489cf81a291a6912f6f4c01dba60e7a45ffad91dcf5eeb9cbbcb5a3b2beec47fdcfa1f1bbe73f777de5dad553339d21f3ef8c3c9e5cb96dff5f297bcfc2b176dbea89030c4996bcf7ce0b9ddcfbd1a0d5ada7e6cec8c71a4093cef4737601a8600c653514930d18b85d6d7f50d04b779d31a45b8a0894aba699288c8e7a82013e08e8567392d11a28003a725b9d0dbf1902a281222fb9d43bcf5df4b175c7ac1160f65e1f3bea56221eb20ccc2306917c2fc42913a37cef97dc533e7c4c1e4d4e449d56af5b1f5ebd6ef6f3634eaebefab7cf6e6cf6e999a9a3a893e820ecb23a323a73ff693c75eb46bf7ae818b365fb42defbd4e3be5b483f7fcf09e0ba6a7a797885e12746511a98b2e2d1006aae7bc0e600a8c178b844b62b1af400896f7de62889513ba899fcf811cf087e1d03521eaeea25ff09368275e82ce7f27ef57da7ce9e62d51b799e62406a3305e30765749c43c54874178e205a4ae93ec1683c383e56b2ebbe607cd1ac817fefd0b2fd8b173c72b224d3495fd07f65ffae0a30f769c7fcef95bdbdbdb6bda7b596bf191c71f593a383cb851ab9e73c42f4fe646ccef94bc2d3a55a9c8e25444d65463d2721790e7fb79ef45190018c951010b8f6ed67475a5825f9073b02a3a7d4d06f3224269f30b3667306fe0412c0438be843c781414e255d0f83b84e842f30e1cc25d050161627ce26444fcd1e9a79dde14f5e496db6e79f1e0f0e0391ccee47325464646ce78f8b187575c78fe85f7c78ca456abf53ff1f3275e8a88258d5693e56a54d882ed8c3c49e5306ab09362b8cb4aad034dc1bb5c380d72bc87c48450bea30a3de70004de3a73f1e9b4d9228730ffa086138456bc4f9dbcd6528ba2c593ec6f600800799c7f097e1fa43d80ce87731dd49f0bc26e03e4fd408e17ebdfc7def7d07daf06ff53727fa6a7a7dbb2f777c24fe22071093874d0dbd77bf5073ffac13f9a989830dafb5d73f9353bdbdada9e485f972409a0c399df5d02ce394892c6ffe9efd96791bfa5c7bcf7481adf2ffdacec3dc8fdf4b5e9f3b21ff2bdc41f748dc793c64f922490d4eadf41793efd8ee963d979aedf9f2e50e927439bd8ebf902f6d6275b7bdefdf5e7528f91fdefc2e7797fd7bf4fe9bc8bcfdbe249fe239baec493ab9cd1c39e16aa097766299c10bd061fef2b18d1c4c4c4a943c3433fde78d6c6e78b7a90dbbf7bfb0b464747cf8c7284c88e3e3a3ebaeea73fff69f59a2bae794c7bcffb1fbe7fd5d0f0d046af7650003e103563518637e9601809f214431c2ca62c182527427cb6b814d605a191d1a73e1522422a9f15205b18760d3a70729885e1a61f24f78860b9a579a42eee8a2084c1821d3e2706a68694bd8f908b786e53f222f5e73df4d843bf373834582aea418c31d3e9b1a85e041b5ec43907db776cffaf9ffcdf9fbc487bcfd3d79c7ebf730eb947f0765bfa98f0bc60d74ebd8a4bc0d5c87312e77904fabde97ddc63f01fe975d28fabf9dfdbf3602ef13c0cf72efc75816789fc640b3c09bd8cc3fa7ba10b0a7e9e274117f67b508fc29274ee859c73503af7c273b704de81fe6f7cb21a65ab6ad492a0000442a19025a8aed1b99f3d37ab8ba4466320882ba7aa53cb76edded573d945973d59c483dc73ef3d67f40ff65f901b9ffb146cb36fffbe33d7ae597bc7d2254b8302e5ea15abfbbef3fdefbc2849920e1578809cbc83c3e10e65e91bc17378c554884bfde751d9a5c7393a44678aab6c5b50bc1c84ba56fc2a49c7936dd0b13609560e08ba05b5449d71b2a883286dbc70e396a0a064402c4479a19892ac8bafd3204e8c6bad066115e7ebd71f1b181a386b51d7a23b57af583d9a67204f3cf5c49cdd7b765fa7a98c6b707492241ddb766c33d75d7bdd8ff87bb6b5b52577df7bf7daf189f133786558642f1740f03485170fd17210479a2012623981a29163509a7149750a315cc67c839198de9c7990150af37acf0b24e91eed848767e8a0b4f1828d5b782ba8977fa4ffd9b0f8c3a7af7a7aac46867b459d5604952097150d85dc247d2c4992f667773ebbe8dacbaebdbb5c2ebb98812c98bf60e0de07ee7d69922473729b83d8771d191939736070e0d1cde76ddec7dff781871f5876b0ffe065126c99d642c47092429fdc73f2c5e2509fcde7946131cd40bccc88b2f775322a26f66628f98824f1a479afa0371c429e9514e6f34ec3ac98c87a413262223306c9486c804e090840ac9892dd47fe65499393dd3575d1f43512ce9f2d2e298125cf1d181a78f1473ff5d197e4e520679e7ee6507b5bfb4f285aa1c6c5613c5dbeffa1fbdf20bd6f6747e7de7437a2f9448a68f1bc248bf13952c490a920e7481c24b5194489e6191ef2953e560b7f5ccd35729a248e70f1ef93a15835194df3729484215bf4d815142dfb7ce1fc8b923d1ce962e8546ce1536f12e42dd4836cd8b4618b5431e7dabb19c7cae8cc4fdeb41f25a749b2f62c1f912aabfc3e0a17f7f5f79dd75a69bd6bdd9a7543312ff2e0a30fcee93dd87b152f584a3ffc3b4f4d4daddafdfcee9f5c7ad1a5cfd1678e8c8e4c6ffdc9d65722628bd66bcf77d3e033844d826e5a41584276f96c5181d3abcc5aa845730b27ccec0305ce77181501543b0363c335638449a93704150e96166639e751db69be91354d11432a9dbde9ec2dde5457cef3b102318e6bc6b21cc4eb5bcfeb4f972af01adcab180a81f7dab7efdcbefa8a175c717b5b5b5ba22dfb552b57edbae7de7baeab25b505bc575cad12938bdcdfd7bfecca4bafbc6dce9c3909a19d8cdc72db2d57d76ab565fc755948480c9f23797cc67714b275f92da779b9472ef41b816333ca47ce8c7135af8c40c46a6f0a3d57b1013a2ea4967889bfd0772e25ece9fb94ce3affac2d3c31cf16b91518a1c450b8bc4ba6bf64c2467cc940a47c448acd69c150320eea45a6a6a7563ffae34793ebaeb9ee21cd4016752d9abee707f7740d8f0c6f0e3c15c8bfd38b50ad56bbb7efdabeedda2baf7d86beef37eff8e685139313eb784299be0fe565a5df39f058ca004a6e38b4b8c53566452380f82c3ed1ab440c27bb8eecfbd17059631468e20c3cbf89ceff10ba0aa3d2a2ce2f128a211883859d4b0d045858c51b6004cfe23151794b2695ebcfa1394b14056a1879fd0e12496f7874f882a7b73dfde495975cb9433392b6b6b6ed0f3df6d0f5ceb939d199da1cf64d3f6378f8a4cb5e70d937e6cf9b9fd150be75e7b7ce191b1b3b5ffa4ee9b42a0ddef4ea429a4081c360204c742e86b6e05d8e1742942bd99ab1287c2e8ee2693411e9da72da4d10823a650373180cdba18621161325ba49cae65d7feefa2d34dfe05c7e8f63654065fa66da5ae4b9948f45d500b556532a7993190744460013c4c7f33288a6a7b7e7e2f189f1ef9fb7e1bc3ea56d76ecbbdffbeebce191e10b344856336640806ab5baf4e9679f7eeeba6bafcbea2ff73d705f57cfc19e97044c01600814b9c83cc40a3c01cf43e8739c1f3a64e087944738c5082220850afdd2f703760c0ed4262d7572140a61740c89738ae7e1481e534e0cbc8644a16246545a7fdefa2d9e77e0833a492ee2c9595a13d64e88e7093c4e9e280033224d5c2c9d722525baccfdb7efd8b5e3fca54b96de71f2ca9327a4cfeeeaec7aea81871ff83597b805525e94d71a3a363e36efb5af7cedd7d3bff7ecdb537bf2e9275f8350272ef241314691fb07618714089e6a6e02f9ecdac2493beab496dc9d9e41d2c13913ba4bd50222b296090741ae2a8e0a17bc2b00f84c0fd7080739d1911f6369fdc6f55bb49ee2cc835825cce28545f4d53ba4fc446d394559ab951e8817e79286199ebca5075b4b6a8b7ffcb31fafdbb471d3ed9d1d9d012377f5cad5133fb8ff07d581c181abd5c516d9c9aab5ea4907fb0edefd820b5fd0536fc31dbffdaedb7fc3d55c478cea5d28f17628569603929d73c526b962411e940371270e627ba57a4fe94331818740e2476abf6ef21c7a05642727e4628942e9134187503a7de3e95ba40e35da8f2ecdbf88d1df0341811c950b1edf4b7d253ca4e2fdc7e2ee060053535327fff0e11f2ebee6f26bee696b6d0b8a88575f7ef513b7dc76cb0baad56ab7a6ec11510fb4139313bb5ff1d2573c0c00d8d5d955fbf75bfefd9a6aadbada5348d47a2e20dc81f924569e3c6a156931b7704a1e121b2a233063254e53e6d55cbc8f3d387e90f30b4d9fa010c59e0fe774725ec2b5af343e1685cead978c0931af5734e4aa114a122b5291d9bf20cee555742085448af890cfa0afc90e9abb4d40181c1a7ccd3bdefd8ef78c8d8d012ff0cd9d33b7b669e3a6bf43c4296f377358e8a7a7b7e725038303191dbe52ae3ccb0b7635576b10fbd28259cd05c5be5aad161410d3e21e250ed2a29df71ef59f5aad06b5c4ff495cd2f8bb16fe484545a970997d8f9aff7d033a7ce253f6395ddf236d727a3e2d182a44d28c624f8a8e9ca428d2e591111fc9fbd21c25c199f72dad3b67dd968ca2cd7b8b89ca9f989f2009a722723231344b84ff40a661f0502a467ff00cd8214c4c4d9c7be7f7ee9c73dd35d7dd5ba9543c4f72d565573dffcddbbfd9393e31be296f77e53b58ad565bf6fcbee7efb9e68a6bf601007ee3b66f9c31323a72b958d751c890daae9c853b2014e98031569d5ef7f0a05fc7127ca960083a58a07610b2eb11f40649b914f3cada5c9018578b6f8622dd86b1d4a921508f2109c959518c4c5968529147e212710920b5ef9822590602d9208a8ac5785d0179d2302e55fd6d0606077effcdef7cf35ff60ff45b1e6afde91ffde9475a5a5a7eaa85596a8318206cdfb5fd9cf47d4aa5d290b743252ed8e1d28b95d231d2e705748f14514a08ad3b99792e2698fd7874f244a0cc730abbb26373da8947ffa87f664a3309a8edcea7dff3991c99b7c0b0418a1a6c76acb4712f4289a7e85e1a17c410349aacabeb9ce44b8df92044c5241dce69dd8cee2c4de052ed596a3016eaf2a468fd5a88319e52b9a4b628a23d7583f1760589dd0b28c2be7497a1dc30e71c0c0c0ebce1cdef7c73db963fdbf217e79c79ce64fabccde76d1eddb471d37bef7fe8fe7f73e8e6688a7f9291f6f5f75d02009f030028954ac3ce394f683a9b3948144cb291064cf042f2b6eaa835131780536480c6c0c004004c1b30d3c6981a00a0018360c0028045c01670d0eed0b523e05c2a67940e7b15c7865329d5fa73d33591e5b5c86635821c9904e743d241d0e687b8b0624f8d8e7a542fb41292f572c071a9ab86672a1b045ee50b321da4c3471a733894aac0d383cd0ecca02c70402065cafbd1ba19b393e864b42c5d90a363a3af7defdfbc77d11fbce10fdef9b297bc6c307dce07def781adbffdfffef607f7eedbfb7e69ca916628e3e3e3977fe25f3eb1e2ed6f7afb9e4a4b65348338596135633f3bb2101443f19809e431faf9dae4a852a9d46b8ce92f97ca7d2d2d2ddbe7b4cfd9ded5d5b57d51e7a29e93579d7c70d58a5563f3e6cc4be6cd9d97742ce8f0e63e8e4f8cdb91d191d2d0f05079d79e5df376ecdeb1bca7b7e7d4e1d1e133272627ce76e89639e79639e716648bdefa035fa90138748de7a49252d050951467bb93f32c9e0fed7a08b421d163b806121a43f700801808348691186b6644ab538915e36654ccebeecb946684e62cda86b81cd7614a2f2699dd1d9b1721c59f5c544ef51ae08279113c39f3603e87303e31fec28f7ffae39fdfb67ddb5bffdb5bfedb73e9e77de1535ff8dccb5ff7f2d3464646dea8cdc7e33797b8b9f7fce09e6bdffea6b7ffef4a4b6538718938d454f5185a91967900c9531863a6cae5f273e57279fb82790bee3f65f5290f5d7dd9d5dbcf3bfbbcf1c58b161fead0d32100d80b008fa4773cbffff9f25df7deb5e8c1ad0f5ed073b0e7c5d55af5dc2449d60280f146cfd9461e2b0d34a523ece890202ed62d6d08926148b59aa0baaea8bcc78ab1e6c5af79311a30604a336266d6da6c4252fabb2dd91995c0d2cc41d8929df9df369402b303a533320485c098a2b7d86fed206c6e21bfa7ec4b87f51859d160e5ee337d5f63cdf31bd66f78dbc7fef6630fa6dfe5b93dcf95dff4476ffae7f1f1f15f2f62200000f3e6cefbd21d5fbbe39defdef2ee33bfffc3efdf698cb13c64e26183578885f077499db07eae7b5aca2d4f772ce8b873d3c64d77befafa57ef5d73ca9aeab1d029def1dc8e967ff9e2bf9cb36de7b6df999c9abc1a01977963b0c9bc19699d78937f992e715ee39da8d4a9803419ed8452e779b35412865be645af7e116639083110cf08a8f1981963ca0ca87ea0d45868ed844ec9155d29c8b3423cb4c1113a4912eaad52b50c6e1cd941939312b03b0127ba9775ffd9173ffdc52fa7dfe5db777d7bde873ffae12f4e57a72f94422dbe93552a95addfbbed7b2ffdbb8ffdddcaaf7febebdf078476319fb0460e1d04fe1b3d67d6da839596caa34b162fb9e5652f79d9ddaf7be5eb06e038bb7df9d62f2ffcc61ddff89d91f1913738e7ba03233182c118e24900c4d1d862119be98ea93a5a2e0cb79c23eb881886b4564aa7ae3f758b4417e1562e26534aff7aaef4a511927241cd3ba08d339e8f5618e24df894aba40c766c191a19faf5af7ce32b6de79c75cefdcb962eabad5db3766a7c7cfcce9f3df5b32b932459cac1010ff20484c4259d038303ffa77b79f7d8838f3ef87b88d8a6b6970aa8a104d31a34e3a572e9e1455d8b3ef6aaeb5ff5e71f79ff476e7ed5cb5ff5b30d676e983896c2dddacfd9679c3dfeaa97beea819672cb97b6eddce6a6abd3672360abd8c61cd12d087a483815476a8f70186de1e66a251ec2a624f033067266c34078bd23b066cb86b9189f02cf2d5e44660c1bca627c9e12e720d13988225ece5b5a5d84a38371b8796a72eaa23beebe63d3ce5d3b1fbcfaf2ab072fdc74e1d8dee7f7def5ec8e67af76ce2d8e25eb88d832353575f74bae79c98e3bbe7bc75b9c73ed81671468fd9c87564fb29f99d33ee74b975c78c9bb6efa5f37ddf8da1b5efbf8a68d9bc6e104b99d75fa5993af7ed9ab7ff0f3677f7ec7c1be8367242e5995823c74c08fc67b5395260d84461519b443db6c3921b5101507d01f030d588776492d219ba3eec8f830303ef66c6712798384a488e00f8c2113913cd21e80388047f2225a9f86878bd36e440ae509f50c09bd989c9abcface7beebcedc73ffbf19f7ef5a6afdefabefff1bee726a7265ff7ddef7df70bd5a47a762c17191c1aec5eba64e9b4732e49010f0d891172132c97cb3f5aba64e9a7defbaef77e7bf3799ba7e104bffdd51fffd5cffa06fa6e78db9fbfed3d631363efcca46cedccec779a9380036f464d86fa2199aa8c711d339584a970af382587ae8dc42550abd6a01c50c88d8f0464ec5913160dc5565b6e18023eaf6ad8a29210734403c32264f654348d5a48fdc472ea77308783718312972cd9fbfcdecf5dfdf2ab3ff7ca97bdf2831f78df079efbd0dc0fbdfa96db6ef97cb556bd40bb4813e313a7ae3869450d1193204f214c650a891b3053a552e98727af3ef9a35ffcf417ef6b6601f61eeccd0a4f4b162f71c7a3912cea5a947ce9c62f7de0f57ff8fa9ee1d1e1bf4d174c36a409c980a6fa0c1a6b6d63a49d69188998ac0bf249514e1db9ce6911b55aad824b1c54abd58c3253abd5c0250ecac3a3c350b22528b594a05c2e43a93cf33ff50206cdcc604f335344cc429b7498643a41a9be237003ca6a2b797343341a494eb390471548452000bd5e6e1e667181e36caa103989e313e3bff7c5af7cf1ca7beebde7cfbe76f3d7ee3a77c3b9d7fff5dffdf5a7a6a6a6ae9790b8f189f1f36fbbf3b65674e8320f22cc8faf9f87a452a9dcb56ecdbabfbde99f6f7a9c9f8eef7effbbaddfbaf35bcb7a7a7a160f8d0c9d343636765aad565b95b86439222e44c456c42cbe37c698aa3166dc18d35b2e97f7b5565ab7777474fc7cf1c2c5fb5ffd8a573f77ed15d74e1d4b43f9e23f7df193af7dcb6be78e4d8cbd8fce7db7582f4643c3a3a4510d1d5b171d480ac2542ab26e329600e399a51cb400e9740d2760cebdea5c4c51048a62955bca502e97a15c2a836db1d0526e015bb6502e95a1d452ca60df14c9020b8ddfd3b1c70022225104aef39a6318153b8574b98e2a5512e10d31d9f31225699394c2a121b7df5a69fdfcb5575dfbfef7bff7fd3d97bce8928f4c4e4dfe57eedeadb5cf6f3a77d36f3cf2d823df429c813b2523696969b97bd5ca551ffe8f9bfee381f4be7ff8a77fe87af4f1474feee9ed39676c7cecaa6a52dd04082701407bb49fc6e48e399b36c6ec696f6bffda9a53d77ce5b31fffec93c7ca4846c746cdebdff1fa7fab25b55f0b502d565dcfa05fa95e241847e29219981f89b24aad41ad093a075d08f57203418760365eb91153e896c3bc01ec5b37a252b904a57209acb5506e29833536331e6bedcc6b4b362800152af8209b05c190298e4a50a3a07c2504f4384c22eecdc86b7c900fff1d007a162c58f0375ff8d4176e7afdefbffe0f464647fe273a6c27c730b57cd9f2371ee839f071445ccc61dc52a9f493eee5dd7f7eeb976efd3e00c05bdff5d693b76ddfb6796474e4e5d56af572cfa80a52478adcc8eb924aa5f2b5f5ebd67ff8331fffccd3c7c248def117efd8f0ecae67bf6b8c69050ba29104086a9a68bb995c37bbbe8c912ba9bef01a5a4033510c24bd4f37106e28c440f873b807b1a6fefc92819229f93513f27bba4ba8922e02f49915f91c53ef160cc5ab8350f25ce2542fe255dea571c30050b2a51fae5cb1f23dd353d373f71fd8ffc9c425abd305d0ded6fef79353936f46c48ef43e6becee79f3e67df4be3beefbf4bbfefc5d4b1fdefaf00b47c7465f9fb8e462ea2144fe91622c87623000303e77cedc0fdcfca99b3fb17ae5eaa39ebb5cff7bd7df584daabf6d8d6d5051c800211a5e79089454385446b65183918acc5a9471640cc4866e9213d934bd2d3a30d41aebcf40340d76664addc80e2251c22b660059a53d717275dde52b7d48146c00804ab972f3fcf9f36f191c1c7c4b9224d7008029954a772749720900b401c054a552f9c2a9a79cfa89d1d1d1ae9ede9eb75593eaaf01c05c6d91abc4c41c636932ec9af9fe2d957ffed1777ef4c78773f11f3870000e1c38003b77eeb4070f1e8483070f425f5f1f1c3c7810f6eddb67fafbfba1b4a4f41bb6623fcfd8016a98e5d14fb4623333943c03f1365a94a38bd040cccce2070b9e6170a3b025dbf018f5797c818158816a22c5929af098b4ab43a33f221d329a42cd9eb85a42206a67c2845c0ad7240f223043b9c70380a19696967f73ce2da9d56abf668c1942c4e5c69ac7e7b4cff99c3106c7c6c7fe3b3a3c4d8aa36306d2949730b37bbc637ec71bbff7adef7d99deb76fdf3ee8eded35fbf7ef87a1a121d3dbdb0ba3a3a3265de853535366646404fbfafaa056ab99dede5e333030008868060707f3eb24979c755ae7d2ce8739774bdc5c8d9125a92044227982ce93f580b9eb42ca52c0e63d92aeb4d09c6c090ed6f27821d1f27a0848022f0d65010361ebaa23957800cf1869922e1509eb285e47ad567b0b181836c68ca2c385d6d8c7c1c1f6d1d1d1f720e049bce89967005e8ec6e7b4b04d25a844a35f74947afde97b4f8e4fdeb068d1a2af21a229bac00ff536323072b0736967f32fc4021bc1e1bad5cf59d98be50cfae3d4188598d61dd2fa48ec4b171d24a3d63fb42fae085e4bdfd7ab9530972af50148bddfea77f46582a61171180c5803e6340058cf11a8bc5e0ff53ed3f4ce5478d342c4527f7f7fe968e620068cf5ae53ce5026ef7b472b05616d4c5c1b8a2e026ffe9b3110942543c52ec174d7148c2230062c182260a42612515aa7f58ff4f3b3842ecf5022272a56a555ba1a4730c1cf22629f2dd93facef2f73d0e1dd08b8dd18f31263cca97917d5ab1b718a37366918583c3771ce6d3d8afb72fa3d4bb1e3578fd7145847b1e1a0058c858fafb047c55561e460202ed6165d1c05b4abf2428c5c8fa517a6869d739f999e98be1e00c096edbb8d314b01e0c919e0cabcd8187341524bfeae365d7b233abc1d11079a598a85f20f4e3265af0fbaf7fc63786460ffc0678f368ad5b9b4f3f4d91e7fa077006676e7ade0ad5c24a4caac1a057758479638b5bd90a52b0681b20fd43d0a46767a940d5272a745bfbb73ee3fc787c7ff3ca926130b162ef817347879fd7c54d1e1cfa00497d5afdee6524b691326f8c9b1e1b13fa94e55c73a9674fcb6b5f69560e07463cc5c6f48571104cb1c9ec580880f8cf48fbc79b06770f4687b90d6f6d6ab534a53d6868cfe310654f702b96b263ea284e4818728106d94b52428cb359c30512a45778caede9e1113053ab32a22c7b952c2b4210fba5358bbbcc34c43cbf8b4a63ccf04084fd5aab58fec7e6af7574f3aeda40be776ccfd1420ac24cf1f71ceed2cd912f5ccc696ec5be775cdfb7f5ccdfde3de67f6de98d4921b97ac5ab2b27d5efbf5b6645f668c39d918b35c5df0e6f0ed8e06ccc12449fe75dff67d1f99189d983edac6b1f6bcb52bcb2de5dfcf385886f58190e3cd00074276cd98cf18af83881dac79c6225cf772133b4e31544a3038eea5c4b0465055e48621c68ea8e70c9c94a88206281faf07a025ee133dbb7b3e3c3e3c3eb9f2f49537942be57f0280567631fad0e11e2529efb465bb65d5fa55bf5b9baefde59ea7f77c1b006e04801b3b96742c98df397f634b6bcb7560e12a63cc2900d0de4c05bd40aed7eb9cfbd250ffd0277b77f71e806374eb5ad6f58700b0509a2830abda4f6a38069b8b068a8658dea223f23a52324e95ca03af9356beeb74e6b442eab94f93834028c355f8542afa5db259eeca982faac498364ca90886130df5b1e9c9e9bfdaf3f49efb000056af5f');
SELECT pg_catalog.lowrite(0, '\xfd265bb67f0d002d7c8122e20e97b85e717c76e379a7b6b4b67cfe940da73c9254934ff6eee9fdcfa1dea191a1dea1fb00e0be4a5ba5dcb9acb3bbd25a5956ae94cfb325bb09004e0680f90030d718d36ec054ead7ae54ff8cf43ad600600a01c701600810fa9c738fd4aab5bbc786c79eecdbdb777096b8d861b96dbc6ae375e596f21bc5911964f3923a09735b6f59e81528580a8cf0a8274182627933071514210b9be897acf78250ec9d1b4fd6d004c2eba59c04c32fecd10d88e41b2d1e66ec59166a79af07421f411d22acbf47b556ab7df2c0ce03ffdfc4e8c494b5d6ae5abfeafdb664dfcca9f844adfdc92449c6b525482fbcb57673a9adf4e9eed3ba9fa8556b370e1d1cba63a87768687a723ae9d9d5b31b007603c0c3e9f32b6d95726b7b6ba55c2957ca2de5365bb22db6642b33319c6935608c736eaa56ad8d4e4f4c8f4d8d4f4d4e8e4f569bc0818ee86ddda6756bdbe7b6ff3d1828f1109bca0649c6e341bccafa11f30f217f8d0141526e52a60f64c62124e2de41b97ad30baf9b20311c33633c9e8731f110c76bad55262c3970e14ecfc60904b9124fcc017c0955086694ec9c9a987af7eea776df03005069adb474afedfe5b63cd1b626edf39b7030c946289b427813423fb73764ba5e59f16af58bcbb6b69d7274687466f3fb8e7e03efe11d393d3c9f4e4f404004cc08cdac82156448edeed8c0bcfd8d0b9a4f333c69a6574a30c98056ce60c8f6a38d5c4cb47d2a7a089229741bbaed0fe4d0de590615ede062b42ad8071e40a586815993c258e1c06107bbc83e211ea4af2c4c8ee1ae819b83e350e0080eeb5dd1fb0d6be212f4e7689db6f8c297398951a47908cdaec22ad2a57ca1fea5cd279f79a8d6bfee6a435279d0ebf00b773ae38e7fa8ec51d5f070b2767e78e691bd073e2790d6d24b961c66474742f96d314c9edca511897a0515ef8423d07ca08581adaa485ae68f8c6c329de30e5d8e429a6792531803d2372a843c80d87534daac9c7f63eb3f7ff9f9a98aaa64773cad9a7fc85b5f68d818187c7519d1a9fdad6d2d6d2cd3d2a358898405cfda277965a4abf3faf6bde6fad3d7fed8f5dcd7d7d626ce2fbfbb6ef7bf644328cd3ce3bedf4ce259def2c954baf30a61e4471be1ef9c751ceb4fd36202862810ddbf8eb2508bf0c78da055ab8d58079b59cc3186f61a749af051be4243481f7b06daa466a506ed125a19b87608132bf1b42fa484c463fa8a340309c73606a7cea5dbb7eb6eb761a9a9c72d629efb525fb3671a66288c33fdbb7afeff9eed3bacfc842566d1a97d497ce75b100da4cc95c644bf6a205ad0baaf3bbe63f549daede34393af9e37d3bf6ed3c5e0d63c5ba15cb96ac58f286726bf9add6da368fa94bd706f104b4179dab70d23516d06408a0c4dbae337de89c9a57b63e0c2849faa1c557620d255b9086e52f08218e4d8c23c811844943c1642308432e3add48aca1382f81df39363cf696bd4feffd317dcd29679ff20e5bb26f2f1ae1a3c3a7eb8bbe5574f13986c145e2d8fd2dc69a4b5bdb5b2f6d6d6f9d5ab078c123b56aed2b13a3130ff53ed7bbb33a5d4d8eb5619cb2e194350b162db8bed25a791318584435d38210ca861b06171b0c66cc88e5a1865e02554d1193750351754f1de615c2aad42da98b9d4e9222c51c2f1c330cc932b29bf4a60d09534dc5c1968caace43b27ac22c0e72f1668c38f7d8c0fe8137f5eee9dd47cfc2ea3357bfa6d4527a0f37b4d8cd39b70d66e437db82dcc386dac4de4e69fc1cc53b7f616cdd6ac05c5a29552eadb456aa0b162e783a49926f4d8d4f7d6fa87768dbd0c1a1d1a365144b572f5db8a87bd1a5ad735a7fb3542a5d6eaca970a3a0306eda4ae115079957e183643565ff00d635c5795652dd4443b8ca3c110e16325998de7c4033a374980a36788815292c660bdff86107eba9088b82b41f03211cc5e6188cebc222a1978fb0b9e5492db9adf7b9deff31d83b384497e18ab52b2e6ea9b4bc5f320e6d0732c6606daa76efccaf666e905f60d85b4dc76cf3d1da01bc298467f5bf5b0c98b34be5d2d995d6ca9fceeb9cb77fc5ba153d80b0bd56ab6dad4dd79eaa4dd7faabd3d591a989a991d1c1d1d1e989e95a3346d0dade5a9ebf68fefcb6396d0b2a6d958595b6ca9a96b6968b4ab674b62dd9956060b11446658bdc82987748da6a1ec48bc657c8879c622e8d300c867311314466b5917f62253d48c81d00968837e1fc2c5248a45070562c1464eb39533590dd01d0477039d282c9c515d2a9b88cdd1b24e8f5d7d4aab59b76fc74c75fd6a66b09bd2c9d4b3b17b5ce69fdfb7a514e8c59456a8dc3270fee3db8b56e20f3b4102bfb24aae20146475d8c7cbf46473160961b30cb016063a95cbaa1b5ad75e694024e02c00420f403403f220e00c030220e23e234204c808116305036604ac69876634da735763158586c8c596c8c99638c69e5df291055481126eb7b074fefca6043d4034ce04d450de308c323ab839870230fa060b2663566055da3e5c06b349b8370af211481023c5ba97f88b3fa10d5f976eaac6ede0445bc47ad5afbd8338f3ef3f7d2e12c5cbef0fd12353d5684aa7736de599daed6ea8b7fbeaa28c9db908545e069197316ae51245ce3f991b160db61a63765a1daa62bc4f9e922e7b942f0bde96396cd0bb1a1066f206cce5ab30bf5e6f368846ec206c329039cd2225c53294228f385cd63343af4841218a1147177ec03b59e06918c881054c5bde1f16c6a69a04622d155eacf999e9cfee0b6addb6e9496d6eaf5ab5f63acb93e8fe325ede2b56aedf1f43dadb5cba99caa77e141310c131918c3f298c0588cb0d00b94088b0ce2115ba50dcbaba8d158f6bb1594fe4190b4e54c0e927b140aaf381a45850ba9d790c2fa48b8257b1064702c9b519e55d153a371843d2930757958a5d540a4c9a5d268649abcd33c45d2d2a2073e3d39fdb7dbb66efb6769e9742eed5cdcd2daf24ee904e6159510f1f9e1fee1478881ac920a5ee2480823780a13861a220c6c74a350e9e1056bedaaf23c3776a3e834f33a870d677ff0bc433a27c1f1516837ddeda9d63367681be2519c6238914236a240770fa82326cc39d2173b74991249e6b69c5fe8e1c2cc6a88859139e54ea08820aa1aac1ce99a9e9cfef0b6addb3ea52d93ae655def06809552e1320fe67589fbc648ffc8100098d639adadc698553c4617c5f38c1cc30786052650d097c22ccd808b8629da63a2919af0bb68fa568177b3398606f142325d3b9e46349b22901a85540ccc8a89741a19cb49d210ac1c4b7e68fd42d345f5b453b5f728201919488a3a10476a711d5d6d8c40fafd6ad5daa79f79f4991bb5cfef3eadfb226bed2b9bcabdc8a2ac4e557f90debd60e1826e63cd497c0e61e00d147924be4ba7097d209ba42ceed91a4324e9f70106a16e438d82fe2d6e1036c7300ad2debd9c8317b34d3c0ae0b592bcda48590cad34c4aa81f9374e0a364af7995874fa858d001df3985158f041922e49f4b0a1efc1742a8790d4923bb76dddf621cd73d892b56d73dbfe14d828ba3c0792ed520e7fdeb3bbe7c1f4fd5be7b4aee1480fcf23bc05c3866366afb1f24294c21eb5622f193498e61e37b2275387dc28ea88bcbe23d53ab4fc293bd7c2cccb740241864a316fe231ad154858cd493c03e1c661fc277b6801c7a5d35e0d1bf2b3686c18db0d54344a98711d24f4d81008f3748d12f7d3fd3bf7bf27a925aaeecacad357fe9631e602c9a3690b891ebb4bdcb729545c6e299f218628425cce637680ba9eb194b05bd0912d08432e355137fa6b6288560c7a0e502c9e4f08c7c9eb3c627e65e4aab957b486705d523637a5a0186320101467822552635d991a479688a7c90fe35a65e42fc3be2c40586507dd7b04d02ee35d71c57609ddf2b8592cdc428703833d837f3270606040338eb91d73e7952be5dfcb3360b54807f07cffbefecfd3f73725b35a1c3e0446ad130486c142293581077df86753d1622c4c13be4becbb05d06d04b1ca0d0f1164951603e1e83543f25fbed04147a8b2c14bcc3bd17ca41c4bca3d148a71aad20a7676c1ea4376e844db00834625c4221ab81e9f4aa29e306a4946476984596e6264e22f9edffefcb6d87259dcbdf8770cf8350fd55004f66d75aa7ad3c8c0885785b7d6764ba4bb60c0299d044b432a007986b8640c1accabd5109a9429f56831523d47332023781090c1070fba36f921a014d1d0e9626abf1149c6c5c43f126ee59315150d2ca9edd60bb10c9b75adc482d2f8de40b84d699e92088ed5a9ea4ddb7fb2fddbb1432a954b255bb62f178d02f38d03117b860e0e7d9d3ead7d5e7bbb2dd935019c4ba70033b4c7ab0d582357a6419ef8aa8d05c81d1d5d203957113225490fa069612c9f148a894611199223e5b57c838f1949ee8c9abc249d8fbd92e0586f8c1afa499407b13148585b84c1a867087bd183619ce0b7db12d5f69feedbbeefe37981c6f253975f678c59cf07416a13b1bc3875c6086f1c3a38d4473fa76349c75a6b6c378da1f9a2cf4229418736136a06082ad222fcab15149584bd19c3919266efb8048f26a2714681aa63854e6364c48aedf45e98041825324a6ddcde7ba41429c18b789574c93838dccbcbfa5e4866d0eb13c9b85dca545b8f8bc5b471c591d0daecb9993e95ea48ffc80786fb8647f20ca4a5d2f262290f12b176ce5e06dcdebba7f7abfc332a6d9553c1804d434c698050ca41f2bc08f70656a0a268496e6cf6610184aa48e1502b50069e40e90414499a0a1a27d14078ab4436b18ce40f85d46ad8dacb720d8c875ae520efa09e801a8e33d900cf80ee9e9e24d720a365741585b6218e457628e6179eaa896228d589eaa7773fb53b5746b36d6e5bbbb1e6fcdcd04ad999ab13d54f4e8e4d4ef2cf2997cba7a517977a0a348d710e54059f5236022814c2445e5c90468fdf83ddd9e4a35731aa4a8c0a237db7204fd2c6859b028541020671b64310b5803ecf521a3beea15bc2f3cb5aae11780e0906e66e8e4db2f53c0886689834d39ad247b25e1007c1d427aff723714feeddb6f73345309c8ec51d1b8c312ba3fde9326a054992dcb6e7993ddf903ea7d452ba58123f4b1788b5d65b24694865c186340d60dec5e4204a4ad852585dbf084f4bf2563c04b311af51a0d019433b3394157cf138512f1949886f229e440368c8fb16eb28140a2941928484a0a7553525353c3e558a4b8b6a9d840d1818c786c6fe616c686cacc8a154da2ae7e41a875481451c1ce91bf94711115bb1f8e452b9748e0671a6c6e14dd8029ffd9acd751450a0205c0321ec5242a84346b3244e94300c49e29049f95251a6aec6caf0c8b4791140ec7a1a8c8676596420cafaa02f0394353b118f9042bc7442149d7c1bfbd2410d8456c1a9f8026014c90200a84e57bfb1eb67bbee2b5a01a80b4c375bf380e989e90ff5efefdf2d7dcebcae79570140ab1772485565dbc84d5235130dd5a25e46ac371441b46016c3784c2461374ab22fa058b162636ee59f85e1125d041ce9fbe0a26f8640bbc86a1b8c7fa58249a8c0bc9e18030dad5ce3a27a8620cc8be32c4bad8c1f70b12289b9978734667a4c0ef70e7faec9f2d81caee71af39ac61848aac9d7f66edbfb0ded73ca2de57325ca77c06e15a46e688f36f53a6a2312d99dc5e94bcdf2b39af03c417e22e51206a2359222289aa7fc423a5dbdb56014aa3a310c5a80a628a4968f489496b2daf0847ec6efc9923ad22e8aa1da471062815c241407bfb3b6dbd4da3d39a0fa9b5527abffba6fc7be679a311004b47995737af19c733fedd9ddf3a148457e7ea95cba40da253de221ef3b278b3e45b6823a82f01e22eb57e36829b48d281801a650e21ef31401c93187f6a2e5025e91d9b17548797b8641f54c6c4e030152502ab65996a3b41049d0c191e61d661c99c51b59034b552da1b98963a443293f99a97df4f5eee9fd52d3e40a84a968ccea773eee1aec19fc9389d18931ed7316752fbac65abb4cf41ae0370d59631ba155dd50b2904bf134018b967b10ad0e22a15b05b95887524cf4103a30b9e858ae47231e8547141ec2cac2230326d34ce0b5132e071543d1ca62b51ce5643b5068d744bd58d39504b3a9e815e765d1da0831b0a989a99b070e0cf4366b20492dd969cb363ff740d836d83bf8df077b07f7c63ea3b5bdf50a0f96350d98d75b2816c23a08a38167bb31a19e883c2d3041671f0f81f2681bb9869107f716a8677065122d27e25ac7c10024e13b898a9e523981af3d4d1301649593d9eb62494c480361d95f42c1f2de1751a7a6d4c988fdfbfb6f9dcdd71eea1bfacee2eec56f07802eed39aee6eee8dbd7f781b1a1b181d87b2d58bc6051a95cba24a056106f40112c5e248cf56c7b7d1642a21c503c248f609adbbd8b56dd254d5d290197a8fa85bc143212a2c2e70b2ae60561eca6a92631a836b37ed2878e0ebd5dceb35093d3578172ee91ce4397f4b138a2353d39fd95fe7dfd076673f8638363fd0b162ef8a74a5be57ddeeb674ef2f6c9f1c94feedfb1ffdb454e6fd7d2aecb8c315d52dcad25e5d2e35a0d24408af8ccf9bc6e43680eea555f57342c920a87cd2e52ce1627b50fae50e2b5d3124309ca04a0cf820c34a4991729c70a824152ce2cdb3302232bc18b051c8c9016f930c6b0c0531de91fb9f350f6867ddbf77d65d9c9cb462a6d95df34d6cc47c4beea54f5b6bee7fb6eaf4e55a78abcb731c654da2b2ff3faad6dd8ef21350bd1104ca2848b8c5eee35843a4414cd9a051d3e77026f844725865490d352cb556ea4454d926fad8e95215e11d1f48c32e5208a6a79037444cf417b42781b2e34baba28bd5b92ffe127c393f081881e16fb3da926f7ecdfb9ffe943759e07761db80300ee30d658f4c6e3167bdfeed3ba2f2997cb978abbadd003924a71d2f0230ba3401fb6495519b30566c30511eb386cca6be4083ed06ed12814acbcb64831da6b8d4594fb3b58f39406ebd2d932b93a58b1116c01559d7c414fbe871987e44982dc817b106e2c0e83e4dd4bf8c9014c8e4ede75e89125f90e0e67359e7ecefc3937041490d44310639060daacf641fa2282e78089d349340f92b3b3cf1649d2e0d222cfcdcb4144c62e0aa2704e90f74165c6392a1e8a160a0b24eca2ba3b2f047a13a4eae19667406054f1b828be2ded0a425d24931e75aebfbfa7ff91c36920b3b92d59b9646db952be92e70c69129ea926523135600c5e4e4eb4428d04e27d1f6207622c949aed3050d3648da4e8e5515ab333d22c0d95783dce819e67a49bbc63f98b600022a9914438b342b144afd234081619aaa3dc6ad3b5bb870f0ef7c131be752ce9781d5868d7646ea295f3d46358f9774df544ca5702c350c62c4477f726f69a98f1895eaa688192b472d3766f11c9127292d9a8b61747b15061de46647eb2932524e859c79720f6c593ee8c8d4b076d4646674d4d4e6d3dd6dea36b595777b9527e71d01b2134417165c1a09e411fb7f196558fb0184199f2041d8a7a8bd8a616dd148d8048c5422ba5204827066479aa51e64ba230f62056f700a5f310c384bd1ce41ac2c8664fde87be19ef15968c422bdc80122b0a5d63f55b757c68fca9636e20cbbb6eb0d6ce0f102a8090ac0721842b215962779d00eb4a50ae9a87cc16b92af0e4d90a44e419a6a483c049869ef62edda01d8af91157f5f71275641191d0755896720fdab115884f238439473327aca84e117b9e4bdc8ebe7d7dcf1d4b0359d4bde894d6b6d6dfccf20d56c5960409328a09cd33a0d1482575e0a9323ba439c8abbd802ca1733817af161e374b8e949ecbc70e888541098635a4c92e1dab06724e41a70fe4e522f4ff7220b215536a17648044fe5613b98c77c294a67a44845ab5f61397383c9606b270f9c2b71863e671b134afae4112f1ac498a7a833a7a25d64278fd04e48ab936da4dad3f9843dff10b235b88850d85d348f8f431914a92d32128b672e7d45762b06f5984ec9826515e7d4484eb72b06e35ec520a36492dd97b2c8de3a435275d54ae94af912adf9a701a429d7b053ea5dd63f64a02080203987a127aad3491676e34457282a2c5c242c6838a11a15ef813498474e066fa5e2e9c3d4867648a454214d69ce645a0d98ec2237c934e147f2c4992a163f5fdca9572cb82450bde628cb119c72aa5a85bf01b9eacaf544261de6c0419e8a390357a4ab48d55a297c3a1853fb3cd4ff2c4b479055c5425a1b33e006501395a54442cf49d66876201f3080293976b5d79bb1e164437b8a711c6f306566fbcb10b53c7ca83ac3a63d56f954aa5b369e59bcffec8f212900775661e05ac2cd9a9e421a917126b1d02a42a52ccf376f566a179a9ae02261a3e8b86c242238ffd6d202c1a03e4aa90884a39b41acf29262027ee018a15845a5a6805916a3a77a146d7e6d5e2c060641664ee33391606b264e592756d73da7e27cd1dd2643b330661916b8b5f137410c32d20b90a0d79a5862865d1aa956e9cdd6e3b9b043c37ef60614d2c9fd094395500a8008ccbd7a32cfb435c9fd8f424f0f929d53d6879a43b848446b03e614d8227451deadfc91c6d0369696da9742deffa6363cd3c4f848125e5dc53886ae860820ec10cc932fa98b36096219fc0c4efc7d9b3779b055562c690e74134a4cae34f41b851c6f2580dc215f30e293f11fe2fe742b2bc079d8f75264df27c98bbea319887c8b48918859955498fba81ac5ebffaade572f95c8e3665c937ed4397264909d411e9bd3c148b8655543d8642ef36a49f703db3dc5ca0d9fcc31cb953cfd125da7fae41ffb4c6e125da80a2a46d103e098f055e04a4965b0c215f7a1f6fa70d144f9493c91be6393f262bd428cd31c69896a36920abce58755d6b7beb6bb304db5851ab96e6076a38850012b131fb9d3114288522a873f0a22d0ae75c6a4738446f1213f69e453213e644a8c0bb28875fbc0018c0c04a3895f2b8d47093799e507a94cc36e77c2b2e1aec8558a6f99394ed0206c5a4c993a02c99f6a365204b572d3d735ee7bc778201237a0de22932e5442161f558bae093f2bcdcc2323abb04d5d63d87177271854b06051f8ddd1ff0505ecaf4c95882ec19005bfc9ea6014bc483cfe0ec70904543a44d416e984213c4b8c1383513a24d454e9a4835219550aacc48e903a57269d1d130908ec51dcb179eb4f07f660541d2cf11ecce266c83f58c86188c5423a10b9df7fdd3a6b5cc3ba1e071b89791a6f11ea176d443362a2d6946bd5828c9f648d3caa464dfcb6d5ca426475e670fe78ed004a0aed3b115c58c4a5be59c23fdb5e675ceeb5a7ecaf2f75b6b5770662d5ddc22391114f40a7214d7a51e105a388c3072673da0d31c7be3c89b3a16db58b5d689c3e6e1bc8ec29c9c439a3318741a724545536cf79006297aa11b917a29954be72f3c69e1eafe7dfdbb8fc4759bdb31b7b37b6df7df94caa533a8c62cef14e4481d0f6b527e15ad7904c443a61d969d4ba5ce44733d090011bdf8d1daec666b20127cab343f49edd86a62ee22de44b82fa09fb030cf7a8515c438962cc56fec839a424350808c812c08f476d24ad7b2ae6b219cb27dc83f0b162d58b662dd8a0f95caa50d124b575cdc1c894a4327e3cf8d0f8a55e827edd96749c9220a75211ea753a81c21bf6d80c5f4855f73380c83874d6cbab177dcfc7c21e846c21ecbe6563211106a6c41228f3202160cf1e4c362bcb2bf093b0d836215ca09a2344536f31c2828c5d338dbcddcd73eb7fdbf2c58b4e03f87fb860f1eaeebb678c5e2758bba17fda52dd99552055b1460307ac55a2afe79efc521743a3e021b5e33f3624ce295cff28e79ed5935111d45af2326d9820148435e3dfd66aa7ac35f038a108896acb313608bc687815cbcc2ba2d32e24a2a104ae89837cd6ae6391dcb4e5ef69b87cb73ac3c63e5af2d5eb1f81f53e3a08b8d2a1ef2852f2ecac8409aec382292fb9e0ca6b0e372d446da74f8f021ede7984456c25c17b19887c2bc4a4168307b8d6349b7a0dc29463d00b2d46d502844852e82610c4c2fb4a8b2c8862b8ae897b6cb31f21a0d2d281cdc3aa7f5bfac5cb7f2f13dcfecf9e16c2fd682450b962e5db5f44d2d6d2d2f0c6a0c063cae150d97b2fe0d8e66c59021218fe3b31bf96c95ec221ab9cec193d4206ccd49e28f959144512960ca36427815781c169e491b893891cc452aeecca0cbfc8205b50e0479308e61c6a1a89b483ba7d4632cd20818abb7be984ce7d2ce3f4b6ac95feddbb16f6b3317a76d4edbdc25ab965c3bb763ee1b8d311dde2ecf0b6da823511cced516a8945f2162361f441c4c84c266c487a09a307c15733bc9108e057a857123951674603088d149000e5d8316cf272547da29780ececf59b990f082c0c76a4ab001e349bb2a2d29f4c7d73b1ee72dec5ef8c1b6b96d9fe9d9dd73c7d8d0d8500e7cbbb07359e7457317cc7d9d2dd9953cac93167f06f30a4209bc06e225f35c58cd14a389aba451c95344bc522efc7b1c205b1a05449aff926760926e96c4ed13efcbd3c90200b366e31af41436b86e2c1f516c15d58e3cada618dc8b28c2795e5f32a28836b8c4f54f4f4e7f7f6264e2a1c9f1c99ea4964c1930a65c29cf6d9dd3bab27d5efba5e54af97cea31b4efcab95341dd03988c8f3585c63a73b576a99b5043c8d4da89600cb31db976b43d48546d5d09b5b229b48e2051ae31c7d21bdbe75858559f849c3d96fe9e602a27d59894ec1a9ec8a1abe720846a4276695f621e4cd83742dc3f9d3215c5eb23497e109b4add67c4ab183460ad5dd83aa7f586d639ad3714862a8def11a55a47a0b79beeda5258c5732d1bf1b60833b2fc84de9e516bb021f1eaf5439810f5933c873856ec105aa28f84d710af73c49b8812b52e84b645c1414079c49f5043d150d8325ff441426ed8e35253151fc1464332cddda3020bb3916e9ea1084dfc5e7c0e18ce33892c9440b2931102b371050a9f8a170853e3f058c9188691bc012d3b6e8381d2062f9a06399c01393c65e7c01c433118753344c1a3a03c9acf837509e1305bf08ecd9411decbbbdf45282b3c490faae3182a2772412f3a75341823dd2c394e52e0e6b22e6cb107491684ed97450653d26310730c7a6e2c783592dc7c0d4dd8db8fc2d862307e4fb6f10113da8119edb3891c371e276575d193a050d453206e71a09292c407e8170af94be4f10ce6f5d8a0101a80d7226b7c648b4294419f0828420e2827ea5ec3bd2149391306e3c5355104ac4825dfe4cc13e763d3b897031384a1da66c30b81b4b7830f90f1206f72fe380d47ecd106734c61dca6bc09422ead2436b3928761de2c4b5e7414c22c9ee34aac9272a07bca75ae3862c2795a82e7d02e785154855e649ed34852933c9c29928368d2389ac6979a53306fe1b10b8409c15e7d47806f83a95c05ce1bef323cae6f8a066e00bf3223e1ea9b019ccb0dc1c90624199c4865497310fac5028f20c4cd7ca7cce279abc08d057b45d4764e69780a4bdac53911184170680e65408557b550c513d3230611c0ae443e13ac40a7a1c6c61bc4e82c700caf815a943cd688550183d6c69d05420b2e271771a857ea35168190d0079c2ec81bc18642724e1276696661d08a0b11d9d266eb230a8ac5abcc5c752fea3d94294d819c284fe09b587552ed82de1fe418d697808dcab946282cc1f935c78f71884622d141f28c43f00aa2c468a4b390875912b2e68d600b4619b0d021a8aa03233892451d5479b198f75087c74bb345786f3b1879049c24df4f60db40304f5097f4720e6878047e9c01f99025e45ecec2ce572a501184a842853e083fe1f82e0e06df59a17a88ff63581be1219614721549e225e0808665656d37e36891c81be261850b119858f85438ecc2d0ab7832a52cf412c32d6de8241a95ba211987346ace3b8714dc30a842be4102aecd55d18c4053283c418a84da35e6e441917602188c0bf70c024262a4e865385ae604aa497671b8583545052c0479050f01f8c45b4df14e2ab0cd0a22a4281a0aaa29b1758272b55fd423e6f40e69b810030bb273892630285e94e55e52044920ae205858b8ef187b1275ea981466a1bca3abde004045b80ae72402c34317af06a540881814b544b950be239a820681f91734780f291e4705ba158c22ab9c1bbf1f9c873781fabdf313ec60b73742a5968685f4bc59c11b44c4c40bc3bbc743a28e05bd07e8b9841862391f16f6fa4304744a320809eaf5432cf029251c25526b1f528597864082ae6a2c4e2e3ae0310f368c2d0c0f7593fa3bd0874a3d4f418d034d58ab911acd84f04e3280d4d8c4ef8efae6923b8fe504a98504c600180db978af8797734806153196ecbc3a08bc57c383185f8c8bcf0491fa143cbe16833969d59d5ef4a62e22c6779fc3796baaf22f685289b41bd6f341fbeb3d8a0bfa6d056a4808f124fdb88476f3902c2139f61e77281a8b681cceaf7fa8a199e22934b0a82c8d32e00b5c2a6e79751360b48c48bc9c0793aaf241450d6516bab35ed8c83c4cb06b3308d643f2a43a0dfb5e946316847c7c1c9ee44d9ab6fee3cf3024a8570dabc0dfd9bdb1ce39f492d8e39a37e1215e592adc88a8880b615caf739027e9ec3d0a5788737699a206550802a5cf678a911efd067d2d2aa99029d56f82704bab4ff01c25d6e39f3fc3f9f884783107c9ca9305454180c10974780afd12b44bacc063385f841b6a394b34856ab136822d3b603bcb90a660035114e9c2e2b98c07d3f2e771094f08bd268780b39ccd109aba91bf372f94e6369a09862851498a18fd719d7fa022b5837aaf48c6e2e50b9a514c525a8a582f01b92d970b3da4cf0b0b85ace7001d367a1478f2ede40454f21e4d7b8e4875b8a88a63f4fd4c08f906e3c362b244a04c563505767203017d3d40b9840d2bb60b1fb7b987560fc1f854b140c79988346470b00be1ddcc38504ed6bd1a0984c9394ff2cb9c6ce88d4050c404384e1f2c160c29e587622c45f393a6e2f218082124f185e06992a7512e9638aa0e14c545854f55a45e744435780f73fd23d77368f988e419147a0a359680f12be872052a28800d55138aff6b3ba604f37a7d0d2624d3d1b10959889033cc3e975bd46498a54e5a22f987175eb1045a4ad469ef86e78d20a27068c209c1227c2b0830343b14f3b87626316a1116f02492b40f5342098c0341ac9da8de0d81f483b085e50924a39fa807bb9d8de424da6c75f48b77d169534d1a47d33516c1b349df49fd9e34bfd0144a80cd568178df3eafce7b39d1897ed3d04869122dea9e440a89f2e05f2937910cc90bb182b89ec6c08ea02ada1aa346e30a24a33414e1c5c466d08fc3b8597ad4f28891a552401c991259cb7cce220f39314719d11cd9633eae7212854dab111963a896e85950a1b30bfdec9c0a5f168b7ac0a8ed0e44a44b846fd1a79f67853189490b38fb0b8f87f35aa15fd5667fd3e769147e4efa14774c23d4534021589e28c9f7e148d8250e5601544b0aadc4fc245207117319d2feeb7b10de73ce69151809978c125201642a1f5e3e808778f19b80795505446cc2b368a122840a23d2ec0e9aa37098394ab4c4e621f2e31ee2e5c786f9c612857f85d02a0baf04e928b1900842d721c04c1d24d579e2026e060da0c5b0930dc206268d6497855ed202c0823b261ee60bc342202dcfe0cc5d6f414b6dca468eb3bd862e3e89aac9ca3ffe22c459a8d746a24816f314dc9034b4ca8113fbda03ad35c73ed37175f7948ce7660cc3db451de978e3de2592ecf28b5a84957a34119a4063982f4c5e4844b9ff9b1b8d9adc23e442ca85370673621a441ef42b4ea345ddc348157031fc92544c5203e232423407f17854e90576306324028a15f4512be107152cd0ea22c71aaa140d56e36219b92aefe52246ee93a1cfd384214e24a8f6701b90daa7ce3d72c1c45d0ab99c6bf0b828a2e519077bbfb23a7b9a1804ef8e0b92f4d41862eda15ccce138dafd82c21d84dab8de85e2541064136841115650204e034766aef90907f5720fcbc74083b09023c6a126eed2742925a7294bd87c504dcfd97db385c11417e98cef687d448b200ea1fbb05078157b7f231b8e16434719cc263e26e184189d7614c22f690481f877c48b88b908e4ab367a9f81a0f4a40b626601bf8ac19f741a9218730b06a1c5eb8562d7c3b47a44f94e2589f72aeb42622f1aafd12f749e88c52f557485fae610101b0b6afa4a86211985847af1d796250c1f1d7a3a576277616a288674c399fc45e99d8b1c45c42389d8883508890283101d732d4a20e51cffac3de1891c7a15bdce0544a5c5fc24621819e4cb0c4e320e6e80b2aa493ab35c1a4a9f8aa081d27a4a254cf9dc0f01e357179639f4139ffb9e39f3dda5d6dba6e26921afc9e3a11db6633deeed2502ede6791927205a390858f6b753e44d955c44f42012f5c4ebb8733ea2e3a97744720c35a63f5a8b41124f68c6a072ea363151883c23fcd50dd4a136411f92621c92770926e80a6895ea81808957073c23865a69350ea96d371a5a0982ccb949ed113496bce6a3285f4c237bfeb285508723d4ca698ce3230af2e8f25123401d29f35a6ec5f984522598d1e0bd5979a02b9d78a898008d4a8b4454163c148f932b1755a07f1e9b78bf48f27fa228201eb5104b3beebc9118a08f5c8b4990aa8a29201b91c7c5f2facca52e3b5607f07ab58590457a1f0e8d6a2726180a7a8476ad9851a91ca93c0e57e4bb4743aa5f92fa47a17382f17034b78f44320c2dcc02ddc3fcdf0100a730d358a3b06f300000000049454e44ae426082');
SELECT pg_catalog.lo_close(0);

COMMIT;

SET search_path = public, pg_catalog;

--
-- Name: tb_image_pkey; Type: CONSTRAINT; Schema: public; Owner: gdadmin; Tablespace: 
--

ALTER TABLE ONLY tb_image
    ADD CONSTRAINT tb_image_pkey PRIMARY KEY (id);


--
-- Name: tb_info_pkey; Type: CONSTRAINT; Schema: public; Owner: gdadmin; Tablespace: 
--

ALTER TABLE ONLY tb_info
    ADD CONSTRAINT tb_info_pkey PRIMARY KEY (id);


--
-- Name: tb_memo_pkey; Type: CONSTRAINT; Schema: public; Owner: gdadmin; Tablespace: 
--

ALTER TABLE ONLY tb_memo
    ADD CONSTRAINT tb_memo_pkey PRIMARY KEY (id);


--
-- Name: tb_news_pkey; Type: CONSTRAINT; Schema: public; Owner: gdadmin; Tablespace: 
--

ALTER TABLE ONLY tb_news
    ADD CONSTRAINT tb_news_pkey PRIMARY KEY (id);


--
-- Name: tb_proyect_pkey; Type: CONSTRAINT; Schema: public; Owner: gdadmin; Tablespace: 
--

ALTER TABLE ONLY tb_proyect
    ADD CONSTRAINT tb_proyect_pkey PRIMARY KEY (id);


--
-- Name: tb_proyecttype_pkey; Type: CONSTRAINT; Schema: public; Owner: gdadmin; Tablespace: 
--

ALTER TABLE ONLY tb_proyecttype
    ADD CONSTRAINT tb_proyecttype_pkey PRIMARY KEY (id);


--
-- Name: tb_service_pkey; Type: CONSTRAINT; Schema: public; Owner: gdadmin; Tablespace: 
--

ALTER TABLE ONLY tb_service
    ADD CONSTRAINT tb_service_pkey PRIMARY KEY (id);


--
-- Name: tb_user_pkey; Type: CONSTRAINT; Schema: public; Owner: gdadmin; Tablespace: 
--

ALTER TABLE ONLY tb_user
    ADD CONSTRAINT tb_user_pkey PRIMARY KEY (cc);


--
-- Name: tb_usertype_pkey; Type: CONSTRAINT; Schema: public; Owner: gdadmin; Tablespace: 
--

ALTER TABLE ONLY tb_usertype
    ADD CONSTRAINT tb_usertype_pkey PRIMARY KEY (id);


--
-- Name: t_delete_info_image; Type: TRIGGER; Schema: public; Owner: gdadmin
--

CREATE TRIGGER t_delete_info_image AFTER DELETE ON tb_info FOR EACH ROW EXECUTE PROCEDURE f_delete_info_image();


--
-- Name: t_delete_news_image; Type: TRIGGER; Schema: public; Owner: gdadmin
--

CREATE TRIGGER t_delete_news_image AFTER DELETE ON tb_news FOR EACH ROW EXECUTE PROCEDURE f_delete_image();


--
-- Name: t_delete_proyect_image; Type: TRIGGER; Schema: public; Owner: gdadmin
--

CREATE TRIGGER t_delete_proyect_image AFTER DELETE ON tb_proyect FOR EACH ROW EXECUTE PROCEDURE f_delete_image();


--
-- Name: t_delete_user_image; Type: TRIGGER; Schema: public; Owner: gdadmin
--

CREATE TRIGGER t_delete_user_image AFTER DELETE ON tb_user FOR EACH ROW EXECUTE PROCEDURE f_delete_image();


--
-- Name: t_update_news_image; Type: TRIGGER; Schema: public; Owner: gdadmin
--

CREATE TRIGGER t_update_news_image BEFORE UPDATE ON tb_news FOR EACH ROW EXECUTE PROCEDURE f_update_image();


--
-- Name: t_update_proyect_image; Type: TRIGGER; Schema: public; Owner: gdadmin
--

CREATE TRIGGER t_update_proyect_image BEFORE UPDATE ON tb_proyect FOR EACH ROW EXECUTE PROCEDURE f_update_image();


--
-- Name: tb_memo_cc_owner_fkey; Type: FK CONSTRAINT; Schema: public; Owner: gdadmin
--

ALTER TABLE ONLY tb_memo
    ADD CONSTRAINT tb_memo_cc_owner_fkey FOREIGN KEY (cc_owner) REFERENCES tb_user(cc);


--
-- Name: tb_news_cc_owner_fkey; Type: FK CONSTRAINT; Schema: public; Owner: gdadmin
--

ALTER TABLE ONLY tb_news
    ADD CONSTRAINT tb_news_cc_owner_fkey FOREIGN KEY (cc_owner) REFERENCES tb_user(cc);


--
-- Name: tb_news_id_image_fkey; Type: FK CONSTRAINT; Schema: public; Owner: gdadmin
--

ALTER TABLE ONLY tb_news
    ADD CONSTRAINT tb_news_id_image_fkey FOREIGN KEY (id_image) REFERENCES tb_image(id);


--
-- Name: tb_proyect_cc_client_fkey; Type: FK CONSTRAINT; Schema: public; Owner: gdadmin
--

ALTER TABLE ONLY tb_proyect
    ADD CONSTRAINT tb_proyect_cc_client_fkey FOREIGN KEY (cc_client) REFERENCES tb_user(cc);


--
-- Name: tb_service_cc_owner_fkey; Type: FK CONSTRAINT; Schema: public; Owner: gdadmin
--

ALTER TABLE ONLY tb_service
    ADD CONSTRAINT tb_service_cc_owner_fkey FOREIGN KEY (cc_owner) REFERENCES tb_user(cc);


--
-- Name: tb_user_id_image_fkey; Type: FK CONSTRAINT; Schema: public; Owner: gdadmin
--

ALTER TABLE ONLY tb_user
    ADD CONSTRAINT tb_user_id_image_fkey FOREIGN KEY (id_image) REFERENCES tb_image(id);


--
-- Name: tb_user_id_usertype_fkey; Type: FK CONSTRAINT; Schema: public; Owner: gdadmin
--

ALTER TABLE ONLY tb_user
    ADD CONSTRAINT tb_user_id_usertype_fkey FOREIGN KEY (id_usertype) REFERENCES tb_usertype(id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

