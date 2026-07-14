```SQL
CREATE TABLE users (
    id UUID PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash TEXT NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    bio TEXT,
    profile_media_id UUID,
    is_verified BOOLEAN DEFAULT FALSE,
    is_public BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
    deleted_at TIMESTAMP,
    version INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE roles (
    id UUID PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL,
    description TEXT
);

CREATE TABLE user_roles (
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    role_id UUID REFERENCES roles(id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, role_id)
);

CREATE TABLE permissions (
    id UUID PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL,
    description TEXT
);

CREATE TABLE role_permissions {
    role_id UUID REFERENCES roles(id) ON DELETE CASCADE,
    permission_id UUID REFERENCES permissions(id) ON DELETE CASCADE,
    PRIMARY KEY (role_id, permission_id)
}

CREATE TABLE media (
    id UUID PRIMARY KEY,
    owner_id UUID REFERENCES users(id) ON DELETE CASCADE,
    type VARCHAR(50) NOT NULL, -- profile_picture, artist_image, event_banner
    storage_key TEXT NOT NULL,
    url TEXT NOT NULL,
    mime_type VARCHAR(100),
    size_bytes INTEGER,
    width INTEGER,
    height INTEGER,
    metadata JSONB,
    is_public BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    deleted_at TIMESTAMP
);

CREATE TABLE media_variants (
    id UUID PRIMARY KEY,
    media_id UUID REFERENCES media(id) ON DELETE CASCADE,
    variant_type VARCHAR(50) NOT NULL, -- thumbnail, small, medium
    url TEXT NOT NULL,
    width INTEGER,
    height INTEGER
);

ALTER TABLE users
ADD CONSTRAINT fk_users_profile_media
FOREIGN KEY (profile_media_id)
REFERENCES media(id)
ON DELETE SET NULL;

CREATE TABLE friendships (
    user_id_1 UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    user_id_2 UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    status VARCHAR(20) NOT NULL, -- pending, accepted, blocked
    requested_by UUID NOT NULL REFERENCES users(id),
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    PRIMARY KEY (user_id_1, user_id_2),
    CHECK (user_id_1 < user_id_2)
);

CREATE TABLE events (
    id UUID PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    location VARCHAR(255),
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    banner_media_id UUID REFERENCES media(id) ON DELETE SET NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
    deleted_at TIMESTAMP,
    version INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE stages (
    id UUID PRIMARY KEY,
    event_id UUID REFERENCES events(id) ON DELETE CASCADE,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
    deleted_at TIMESTAMP,
    version INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE artists (
    id UUID PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    bio TEXT,
    genre VARCHAR(255),
    image_media_id UUID REFERENCES media(id) ON DELETE SET NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
    deleted_at TIMESTAMP,
    version INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE acts (
    id UUID PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
    deleted_at TIMESTAMP,
    version INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE act_artists (
    act_id UUID REFERENCES acts(id) ON DELETE CASCADE,
    artist_id UUID REFERENCES artists(id) ON DELETE CASCADE,
    PRIMARY KEY (act_id, artist_id)
);

CREATE TABLE events_acts (
    event_id UUID REFERENCES events(id) ON DELETE CASCADE,
    act_id UUID REFERENCES acts(id) ON DELETE CASCADE,
    announcement_date TIMESTAMP,
    is_headliner BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    PRIMARY KEY (event_id, act_id)
);

CREATE TABLE timetable_entries (
    id UUID PRIMARY KEY,
    event_id UUID REFERENCES events(id) ON DELETE CASCADE,
    stage_id UUID REFERENCES stages(id) ON DELETE CASCADE,
    act_id UUID REFERENCES acts(id),
    start_time TIMESTAMP NOT NULL,
    end_time TIMESTAMP NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
    deleted_at TIMESTAMP,
    version INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE user_event_attendance (
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    event_id UUID REFERENCES events(id) ON DELETE CASCADE,
    status VARCHAR(20) NOT NULL, -- going, interested
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    PRIMARY KEY (user_id, event_id)
);

CREATE TABLE user_timetable_favorites (
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    timetable_entry_id UUID REFERENCES timetable_entries(id) ON DELETE CASCADE,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    PRIMARY KEY (user_id, timetable_entry_id)
);

CREATE TABLE personal_timetables (
    id UUID PRIMARY KEY,
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    event_id UUID REFERENCES events(id) ON DELETE CASCADE,
    name VARCHAR(255) NOT NULL,
    is_public BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
    version INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE personal_timetable_entries (
    timetable_id UUID REFERENCES personal_timetables(id) ON DELETE CASCADE,
    timetable_entry_id UUID REFERENCES timetable_entries(id) ON DELETE CASCADE,
    PRIMARY KEY (timetable_id, timetable_entry_id)
);

CREATE TABLE groups (
    id UUID PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    owner_id UUID REFERENCES users(id),
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE group_members (
    group_id UUID REFERENCES groups(id) ON DELETE CASCADE,
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    role VARCHAR(20) NOT NULL, -- owner, admin, member
    PRIMARY KEY (group_id, user_id)
);

CREATE TABLE group_timetables (
    id UUID PRIMARY KEY,
    group_id UUID REFERENCES groups(id) ON DELETE CASCADE,
    event_id UUID REFERENCES events(id) ON DELETE CASCADE,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW(),
    version INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE group_timetable_entries (
    group_timetable_id UUID REFERENCES group_timetables(id) ON DELETE CASCADE,
    timetable_entry_id UUID REFERENCES timetable_entries(id) ON DELETE CASCADE,
    added_by UUID REFERENCES users(id),
    PRIMARY KEY (group_timetable_id, timetable_entry_id)
);

CREATE TABLE exports (
    id UUID PRIMARY KEY,
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    timetable_type VARCHAR(20) NOT NULL, -- personal, group
    timetable_id UUID NOT NULL,
    export_format VARCHAR(20) NOT NULL, -- pdf, ics, json
    config JSONB,
    created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE import_sources (
    id UUID PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    source_url TEXT,
    type VARCHAR(50) NOT NULL, -- API, CSV, Manual
    last_imported_at TIMESTAMP
);

CREATE TABLE import_logs (
    id UUID PRIMARY KEY,
    source_id UUID REFERENCES import_sources(id),
    status VARCHAR(20) NOT NULL,
    message TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE sync_logs (
    id UUID PRIMARY KEY,
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    device_id VARCHAR(255) NOT NULL,
    last_synced_at TIMESTAMP NOT NULL
);
```
