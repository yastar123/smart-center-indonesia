--
-- PostgreSQL database dump
--

\restrict DdWlYGckQKr94MDHQuyjAurkfNM6y84iAAeh5Ql6gKqJye0iV9GpJIlJc3hM7Dc

-- Dumped from database version 16.10
-- Dumped by pg_dump version 16.10

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

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: absensi_gurus; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.absensi_gurus (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    jadwal_id bigint NOT NULL,
    guru_id bigint NOT NULL,
    status character varying(20) DEFAULT 'hadir'::character varying NOT NULL,
    catatan text
);


--
-- Name: absensi_gurus_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.absensi_gurus_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: absensi_gurus_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.absensi_gurus_id_seq OWNED BY public.absensi_gurus.id;


--
-- Name: absensi_siswas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.absensi_siswas (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    jadwal_id bigint NOT NULL,
    siswa_id bigint NOT NULL,
    status character varying(20) DEFAULT 'hadir'::character varying NOT NULL,
    catatan text,
    guru_hadir boolean DEFAULT false NOT NULL,
    siswa_konfirmasi_at timestamp(0) without time zone
);


--
-- Name: absensi_siswas_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.absensi_siswas_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: absensi_siswas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.absensi_siswas_id_seq OWNED BY public.absensi_siswas.id;


--
-- Name: academic_years; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.academic_years (
    id bigint NOT NULL,
    name character varying(20) NOT NULL,
    year_start integer NOT NULL,
    year_end integer NOT NULL,
    is_active boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: academic_years_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.academic_years_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: academic_years_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.academic_years_id_seq OWNED BY public.academic_years.id;


--
-- Name: activity_log; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.activity_log (
    id bigint NOT NULL,
    log_name character varying(255),
    description text NOT NULL,
    subject_type character varying(255),
    subject_id bigint,
    causer_type character varying(255),
    causer_id bigint,
    properties json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    event character varying(255),
    batch_uuid uuid
);


--
-- Name: activity_log_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.activity_log_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: activity_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.activity_log_id_seq OWNED BY public.activity_log.id;


--
-- Name: activity_logs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.activity_logs (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: activity_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.activity_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: activity_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.activity_logs_id_seq OWNED BY public.activity_logs.id;


--
-- Name: announcements; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.announcements (
    id bigint NOT NULL,
    cabang_id bigint,
    dibuat_oleh bigint,
    judul character varying(255) NOT NULL,
    konten text NOT NULL,
    jenis character varying(30) DEFAULT 'info'::character varying NOT NULL,
    target character varying(30) DEFAULT 'semua'::character varying NOT NULL,
    file character varying(255),
    tanggal_mulai date,
    tanggal_selesai date,
    is_pinned boolean DEFAULT false NOT NULL,
    status character varying(20) DEFAULT 'aktif'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    target_teacher_ids json,
    target_student_ids json
);


--
-- Name: announcements_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.announcements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: announcements_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.announcements_id_seq OWNED BY public.announcements.id;


--
-- Name: branches; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.branches (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    city character varying(255) NOT NULL,
    admin_id bigint,
    student_count integer DEFAULT 0 NOT NULL,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    regency character varying(255),
    user_id bigint,
    email character varying(255),
    password character varying(255),
    can_students boolean DEFAULT true NOT NULL,
    can_teachers boolean DEFAULT true NOT NULL,
    can_schedules boolean DEFAULT true NOT NULL,
    can_payments boolean DEFAULT true NOT NULL,
    can_tryouts boolean DEFAULT true NOT NULL,
    address character varying(255),
    phone character varying(255),
    created_by bigint,
    updated_by bigint,
    allowed_pages json,
    CONSTRAINT branches_status_check CHECK (((status)::text = ANY (ARRAY[('active'::character varying)::text, ('inactive'::character varying)::text])))
);


--
-- Name: branches_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.branches_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: branches_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.branches_id_seq OWNED BY public.branches.id;


--
-- Name: categories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.categories (
    id bigint NOT NULL,
    name character varying(100) NOT NULL,
    slug character varying(120),
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: categories_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.categories_id_seq OWNED BY public.categories.id;


--
-- Name: certificates; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.certificates (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    siswa_id bigint,
    cabang_id bigint,
    diterbitkan_oleh character varying(200),
    nomor_sertifikat character varying(255),
    jenis character varying(50),
    judul character varying(255),
    deskripsi text,
    tanggal_terbit date,
    tanggal_expired date,
    file_sertifikat character varying(255),
    file_qrcode character varying(255),
    course_id bigint
);


--
-- Name: certificates_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.certificates_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: certificates_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.certificates_id_seq OWNED BY public.certificates.id;


--
-- Name: chat_messages; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.chat_messages (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    room_id bigint NOT NULL,
    pengirim_id bigint NOT NULL,
    jenis character varying(20) DEFAULT 'teks'::character varying NOT NULL,
    pesan text,
    file_path character varying(255),
    dibaca_oleh json,
    is_deleted boolean DEFAULT false NOT NULL
);


--
-- Name: chat_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.chat_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: chat_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.chat_messages_id_seq OWNED BY public.chat_messages.id;


--
-- Name: chat_rooms; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.chat_rooms (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    nama_room character varying(100) NOT NULL,
    jenis_room character varying(20) DEFAULT 'grup'::character varying NOT NULL,
    cabang_id bigint,
    peserta_id json,
    waktu_pesan_terakhir timestamp(0) without time zone
);


--
-- Name: chat_rooms_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.chat_rooms_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: chat_rooms_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.chat_rooms_id_seq OWNED BY public.chat_rooms.id;


--
-- Name: class_students; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.class_students (
    id bigint NOT NULL,
    class_id bigint NOT NULL,
    student_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: class_students_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.class_students_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: class_students_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.class_students_id_seq OWNED BY public.class_students.id;


--
-- Name: course_fees; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.course_fees (
    id bigint NOT NULL,
    course_id bigint NOT NULL,
    amount numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: course_fees_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.course_fees_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: course_fees_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.course_fees_id_seq OWNED BY public.course_fees.id;


--
-- Name: course_package; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.course_package (
    package_id bigint NOT NULL,
    course_id bigint NOT NULL
);


--
-- Name: courses; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.courses (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    cabang_id bigint,
    kode character varying(20),
    nama character varying(100),
    deskripsi text,
    status character varying(255) DEFAULT 'aktif'::character varying NOT NULL,
    deleted_at timestamp(0) without time zone,
    kategori character varying(50) DEFAULT 'academic'::character varying NOT NULL,
    CONSTRAINT courses_status_check CHECK (((status)::text = ANY (ARRAY[('aktif'::character varying)::text, ('nonaktif'::character varying)::text])))
);


--
-- Name: courses_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.courses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: courses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.courses_id_seq OWNED BY public.courses.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: gajis; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gajis (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gajis_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gajis_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gajis_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gajis_id_seq OWNED BY public.gajis.id;


--
-- Name: grades; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.grades (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    siswa_id bigint NOT NULL,
    mata_pelajaran_id bigint,
    guru_id bigint,
    semester_id bigint,
    jenis_penilaian character varying(50) NOT NULL,
    nama_penilaian character varying(100),
    nilai numeric(5,2) NOT NULL,
    nilai_maksimal numeric(5,2) DEFAULT '100'::numeric NOT NULL,
    bobot numeric(5,2) DEFAULT '1'::numeric NOT NULL,
    tanggal date,
    catatan text
);


--
-- Name: grades_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.grades_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: grades_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.grades_id_seq OWNED BY public.grades.id;


--
-- Name: guru_mapel; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.guru_mapel (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: guru_mapel_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.guru_mapel_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: guru_mapel_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.guru_mapel_id_seq OWNED BY public.guru_mapel.id;


--
-- Name: gurus; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.gurus (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: gurus_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.gurus_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: gurus_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.gurus_id_seq OWNED BY public.gurus.id;


--
-- Name: invoices; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.invoices (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    siswa_id bigint,
    cabang_id bigint,
    nomor_invoice character varying(255),
    subtotal numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    diskon numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    pajak numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    total numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    deskripsi text,
    periode character varying(50),
    jatuh_tempo date,
    status character varying(255) DEFAULT 'belum_bayar'::character varying NOT NULL,
    catatan text,
    deleted_at timestamp(0) without time zone,
    kelas_id bigint,
    CONSTRAINT invoices_status_check CHECK (((status)::text = ANY (ARRAY[('belum_bayar'::character varying)::text, ('sebagian'::character varying)::text, ('lunas'::character varying)::text])))
);


--
-- Name: invoices_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.invoices_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: invoices_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.invoices_id_seq OWNED BY public.invoices.id;


--
-- Name: jadwals; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.jadwals (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: jadwals_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.jadwals_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: jadwals_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.jadwals_id_seq OWNED BY public.jadwals.id;


--
-- Name: kelas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.kelas (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: kelas_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.kelas_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: kelas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.kelas_id_seq OWNED BY public.kelas.id;


--
-- Name: kelas_siswa; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.kelas_siswa (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: kelas_siswa_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.kelas_siswa_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: kelas_siswa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.kelas_siswa_id_seq OWNED BY public.kelas_siswa.id;


--
-- Name: landing_programs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.landing_programs (
    id bigint NOT NULL,
    title character varying(255) NOT NULL,
    description text NOT NULL,
    badge_label character varying(80) DEFAULT 'PROGRAM'::character varying NOT NULL,
    badge_bg character varying(255) DEFAULT 'rgba(200,77,223,.1)'::character varying NOT NULL,
    badge_color character varying(255) DEFAULT '#68117e'::character varying NOT NULL,
    icon_emoji character varying(10) DEFAULT '📖'::character varying NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    is_popular boolean DEFAULT false NOT NULL,
    is_new boolean DEFAULT false NOT NULL,
    sort_order smallint DEFAULT '0'::smallint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: landing_programs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.landing_programs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: landing_programs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.landing_programs_id_seq OWNED BY public.landing_programs.id;


--
-- Name: landing_settings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.landing_settings (
    id bigint NOT NULL,
    section character varying(60) NOT NULL,
    key character varying(100) NOT NULL,
    value text,
    type character varying(30) DEFAULT 'text'::character varying NOT NULL,
    label character varying(150) NOT NULL,
    sort_order smallint DEFAULT '0'::smallint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: landing_settings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.landing_settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: landing_settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.landing_settings_id_seq OWNED BY public.landing_settings.id;


--
-- Name: landing_testimonials; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.landing_testimonials (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    role character varying(255) NOT NULL,
    text text NOT NULL,
    gradient character varying(255) DEFAULT 'linear-gradient(135deg,#c84ddf,#68117e)'::character varying NOT NULL,
    initial character varying(5) DEFAULT 'A'::character varying NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    sort_order smallint DEFAULT '0'::smallint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: landing_testimonials_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.landing_testimonials_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: landing_testimonials_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.landing_testimonials_id_seq OWNED BY public.landing_testimonials.id;


--
-- Name: landing_wa_numbers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.landing_wa_numbers (
    id bigint NOT NULL,
    label character varying(255) NOT NULL,
    number character varying(30) NOT NULL,
    description character varying(255),
    is_primary boolean DEFAULT false NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    sort_order smallint DEFAULT '0'::smallint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: landing_wa_numbers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.landing_wa_numbers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: landing_wa_numbers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.landing_wa_numbers_id_seq OWNED BY public.landing_wa_numbers.id;


--
-- Name: mapel_paket; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.mapel_paket (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: mapel_paket_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.mapel_paket_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mapel_paket_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.mapel_paket_id_seq OWNED BY public.mapel_paket.id;


--
-- Name: mapels; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.mapels (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: mapels_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.mapels_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mapels_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.mapels_id_seq OWNED BY public.mapels.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: model_has_permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.model_has_permissions (
    permission_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);


--
-- Name: model_has_roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.model_has_roles (
    role_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);


--
-- Name: modules; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.modules (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    mata_pelajaran_id bigint,
    diupload_oleh bigint,
    judul character varying(200) NOT NULL,
    deskripsi text,
    jenis character varying(20) NOT NULL,
    file_path character varying(255),
    file_url character varying(255),
    ukuran_file bigint,
    is_gratis boolean DEFAULT false NOT NULL,
    status character varying(20) DEFAULT 'draft'::character varying NOT NULL,
    jumlah_download integer DEFAULT 0 NOT NULL,
    deleted_at timestamp(0) without time zone,
    kode_modul character varying(30)
);


--
-- Name: modules_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.modules_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: modules_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.modules_id_seq OWNED BY public.modules.id;


--
-- Name: moduls; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.moduls (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: moduls_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.moduls_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: moduls_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.moduls_id_seq OWNED BY public.moduls.id;


--
-- Name: nilais; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.nilais (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: nilais_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.nilais_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: nilais_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.nilais_id_seq OWNED BY public.nilais.id;


--
-- Name: package_course_teachers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.package_course_teachers (
    id bigint NOT NULL,
    package_id bigint NOT NULL,
    course_id bigint NOT NULL,
    teacher_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: package_course_teachers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.package_course_teachers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: package_course_teachers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.package_course_teachers_id_seq OWNED BY public.package_course_teachers.id;


--
-- Name: packages; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.packages (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    cabang_id bigint,
    nama character varying(150) NOT NULL,
    deskripsi text,
    harga numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    durasi_bulan integer DEFAULT 1 NOT NULL,
    jumlah_pertemuan integer DEFAULT 1 NOT NULL,
    jenis character varying(50) NOT NULL,
    fitur json,
    is_unggulan boolean DEFAULT false NOT NULL,
    status character varying(20) DEFAULT 'aktif'::character varying NOT NULL,
    deleted_at timestamp(0) without time zone,
    guru_id bigint,
    metode_absensi character varying(30) DEFAULT 'manual'::character varying NOT NULL,
    tipe_kelas character varying(30) DEFAULT 'offline'::character varying NOT NULL
);


--
-- Name: packages_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.packages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: packages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.packages_id_seq OWNED BY public.packages.id;


--
-- Name: pakets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.pakets (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: pakets_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.pakets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: pakets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.pakets_id_seq OWNED BY public.pakets.id;


--
-- Name: password_resets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_resets (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


--
-- Name: payments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.payments (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    invoice_id bigint,
    siswa_id bigint,
    cabang_id bigint,
    nomor_pembayaran character varying(255),
    jumlah numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    metode character varying(255) DEFAULT 'cash'::character varying NOT NULL,
    nama_bank character varying(255),
    nomor_rekening character varying(255),
    bukti_pembayaran character varying(255),
    tanggal_pembayaran date,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    alasan_penolakan text,
    catatan text,
    disetujui_oleh bigint,
    tanggal_disetujui timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT payments_metode_check CHECK (((metode)::text = ANY (ARRAY[('cash'::character varying)::text, ('transfer'::character varying)::text, ('qris'::character varying)::text]))),
    CONSTRAINT payments_status_check CHECK (((status)::text = ANY (ARRAY[('pending'::character varying)::text, ('verified'::character varying)::text, ('rejected'::character varying)::text])))
);


--
-- Name: payments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.payments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: payments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.payments_id_seq OWNED BY public.payments.id;


--
-- Name: pembayarans; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.pembayarans (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: pembayarans_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.pembayarans_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: pembayarans_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.pembayarans_id_seq OWNED BY public.pembayarans.id;


--
-- Name: permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.permissions (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.permissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;


--
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- Name: questions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.questions (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    tryout_id bigint NOT NULL,
    teks_pertanyaan text NOT NULL,
    gambar_pertanyaan character varying(255),
    jenis character varying(30) DEFAULT 'pilihan_ganda'::character varying NOT NULL,
    pilihan_jawaban json,
    penjelasan text,
    poin numeric(5,2) DEFAULT '1'::numeric NOT NULL,
    urutan integer DEFAULT 1 NOT NULL,
    tingkat_kesulitan character varying(20) DEFAULT 'sedang'::character varying NOT NULL,
    kunci_jawaban character varying(10)
);


--
-- Name: questions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.questions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: questions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.questions_id_seq OWNED BY public.questions.id;


--
-- Name: role_has_permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.role_has_permissions (
    permission_id bigint NOT NULL,
    role_id bigint NOT NULL
);


--
-- Name: roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.roles (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- Name: salaries; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.salaries (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    guru_id bigint NOT NULL,
    cabang_id bigint,
    periode character varying(20) NOT NULL,
    gaji_pokok numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    jam_mengajar numeric(6,1),
    tarif_per_jam numeric(12,2),
    total_gaji_mengajar numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    bonus numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    potongan numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    total_gaji numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    metode_pembayaran character varying(50),
    nama_bank character varying(50),
    nomor_rekening character varying(50),
    tanggal_pembayaran date,
    status character varying(20) DEFAULT 'pending'::character varying NOT NULL,
    catatan text,
    dibayar_oleh bigint,
    deleted_at timestamp(0) without time zone,
    bukti_pembayaran character varying(255),
    tipe_gaji character varying(50) DEFAULT 'bulanan'::character varying NOT NULL
);


--
-- Name: salaries_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.salaries_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: salaries_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.salaries_id_seq OWNED BY public.salaries.id;


--
-- Name: schedule_proposal_approvals; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.schedule_proposal_approvals (
    id bigint NOT NULL,
    proposal_id bigint NOT NULL,
    approver_type character varying(255) NOT NULL,
    approver_id bigint NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    responded_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT schedule_proposal_approvals_approver_type_check CHECK (((approver_type)::text = ANY ((ARRAY['guru'::character varying, 'siswa'::character varying])::text[]))),
    CONSTRAINT schedule_proposal_approvals_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'approved'::character varying, 'rejected'::character varying])::text[])))
);


--
-- Name: schedule_proposal_approvals_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.schedule_proposal_approvals_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: schedule_proposal_approvals_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.schedule_proposal_approvals_id_seq OWNED BY public.schedule_proposal_approvals.id;


--
-- Name: schedule_proposals; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.schedule_proposals (
    id bigint NOT NULL,
    class_id bigint NOT NULL,
    pertemuan_ke smallint,
    proposed_by_type character varying(255) NOT NULL,
    proposed_by_id bigint NOT NULL,
    tanggal date NOT NULL,
    jam_mulai time(0) without time zone NOT NULL,
    jam_selesai time(0) without time zone NOT NULL,
    jenis character varying(255) DEFAULT 'offline'::character varying NOT NULL,
    ruangan character varying(255),
    link_meeting character varying(255),
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    schedule_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT schedule_proposals_jenis_check CHECK (((jenis)::text = ANY ((ARRAY['online'::character varying, 'offline'::character varying, 'private'::character varying])::text[]))),
    CONSTRAINT schedule_proposals_proposed_by_type_check CHECK (((proposed_by_type)::text = ANY ((ARRAY['guru'::character varying, 'siswa'::character varying])::text[]))),
    CONSTRAINT schedule_proposals_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'approved'::character varying, 'rejected'::character varying])::text[])))
);


--
-- Name: COLUMN schedule_proposals.schedule_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.schedule_proposals.schedule_id IS 'Filled after approved';


--
-- Name: schedule_proposals_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.schedule_proposals_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: schedule_proposals_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.schedule_proposals_id_seq OWNED BY public.schedule_proposals.id;


--
-- Name: schedule_student_agreements; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.schedule_student_agreements (
    id bigint NOT NULL,
    schedule_id bigint NOT NULL,
    student_id bigint NOT NULL,
    guru_confirmed_at timestamp(0) without time zone,
    siswa_confirmed_at timestamp(0) without time zone,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT schedule_student_agreements_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'agreed'::character varying])::text[])))
);


--
-- Name: schedule_student_agreements_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.schedule_student_agreements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: schedule_student_agreements_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.schedule_student_agreements_id_seq OWNED BY public.schedule_student_agreements.id;


--
-- Name: schedules; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.schedules (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    kelas_id bigint,
    guru_id bigint,
    cabang_id bigint,
    tanggal date,
    jam_mulai time(0) without time zone,
    jam_selesai time(0) without time zone,
    topik character varying(255),
    jenis character varying(255) DEFAULT 'offline'::character varying NOT NULL,
    ruangan character varying(255),
    link_meeting character varying(255),
    status character varying(255) DEFAULT 'dijadwalkan'::character varying NOT NULL,
    catatan text,
    reminder_terkirim boolean DEFAULT false NOT NULL,
    deleted_at timestamp(0) without time zone,
    pertemuan_ke smallint,
    tanggal_selesai date,
    paket_id bigint,
    module_id bigint,
    mata_pelajaran_id bigint,
    CONSTRAINT schedules_jenis_check CHECK (((jenis)::text = ANY ((ARRAY['online'::character varying, 'offline'::character varying, 'private'::character varying])::text[]))),
    CONSTRAINT schedules_status_check CHECK (((status)::text = ANY (ARRAY[('dijadwalkan'::character varying)::text, ('berlangsung'::character varying)::text, ('selesai'::character varying)::text, ('dibatalkan'::character varying)::text])))
);


--
-- Name: schedules_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.schedules_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: schedules_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.schedules_id_seq OWNED BY public.schedules.id;


--
-- Name: school_classes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.school_classes (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    cabang_id bigint,
    mata_pelajaran_id bigint,
    guru_id bigint,
    tahun_akademik_id bigint,
    nama character varying(255),
    nama_kelas character varying(255),
    kapasitas smallint DEFAULT '30'::smallint NOT NULL,
    jenis character varying(255) DEFAULT 'offline'::character varying NOT NULL,
    link_zoom character varying(255),
    status character varying(255) DEFAULT 'aktif'::character varying NOT NULL,
    deleted_at timestamp(0) without time zone,
    jumlah_pertemuan smallint DEFAULT '1'::smallint NOT NULL,
    billing_mode character varying(20) DEFAULT 'prepaid'::character varying,
    CONSTRAINT school_classes_jenis_check CHECK (((jenis)::text = ANY ((ARRAY['online'::character varying, 'offline'::character varying, 'private'::character varying])::text[]))),
    CONSTRAINT school_classes_status_check CHECK (((status)::text = ANY (ARRAY[('aktif'::character varying)::text, ('nonaktif'::character varying)::text])))
);


--
-- Name: school_classes_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.school_classes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: school_classes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.school_classes_id_seq OWNED BY public.school_classes.id;


--
-- Name: semesters; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.semesters (
    id bigint NOT NULL,
    academic_year_id bigint NOT NULL,
    name character varying(20) NOT NULL,
    semester_number smallint NOT NULL,
    start_date date NOT NULL,
    end_date date NOT NULL,
    is_active boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: semesters_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.semesters_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: semesters_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.semesters_id_seq OWNED BY public.semesters.id;


--
-- Name: siswas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.siswas (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: siswas_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.siswas_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: siswas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.siswas_id_seq OWNED BY public.siswas.id;


--
-- Name: student_course_payments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.student_course_payments (
    id bigint NOT NULL,
    student_id bigint NOT NULL,
    course_id bigint NOT NULL,
    amount numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    proof character varying(255),
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    catatan text,
    rejected_reason text,
    verified_by bigint,
    CONSTRAINT student_course_payments_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'verified'::character varying, 'rejected'::character varying])::text[])))
);


--
-- Name: student_course_payments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.student_course_payments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: student_course_payments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.student_course_payments_id_seq OWNED BY public.student_course_payments.id;


--
-- Name: student_registrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.student_registrations (
    id bigint NOT NULL,
    no_reg character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    phone character varying(255),
    gender character varying(255),
    birth_place character varying(255),
    birth_date date,
    address text,
    parent_name character varying(255),
    parent_phone character varying(255),
    job character varying(255),
    program character varying(255),
    system character varying(255),
    learning_place character varying(255),
    pickup_mode character varying(255),
    branch character varying(255),
    interests json,
    day_preferences json,
    schedule_time character varying(255),
    start_date date,
    notes text,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    education_level character varying(255)
);


--
-- Name: student_registrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.student_registrations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: student_registrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.student_registrations_id_seq OWNED BY public.student_registrations.id;


--
-- Name: student_teachers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.student_teachers (
    id bigint NOT NULL,
    student_id bigint NOT NULL,
    teacher_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: student_teachers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.student_teachers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: student_teachers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.student_teachers_id_seq OWNED BY public.student_teachers.id;


--
-- Name: students; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.students (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    branch_id bigint,
    user_id bigint,
    nis character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    gender character varying(255) NOT NULL,
    birth_date date,
    birth_place character varying(255),
    address text,
    phone character varying(255),
    parent_name character varying(255),
    parent_phone character varying(255),
    photo character varying(255),
    status character varying(255) DEFAULT 'aktif'::character varying NOT NULL,
    join_date date,
    school_name character varying(255),
    grade character varying(255),
    kategori_peserta_didik character varying(255),
    package_id bigint,
    CONSTRAINT students_gender_check CHECK (((gender)::text = ANY (ARRAY[('L'::character varying)::text, ('P'::character varying)::text])))
);


--
-- Name: students_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.students_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: students_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.students_id_seq OWNED BY public.students.id;


--
-- Name: system_settings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.system_settings (
    id bigint NOT NULL,
    key character varying(100) NOT NULL,
    value text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: system_settings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.system_settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: system_settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.system_settings_id_seq OWNED BY public.system_settings.id;


--
-- Name: tagihans; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.tagihans (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: tagihans_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.tagihans_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tagihans_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.tagihans_id_seq OWNED BY public.tagihans.id;


--
-- Name: tahun_ajarans; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.tahun_ajarans (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: tahun_ajarans_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.tahun_ajarans_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tahun_ajarans_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.tahun_ajarans_id_seq OWNED BY public.tahun_ajarans.id;


--
-- Name: teacher_courses; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.teacher_courses (
    id bigint NOT NULL,
    teacher_id bigint NOT NULL,
    course_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: teacher_courses_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.teacher_courses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: teacher_courses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.teacher_courses_id_seq OWNED BY public.teacher_courses.id;


--
-- Name: teachers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.teachers (
    id bigint NOT NULL,
    branch_id bigint,
    nig character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    gender character varying(255),
    birth_date date,
    birth_place character varying(255),
    address text,
    phone character varying(255),
    email character varying(255),
    education character varying(255),
    subjects text,
    photo character varying(255),
    salary_base numeric(15,2) DEFAULT '0'::numeric NOT NULL,
    join_date date,
    status character varying(255) DEFAULT 'aktif'::character varying NOT NULL,
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    user_id bigint,
    cv_path character varying(255),
    jenis_guru character varying(20),
    CONSTRAINT teachers_gender_check CHECK (((gender)::text = ANY (ARRAY[('L'::character varying)::text, ('P'::character varying)::text]))),
    CONSTRAINT teachers_status_check CHECK (((status)::text = ANY (ARRAY[('aktif'::character varying)::text, ('nonaktif'::character varying)::text])))
);


--
-- Name: teachers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.teachers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: teachers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.teachers_id_seq OWNED BY public.teachers.id;


--
-- Name: tryout_attempts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.tryout_attempts (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    tryout_id bigint NOT NULL,
    siswa_id bigint NOT NULL,
    waktu_mulai timestamp(0) without time zone,
    waktu_selesai timestamp(0) without time zone,
    nilai numeric(5,2),
    jawaban_benar integer DEFAULT 0 NOT NULL,
    jawaban_salah integer DEFAULT 0 NOT NULL,
    tidak_dijawab integer DEFAULT 0 NOT NULL,
    percobaan_ke integer DEFAULT 1 NOT NULL,
    status character varying(20) DEFAULT 'berlangsung'::character varying NOT NULL,
    jawaban json
);


--
-- Name: tryout_attempts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.tryout_attempts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tryout_attempts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.tryout_attempts_id_seq OWNED BY public.tryout_attempts.id;


--
-- Name: tryouts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.tryouts (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    cabang_id bigint,
    dibuat_oleh bigint,
    judul character varying(200) NOT NULL,
    deskripsi text,
    kategori character varying(50) NOT NULL,
    durasi_menit integer DEFAULT 60 NOT NULL,
    total_soal integer DEFAULT 0 NOT NULL,
    nilai_kelulusan numeric(5,2),
    waktu_mulai timestamp(0) without time zone,
    waktu_selesai timestamp(0) without time zone,
    is_random boolean DEFAULT false NOT NULL,
    tampilkan_hasil_langsung boolean DEFAULT true NOT NULL,
    tampilkan_kunci_jawaban boolean DEFAULT false NOT NULL,
    maksimal_percobaan integer,
    status character varying(20) DEFAULT 'draft'::character varying NOT NULL,
    deleted_at timestamp(0) without time zone
);


--
-- Name: tryouts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.tryouts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tryouts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.tryouts_id_seq OWNED BY public.tryouts.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    phone character varying(255),
    avatar character varying(255),
    branch_id bigint,
    is_active boolean DEFAULT true NOT NULL,
    last_login_at timestamp(0) without time zone,
    username character varying(255)
);


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: absensi_gurus id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.absensi_gurus ALTER COLUMN id SET DEFAULT nextval('public.absensi_gurus_id_seq'::regclass);


--
-- Name: absensi_siswas id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.absensi_siswas ALTER COLUMN id SET DEFAULT nextval('public.absensi_siswas_id_seq'::regclass);


--
-- Name: academic_years id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.academic_years ALTER COLUMN id SET DEFAULT nextval('public.academic_years_id_seq'::regclass);


--
-- Name: activity_log id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.activity_log ALTER COLUMN id SET DEFAULT nextval('public.activity_log_id_seq'::regclass);


--
-- Name: activity_logs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.activity_logs ALTER COLUMN id SET DEFAULT nextval('public.activity_logs_id_seq'::regclass);


--
-- Name: announcements id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.announcements ALTER COLUMN id SET DEFAULT nextval('public.announcements_id_seq'::regclass);


--
-- Name: branches id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.branches ALTER COLUMN id SET DEFAULT nextval('public.branches_id_seq'::regclass);


--
-- Name: categories id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categories ALTER COLUMN id SET DEFAULT nextval('public.categories_id_seq'::regclass);


--
-- Name: certificates id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.certificates ALTER COLUMN id SET DEFAULT nextval('public.certificates_id_seq'::regclass);


--
-- Name: chat_messages id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.chat_messages ALTER COLUMN id SET DEFAULT nextval('public.chat_messages_id_seq'::regclass);


--
-- Name: chat_rooms id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.chat_rooms ALTER COLUMN id SET DEFAULT nextval('public.chat_rooms_id_seq'::regclass);


--
-- Name: class_students id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.class_students ALTER COLUMN id SET DEFAULT nextval('public.class_students_id_seq'::regclass);


--
-- Name: course_fees id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_fees ALTER COLUMN id SET DEFAULT nextval('public.course_fees_id_seq'::regclass);


--
-- Name: courses id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.courses ALTER COLUMN id SET DEFAULT nextval('public.courses_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: gajis id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gajis ALTER COLUMN id SET DEFAULT nextval('public.gajis_id_seq'::regclass);


--
-- Name: grades id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.grades ALTER COLUMN id SET DEFAULT nextval('public.grades_id_seq'::regclass);


--
-- Name: guru_mapel id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.guru_mapel ALTER COLUMN id SET DEFAULT nextval('public.guru_mapel_id_seq'::regclass);


--
-- Name: gurus id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gurus ALTER COLUMN id SET DEFAULT nextval('public.gurus_id_seq'::regclass);


--
-- Name: invoices id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoices ALTER COLUMN id SET DEFAULT nextval('public.invoices_id_seq'::regclass);


--
-- Name: jadwals id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jadwals ALTER COLUMN id SET DEFAULT nextval('public.jadwals_id_seq'::regclass);


--
-- Name: kelas id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.kelas ALTER COLUMN id SET DEFAULT nextval('public.kelas_id_seq'::regclass);


--
-- Name: kelas_siswa id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.kelas_siswa ALTER COLUMN id SET DEFAULT nextval('public.kelas_siswa_id_seq'::regclass);


--
-- Name: landing_programs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.landing_programs ALTER COLUMN id SET DEFAULT nextval('public.landing_programs_id_seq'::regclass);


--
-- Name: landing_settings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.landing_settings ALTER COLUMN id SET DEFAULT nextval('public.landing_settings_id_seq'::regclass);


--
-- Name: landing_testimonials id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.landing_testimonials ALTER COLUMN id SET DEFAULT nextval('public.landing_testimonials_id_seq'::regclass);


--
-- Name: landing_wa_numbers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.landing_wa_numbers ALTER COLUMN id SET DEFAULT nextval('public.landing_wa_numbers_id_seq'::regclass);


--
-- Name: mapel_paket id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.mapel_paket ALTER COLUMN id SET DEFAULT nextval('public.mapel_paket_id_seq'::regclass);


--
-- Name: mapels id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.mapels ALTER COLUMN id SET DEFAULT nextval('public.mapels_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: modules id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.modules ALTER COLUMN id SET DEFAULT nextval('public.modules_id_seq'::regclass);


--
-- Name: moduls id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.moduls ALTER COLUMN id SET DEFAULT nextval('public.moduls_id_seq'::regclass);


--
-- Name: nilais id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.nilais ALTER COLUMN id SET DEFAULT nextval('public.nilais_id_seq'::regclass);


--
-- Name: package_course_teachers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.package_course_teachers ALTER COLUMN id SET DEFAULT nextval('public.package_course_teachers_id_seq'::regclass);


--
-- Name: packages id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.packages ALTER COLUMN id SET DEFAULT nextval('public.packages_id_seq'::regclass);


--
-- Name: pakets id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pakets ALTER COLUMN id SET DEFAULT nextval('public.pakets_id_seq'::regclass);


--
-- Name: payments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payments ALTER COLUMN id SET DEFAULT nextval('public.payments_id_seq'::regclass);


--
-- Name: pembayarans id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pembayarans ALTER COLUMN id SET DEFAULT nextval('public.pembayarans_id_seq'::regclass);


--
-- Name: permissions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);


--
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- Name: questions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.questions ALTER COLUMN id SET DEFAULT nextval('public.questions_id_seq'::regclass);


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- Name: salaries id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.salaries ALTER COLUMN id SET DEFAULT nextval('public.salaries_id_seq'::regclass);


--
-- Name: schedule_proposal_approvals id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedule_proposal_approvals ALTER COLUMN id SET DEFAULT nextval('public.schedule_proposal_approvals_id_seq'::regclass);


--
-- Name: schedule_proposals id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedule_proposals ALTER COLUMN id SET DEFAULT nextval('public.schedule_proposals_id_seq'::regclass);


--
-- Name: schedule_student_agreements id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedule_student_agreements ALTER COLUMN id SET DEFAULT nextval('public.schedule_student_agreements_id_seq'::regclass);


--
-- Name: schedules id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedules ALTER COLUMN id SET DEFAULT nextval('public.schedules_id_seq'::regclass);


--
-- Name: school_classes id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.school_classes ALTER COLUMN id SET DEFAULT nextval('public.school_classes_id_seq'::regclass);


--
-- Name: semesters id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.semesters ALTER COLUMN id SET DEFAULT nextval('public.semesters_id_seq'::regclass);


--
-- Name: siswas id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.siswas ALTER COLUMN id SET DEFAULT nextval('public.siswas_id_seq'::regclass);


--
-- Name: student_course_payments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_course_payments ALTER COLUMN id SET DEFAULT nextval('public.student_course_payments_id_seq'::regclass);


--
-- Name: student_registrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_registrations ALTER COLUMN id SET DEFAULT nextval('public.student_registrations_id_seq'::regclass);


--
-- Name: student_teachers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_teachers ALTER COLUMN id SET DEFAULT nextval('public.student_teachers_id_seq'::regclass);


--
-- Name: students id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.students ALTER COLUMN id SET DEFAULT nextval('public.students_id_seq'::regclass);


--
-- Name: system_settings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.system_settings ALTER COLUMN id SET DEFAULT nextval('public.system_settings_id_seq'::regclass);


--
-- Name: tagihans id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tagihans ALTER COLUMN id SET DEFAULT nextval('public.tagihans_id_seq'::regclass);


--
-- Name: tahun_ajarans id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tahun_ajarans ALTER COLUMN id SET DEFAULT nextval('public.tahun_ajarans_id_seq'::regclass);


--
-- Name: teacher_courses id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teacher_courses ALTER COLUMN id SET DEFAULT nextval('public.teacher_courses_id_seq'::regclass);


--
-- Name: teachers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teachers ALTER COLUMN id SET DEFAULT nextval('public.teachers_id_seq'::regclass);


--
-- Name: tryout_attempts id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tryout_attempts ALTER COLUMN id SET DEFAULT nextval('public.tryout_attempts_id_seq'::regclass);


--
-- Name: tryouts id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tryouts ALTER COLUMN id SET DEFAULT nextval('public.tryouts_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: absensi_gurus absensi_gurus_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.absensi_gurus
    ADD CONSTRAINT absensi_gurus_pkey PRIMARY KEY (id);


--
-- Name: absensi_siswas absensi_siswas_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.absensi_siswas
    ADD CONSTRAINT absensi_siswas_pkey PRIMARY KEY (id);


--
-- Name: academic_years academic_years_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.academic_years
    ADD CONSTRAINT academic_years_pkey PRIMARY KEY (id);


--
-- Name: activity_log activity_log_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.activity_log
    ADD CONSTRAINT activity_log_pkey PRIMARY KEY (id);


--
-- Name: activity_logs activity_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.activity_logs
    ADD CONSTRAINT activity_logs_pkey PRIMARY KEY (id);


--
-- Name: announcements announcements_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.announcements
    ADD CONSTRAINT announcements_pkey PRIMARY KEY (id);


--
-- Name: branches branches_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.branches
    ADD CONSTRAINT branches_pkey PRIMARY KEY (id);


--
-- Name: categories categories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);


--
-- Name: categories categories_slug_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_slug_unique UNIQUE (slug);


--
-- Name: certificates certificates_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.certificates
    ADD CONSTRAINT certificates_pkey PRIMARY KEY (id);


--
-- Name: chat_messages chat_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.chat_messages
    ADD CONSTRAINT chat_messages_pkey PRIMARY KEY (id);


--
-- Name: chat_rooms chat_rooms_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.chat_rooms
    ADD CONSTRAINT chat_rooms_pkey PRIMARY KEY (id);


--
-- Name: class_students class_students_class_id_student_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.class_students
    ADD CONSTRAINT class_students_class_id_student_id_unique UNIQUE (class_id, student_id);


--
-- Name: class_students class_students_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.class_students
    ADD CONSTRAINT class_students_pkey PRIMARY KEY (id);


--
-- Name: course_fees course_fees_course_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_fees
    ADD CONSTRAINT course_fees_course_id_unique UNIQUE (course_id);


--
-- Name: course_fees course_fees_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_fees
    ADD CONSTRAINT course_fees_pkey PRIMARY KEY (id);


--
-- Name: course_package course_package_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_package
    ADD CONSTRAINT course_package_pkey PRIMARY KEY (package_id, course_id);


--
-- Name: courses courses_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.courses
    ADD CONSTRAINT courses_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: gajis gajis_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gajis
    ADD CONSTRAINT gajis_pkey PRIMARY KEY (id);


--
-- Name: grades grades_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.grades
    ADD CONSTRAINT grades_pkey PRIMARY KEY (id);


--
-- Name: guru_mapel guru_mapel_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.guru_mapel
    ADD CONSTRAINT guru_mapel_pkey PRIMARY KEY (id);


--
-- Name: gurus gurus_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.gurus
    ADD CONSTRAINT gurus_pkey PRIMARY KEY (id);


--
-- Name: invoices invoices_nomor_invoice_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoices
    ADD CONSTRAINT invoices_nomor_invoice_unique UNIQUE (nomor_invoice);


--
-- Name: invoices invoices_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoices
    ADD CONSTRAINT invoices_pkey PRIMARY KEY (id);


--
-- Name: jadwals jadwals_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jadwals
    ADD CONSTRAINT jadwals_pkey PRIMARY KEY (id);


--
-- Name: kelas kelas_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.kelas
    ADD CONSTRAINT kelas_pkey PRIMARY KEY (id);


--
-- Name: kelas_siswa kelas_siswa_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.kelas_siswa
    ADD CONSTRAINT kelas_siswa_pkey PRIMARY KEY (id);


--
-- Name: landing_programs landing_programs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.landing_programs
    ADD CONSTRAINT landing_programs_pkey PRIMARY KEY (id);


--
-- Name: landing_settings landing_settings_key_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.landing_settings
    ADD CONSTRAINT landing_settings_key_unique UNIQUE (key);


--
-- Name: landing_settings landing_settings_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.landing_settings
    ADD CONSTRAINT landing_settings_pkey PRIMARY KEY (id);


--
-- Name: landing_testimonials landing_testimonials_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.landing_testimonials
    ADD CONSTRAINT landing_testimonials_pkey PRIMARY KEY (id);


--
-- Name: landing_wa_numbers landing_wa_numbers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.landing_wa_numbers
    ADD CONSTRAINT landing_wa_numbers_pkey PRIMARY KEY (id);


--
-- Name: mapel_paket mapel_paket_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.mapel_paket
    ADD CONSTRAINT mapel_paket_pkey PRIMARY KEY (id);


--
-- Name: mapels mapels_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.mapels
    ADD CONSTRAINT mapels_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: model_has_permissions model_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_pkey PRIMARY KEY (permission_id, model_id, model_type);


--
-- Name: model_has_roles model_has_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_pkey PRIMARY KEY (role_id, model_id, model_type);


--
-- Name: modules modules_kode_modul_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.modules
    ADD CONSTRAINT modules_kode_modul_unique UNIQUE (kode_modul);


--
-- Name: modules modules_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.modules
    ADD CONSTRAINT modules_pkey PRIMARY KEY (id);


--
-- Name: moduls moduls_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.moduls
    ADD CONSTRAINT moduls_pkey PRIMARY KEY (id);


--
-- Name: nilais nilais_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.nilais
    ADD CONSTRAINT nilais_pkey PRIMARY KEY (id);


--
-- Name: package_course_teachers package_course_teachers_package_id_course_id_teacher_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.package_course_teachers
    ADD CONSTRAINT package_course_teachers_package_id_course_id_teacher_id_unique UNIQUE (package_id, course_id, teacher_id);


--
-- Name: package_course_teachers package_course_teachers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.package_course_teachers
    ADD CONSTRAINT package_course_teachers_pkey PRIMARY KEY (id);


--
-- Name: packages packages_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.packages
    ADD CONSTRAINT packages_pkey PRIMARY KEY (id);


--
-- Name: pakets pakets_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pakets
    ADD CONSTRAINT pakets_pkey PRIMARY KEY (id);


--
-- Name: password_resets password_resets_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.password_resets
    ADD CONSTRAINT password_resets_pkey PRIMARY KEY (email);


--
-- Name: payments payments_nomor_pembayaran_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payments
    ADD CONSTRAINT payments_nomor_pembayaran_unique UNIQUE (nomor_pembayaran);


--
-- Name: payments payments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payments
    ADD CONSTRAINT payments_pkey PRIMARY KEY (id);


--
-- Name: pembayarans pembayarans_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pembayarans
    ADD CONSTRAINT pembayarans_pkey PRIMARY KEY (id);


--
-- Name: permissions permissions_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_name_guard_name_unique UNIQUE (name, guard_name);


--
-- Name: permissions permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- Name: schedule_proposal_approvals proposal_approvals_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedule_proposal_approvals
    ADD CONSTRAINT proposal_approvals_unique UNIQUE (proposal_id, approver_type, approver_id);


--
-- Name: questions questions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.questions
    ADD CONSTRAINT questions_pkey PRIMARY KEY (id);


--
-- Name: role_has_permissions role_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_pkey PRIMARY KEY (permission_id, role_id);


--
-- Name: roles roles_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_name_guard_name_unique UNIQUE (name, guard_name);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: salaries salaries_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.salaries
    ADD CONSTRAINT salaries_pkey PRIMARY KEY (id);


--
-- Name: schedule_proposal_approvals schedule_proposal_approvals_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedule_proposal_approvals
    ADD CONSTRAINT schedule_proposal_approvals_pkey PRIMARY KEY (id);


--
-- Name: schedule_proposals schedule_proposals_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedule_proposals
    ADD CONSTRAINT schedule_proposals_pkey PRIMARY KEY (id);


--
-- Name: schedule_student_agreements schedule_student_agreements_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedule_student_agreements
    ADD CONSTRAINT schedule_student_agreements_pkey PRIMARY KEY (id);


--
-- Name: schedule_student_agreements schedule_student_agreements_schedule_id_student_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedule_student_agreements
    ADD CONSTRAINT schedule_student_agreements_schedule_id_student_id_unique UNIQUE (schedule_id, student_id);


--
-- Name: schedules schedules_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedules
    ADD CONSTRAINT schedules_pkey PRIMARY KEY (id);


--
-- Name: school_classes school_classes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.school_classes
    ADD CONSTRAINT school_classes_pkey PRIMARY KEY (id);


--
-- Name: semesters semesters_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.semesters
    ADD CONSTRAINT semesters_pkey PRIMARY KEY (id);


--
-- Name: siswas siswas_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.siswas
    ADD CONSTRAINT siswas_pkey PRIMARY KEY (id);


--
-- Name: student_course_payments student_course_payments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_course_payments
    ADD CONSTRAINT student_course_payments_pkey PRIMARY KEY (id);


--
-- Name: student_registrations student_registrations_no_reg_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_registrations
    ADD CONSTRAINT student_registrations_no_reg_unique UNIQUE (no_reg);


--
-- Name: student_registrations student_registrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_registrations
    ADD CONSTRAINT student_registrations_pkey PRIMARY KEY (id);


--
-- Name: student_teachers student_teachers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_teachers
    ADD CONSTRAINT student_teachers_pkey PRIMARY KEY (id);


--
-- Name: student_teachers student_teachers_student_id_teacher_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_teachers
    ADD CONSTRAINT student_teachers_student_id_teacher_id_unique UNIQUE (student_id, teacher_id);


--
-- Name: students students_nis_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_nis_unique UNIQUE (nis);


--
-- Name: students students_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_pkey PRIMARY KEY (id);


--
-- Name: system_settings system_settings_key_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.system_settings
    ADD CONSTRAINT system_settings_key_unique UNIQUE (key);


--
-- Name: system_settings system_settings_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.system_settings
    ADD CONSTRAINT system_settings_pkey PRIMARY KEY (id);


--
-- Name: tagihans tagihans_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tagihans
    ADD CONSTRAINT tagihans_pkey PRIMARY KEY (id);


--
-- Name: tahun_ajarans tahun_ajarans_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tahun_ajarans
    ADD CONSTRAINT tahun_ajarans_pkey PRIMARY KEY (id);


--
-- Name: teacher_courses teacher_courses_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teacher_courses
    ADD CONSTRAINT teacher_courses_pkey PRIMARY KEY (id);


--
-- Name: teacher_courses teacher_courses_teacher_id_course_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teacher_courses
    ADD CONSTRAINT teacher_courses_teacher_id_course_id_unique UNIQUE (teacher_id, course_id);


--
-- Name: teachers teachers_nig_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teachers
    ADD CONSTRAINT teachers_nig_unique UNIQUE (nig);


--
-- Name: teachers teachers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teachers
    ADD CONSTRAINT teachers_pkey PRIMARY KEY (id);


--
-- Name: tryout_attempts tryout_attempts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tryout_attempts
    ADD CONSTRAINT tryout_attempts_pkey PRIMARY KEY (id);


--
-- Name: tryouts tryouts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tryouts
    ADD CONSTRAINT tryouts_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: users users_username_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_username_unique UNIQUE (username);


--
-- Name: activity_log_log_name_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX activity_log_log_name_index ON public.activity_log USING btree (log_name);


--
-- Name: branches_status_city_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX branches_status_city_index ON public.branches USING btree (status, city);


--
-- Name: causer; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX causer ON public.activity_log USING btree (causer_type, causer_id);


--
-- Name: courses_cabang_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX courses_cabang_id_index ON public.courses USING btree (cabang_id);


--
-- Name: courses_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX courses_status_index ON public.courses USING btree (status);


--
-- Name: invoices_cabang_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX invoices_cabang_id_index ON public.invoices USING btree (cabang_id);


--
-- Name: invoices_jatuh_tempo_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX invoices_jatuh_tempo_index ON public.invoices USING btree (jatuh_tempo);


--
-- Name: invoices_siswa_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX invoices_siswa_id_index ON public.invoices USING btree (siswa_id);


--
-- Name: invoices_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX invoices_status_index ON public.invoices USING btree (status);


--
-- Name: model_has_permissions_model_id_model_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX model_has_permissions_model_id_model_type_index ON public.model_has_permissions USING btree (model_id, model_type);


--
-- Name: model_has_roles_model_id_model_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX model_has_roles_model_id_model_type_index ON public.model_has_roles USING btree (model_id, model_type);


--
-- Name: payments_cabang_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX payments_cabang_id_index ON public.payments USING btree (cabang_id);


--
-- Name: payments_invoice_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX payments_invoice_id_index ON public.payments USING btree (invoice_id);


--
-- Name: payments_siswa_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX payments_siswa_id_index ON public.payments USING btree (siswa_id);


--
-- Name: payments_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX payments_status_index ON public.payments USING btree (status);


--
-- Name: payments_tanggal_pembayaran_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX payments_tanggal_pembayaran_index ON public.payments USING btree (tanggal_pembayaran);


--
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- Name: schedules_cabang_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX schedules_cabang_id_index ON public.schedules USING btree (cabang_id);


--
-- Name: schedules_guru_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX schedules_guru_id_index ON public.schedules USING btree (guru_id);


--
-- Name: schedules_tanggal_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX schedules_tanggal_index ON public.schedules USING btree (tanggal);


--
-- Name: school_classes_cabang_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX school_classes_cabang_id_index ON public.school_classes USING btree (cabang_id);


--
-- Name: school_classes_guru_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX school_classes_guru_id_index ON public.school_classes USING btree (guru_id);


--
-- Name: school_classes_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX school_classes_status_index ON public.school_classes USING btree (status);


--
-- Name: subject; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX subject ON public.activity_log USING btree (subject_type, subject_id);


--
-- Name: absensi_gurus absensi_gurus_guru_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.absensi_gurus
    ADD CONSTRAINT absensi_gurus_guru_id_foreign FOREIGN KEY (guru_id) REFERENCES public.teachers(id) ON DELETE CASCADE;


--
-- Name: absensi_gurus absensi_gurus_jadwal_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.absensi_gurus
    ADD CONSTRAINT absensi_gurus_jadwal_id_foreign FOREIGN KEY (jadwal_id) REFERENCES public.schedules(id) ON DELETE CASCADE;


--
-- Name: absensi_siswas absensi_siswas_jadwal_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.absensi_siswas
    ADD CONSTRAINT absensi_siswas_jadwal_id_foreign FOREIGN KEY (jadwal_id) REFERENCES public.schedules(id) ON DELETE CASCADE;


--
-- Name: absensi_siswas absensi_siswas_siswa_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.absensi_siswas
    ADD CONSTRAINT absensi_siswas_siswa_id_foreign FOREIGN KEY (siswa_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: branches branches_admin_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.branches
    ADD CONSTRAINT branches_admin_id_foreign FOREIGN KEY (admin_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: branches branches_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.branches
    ADD CONSTRAINT branches_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: branches branches_updated_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.branches
    ADD CONSTRAINT branches_updated_by_foreign FOREIGN KEY (updated_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: certificates certificates_course_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.certificates
    ADD CONSTRAINT certificates_course_id_foreign FOREIGN KEY (course_id) REFERENCES public.courses(id) ON DELETE SET NULL;


--
-- Name: chat_messages chat_messages_pengirim_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.chat_messages
    ADD CONSTRAINT chat_messages_pengirim_id_foreign FOREIGN KEY (pengirim_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: chat_messages chat_messages_room_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.chat_messages
    ADD CONSTRAINT chat_messages_room_id_foreign FOREIGN KEY (room_id) REFERENCES public.chat_rooms(id) ON DELETE CASCADE;


--
-- Name: class_students class_students_class_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.class_students
    ADD CONSTRAINT class_students_class_id_foreign FOREIGN KEY (class_id) REFERENCES public.school_classes(id) ON DELETE CASCADE;


--
-- Name: class_students class_students_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.class_students
    ADD CONSTRAINT class_students_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: course_fees course_fees_course_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_fees
    ADD CONSTRAINT course_fees_course_id_foreign FOREIGN KEY (course_id) REFERENCES public.courses(id) ON DELETE CASCADE;


--
-- Name: course_package course_package_course_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_package
    ADD CONSTRAINT course_package_course_id_foreign FOREIGN KEY (course_id) REFERENCES public.courses(id) ON DELETE CASCADE;


--
-- Name: course_package course_package_package_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_package
    ADD CONSTRAINT course_package_package_id_foreign FOREIGN KEY (package_id) REFERENCES public.packages(id) ON DELETE CASCADE;


--
-- Name: grades grades_guru_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.grades
    ADD CONSTRAINT grades_guru_id_foreign FOREIGN KEY (guru_id) REFERENCES public.teachers(id) ON DELETE SET NULL;


--
-- Name: grades grades_mata_pelajaran_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.grades
    ADD CONSTRAINT grades_mata_pelajaran_id_foreign FOREIGN KEY (mata_pelajaran_id) REFERENCES public.courses(id) ON DELETE SET NULL;


--
-- Name: grades grades_semester_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.grades
    ADD CONSTRAINT grades_semester_id_foreign FOREIGN KEY (semester_id) REFERENCES public.semesters(id) ON DELETE SET NULL;


--
-- Name: grades grades_siswa_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.grades
    ADD CONSTRAINT grades_siswa_id_foreign FOREIGN KEY (siswa_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: model_has_permissions model_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: model_has_roles model_has_roles_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: modules modules_diupload_oleh_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.modules
    ADD CONSTRAINT modules_diupload_oleh_foreign FOREIGN KEY (diupload_oleh) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: modules modules_mata_pelajaran_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.modules
    ADD CONSTRAINT modules_mata_pelajaran_id_foreign FOREIGN KEY (mata_pelajaran_id) REFERENCES public.courses(id) ON DELETE SET NULL;


--
-- Name: package_course_teachers package_course_teachers_course_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.package_course_teachers
    ADD CONSTRAINT package_course_teachers_course_id_foreign FOREIGN KEY (course_id) REFERENCES public.courses(id) ON DELETE CASCADE;


--
-- Name: package_course_teachers package_course_teachers_package_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.package_course_teachers
    ADD CONSTRAINT package_course_teachers_package_id_foreign FOREIGN KEY (package_id) REFERENCES public.packages(id) ON DELETE CASCADE;


--
-- Name: package_course_teachers package_course_teachers_teacher_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.package_course_teachers
    ADD CONSTRAINT package_course_teachers_teacher_id_foreign FOREIGN KEY (teacher_id) REFERENCES public.teachers(id) ON DELETE CASCADE;


--
-- Name: packages packages_cabang_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.packages
    ADD CONSTRAINT packages_cabang_id_foreign FOREIGN KEY (cabang_id) REFERENCES public.branches(id) ON DELETE SET NULL;


--
-- Name: packages packages_guru_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.packages
    ADD CONSTRAINT packages_guru_id_foreign FOREIGN KEY (guru_id) REFERENCES public.teachers(id) ON DELETE SET NULL;


--
-- Name: questions questions_tryout_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.questions
    ADD CONSTRAINT questions_tryout_id_foreign FOREIGN KEY (tryout_id) REFERENCES public.tryouts(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: salaries salaries_cabang_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.salaries
    ADD CONSTRAINT salaries_cabang_id_foreign FOREIGN KEY (cabang_id) REFERENCES public.branches(id) ON DELETE SET NULL;


--
-- Name: salaries salaries_dibayar_oleh_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.salaries
    ADD CONSTRAINT salaries_dibayar_oleh_foreign FOREIGN KEY (dibayar_oleh) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: salaries salaries_guru_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.salaries
    ADD CONSTRAINT salaries_guru_id_foreign FOREIGN KEY (guru_id) REFERENCES public.teachers(id) ON DELETE CASCADE;


--
-- Name: schedule_proposal_approvals schedule_proposal_approvals_proposal_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedule_proposal_approvals
    ADD CONSTRAINT schedule_proposal_approvals_proposal_id_foreign FOREIGN KEY (proposal_id) REFERENCES public.schedule_proposals(id) ON DELETE CASCADE;


--
-- Name: schedule_proposals schedule_proposals_class_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedule_proposals
    ADD CONSTRAINT schedule_proposals_class_id_foreign FOREIGN KEY (class_id) REFERENCES public.school_classes(id) ON DELETE CASCADE;


--
-- Name: schedule_proposals schedule_proposals_schedule_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedule_proposals
    ADD CONSTRAINT schedule_proposals_schedule_id_foreign FOREIGN KEY (schedule_id) REFERENCES public.schedules(id) ON DELETE SET NULL;


--
-- Name: schedule_student_agreements schedule_student_agreements_schedule_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedule_student_agreements
    ADD CONSTRAINT schedule_student_agreements_schedule_id_foreign FOREIGN KEY (schedule_id) REFERENCES public.schedules(id) ON DELETE CASCADE;


--
-- Name: schedule_student_agreements schedule_student_agreements_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedule_student_agreements
    ADD CONSTRAINT schedule_student_agreements_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: schedules schedules_mata_pelajaran_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedules
    ADD CONSTRAINT schedules_mata_pelajaran_id_foreign FOREIGN KEY (mata_pelajaran_id) REFERENCES public.courses(id) ON DELETE SET NULL;


--
-- Name: schedules schedules_paket_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedules
    ADD CONSTRAINT schedules_paket_id_foreign FOREIGN KEY (paket_id) REFERENCES public.packages(id) ON DELETE SET NULL;


--
-- Name: semesters semesters_academic_year_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.semesters
    ADD CONSTRAINT semesters_academic_year_id_foreign FOREIGN KEY (academic_year_id) REFERENCES public.academic_years(id) ON DELETE CASCADE;


--
-- Name: student_course_payments student_course_payments_course_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_course_payments
    ADD CONSTRAINT student_course_payments_course_id_foreign FOREIGN KEY (course_id) REFERENCES public.courses(id) ON DELETE CASCADE;


--
-- Name: student_course_payments student_course_payments_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_course_payments
    ADD CONSTRAINT student_course_payments_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: student_course_payments student_course_payments_verified_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_course_payments
    ADD CONSTRAINT student_course_payments_verified_by_foreign FOREIGN KEY (verified_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: student_teachers student_teachers_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_teachers
    ADD CONSTRAINT student_teachers_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: student_teachers student_teachers_teacher_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_teachers
    ADD CONSTRAINT student_teachers_teacher_id_foreign FOREIGN KEY (teacher_id) REFERENCES public.teachers(id) ON DELETE CASCADE;


--
-- Name: students students_branch_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_branch_id_foreign FOREIGN KEY (branch_id) REFERENCES public.branches(id) ON DELETE CASCADE;


--
-- Name: students students_package_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_package_id_foreign FOREIGN KEY (package_id) REFERENCES public.packages(id) ON DELETE SET NULL;


--
-- Name: students students_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: teacher_courses teacher_courses_course_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teacher_courses
    ADD CONSTRAINT teacher_courses_course_id_foreign FOREIGN KEY (course_id) REFERENCES public.courses(id) ON DELETE CASCADE;


--
-- Name: teacher_courses teacher_courses_teacher_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teacher_courses
    ADD CONSTRAINT teacher_courses_teacher_id_foreign FOREIGN KEY (teacher_id) REFERENCES public.teachers(id) ON DELETE CASCADE;


--
-- Name: tryout_attempts tryout_attempts_siswa_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tryout_attempts
    ADD CONSTRAINT tryout_attempts_siswa_id_foreign FOREIGN KEY (siswa_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: tryout_attempts tryout_attempts_tryout_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tryout_attempts
    ADD CONSTRAINT tryout_attempts_tryout_id_foreign FOREIGN KEY (tryout_id) REFERENCES public.tryouts(id) ON DELETE CASCADE;


--
-- Name: tryouts tryouts_cabang_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tryouts
    ADD CONSTRAINT tryouts_cabang_id_foreign FOREIGN KEY (cabang_id) REFERENCES public.branches(id) ON DELETE SET NULL;


--
-- Name: tryouts tryouts_dibuat_oleh_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tryouts
    ADD CONSTRAINT tryouts_dibuat_oleh_foreign FOREIGN KEY (dibuat_oleh) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- PostgreSQL database dump complete
--

\unrestrict DdWlYGckQKr94MDHQuyjAurkfNM6y84iAAeh5Ql6gKqJye0iV9GpJIlJc3hM7Dc

--
-- PostgreSQL database dump
--

\restrict GBZe3VjRbu0raLvxH6WbRZ0Nf6iYJtJazGngobeOiidvSySP6Urjtm7F3ayGBq6

-- Dumped from database version 16.10
-- Dumped by pg_dump version 16.10

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
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	2014_10_12_000000_create_users_table	1
2	2014_10_12_100000_create_password_resets_table	1
3	2019_08_19_000000_create_failed_jobs_table	1
4	2019_12_14_000001_create_personal_access_tokens_table	1
5	2026_05_29_071839_create_branches_table	1
6	2026_05_29_071858_create_academic_years_table	1
7	2026_05_29_071947_create_courses_table	1
8	2026_05_29_071954_create_packages_table	1
9	2026_05_29_072000_create_students_table	1
10	2026_05_29_072005_create_teachers_table	1
11	2026_05_29_072011_create_school_classes_table	1
12	2026_05_29_072018_create_schedules_table	1
13	2026_05_29_072035_create_modules_table	1
14	2026_05_29_072043_create_invoices_table	1
15	2026_05_29_072050_create_payments_table	1
16	2026_05_29_072107_create_salaries_table	1
17	2026_05_29_072126_create_grades_table	1
18	2026_05_29_072133_create_tryouts_table	1
19	2026_05_29_072138_create_questions_table	1
20	2026_05_29_072143_create_tryout_attempts_table	1
21	2026_05_29_072149_create_certificates_table	1
22	2026_05_29_072159_create_chat_rooms_table	1
23	2026_05_29_072205_create_chat_messages_table	1
24	2026_05_29_072211_create_activity_logs_table	1
25	2026_05_29_075106_create_tahun_ajarans_table	1
26	2026_05_29_075124_create_mapels_table	1
27	2026_05_29_075129_create_pakets_table	1
28	2026_05_29_075134_create_mapel_paket_table	1
29	2026_05_29_075140_create_siswas_table	1
30	2026_05_29_075147_create_gurus_table	1
31	2026_05_29_075152_create_guru_mapel_table	1
32	2026_05_29_075157_create_kelas_table	1
33	2026_05_29_075222_create_kelas_siswa_table	1
34	2026_05_29_075226_create_jadwals_table	1
35	2026_05_29_075231_create_absensi_siswas_table	1
36	2026_05_29_075236_create_absensi_gurus_table	1
37	2026_05_29_075247_create_moduls_table	1
38	2026_05_29_075252_create_tagihans_table	1
39	2026_05_29_075256_create_pembayarans_table	1
40	2026_05_29_075301_create_gajis_table	1
41	2026_05_29_075313_create_nilais_table	1
42	2026_05_29_083356_create_permission_tables	1
43	2026_05_29_083817_add_columns_to_users_table	1
44	2026_05_29_084319_create_activity_log_table	1
45	2026_05_29_084320_add_event_column_to_activity_log_table	1
46	2026_05_29_084321_add_batch_uuid_column_to_activity_log_table	1
47	2026_05_29_093916_add_deleted_at_to_students_table	1
48	2026_05_29_100111_add_branch_id_to_students_table	1
49	2026_06_02_000001_add_columns_to_students_table	1
50	2026_06_02_092952_add_account_to_branches_table	1
51	2026_06_02_093248_add_branch_accounts_table	1
52	2026_06_05_000001_add_username_to_users_table	1
53	2026_06_05_000002_add_branch_extra_columns	1
54	2026_06_05_100001_add_columns_to_schedules_table	1
55	2026_06_05_100002_add_columns_to_core_tables	1
56	2026_06_05_100003_add_user_id_to_teachers	1
57	2026_06_06_000000_create_categories_table	1
58	2026_06_06_010000_modify_courses_icon_length	2
59	2026_06_06_150000_add_deleted_at_to_certificates_table	2
60	2026_06_06_160000_update_certificates_add_columns	2
61	2026_06_07_100000_create_announcements_table	3
62	2026_06_09_000001_add_cv_path_to_teachers_table	3
63	2026_06_09_132034_add_columns_to_chat_tables	3
64	2026_06_09_200000_add_missing_columns_to_all_tables	3
65	2026_06_10_000001_refactor_courses_teachers_modules	3
66	2026_06_10_000002_add_student_accounts_private_types_announcement_targets	3
67	2026_06_10_000003_student_teachers_class_sessions	3
68	2026_06_10_100000_add_kunci_jawaban_to_questions	3
69	2026_06_10_100000_create_class_students_table	3
70	2026_06_10_110000_create_course_fees_table	3
71	2026_06_10_110100_create_student_course_payments_table	3
72	2026_06_10_161434_create_landing_content_tables	3
73	2026_06_10_162503_create_landing_wa_numbers_table	3
74	2026_06_10_163351_create_system_settings_table	3
75	2026_06_11_000000_add_bukti_pembayaran_to_salaries	3
76	2026_06_11_100000_create_schedule_agreements_and_payment_fields	3
77	2026_06_11_120000_add_allowed_pages_to_branches_table	3
78	2026_06_11_120000_add_course_id_to_certificates_table	3
79	2026_06_11_120000_add_tipe_gaji_to_salaries	3
80	2026_06_11_182851_add_pertemuan_ke_to_schedule_proposals	3
81	2026_06_12_000001_create_schedule_proposals_tables	3
82	2026_06_12_100000_revamp_attendance_dual_confirmation	3
83	2026_06_20_000001_create_student_registrations_table	3
84	2026_06_20_000507_add_kategori_to_courses_table	3
85	2026_06_20_001742_create_course_package_table	3
86	2026_06_20_003643_add_kode_modul_to_modules_table	3
87	2026_06_20_100000_add_jenis_guru_to_teachers_table	3
88	2026_06_20_152029_add_guru_id_to_packages_and_paket_id_to_schedules	3
89	2026_06_21_000001_add_kategori_peserta_didik_to_students_table	3
90	2026_06_21_000002_add_attendance_and_class_type_to_packages_table	3
91	2026_06_21_000003_add_package_id_to_students_table	3
92	2026_06_22_000001_add_billing_mode_to_school_classes	3
93	2026_06_22_000001_add_education_level_to_student_registrations	3
94	2026_06_22_122921_add_columns_to_payments_table	3
95	2026_06_22_200000_change_diterbitkan_oleh_to_string_in_certificates	3
96	2026_06_22_210000_add_kelas_id_to_invoices_module_id_to_schedules	3
97	2026_06_25_000001_add_mata_pelajaran_id_to_schedules	3
98	2026_06_25_100001_create_package_course_teachers_table	3
\.


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 98, true);


--
-- PostgreSQL database dump complete
--

\unrestrict GBZe3VjRbu0raLvxH6WbRZ0Nf6iYJtJazGngobeOiidvSySP6Urjtm7F3ayGBq6

