# Bangers API Documentation (v2)

This document provides a comprehensive overview of the new and updated API routes, controllers, and expected request bodies following the Timetable & Group Planning implementation and Mobile Namespace migration.

---

## Admin API (`/api/admin/*`)

All admin routes require a token with the **admin** role. Base URL: `{{host}}/api/admin`

### Timetables

| Method   | Endpoint                   | Controller                          | Description                         |
| :------- | :------------------------- | :---------------------------------- | :---------------------------------- |
| `GET`    | `/timetables`              | `Admin\TimetableController@index`   | List all timetables                 |
| `GET`    | `/timetables/{id}`         | `Admin\TimetableController@show`    | Get detailed timetable with entries |
| `POST`   | `/timetables`              | `Admin\TimetableController@store`   | Create a new official timetable     |
| `PUT`    | `/timetables/{id}`         | `Admin\TimetableController@update`  | Update metadata and batch entries   |
| `PATCH`  | `/timetables/{id}/publish` | `Admin\TimetableController@publish` | Toggle public visibility            |
| `DELETE` | `/timetables/{id}`         | `Admin\TimetableController@destroy` | Delete a timetable                  |

#### Request Bodies

**POST `/timetables`**

```json
{
    "event_id": "uuid",
    "name": "Official Timetable",
    "is_official": true,
    "is_public": false
}
```

**PUT `/timetables/{id}`**

```json
{
    "name": "Updated Name",
    "is_public": true,
    "entries": [
        {
            "stage_id": "uuid",
            "act_id": "uuid",
            "start_time": "2026-07-24 14:00:00",
            "end_time": "2026-07-24 15:30:00"
        }
    ]
}
```

---

## Mobile API (`/api/mobile/*`)

Endpoints for regular users. Base URL: `{{host}}/api/mobile`

### Authentication

| Method | Endpoint         | Controller                       | Description         |
| :----- | :--------------- | :------------------------------- | :------------------ |
| `POST` | `/auth/register` | `Mobile\AuthController@register` | Register new user   |
| `POST` | `/auth/login`    | `Mobile\AuthController@login`    | Standard user login |
| `POST` | `/auth/refresh`  | `Mobile\AuthController@refresh`  | Refresh auth token  |
| `POST` | `/auth/logout`   | `Mobile\AuthController@logout`   | Revoke token        |

#### Request Bodies

**POST `/auth/register`**

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

### Personal Planning

| Method | Endpoint                            | Description                                  |
| :----- | :---------------------------------- | :------------------------------------------- |
| `GET`  | `/events/{event_id}/timetable`      | Get official event schedule                  |
| `POST` | `/personal-timetables`              | Create personal schedule for event           |
| `GET`  | `/personal-timetables/{event_id}`   | View personal schedule                       |
| `PUT`  | `/personal-timetables/{id}/entries` | Batch update entries (Overlap check enabled) |
| `GET`  | `/favorites`                        | List favorite entries                        |
| `POST` | `/favorites/{entry_id}`             | Bookmark an act                              |

#### Request Bodies

**POST `/personal-timetables`**

```json
{
    "event_id": "uuid",
    "name": "My Spectacular Weekend"
}
```

**PUT `/personal-timetables/{id}/entries`**

```json
{
    "entry_ids": ["uuid-1", "uuid-2"]
}
```

### Groups & Social

| Method | Endpoint                        | Description                            |
| :----- | :------------------------------ | :------------------------------------- |
| `GET`  | `/friends`                      | List accepted friends                  |
| `POST` | `/friends/{user_id}`            | Send/Request friendship                |
| `POST` | `/groups`                       | Create a new planning group            |
| `POST` | `/groups/{id}/members`          | Add member to group (owner/admin only) |
| `POST` | `/groups/{group_id}/timetables` | Create shared group timetable          |

#### Request Bodies

**POST `/groups`**

```json
{
    "name": "The Rave Squad",
    "description": "Planning for summer festivals"
}
```

**POST `/groups/{id}/members`**

```json
{
    "user_id": "uuid",
    "role": "admin" // admin or member
}
```

---

## Technical Notes

- **UUIDs**: All IDs (`event_id`, `stage_id`, `act_id`, `entry_id`) must be valid UUIDs.
- **Overlap Validation**: Personal and Admin timetables strictly enforce no overlaps for the same stage/schedule at the database level.
- **Namespacing**: Ensure headers include `Accept: application/json` to avoid HTML responses.
