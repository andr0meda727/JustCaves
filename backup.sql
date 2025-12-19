--
-- PostgreSQL database dump
--

\restrict fyGd6KUt97aNVxnfYchyBpvYcInmfKjdrAGpvJCnQhxhCupEbmugz4rUpNCHsL6

-- Dumped from database version 18.1 (Debian 18.1-1.pgdg13+2)
-- Dumped by pg_dump version 18.1 (Debian 18.1-1.pgdg13+2)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: cave_status_type; Type: TYPE; Schema: public; Owner: docker
--

CREATE TYPE public.cave_status_type AS ENUM (
    'PENDING',
    'APPROVED',
    'REJECTED'
);


ALTER TYPE public.cave_status_type OWNER TO docker;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: cave_ratings; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.cave_ratings (
    id integer NOT NULL,
    user_id integer NOT NULL,
    cave_id integer NOT NULL,
    difficulty_score integer NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT check_difficulty_range CHECK (((difficulty_score >= 1) AND (difficulty_score <= 10)))
);


ALTER TABLE public.cave_ratings OWNER TO docker;

--
-- Name: cave_ratings_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.cave_ratings_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.cave_ratings_id_seq OWNER TO docker;

--
-- Name: cave_ratings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.cave_ratings_id_seq OWNED BY public.cave_ratings.id;


--
-- Name: cave_visits; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.cave_visits (
    user_id integer NOT NULL,
    cave_id integer NOT NULL
);


ALTER TABLE public.cave_visits OWNER TO docker;

--
-- Name: caves; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.caves (
    id integer NOT NULL,
    name character varying NOT NULL,
    description text NOT NULL,
    region_id integer NOT NULL,
    latitude numeric(10,8),
    longitude numeric(11,8),
    map_image_path character varying,
    difficulty_avg numeric,
    author_id integer NOT NULL,
    status public.cave_status_type DEFAULT 'PENDING'::public.cave_status_type NOT NULL,
    approved_by integer,
    created_at date NOT NULL,
    updated_at date NOT NULL
);


ALTER TABLE public.caves OWNER TO docker;

--
-- Name: comments; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.comments (
    id bigint NOT NULL,
    cave_id integer NOT NULL,
    author_id integer NOT NULL,
    content text NOT NULL,
    created_at date NOT NULL
);


ALTER TABLE public.comments OWNER TO docker;

--
-- Name: regions; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.regions (
    id integer NOT NULL,
    name character varying NOT NULL,
    description text
);


ALTER TABLE public.regions OWNER TO docker;

--
-- Name: roles; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.roles (
    id integer NOT NULL,
    name character varying NOT NULL
);


ALTER TABLE public.roles OWNER TO docker;

--
-- Name: users; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.users (
    id integer NOT NULL,
    username character varying NOT NULL,
    email character varying NOT NULL,
    password_hash character varying NOT NULL,
    role_id integer NOT NULL,
    created_at timestamp without time zone NOT NULL
);


ALTER TABLE public.users OWNER TO docker;

--
-- Name: cave_ratings id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.cave_ratings ALTER COLUMN id SET DEFAULT nextval('public.cave_ratings_id_seq'::regclass);


--
-- Data for Name: cave_ratings; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.cave_ratings (id, user_id, cave_id, difficulty_score, created_at) FROM stdin;
\.


--
-- Data for Name: cave_visits; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.cave_visits (user_id, cave_id) FROM stdin;
\.


--
-- Data for Name: caves; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.caves (id, name, description, region_id, latitude, longitude, map_image_path, difficulty_avg, author_id, status, approved_by, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: comments; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.comments (id, cave_id, author_id, content, created_at) FROM stdin;
\.


--
-- Data for Name: regions; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.regions (id, name, description) FROM stdin;
\.


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.roles (id, name) FROM stdin;
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.users (id, username, email, password_hash, role_id, created_at) FROM stdin;
\.


--
-- Name: cave_ratings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.cave_ratings_id_seq', 1, false);


--
-- Name: cave_ratings cave_ratings_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.cave_ratings
    ADD CONSTRAINT cave_ratings_pkey PRIMARY KEY (id);


--
-- Name: cave_visits cave_visits_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.cave_visits
    ADD CONSTRAINT cave_visits_pkey PRIMARY KEY (user_id, cave_id);


--
-- Name: caves caves_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.caves
    ADD CONSTRAINT caves_pkey PRIMARY KEY (id);


--
-- Name: comments comments_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_pkey PRIMARY KEY (id);


--
-- Name: regions regions_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.regions
    ADD CONSTRAINT regions_pkey PRIMARY KEY (id);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: cave_ratings unique_user_cave_rating; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.cave_ratings
    ADD CONSTRAINT unique_user_cave_rating UNIQUE (user_id, cave_id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: users users_username_unique; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_username_unique UNIQUE (username);


--
-- Name: cave_ratings cave_ratings_cave_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.cave_ratings
    ADD CONSTRAINT cave_ratings_cave_id_fkey FOREIGN KEY (cave_id) REFERENCES public.caves(id) ON DELETE CASCADE;


--
-- Name: cave_ratings cave_ratings_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.cave_ratings
    ADD CONSTRAINT cave_ratings_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: caves caves_regions_fk; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.caves
    ADD CONSTRAINT caves_regions_fk FOREIGN KEY (region_id) REFERENCES public.regions(id) NOT VALID;


--
-- Name: caves caves_users_approved_by_fk; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.caves
    ADD CONSTRAINT caves_users_approved_by_fk FOREIGN KEY (approved_by) REFERENCES public.users(id) NOT VALID;


--
-- Name: caves caves_users_author_fk; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.caves
    ADD CONSTRAINT caves_users_author_fk FOREIGN KEY (author_id) REFERENCES public.users(id) NOT VALID;


--
-- Name: comments comments_cave_fk; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_cave_fk FOREIGN KEY (cave_id) REFERENCES public.caves(id) NOT VALID;


--
-- Name: comments comments_users_fk; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_users_fk FOREIGN KEY (author_id) REFERENCES public.users(id) NOT VALID;


--
-- Name: cave_visits visits_cave_fk; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.cave_visits
    ADD CONSTRAINT visits_cave_fk FOREIGN KEY (cave_id) REFERENCES public.caves(id) ON DELETE CASCADE;


--
-- Name: cave_visits visits_user_fk; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.cave_visits
    ADD CONSTRAINT visits_user_fk FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict fyGd6KUt97aNVxnfYchyBpvYcInmfKjdrAGpvJCnQhxhCupEbmugz4rUpNCHsL6

