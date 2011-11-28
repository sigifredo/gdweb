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

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: tb_information; Type: TABLE; Schema: public; Owner: gdadmin; Tablespace: 
--

CREATE TABLE tb_information (
    id integer NOT NULL,
    title character varying(10) NOT NULL,
    description text NOT NULL,
    image oid NOT NULL,
    cc_user character varying(10) NOT NULL
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
    title character varying(15) NOT NULL,
    description character varying(50) NOT NULL,
    news text NOT NULL,
    "time" timestamp without time zone DEFAULT now() NOT NULL,
    image oid,
    cc_user character varying(10) NOT NULL
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

SELECT pg_catalog.setval('tb_news_id_seq', 2, true);


--
-- Name: tb_user; Type: TABLE; Schema: public; Owner: gdadmin; Tablespace: 
--

CREATE TABLE tb_user (
    cc character varying(10) NOT NULL,
    names character varying(30) NOT NULL,
    lastnames character varying(30) NOT NULL,
    password character varying(41) NOT NULL,
    level integer DEFAULT 1 NOT NULL
);


ALTER TABLE public.tb_user OWNER TO gdadmin;

--
-- Name: id; Type: DEFAULT; Schema: public; Owner: gdadmin
--

ALTER TABLE tb_information ALTER COLUMN id SET DEFAULT nextval('tb_information_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: gdadmin
--

ALTER TABLE tb_news ALTER COLUMN id SET DEFAULT nextval('tb_news_id_seq'::regclass);


--
-- Data for Name: tb_information; Type: TABLE DATA; Schema: public; Owner: gdadmin
--

COPY tb_information (id, title, description, image, cc_user) FROM stdin;
\.


--
-- Data for Name: tb_news; Type: TABLE DATA; Schema: public; Owner: gdadmin
--

COPY tb_news (id, title, description, news, "time", image, cc_user) FROM stdin;
1	Titulo 1	Descripción 1	Noticia 1	2011-10-29 15:18:13.685288	\N	1128422444
2	Titulo 2	Descripción 2	Noticia 2	2011-10-29 15:18:51.220063	\N	1128422444
\.


--
-- Data for Name: tb_user; Type: TABLE DATA; Schema: public; Owner: gdadmin
--

COPY tb_user (cc, names, lastnames, password, level) FROM stdin;
1128422444	Sigifredo	Escobar Gómez		1
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
-- Name: tb_information_cc_user_fkey; Type: FK CONSTRAINT; Schema: public; Owner: gdadmin
--

ALTER TABLE ONLY tb_information
    ADD CONSTRAINT tb_information_cc_user_fkey FOREIGN KEY (cc_user) REFERENCES tb_user(cc);


--
-- Name: tb_news_cc_user_fkey; Type: FK CONSTRAINT; Schema: public; Owner: gdadmin
--

ALTER TABLE ONLY tb_news
    ADD CONSTRAINT tb_news_cc_user_fkey FOREIGN KEY (cc_user) REFERENCES tb_user(cc);


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

