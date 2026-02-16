# Bangers Backend

This is the backend API for the Bangers app, built with Laravel. It manages festivals, stages, artists, and acts, providing a robust platform for festival discovery and management.

## Currently Implemented

### Core Features
- **Centralized Media Handling**: Dedicated `media` table for managing images and banners with file upload support.
- **Festival Management**: CRUD operations for festivals, including location and date tracking.
- **Stage Management**: Linking stages to specific festivals.
- **Artist & Act System**: Support for individual artists and groups (acts).
- **Linkage Logic**: Modular endpoints to link artists to acts and acts to festivals.
- **Advanced Search**: Unified search endpoint filtering by name, date, location, and entity type.

### Technical highlights
- **UUIDs**: All primary and foreign keys use UUIDs for enhanced security and scalability.
- **Soft Deletes**: Implemented across all core entities for data safety.
- **Modular Routing**: API routes are split into granular controller-specific files (`routes/api/*Routes.php`).
- **Standardized Naming**: All routes follow a consistent `api.*` naming convention.

## Testing & API Tools
- **Insomnia Collection**: A full collection for testing all endpoints is available in `insomnia_bangers.json`.
- **Automated Tests**: Initial Pest test suite and Eloquent factories have been created.

> [!NOTE]
> PostgreSQL integration for the automated test environment (RefreshDatabase) is scheduled for a later iteration. Current implementation handles pdo_pgsql for runtime operations.

## Setup
1. Clone the repository.
2. Run `composer install`.
3. Configure your `.env` file with your database credentials.
4. Run `php artisan migrate`.
5. Run `php artisan serve`.
