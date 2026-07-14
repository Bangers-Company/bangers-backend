# Bangers Full API Documentation

This document provides a single source of truth for all available API endpoints in the Bangers application, categorized by Administrative and Mobile contexts.

---

## 1. Authentication

### Admin Authentication

Admin login is restricted to users with the `admin` role.

| Method | Endpoint          | Description              |
| :----- | :---------------- | :----------------------- |
| `POST` | `/api/auth/login` | Login for administrators |

**Request Body**

```json
{
    "email": "admin@bangers.com",
    "password": "password"
}
```

**Response Body (200 OK)**

```json
{
    "accessToken": "1|token...",
    "refreshToken": "2|token...",
    "expiresAt": "2026-03-01T20:20:41+01:00",
    "user": {
        "id": "uuid",
        "email": "admin@bangers.com",
        "username": "admin",
        "first_name": "Admin",
        "last_name": "User",
        "dob": "1990-01-01",
        "bio": "System administrator",
        "is_public": true,
        "roles": ["admin"],
        "permissions": ["manage_users", "manage_events"],
        "profile_media": null,
        "created_at": "2026-01-01T00:00:00Z",
        "updated_at": "2026-01-01T00:00:00Z"
    }
}
```

### Mobile Authentication

| Method | Endpoint                    | Description              |
| :----- | :-------------------------- | :----------------------- |
| `POST` | `/api/mobile/auth/register` | Register new mobile user |
| `POST` | `/api/mobile/auth/login`    | Standard user login      |
| `POST` | `/api/mobile/auth/refresh`  | Refresh access token     |
| `POST` | `/api/mobile/auth/logout`   | Revoke current token     |

**Register Request Body**

```json
{
    "email": "user@example.com",
    "username": "johndoe",
    "password": "password123",
    "first_name": "John",
    "last_name": "Doe",
    "dob": "1990-01-01"
}
```

**Login Request Body**

```json
{
    "email": "user@example.com",
    "password": "password123"
}
```

**Response Body (200 OK / 201 Created)**

```json
{
    "accessToken": "1|token...",
    "refreshToken": "2|token...",
    "expiresAt": "2026-03-01T20:20:41+01:00",
    "user": {
        "id": "uuid",
        "email": "user@example.com",
        "username": "johndoe",
        "first_name": "John",
        "last_name": "Doe",
        "dob": "1990-01-01",
        "bio": "I love festivals!",
        "is_public": true,
        "roles": ["user"],
        "permissions": [],
        "profile_media": {
            "id": "uuid",
            "url": "https://...",
            "type": "profile_picture"
        },
        "created_at": "2026-02-01T12:00:00Z",
        "updated_at": "2026-02-01T12:00:00Z"
    }
}
```

---

## 2. Admin API (`/api/admin/*`)

Requires `auth:sanctum` and `role:admin`.

### Event Management

| Method   | Endpoint                   | Description                                  |
| :------- | :------------------------- | :------------------------------------------- |
| `GET`    | `/events`                  | List all events (supports filtering/sorting) |
| `POST`   | `/events`                  | Create new event                             |
| `GET`    | `/events/{id}`             | Get event details                            |
| `PUT`    | `/events/{id}`             | Update event                                 |
| `DELETE` | `/events/{id}`             | Delete event                                 |
| `POST`   | `/events/{id}/lineup-sync` | Batch sync entire lineup for an event        |

**Create/Update Request Body**

```json
{
    "name": "Summer Festival 2026",
    "description": "The biggest event of the year.",
    "location": "Central Park, NY",
    "start_date": "2026-07-01T12:00:00Z",
    "end_date": "2026-07-03T23:59:59Z",
    "banner_media_id": "uuid-optional"
}
```

**Lineup Sync Request Body**

```json
{
    "lineup": [
        {
            "act_id": "uuid",
            "stage_id": "uuid-optional",
            "date": "2026-07-01"
        }
    ]
}
```

**Response Body (Event Object)**

```json
{
    "id": "uuid",
    "name": "Summer Festival 2026",
    "description": "The biggest event of the year.",
    "location": "Central Park, NY",
    "start_date": "2026-07-01",
    "end_date": "2026-07-03",
    "version": 1,
    "banner": {
        "id": "uuid",
        "url": "https://...",
        "type": "event_banner"
    },
    "stages": [{ "id": "uuid", "name": "Main Stage" }],
    "acts": [{ "id": "uuid", "name": "Opening Set", "version": 1 }],
    "created_at": "2026-01-01T00:00:00Z",
    "updated_at": "2026-03-01T20:20:41Z"
}
```

### Artist & Act Management

| Method           | Endpoint             | Description                       |
| :--------------- | :------------------- | :-------------------------------- |
| `GET/POST`       | `/artists`           | List or Create artists            |
| `GET/PUT/DELETE` | `/artists/{id}`      | Artist CRUD                       |
| `GET/POST`       | `/acts`              | List or Create acts               |
| `GET/PUT/DELETE` | `/acts/{id}`         | Act CRUD                          |
| `POST/DELETE`    | `/acts/{id}/artists` | Associate/Remove artists from act |
| `POST/DELETE`    | `/acts/{id}/stages`  | Associate/Remove stages from act  |
| `POST/DELETE`    | `/acts/{id}/events`  | Associate/Remove events from act  |

**Artist Response Body**

```json
{
    "id": "uuid",
    "name": "The Bangers",
    "bio": "Best electronic duo.",
    "genre": "Electronic",
    "version": 1,
    "image": {
        "id": "uuid",
        "url": "https://...",
        "type": "artist_image"
    },
    "acts": [],
    "created_at": "2026-01-01T00:00:00Z",
    "updated_at": "2026-01-01T00:00:00Z"
}
```

**Act Response Body**

```json
{
    "id": "uuid",
    "name": "Opening Set",
    "description": "Chill vibes for the opening.",
    "version": 1,
    "stage_id": "uuid-from-pivot",
    "date": "2026-07-01",
    "artists": [{ "id": "uuid", "name": "The Bangers" }],
    "created_at": "2026-01-01T00:00:00Z",
    "updated_at": "2026-01-01T00:00:00Z"
}
```

### Stage & Media Management

| Method           | Endpoint       | Description                    |
| :--------------- | :------------- | :----------------------------- |
| `GET/POST`       | `/stages`      | List or Create reusable stages |
| `GET/PUT/DELETE` | `/stages/{id}` | Stage CRUD                     |
| `GET/POST`       | `/media`       | List or Upload media files     |
| `DELETE`         | `/media/{id}`  | Remove media                   |

**Stage Response Body**

```json
{
    "id": "uuid",
    "name": "Main Stage",
    "description": "The largest outdoor stage.",
    "version": 1,
    "event_id": "uuid-from-pivot",
    "created_at": "2026-01-01T00:00:00Z",
    "updated_at": "2026-01-01T00:00:00Z"
}
```

**Media Response Body**

```json
{
    "id": "uuid",
    "url": "https://...",
    "type": "event_banner",
    "mime_type": "image/jpeg",
    "size_bytes": 102456,
    "width": 1920,
    "height": 1080,
    "is_public": true,
    "created_at": "2026-01-01T00:00:00Z"
}
```

### User & RBAC Management

| Method           | Endpoint                     | Description                |
| :--------------- | :--------------------------- | :------------------------- |
| `GET`            | `/users`                     | List all users             |
| `GET/PUT/DELETE` | `/users/{id}`                | User record management     |
| `POST/DELETE`    | `/users/{id}/roles/{roleId}` | Assign/Revoke roles        |
| `GET`            | `/roles`                     | List available roles       |
| `GET`            | `/permissions`               | List available permissions |

**Update User Request Body**

```json
{
    "email": "user@example.com",
    "username": "johndoe_updated",
    "first_name": "John",
    "last_name": "Doe",
    "dob": "1990-01-01",
    "bio": "Electronic music lover.",
    "is_public": true,
    "profile_media_id": "uuid"
}
```

**Role Response Body**

```json
{
    "id": "uuid",
    "name": "admin",
    "description": "System Administrator",
    "permissions": [
        { "id": "uuid", "name": "Manage Users", "slug": "manage_users" }
    ]
}
```

### Social & Friendship (Admin)

| Method   | Endpoint        | Description             |
| :------- | :-------------- | :---------------------- |
| `GET`    | `/friends`      | List all friendships    |
| `POST`   | `/friends/{id}` | Force create friendship |
| `DELETE` | `/friends/{id}` | Delete friendship       |

### Attendance & Engagement (Admin)

| Method | Endpoint                  | Description                      |
| :----- | :------------------------ | :------------------------------- |
| `GET`  | `/events/{id}/attendees`  | List all users going to event    |
| `GET`  | `/users/{id}/events`      | List all events user is going to |
| `PUT`  | `/events/{id}/attendance` | Force update user attendance     |

### Timetable Administration

| Method   | Endpoint                   | Description                         |
| :------- | :------------------------- | :---------------------------------- |
| `GET`    | `/timetables`              | List all timetables                 |
| `GET`    | `/timetables/{id}`         | Get detailed timetable with entries |
| `POST`   | `/timetables`              | Create a new official timetable     |
| `PUT`    | `/timetables/{id}`         | Update metadata and batch entries   |
| `PATCH`  | `/timetables/{id}/publish` | Toggle public visibility            |
| `DELETE` | `/timetables/{id}`         | Delete a timetable                  |

**Create Timetable Request Body**

```json
{
    "event_id": "uuid",
    "name": "Official Schedule",
    "is_official": true,
    "is_public": false
}
```

**Update Timetable Entries Request Body**

```json
{
    "name": "Updated Schedule",
    "entries": [
        {
            "stage_id": "uuid",
            "act_id": "uuid",
            "start_time": "2026-07-01T14:00:00Z",
            "end_time": "2026-07-01T15:30:00Z"
        }
    ]
}
```

---

## 3. Mobile API (`/api/mobile/*`)

Requires `auth:sanctum`.

### Discovery & Sync

| Method | Endpoint            | Description                              |
| :----- | :------------------ | :--------------------------------------- |
| `GET`  | `/dashboard`        | User dashboard data                      |
| `GET`  | `/search`           | Global search for events/artists         |
| `GET`  | `/events/suggested` | Personalized event suggestions           |
| `GET`  | `/sync/events`      | Delta-sync for offline support (Events)  |
| `GET`  | `/sync/artists`     | Delta-sync for offline support (Artists) |
| `GET`  | `/sync/acts`        | Delta-sync for offline support (Acts)    |

**Dashboard Response Body**

```json
{
    "data": {
        "attending_events": [],
        "upcoming_events": [
            {
                "id": "uuid",
                "name": "Summer Festival",
                "banner": { "id": "uuid", "url": "https://..." }
            }
        ],
        "sync_timestamp": "2026-03-01T20:20:41Z"
    },
    "meta": {
        "discovery_endpoints": {
            "suggested": "/api/mobile/events/suggested",
            "friends": "/api/mobile/events/friends"
        }
    }
}
```

**Search Response Body**

```json
{
  "events": {
    "data": [
      { "id": "uuid", "name": "Event Name", "banner": { "url": "..." } }
    ],
    "meta": { "current_page": 1, "last_page": 1, "per_page": 10, "total": 1 },
    "links": { "first": "...", "last": "...", "prev": null, "next": null }
  },
  "artists": { "data": [], "meta": {...}, "links": {...} },
  "acts": { "data": [], "meta": {...}, "links": {...} }
}
```

**Sync Response Body**
Returns a collection of Resources:

- `/sync/events`: `EventResource[]`
- `/sync/artists`: `ArtistResource[]`
- `/sync/acts`: `ActResource[]`

### Social & Friends

| Method   | Endpoint                    | Description             |
| :------- | :-------------------------- | :---------------------- |
| `GET`    | `/friends`                  | List accepted friends   |
| `GET`    | `/friends/requests`         | Pending friend requests |
| `POST`   | `/friends/{user_id}`        | Send friend request     |
| `PUT`    | `/friends/{user_id}/accept` | Accept request          |
| `DELETE` | `/friends/{user_id}`        | Unfriend                |

**Friendship Response Body**

```json
{
    "id": "uuid",
    "status": "accepted",
    "requester_id": "uuid",
    "user1": { "id": "uuid", "username": "alice" },
    "user2": { "id": "uuid", "username": "bob" },
    "created_at": "2026-01-01T00:00:00Z",
    "updated_at": "2026-01-01T00:00:00Z"
}
```

### Personal & Group Planning

| Method | Endpoint                            | Description                                  |
| :----- | :---------------------------------- | :------------------------------------------- |
| `GET`  | `/events/{event_id}/timetable`      | Get official event schedule                  |
| `POST` | `/personal-timetables`              | Create personal schedule for event           |
| `GET`  | `/personal-timetables/{event_id}`   | View personal schedule                       |
| `PUT`  | `/personal-timetables/{id}/entries` | Batch update entries (Overlap check enabled) |

**Create Personal Timetable Request Body**

```json
{
    "event_id": "uuid",
    "name": "My Weekend Plan"
}
```

**Update Timetable Entries Request Body**

```json
{
    "entry_ids": ["uuid1", "uuid2"]
}
```

### Groups

| Method | Endpoint                        | Description                            |
| :----- | :------------------------------ | :------------------------------------- |
| `POST` | `/groups`                       | Create a new planning group            |
| `GET`  | `/groups`                       | List user's groups                     |
| `POST` | `/groups/{id}/members`          | Add member to group (owner/admin only) |
| `POST` | `/groups/{group_id}/timetables` | Create shared group timetable          |

**Create Group Request Body**

```json
{
    "name": "The Festival Crew",
    "description": "Planning for Summer Fest."
}
```

**Add Member Request Body**

```json
{
    "user_id": "uuid",
    "role": "member"
}
```

### Favorites & Attendance

| Method        | Endpoint                  | Description               |
| :------------ | :------------------------ | :------------------------ |
| `POST/DELETE` | `/favorites/{entry_id}`   | Bookmark/Unbookmark act   |
| `PUT/DELETE`  | `/events/{id}/attendance` | Mark as "Going" or remove |

**Personal Timetable Response Body**

```json
{
    "id": "uuid",
    "user_id": "uuid",
    "event_id": "uuid",
    "name": "My Weekend Plan",
    "entries": [
        {
            "id": "uuid",
            "start_time": "2026-07-01T14:00:00Z",
            "end_time": "2026-07-01T15:30:00Z",
            "act": { "id": "uuid", "name": "Artist Name" },
            "stage": { "id": "uuid", "name": "Main Stage" }
        }
    ]
}
```

**Attendance Response Body**

```json
{
    "event_id": "uuid",
    "user_id": "uuid",
    "status": "going",
    "updated_at": "2026-03-01T20:20:41Z"
}
```

---

## 4. Mobile Delta Sync Guide

The mobile application uses a **Delta Sync** mechanism to ensure data is available offline while minimizing bandwidth usage.

### How it works

The backend exposes sync endpoints (Events, Artists, Acts) that accept a `since` parameter (a Unix timestamp in seconds or milliseconds).

- **Full Sync**: If `since` is omitted, the API returns all active records.
- **Delta Sync**: If `since` is provided, the API returns only records that have been **updated or (soft) deleted** after that timestamp.

### Implementation Checklist

1. **Initial Load**: Perform a full sync on application start if no local data exists. Store the current server time (or the highest `updated_at` from the results).
2. **Background Sync**: Trigger a delta sync when:
    - The app comes to the foreground.
    - The user pulls to refresh on the Dashboard, Events, or Artist pages.
    - A push notification indicates a schedule change.
3. **Data Handling**:
    - **Upsert**: If the record exists locally, update it. If not, insert it.
    - **Delete**: If a record in the payload has a `deleted_at` timestamp, remove it from the local database.

### Necessary Mobile Checks

- **Timestamp Integrity**: Always use the timestamp returned by the _previous_ sync as the `since` value for the _next_ sync.
- **Conflict Resolution**: Local user data (like `personal-timetables`) should refer to the sync'd IDs (`event_id`, etc.). If a sync indicates an event was deleted, clean up associated local plans.
- **Media Caching**: The sync payload contains media URLs. The mobile app should lazily cache these images to prevent re-downloads.

---

## 5. Technical Notes

- **UUIDs**: All IDs (`event_id`, `stage_id`, `act_id`, `entry_id`) must be valid UUIDs.
- **Overlap Validation**: Personal and Admin timetables strictly enforce no overlaps for the same stage/schedule at the database level.
- **Headers**: Always include `Accept: application/json` to ensure the backend returns JSON even on errors.
- **Pagination**: Admin listing routes (Events, Users, etc.) support Laravel's standard `?page=X` pagination.
