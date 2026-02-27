# Bangers Full API Documentation

This document provides a single source of truth for all available API endpoints in the Bangers application, categorized by Administrative and Mobile contexts.

---

## 1. Authentication

### Admin Authentication

Admin login is restricted to users with the `admin` role.
| Method | Endpoint | Controller | Description |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/auth/login` | `Api\AuthController@adminLogin` | Login for administrators |

### Mobile Authentication

| Method | Endpoint                    | Controller                       | Description              |
| :----- | :-------------------------- | :------------------------------- | :----------------------- |
| `POST` | `/api/mobile/auth/register` | `Mobile\AuthController@register` | Register new mobile user |
| `POST` | `/api/mobile/auth/login`    | `Mobile\AuthController@login`    | Standard user login      |
| `POST` | `/api/mobile/auth/refresh`  | `Mobile\AuthController@refresh`  | Refresh access token     |
| `POST` | `/api/mobile/auth/logout`   | `Mobile\AuthController@logout`   | Revoke current token     |

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

### Stage & Media Management

| Method           | Endpoint       | Description                    |
| :--------------- | :------------- | :----------------------------- |
| `GET/POST`       | `/stages`      | List or Create reusable stages |
| `GET/PUT/DELETE` | `/stages/{id}` | Stage CRUD                     |
| `GET/POST`       | `/media`       | List or Upload media files     |
| `DELETE`         | `/media/{id}`  | Remove media                   |

### User & RBAC Management

| Method           | Endpoint                     | Description                |
| :--------------- | :--------------------------- | :------------------------- |
| `GET`            | `/users`                     | List all users             |
| `GET/PUT/DELETE` | `/users/{id}`                | User record management     |
| `POST/DELETE`    | `/users/{id}/roles/{roleId}` | Assign/Revoke roles        |
| `GET`            | `/roles`                     | List available roles       |
| `GET`            | `/permissions`               | List available permissions |

### Timetable Administration

| Method   | Endpoint                   | Controller                          | Description                         |
| :------- | :------------------------- | :---------------------------------- | :---------------------------------- |
| `GET`    | `/timetables`              | `Admin\TimetableController@index`   | List all timetables                 |
| `GET`    | `/timetables/{id}`         | `Admin\TimetableController@show`    | Get detailed timetable with entries |
| `POST`   | `/timetables`              | `Admin\TimetableController@store`   | Create a new official timetable     |
| `PUT`    | `/timetables/{id}`         | `Admin\TimetableController@update`  | Update metadata and batch entries   |
| `PATCH`  | `/timetables/{id}/publish` | `Admin\TimetableController@publish` | Toggle public visibility            |
| `DELETE` | `/timetables/{id}`         | `Admin\TimetableController@destroy` | Delete a timetable                  |

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

### Social & Friends

| Method   | Endpoint                    | Description             |
| :------- | :-------------------------- | :---------------------- |
| `GET`    | `/friends`                  | List accepted friends   |
| `GET`    | `/friends/requests`         | Pending friend requests |
| `POST`   | `/friends/{user_id}`        | Send friend request     |
| `PUT`    | `/friends/{user_id}/accept` | Accept request          |
| `DELETE` | `/friends/{user_id}`        | Unfriend                |

### Personal & Group Planning

| Method | Endpoint                            | Description                                  |
| :----- | :---------------------------------- | :------------------------------------------- |
| `GET`  | `/events/{event_id}/timetable`      | Get official event schedule                  |
| `POST` | `/personal-timetables`              | Create personal schedule for event           |
| `GET`  | `/personal-timetables/{event_id}`   | View personal schedule                       |
| `PUT`  | `/personal-timetables/{id}/entries` | Batch update entries (Overlap check enabled) |

### Groups

| Method | Endpoint                        | Description                            |
| :----- | :------------------------------ | :------------------------------------- |
| `POST` | `/groups`                       | Create a new planning group            |
| `GET`  | `/groups`                       | List user's groups                     |
| `POST` | `/groups/{id}/members`          | Add member to group (owner/admin only) |
| `POST` | `/groups/{group_id}/timetables` | Create shared group timetable          |

### Favorites & Attendance

| Method        | Endpoint                  | Description               |
| :------------ | :------------------------ | :------------------------ |
| `POST/DELETE` | `/favorites/{entry_id}`   | Bookmark/Unbookmark act   |
| `PUT/DELETE`  | `/events/{id}/attendance` | Mark as "Going" or remove |

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
