-- Tabla: users
CREATE TABLE users
(
    id                BIGSERIAL PRIMARY KEY,
    code              INTEGER UNIQUE      NOT NULL,
    name              VARCHAR(255)        NOT NULL,
    last_name         VARCHAR(255)        NOT NULL,
    phone             CHAR(15),
    email             VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP,
    password          VARCHAR(255)        NOT NULL,
    address           TEXT,
    document_type     VARCHAR(10) DEFAULT 'CI' CHECK (document_type IN ('CI', 'PASSPORT')),
    document_number   CHAR(20) UNIQUE     NOT NULL,
    is_active         BOOLEAN     DEFAULT TRUE,
    remember_token    VARCHAR(100),
    created_at        TIMESTAMP   DEFAULT CURRENT_TIMESTAMP,
    updated_at        TIMESTAMP   DEFAULT CURRENT_TIMESTAMP
);

-- Tabla: password_reset_tokens
CREATE TABLE password_reset_tokens
(
    email      VARCHAR(255) PRIMARY KEY,
    token      VARCHAR(255) NOT NULL,
    created_at TIMESTAMP
);

-- Tabla: sessions
CREATE TABLE sessions
(
    id            VARCHAR(255) PRIMARY KEY,
    user_id       BIGINT,
    ip_address    VARCHAR(45),
    user_agent    TEXT,
    payload       TEXT    NOT NULL,
    last_activity INTEGER NOT NULL
);

CREATE INDEX idx_sessions_user_id ON sessions (user_id);
CREATE INDEX idx_sessions_last_activity ON sessions (last_activity);

-- Tabla: academic_management
CREATE TABLE academic_management
(
    id         BIGSERIAL PRIMARY KEY,
    name       VARCHAR(255) NOT NULL,
    start_date DATE         NOT NULL,
    end_date   DATE         NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla: groups
CREATE TABLE groups
(
    id                     BIGSERIAL PRIMARY KEY,
    academic_management_id BIGINT  NOT NULL,
    name                   CHAR(3) NOT NULL,
    is_active              BOOLEAN   DEFAULT TRUE,
    created_at             TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at             TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_groups_academic_management
        FOREIGN KEY (academic_management_id)
            REFERENCES academic_management (id)
            ON DELETE CASCADE
);

-- Tabla: subjects
CREATE TABLE subjects
(
    id         BIGSERIAL PRIMARY KEY,
    code       CHAR(6) UNIQUE NOT NULL,
    name       VARCHAR(255)   NOT NULL,
    credits    INTEGER        NOT NULL,
    is_active  BOOLEAN   DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla pivote: group_subject
CREATE TABLE group_subject
(
    group_id   BIGINT NOT NULL,
    subject_id BIGINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (group_id, subject_id),
    CONSTRAINT fk_group_subject_group
        FOREIGN KEY (group_id)
            REFERENCES groups (id)
            ON DELETE CASCADE,
    CONSTRAINT fk_group_subject_subject
        FOREIGN KEY (subject_id)
            REFERENCES subjects (id)
            ON DELETE CASCADE
);

-- Tabla: modules
CREATE TABLE modules
(
    id         BIGSERIAL PRIMARY KEY,
    code       INTEGER NOT NULL,
    address    TEXT    NOT NULL,
    is_active  BOOLEAN   DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla: classrooms
CREATE TABLE classrooms
(
    id         BIGSERIAL PRIMARY KEY,
    module_id  BIGINT  NOT NULL,
    number     INTEGER NOT NULL,
    type       VARCHAR(50) DEFAULT 'aula'
        CHECK (type IN ('aula', 'laboratorio pcs', 'auditorio', 'biblioteca', 'laboratorio fisica')),
    capacity   INTEGER NOT NULL,
    is_active  BOOLEAN     DEFAULT TRUE,
    created_at TIMESTAMP   DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP   DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_classrooms_module
        FOREIGN KEY (module_id)
            REFERENCES modules (id)
            ON DELETE CASCADE
);

-- Tabla: roles
CREATE TABLE roles
(
    id          BIGSERIAL PRIMARY KEY,
    name        VARCHAR(255) NOT NULL,
    description TEXT,
    level       INTEGER      NOT NULL,
    is_active   BOOLEAN   DEFAULT TRUE,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla pivote: role_user
CREATE TABLE role_user
(
    role_id    BIGINT NOT NULL,
    user_id    BIGINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (role_id, user_id),
    CONSTRAINT fk_role_user_role
        FOREIGN KEY (role_id)
            REFERENCES roles (id)
            ON DELETE CASCADE,
    CONSTRAINT fk_role_user_user
        FOREIGN KEY (user_id)
            REFERENCES users (id)
            ON DELETE CASCADE
);

-- Tabla: permissions
CREATE TABLE permissions
(
    id         BIGSERIAL PRIMARY KEY,
    name       VARCHAR(255) NOT NULL,
    action     VARCHAR(255) NOT NULL,
    is_active  BOOLEAN   DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla pivote: permission_role
CREATE TABLE permission_role
(
    permission_id BIGINT       NOT NULL,
    role_id       BIGINT       NOT NULL,
    module        VARCHAR(255) NOT NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (permission_id, role_id),
    CONSTRAINT fk_permission_role_permission
        FOREIGN KEY (permission_id)
            REFERENCES permissions (id)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
    CONSTRAINT fk_permission_role_role
        FOREIGN KEY (role_id)
            REFERENCES roles (id)
            ON DELETE CASCADE
            ON UPDATE CASCADE
);

-- Tabla: days
CREATE TABLE days
(
    id         BIGSERIAL PRIMARY KEY,
    name       CHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla: schedules
CREATE TABLE schedules
(
    id         BIGSERIAL PRIMARY KEY,
    start      TIME NOT NULL,
    "end"      TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla: day_schedules
CREATE TABLE day_schedules
(
    id          BIGSERIAL PRIMARY KEY,
    day_id      BIGINT NOT NULL,
    schedule_id BIGINT NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_day_schedules_day
        FOREIGN KEY (day_id)
            REFERENCES days (id)
            ON DELETE CASCADE,
    CONSTRAINT fk_day_schedules_schedule
        FOREIGN KEY (schedule_id)
            REFERENCES schedules (id)
            ON DELETE CASCADE,
    CONSTRAINT uk_day_schedules_day_schedule UNIQUE (day_id, schedule_id)
);

-- Tabla: assignments
CREATE TABLE assignments
(
    id              BIGSERIAL PRIMARY KEY,
    day_schedule_id BIGINT NOT NULL,
    subject_id      BIGINT NOT NULL,
    classroom_id    BIGINT NOT NULL,
    user_id         BIGINT NOT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_assignments_day_schedule
        FOREIGN KEY (day_schedule_id)
            REFERENCES day_schedules (id)
            ON DELETE CASCADE,
    CONSTRAINT fk_assignments_subject
        FOREIGN KEY (subject_id)
            REFERENCES subjects (id)
            ON DELETE CASCADE,
    CONSTRAINT fk_assignments_classroom
        FOREIGN KEY (classroom_id)
            REFERENCES classrooms (id)
            ON DELETE CASCADE,
    CONSTRAINT fk_assignments_user
        FOREIGN KEY (user_id)
            REFERENCES users (id)
            ON DELETE CASCADE,
    CONSTRAINT uk_assignments_classroom_day_schedule UNIQUE (classroom_id, day_schedule_id)
);

-- Tabla: audit_logs
CREATE TABLE audit_logs
(
    id                BIGSERIAL PRIMARY KEY,
    user_id           BIGINT       NOT NULL,
    action            VARCHAR(255) NOT NULL,
    affected_model    VARCHAR(255),
    changes           JSONB,
    affected_model_id BIGINT,
    ip_address        VARCHAR(45),
    user_agent        TEXT,
    created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_audit_logs_user
        FOREIGN KEY (user_id)
            REFERENCES users (id)
            ON DELETE CASCADE
);

-- Índices adicionales para optimización
CREATE INDEX idx_groups_academic_management ON groups (academic_management_id);
CREATE INDEX idx_classrooms_module ON classrooms (module_id);
CREATE INDEX idx_day_schedules_day ON day_schedules (day_id);
CREATE INDEX idx_day_schedules_schedule ON day_schedules (schedule_id);
CREATE INDEX idx_assignments_day_schedule ON assignments (day_schedule_id);
CREATE INDEX idx_assignments_subject ON assignments (subject_id);
CREATE INDEX idx_assignments_classroom ON assignments (classroom_id);
CREATE INDEX idx_assignments_user ON assignments (user_id);
CREATE INDEX idx_audit_logs_user ON audit_logs (user_id);
CREATE INDEX idx_audit_logs_affected_model ON audit_logs (affected_model, affected_model_id);

-- Comentarios en las tablas
COMMENT
ON TABLE users IS 'Tabla de usuarios del sistema';
COMMENT
ON TABLE academic_management IS 'Gestión académica por periodos';
COMMENT
ON TABLE groups IS 'Grupos académicos';
COMMENT
ON TABLE subjects IS 'Materias o asignaturas';
COMMENT
ON TABLE modules IS 'Módulos o edificios';
COMMENT
ON TABLE classrooms IS 'Aulas y espacios físicos';
COMMENT
ON TABLE roles IS 'Roles de usuarios';
COMMENT
ON TABLE permissions IS 'Permisos del sistema';
COMMENT
ON TABLE days IS 'Días de la semana';
COMMENT
ON TABLE schedules IS 'Horarios disponibles';
COMMENT
ON TABLE day_schedules IS 'Combinación de días y horarios';
COMMENT
ON TABLE assignments IS 'Asignaciones de aulas a materias y docentes';
COMMENT
ON TABLE audit_logs IS 'Registro de auditoría del sistema';