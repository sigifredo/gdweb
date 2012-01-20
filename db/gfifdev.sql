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
-- Name: f_insertadmin(character varying, character varying, character varying, character varying, character varying, character varying, character varying); Type: FUNCTION; Schema: public; Owner: gdadmin
--

CREATE FUNCTION f_insertadmin(cc1 character varying, password2 character varying, names3 character varying, lastnames4 character varying, telephone5 character varying, movil6 character varying, image7 character varying) RETURNS void
    LANGUAGE plpgsql
    AS $_$BEGIN
  IF $7 = '' THEN
    INSERT INTO tb_user (cc, password, names, lastnames, telephone, movil, id_usertype) VALUES ($1, $2, $3, $4, $5, $6, 1);
  ELSE
    INSERT INTO tb_user (cc, password, names, lastnames, telephone, movil, id_usertype, image) VALUES ($1, $2, $3, $4, $5, $6, 1, $7);
  END IF;
END;
$_$;


ALTER FUNCTION public.f_insertadmin(cc1 character varying, password2 character varying, names3 character varying, lastnames4 character varying, telephone5 character varying, movil6 character varying, image7 character varying) OWNER TO gdadmin;

--
-- Name: f_insertclient(character varying, character varying, character varying, character varying, character varying, character varying, character varying); Type: FUNCTION; Schema: public; Owner: gdadmin
--

CREATE FUNCTION f_insertclient(cc1 character varying, password2 character varying, names3 character varying, lastnames4 character varying, telephone5 character varying, movil6 character varying, image7 character varying) RETURNS void
    LANGUAGE plpgsql
    AS $_$BEGIN
  IF $7 = '' THEN
    INSERT INTO tb_user (cc, password, names, lastnames, telephone, movil, id_usertype) VALUES ($1, $2, $3, $4, $5, $6, 2);
  ELSE
    INSERT INTO tb_user (cc, password, names, lastnames, telephone, movil, id_usertype, image) VALUES ($1, $2, $3, $4, $5, $6, 2, lo_import($7));
  END IF;
END;$_$;


ALTER FUNCTION public.f_insertclient(cc1 character varying, password2 character varying, names3 character varying, lastnames4 character varying, telephone5 character varying, movil6 character varying, image7 character varying) OWNER TO gdadmin;

--
-- Name: f_insertdeveloper(character varying, character varying, character varying, character varying, character varying, character varying, character varying); Type: FUNCTION; Schema: public; Owner: gdadmin
--

CREATE FUNCTION f_insertdeveloper(cc1 character varying, password2 character varying, names3 character varying, lastnames4 character varying, telephone5 character varying, movil6 character varying, image7 character varying) RETURNS void
    LANGUAGE plpgsql
    AS $_$BEGIN
  IF $7 = '' THEN
    INSERT INTO tb_user (cc, password, names, lastnames, telephone, movil, id_usertype) VALUES ($1, $2, $3, $4, $5, $6, 3);
  ELSE
    INSERT INTO tb_user (cc, password, names, lastnames, telephone, movil, id_usertype, image) VALUES ($1, $2, $3, $4, $5, $6, 3, $7);
  END IF;
END;
$_$;


ALTER FUNCTION public.f_insertdeveloper(cc1 character varying, password2 character varying, names3 character varying, lastnames4 character varying, telephone5 character varying, movil6 character varying, image7 character varying) OWNER TO gdadmin;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: tb_information; Type: TABLE; Schema: public; Owner: gdadmin; Tablespace: 
--

CREATE TABLE tb_information (
    id integer NOT NULL,
    title character varying(20) NOT NULL,
    description text NOT NULL,
    cc_owner character varying(10) NOT NULL,
    image oid NOT NULL
);


ALTER TABLE public.tb_information OWNER TO gdadmin;

--
-- Name: tb_information_id_seq; Type: SEQUENCE; Schema: public; Owner: gdadmin
--

CREATE SEQUENCE tb_information_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tb_information_id_seq OWNER TO gdadmin;

--
-- Name: tb_information_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gdadmin
--

ALTER SEQUENCE tb_information_id_seq OWNED BY tb_information.id;


--
-- Name: tb_information_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gdadmin
--

SELECT pg_catalog.setval('tb_information_id_seq', 1, false);


--
-- Name: tb_news; Type: TABLE; Schema: public; Owner: gdadmin; Tablespace: 
--

CREATE TABLE tb_news (
    id integer NOT NULL,
    title character varying(20) NOT NULL,
    description text NOT NULL,
    cc_owner character varying(10) NOT NULL,
    image oid NOT NULL,
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

SELECT pg_catalog.setval('tb_news_id_seq', 1, false);


--
-- Name: tb_user; Type: TABLE; Schema: public; Owner: gdadmin; Tablespace: 
--

CREATE TABLE tb_user (
    cc character varying(10) NOT NULL,
    password character varying(40) NOT NULL,
    names character varying(25) NOT NULL,
    lastnames character varying(25) NOT NULL,
    telephone character varying(7),
    movil character varying(10),
    id_usertype integer NOT NULL,
    image oid NOT NULL
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
-- Name: version; Type: TABLE; Schema: public; Owner: gdadmin; Tablespace: 
--

CREATE TABLE version (
    version real NOT NULL
);


ALTER TABLE public.version OWNER TO gdadmin;

--
-- Name: id; Type: DEFAULT; Schema: public; Owner: gdadmin
--

ALTER TABLE tb_information ALTER COLUMN id SET DEFAULT nextval('tb_information_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: gdadmin
--

ALTER TABLE tb_news ALTER COLUMN id SET DEFAULT nextval('tb_news_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: gdadmin
--

ALTER TABLE tb_usertype ALTER COLUMN id SET DEFAULT nextval('tb_usertype_id_seq'::regclass);


--
-- Data for Name: tb_information; Type: TABLE DATA; Schema: public; Owner: gdadmin
--

COPY tb_information (id, title, description, cc_owner, image) FROM stdin;
\.


--
-- Data for Name: tb_news; Type: TABLE DATA; Schema: public; Owner: gdadmin
--

COPY tb_news (id, title, description, cc_owner, image, date) FROM stdin;
\.


--
-- Data for Name: tb_user; Type: TABLE DATA; Schema: public; Owner: gdadmin
--

COPY tb_user (cc, password, names, lastnames, telephone, movil, id_usertype, image) FROM stdin;
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
2
\.


--
-- Name: tb_information_pkey; Type: CONSTRAINT; Schema: public; Owner: gdadmin; Tablespace: 
--

ALTER TABLE ONLY tb_information
    ADD CONSTRAINT tb_information_pkey PRIMARY KEY (id);


--
-- Name: tb_news_pkey; Type: CONSTRAINT; Schema: public; Owner: gdadmin; Tablespace: 
--

ALTER TABLE ONLY tb_news
    ADD CONSTRAINT tb_news_pkey PRIMARY KEY (id);


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
-- Name: tb_information_cc_owner_fkey; Type: FK CONSTRAINT; Schema: public; Owner: gdadmin
--

ALTER TABLE ONLY tb_information
    ADD CONSTRAINT tb_information_cc_owner_fkey FOREIGN KEY (cc_owner) REFERENCES tb_user(cc);


--
-- Name: tb_news_cc_owner_fkey; Type: FK CONSTRAINT; Schema: public; Owner: gdadmin
--

ALTER TABLE ONLY tb_news
    ADD CONSTRAINT tb_news_cc_owner_fkey FOREIGN KEY (cc_owner) REFERENCES tb_user(cc);


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

