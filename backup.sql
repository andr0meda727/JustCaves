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

INSERT INTO public.roles (id, name) VALUES 
    (1, 'user'),
    (2, 'moderator'),
    (3, 'admin')
ON CONFLICT (id) DO NOTHING;


INSERT INTO public.regions (name, description) VALUES 
    ('Tatry', 'Najwyższe góry w Polsce, posiadające najgłębsze i najdłuższe jaskinie krasowe.'),
    ('Góry Świętokrzyskie', 'Region z jaskiniami takimi jak Jaskinia Raj, charakteryzujący się specyficzną budową geologiczną.'),
    ('Wyżyna Krakowsko-Częstochowska', 'Jura, słynąca z tysięcy mniejszych jaskiń i schronisk w wapieniach jurajskich.'),
    ('Sudety', 'Góry z jaskiniami takimi jak Jaskinia Niedźwiedzia, jedne z najstarszych w Polsce.'),
    ('Pieniny', 'Region wapienny z mniejszymi, ale urokliwymi jaskiniami.');


INSERT INTO public.users (username, email, password_hash, role_id) VALUES 
    ('admin', 'admin@justcaves.com', '$2y$10$vY3tYfN18jZ9I.7hU6X7GeA0Y4Jq4Rj/5h7L4Q0L5N/8D2G8O6hH.', 3)
ON CONFLICT (username) DO NOTHING;


SELECT setval(pg_get_serial_sequence('roles', 'id'), (SELECT MAX(id) FROM public.roles));
SELECT setval(pg_get_serial_sequence('regions', 'id'), (SELECT MAX(id) FROM public.regions));
SELECT setval(pg_get_serial_sequence('users', 'id'), (SELECT MAX(id) FROM public.users));

-- Oblicza średnią ocenę jaskini na podstawie tabeli cave_ratings
CREATE OR REPLACE FUNCTION get_cave_avg_rating(p_cave_id INT)
RETURNS NUMERIC AS $$
BEGIN
    RETURN (SELECT ROUND(AVG(difficulty_score), 1) FROM public.cave_ratings WHERE cave_id = p_cave_id);
END;
$$ LANGUAGE plpgsql;

-- Pełne informacje o jaskiniach
CREATE OR REPLACE VIEW v_caves_details AS
SELECT 
    c.*, 
    r.name AS region_name, 
    u.username AS author_name,
    get_cave_avg_rating(c.id) AS calculated_rating
FROM public.caves c
LEFT JOIN public.regions r ON c.region_id = r.id
LEFT JOIN public.users u ON c.author_id = u.id;

-- Statystyki użytkowników
CREATE OR REPLACE VIEW v_user_activity AS
SELECT 
    u.id, 
    u.username, 
    u.email, 
    u.role_id,
    u.created_at,
    (SELECT COUNT(*) FROM public.cave_visits cv WHERE cv.user_id = u.id) AS visited_count
FROM public.users u;

-- 4. FUNKCJA I WYZWALACZ (TRIGGER): Automatyczna aktualizacja daty 'updated_at'
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER tr_caves_updated_at
BEFORE UPDATE ON public.caves
FOR EACH ROW
EXECUTE PROCEDURE update_updated_at_column();