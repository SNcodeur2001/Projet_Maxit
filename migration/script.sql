--
-- PostgreSQL database dump
--

-- Dumped from database version 16.9 (Ubuntu 16.9-0ubuntu0.24.04.1)
-- Dumped by pg_dump version 16.9 (Ubuntu 16.9-0ubuntu0.24.04.1)

-- Started on 2025-07-11 23:19:42 GMT

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 845 (class 1247 OID 16747)
-- Name: profil_type; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.profil_type AS ENUM (
    'CLIENT',
    'SERVICE_COMMERCIAL'
);


ALTER TYPE public.profil_type OWNER TO postgres;

--
-- TOC entry 860 (class 1247 OID 16806)
-- Name: statut_type; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.statut_type AS ENUM (
    'COMPTE_PRINCIPAL',
    'COMPTE_SECONDAIRE'
);


ALTER TYPE public.statut_type OWNER TO postgres;

--
-- TOC entry 848 (class 1247 OID 16752)
-- Name: transaction_type; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.transaction_type AS ENUM (
    'DEPOT',
    'RETRAIT',
    'PAIEMENT'
);


ALTER TYPE public.transaction_type OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 218 (class 1259 OID 16773)
-- Name: compte; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.compte (
    id integer NOT NULL,
    utilisateur_id integer NOT NULL,
    numero character varying(20) NOT NULL,
    solde numeric(10,2) DEFAULT 0,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    statut public.statut_type DEFAULT 'COMPTE_SECONDAIRE'::public.statut_type NOT NULL
);


ALTER TABLE public.compte OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 16772)
-- Name: compte_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.compte_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.compte_id_seq OWNER TO postgres;

--
-- TOC entry 3453 (class 0 OID 0)
-- Dependencies: 217
-- Name: compte_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.compte_id_seq OWNED BY public.compte.id;


--
-- TOC entry 220 (class 1259 OID 16793)
-- Name: transaction; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.transaction (
    id integer NOT NULL,
    compte_id integer NOT NULL,
    type public.transaction_type NOT NULL,
    montant numeric(10,2) NOT NULL,
    libelle character varying(255),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.transaction OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 16792)
-- Name: transaction_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.transaction_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.transaction_id_seq OWNER TO postgres;

--
-- TOC entry 3454 (class 0 OID 0)
-- Dependencies: 219
-- Name: transaction_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.transaction_id_seq OWNED BY public.transaction.id;


--
-- TOC entry 216 (class 1259 OID 16760)
-- Name: utilisateur; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.utilisateur (
    id integer NOT NULL,
    nom character varying(100) NOT NULL,
    prenom character varying(100) NOT NULL,
    adresse character varying(255),
    telephone character varying(20) NOT NULL,
    numero_piece_identite character varying(50),
    photo_recto character varying(255),
    photo_verso character varying(255),
    profil public.profil_type DEFAULT 'CLIENT'::public.profil_type NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.utilisateur OWNER TO postgres;

--
-- TOC entry 215 (class 1259 OID 16759)
-- Name: utilisateur_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.utilisateur_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.utilisateur_id_seq OWNER TO postgres;

--
-- TOC entry 3455 (class 0 OID 0)
-- Dependencies: 215
-- Name: utilisateur_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.utilisateur_id_seq OWNED BY public.utilisateur.id;


--
-- TOC entry 3279 (class 2604 OID 16776)
-- Name: compte id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.compte ALTER COLUMN id SET DEFAULT nextval('public.compte_id_seq'::regclass);


--
-- TOC entry 3283 (class 2604 OID 16796)
-- Name: transaction id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transaction ALTER COLUMN id SET DEFAULT nextval('public.transaction_id_seq'::regclass);


--
-- TOC entry 3276 (class 2604 OID 16763)
-- Name: utilisateur id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.utilisateur ALTER COLUMN id SET DEFAULT nextval('public.utilisateur_id_seq'::regclass);


--
-- TOC entry 3445 (class 0 OID 16773)
-- Dependencies: 218
-- Data for Name: compte; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.compte (id, utilisateur_id, numero, solde, created_at, statut) FROM stdin;
1	1	CP001	20000.00	2025-07-09 00:39:50.165408	COMPTE_SECONDAIRE
2	1	CS002	5000.00	2025-07-09 00:39:55.398993	COMPTE_SECONDAIRE
3	6	MAX4962030386	0.00	2025-07-10 07:10:21.404064	COMPTE_PRINCIPAL
10	13	MAX3169095625	0.00	2025-07-10 20:44:05.426663	COMPTE_PRINCIPAL
13	25	MAX2778251456	0.00	2025-07-10 21:43:04.005636	COMPTE_PRINCIPAL
14	26	MAX2604805805	0.00	2025-07-11 12:23:23.902168	COMPTE_PRINCIPAL
\.


--
-- TOC entry 3447 (class 0 OID 16793)
-- Dependencies: 220
-- Data for Name: transaction; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.transaction (id, compte_id, type, montant, libelle, created_at) FROM stdin;
1	1	DEPOT	20000.00	Solde initial	2025-07-09 00:40:06.160938
2	1	PAIEMENT	5000.00	Paiement SENELEC	2025-07-09 00:40:06.160938
3	2	DEPOT	5000.00	Montant envoyé depuis compte principal	2025-07-09 00:40:06.160938
4	1	DEPOT	10000.00	Versement initial	2025-07-10 23:21:46.514272
5	1	PAIEMENT	2500.00	Paiement abonnement Orange	2025-07-10 23:21:46.514272
6	1	RETRAIT	2000.00	Retrait au guichet	2025-07-10 23:21:46.514272
7	2	DEPOT	3000.00	Transfert reçu	2025-07-10 23:21:46.514272
8	2	PAIEMENT	1500.00	Achat boutique Dakar	2025-07-10 23:21:46.514272
9	1	PAIEMENT	1800.00	Paiement Wari	2025-07-10 23:21:46.514272
10	1	DEPOT	5000.00	Solde rechargé	2025-07-10 23:21:46.514272
11	2	RETRAIT	1000.00	\N	2025-07-10 23:21:46.514272
12	2	DEPOT	4500.00	Versement depuis principal	2025-07-10 23:21:46.514272
13	1	RETRAIT	1200.00	Retrait distributeur automatique	2025-07-10 23:21:46.514272
\.


--
-- TOC entry 3443 (class 0 OID 16760)
-- Dependencies: 216
-- Data for Name: utilisateur; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.utilisateur (id, nom, prenom, adresse, telephone, numero_piece_identite, photo_recto, photo_verso, profil, created_at) FROM stdin;
1	Ndiaye	Fatou	Dakar, Pikine	771234567	CNI123456	fatou_recto.jpg	fatou_verso.jpg	CLIENT	2025-07-09 00:39:35.949164
2	Sow	Mamadou	\N	780001122	\N	\N	\N	SERVICE_COMMERCIAL	2025-07-09 00:39:35.949164
3	Ndiaye	Mapathe	dakar	771234565	CNI12432124	recto_686f58444a3ea_1752127556.png	verso_686f58444a5f7_1752127556.png	CLIENT	2025-07-10 06:05:56.30914
6	allou	Alassane	Dakar	778965432	CNI12432128	recto_686f675d62476_1752131421.png	verso_686f675d62868_1752131421.png	CLIENT	2025-07-10 07:10:21.404064
13	Ndiaye	Mapathe	Dakar	779874532	1243212423455	recto_687026156807d_1752180245.png	verso_68702615681f6_1752180245.png	CLIENT	2025-07-10 20:44:05.426663
25	Ndiaye	Mapathe	sdds	784620621	1243212423459	recto_687033e8014b5_1752183784.png	verso_687033e8015a4_1752183784.png	CLIENT	2025-07-10 21:43:04.005636
26	Ndiaye	Mapathe	AZ	771234568	1243212423421	recto_6871023bdc190_1752236603.png	verso_6871023bdc34b_1752236603.png	CLIENT	2025-07-11 12:23:23.902168
\.


--
-- TOC entry 3456 (class 0 OID 0)
-- Dependencies: 217
-- Name: compte_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.compte_id_seq', 14, true);


--
-- TOC entry 3457 (class 0 OID 0)
-- Dependencies: 219
-- Name: transaction_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.transaction_id_seq', 13, true);


--
-- TOC entry 3458 (class 0 OID 0)
-- Dependencies: 215
-- Name: utilisateur_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.utilisateur_id_seq', 26, true);


--
-- TOC entry 3292 (class 2606 OID 16784)
-- Name: compte compte_numero_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.compte
    ADD CONSTRAINT compte_numero_key UNIQUE (numero);


--
-- TOC entry 3294 (class 2606 OID 16782)
-- Name: compte compte_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.compte
    ADD CONSTRAINT compte_pkey PRIMARY KEY (id);


--
-- TOC entry 3296 (class 2606 OID 16799)
-- Name: transaction transaction_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transaction
    ADD CONSTRAINT transaction_pkey PRIMARY KEY (id);


--
-- TOC entry 3286 (class 2606 OID 16817)
-- Name: utilisateur utilisateur_piece_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.utilisateur
    ADD CONSTRAINT utilisateur_piece_unique UNIQUE (numero_piece_identite);


--
-- TOC entry 3288 (class 2606 OID 16769)
-- Name: utilisateur utilisateur_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.utilisateur
    ADD CONSTRAINT utilisateur_pkey PRIMARY KEY (id);


--
-- TOC entry 3290 (class 2606 OID 16771)
-- Name: utilisateur utilisateur_telephone_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.utilisateur
    ADD CONSTRAINT utilisateur_telephone_key UNIQUE (telephone);


--
-- TOC entry 3297 (class 2606 OID 16785)
-- Name: compte compte_utilisateur_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.compte
    ADD CONSTRAINT compte_utilisateur_id_fkey FOREIGN KEY (utilisateur_id) REFERENCES public.utilisateur(id) ON DELETE CASCADE;


--
-- TOC entry 3298 (class 2606 OID 16800)
-- Name: transaction transaction_compte_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transaction
    ADD CONSTRAINT transaction_compte_id_fkey FOREIGN KEY (compte_id) REFERENCES public.compte(id) ON DELETE CASCADE;


-- Completed on 2025-07-11 23:19:42 GMT

--
-- PostgreSQL database dump complete
--

