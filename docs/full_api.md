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

| Method       | Endpoint                   | Description                        |
| :----------- | :------------------------- | :--------------------------------- |
| `GET/POST`   | `/timetables`              | List or Create official timetables |
| `PUT/DELETE` | `/timetables/{id}`         | Update entries or delete schedule  |
| `PATCH`      | `/timetables/{id}/publish` | Toggle public visibility           |

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

### Social & Friends

| Method   | Endpoint                    | Description             |
| :------- | :-------------------------- | :---------------------- |
| `GET`    | `/friends`                  | List accepted friends   |
| `GET`    | `/friends/requests`         | Pending friend requests |
| `POST`   | `/friends/{user_id}`        | Send friend request     |
| `PUT`    | `/friends/{user_id}/accept` | Accept request          |
| `DELETE` | `/friends/{user_id}`        | Unfriend                |

### Personal & Group Planning

| Method     | Endpoint                            | Description                         |
| :--------- | :---------------------------------- | :---------------------------------- |
| `GET`      | `/events/{id}/timetable`            | View official event schedule        |
| `POST`     | `/personal-timetables`              | Create personal plan for event      |
| `PUT`      | `/personal-timetables/{id}/entries` | Sync selected acts to personal plan |
| `POST/GET` | `/groups`                           | Create or View planning groups      |
| `POST`     | `/groups/{id}/timetables`           | Create shared group schedule        |

### Favorites & Attendance

| Method        | Endpoint                  | Description               |
| :------------ | :------------------------ | :------------------------ |
| `POST/DELETE` | `/favorites/{entry_id}`   | Bookmark/Unbookmark act   |
| `PUT/DELETE`  | `/events/{id}/attendance` | Mark as "Going" or remove |
