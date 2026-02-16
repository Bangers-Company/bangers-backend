# Completed
## Prompt kennisboog
Je bent een volwaardige Laravel PHP ontwikkelaar met een hoog begrip en kennis van Design principes, Security en veel belang aan het Separation of Concerns en Single Responsibility.

---

Dit implementatie plan is geschreven voor fase 2.1 volgens het projectvoorstel. Het is de bedoeling dat de basis entiteiten van het project worden opgesteld. Migrations hiervoor worden geschreven en de CRUD functionaliteiten en Search functionaliteiten geschreven worden
## Iteratieplan Overview
Voor deze iteratie is het van belang de API-endpoints te schrijven voor Bangers. Dit heeft voornamelijk betrekking op de tables die later inzichtelijk zijn gemaakt middels SQL.

## PostgreSQL tables Iteratie 2.1
De volgende tables moeten geimplementeerd worden voor een PostgreSQL database: 
``` SQL
CREATE TABLE media (
    id UUID PRIMARY KEY,
    owner_id UUID REFERENCES users(id) ON DELETE CASCADE,
    type VARCHAR(50) NOT NULL, -- profile_picture, artist_image, festival_banner
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

CREATE TABLE festivals (
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
    festival_id UUID REFERENCES festivals(id) ON DELETE CASCADE,
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

CREATE TABLE festival_acts (
    festival_id UUID REFERENCES festivals(id) ON DELETE CASCADE,
    act_id UUID REFERENCES acts(id) ON DELETE CASCADE,
    announcement_date TIMESTAMP,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    PRIMARY KEY (festival_id, act_id)
);
```

## Core Implementaties 2.1
In de huidige iteratie willen we voornamelijk API-endpoints beschikbaar stellen voor de CRUD methoden van de tables en het koppelen van artiesten/acts aan festivals.
### Migraties
De migraties moeten geschreven zijn voor het implementeren in de Database. Deze hoeven niet direct uitgevoerd te worden.
### CRUD functionaliteiten
De hoofdtabellen moeten hun CRUD functionaliteiten hebben en in de vorm van endpoints bereikbaar zijn. 
De koppeltabellen moeten kunnen worden gevuld via endpoints.
### Search engine met custom filtering
Een endpoint voor het searchen met een custom filter waarmee elke individuele table aan of uitgezet kan worden door de query aan te passen.
Er moet gefiltered kunnen worden op:
- Datum
- Naam
- Locatie
- [Artiesten, Acts, Festivals] (en latere iteraties ook users)
## Acceptatie Criteria
- [] Migrations voor alle opgegeven tables en koppelingen
- [] Werkende endpoints CRUD Festivals
- [] Werkende endpoints CRUD artists
- [] Werkende endpoints CRUD Stages
- [] Werkende endpoints CRUD acts
- [] Mogelijkheid tot uploading van banner(festival) of profile(artist) foto's
- [] Werkende endpoints voor het koppelen artiesten aan acts
- [] Werkende endpoints voor het koppelen van acts aan festivals
- [] Schaalbare Search functionaliteit met filtering obv vooraf opgegeven waarden