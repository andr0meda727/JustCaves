CREATE TYPE public.cave_status_type AS ENUM ('PENDING', 'APPROVED', 'REJECTED');

CREATE TABLE public.roles (
    id SERIAL PRIMARY KEY,
    name character varying NOT NULL
);

CREATE TABLE public.users (
    id SERIAL PRIMARY KEY,
    username character varying NOT NULL UNIQUE,
    email character varying NOT NULL UNIQUE,
    password_hash character varying NOT NULL,
    role_id integer NOT NULL REFERENCES public.roles(id),
    created_at timestamp without time zone NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE public.regions (
    id SERIAL PRIMARY KEY,
    name character varying NOT NULL,
    description text
);

CREATE TABLE public.caves (
    id SERIAL PRIMARY KEY,
    name character varying NOT NULL,
    description text NOT NULL,
    region_id integer NOT NULL REFERENCES public.regions(id),
    latitude numeric(10,8),
    longitude numeric(11,8),
    map_image_path character varying,
    author_id integer NOT NULL REFERENCES public.users(id),
    status public.cave_status_type DEFAULT 'PENDING' NOT NULL,
    approved_by integer REFERENCES public.users(id),
    created_at date NOT NULL DEFAULT CURRENT_DATE,
    updated_at date NOT NULL DEFAULT CURRENT_DATE
);

CREATE TABLE public.cave_ratings (
    id SERIAL PRIMARY KEY,
    user_id integer NOT NULL REFERENCES public.users(id) ON DELETE CASCADE,
    cave_id integer NOT NULL REFERENCES public.caves(id) ON DELETE CASCADE,
    difficulty_score integer NOT NULL CHECK (difficulty_score >= 1 AND difficulty_score <= 10),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT unique_user_cave_rating UNIQUE (user_id, cave_id)
);

CREATE TABLE public.cave_visits (
    user_id integer NOT NULL REFERENCES public.users(id) ON DELETE CASCADE,
    cave_id integer NOT NULL REFERENCES public.caves(id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, cave_id)
);

CREATE TABLE public.comments (
    id BIGSERIAL PRIMARY KEY,
    cave_id integer NOT NULL REFERENCES public.caves(id),
    author_id integer NOT NULL REFERENCES public.users(id),
    content text NOT NULL,
    created_at date NOT NULL DEFAULT CURRENT_DATE
);

-- Wstawianie podstawowych ról
INSERT INTO public.roles (id, name) VALUES 
    (1, 'user'),
    (2, 'moderator'),
    (3, 'admin')
ON CONFLICT (id) DO NOTHING;


-- Resetowanie liczników sekwencji
SELECT setval(pg_get_serial_sequence('roles', 'id'), 4, false);