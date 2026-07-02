# 🎟️ Enterprise Event & Workshop Booking System

A scalable, high-concurrency event management and ticketing platform built with a strict adherence to the Single Responsibility Principle (SRP) and enterprise design patterns.

This system allows Organizers to publish events, Attendees to book tickets, and Administrators to manage the entire ecosystem via a reactive, tabbed dashboard. It is engineered to handle high traffic using a hybrid caching strategy and pessimistic database locking to prevent booking race conditions.

## 🚀 Tech Stack

- **Backend:** Laravel (PHP 8.2+)
- **Frontend:** Blade Templates, Alpine.js (Client-side reactivity), Tailwind CSS
- **Database:** MySQL / PostgreSQL
- **Caching & Queues:** Redis (Hybrid Cache Versioning)

## 🏛️ Enterprise Architecture & The Pipeline

This project intentionally moves away from traditional "Fat Controllers" found in standard MVC tutorials. Instead, it utilizes a strict, one-way data pipeline to ensure complete decoupling, testability, and modularity.

### The Execution Pipeline

Every HTTP request follows this exact lifecycle:

`Route ➡️ Middleware ➡️ Thin Controller ➡️ FormRequest ➡️ DTO ➡️ Feature Class ➡️ Repository ➡️ View/Response`

### Layer Responsibilities

- **Thin Web Controllers (`app/Http/Controllers/`)**: Act strictly as traffic directors. They do not contain any business logic, database queries, or manual authentication. They catch the request, map it to a DTO, pass it to a Feature, and return a view or redirect.
- **FormRequests (`app/Http/Requests/`)**: Handle 100% of incoming data validation and authorization before the Controller is even hit.
- **Data Transfer Objects (`app/DTOs/`)**: Strongly-typed, immutable PHP objects. Incoming request arrays are mapped to DTOs to guarantee data integrity across the application.
- **Feature Classes (`app/Features/`)**: The "Brains" of the application. All business logic, mathematical calculations, and database transactions live here. Feature classes are entirely decoupled from HTTP routing, meaning they can be reused by APIs or Console Commands.
- **Repositories (`app/Repositories/`)**: The only layer allowed to communicate with the database via Eloquent. This isolates SQL logic and makes the system database-agnostic.

### Architectural Rules Enforced

- **The "Manage" (Upsert) Pattern**: Create and Update operations are unified into a single `Manage[Entity]Feature` utilizing DTOs, significantly reducing code duplication.
- **Enum State Integrity**: System statuses and roles (e.g., booking statuses, event approval statuses, user roles) are strictly implemented as PHP Enums to ensure type safety and consistency across the application.
- **Concurrency Protection**: Ticket booking utilizes pessimistic locking (`lockForUpdate()`) within isolated database transactions to prevent race conditions and overbooking.

## ⚡ Core Features

- **Hybrid Caching System**: Utilizes a highly optimized "versioning" strategy for public listings and admin dashboards. Instead of tracking thousands of individual filtered search cache keys, the system relies on global cache versions for different entities. Upon any mutation (create/update/delete/approve), the related version is incremented, instantly invalidating all stale permutations in an O(1) operation.
- **Alpine.js Dashboards**: Dashboards utilize Alpine.js. The Admin panel relies on robust URL-driven state for server-side filtering and pagination while maintaining clean UI interactions.
- **God-Mode Admin Panel**: Administrators have full oversight to soft-delete users, cancel events, and approve/reject pending Organizer profiles.
- **Automated Cleanup Commands**: Artisan console commands scheduled via CRON to automatically prune expired draft events from the database.

## 📂 Project Structure

```text
├── app/
│   ├── Console/Commands/        # Scheduled cleanup tasks
│   ├── DTOs/                    # Immutable Data Transfer Objects
│   ├── Enums/                   # Strongly typed application states
│   ├── Features/                # Isolated Business Logic & Transactions
│   ├── Http/
│   │   ├── Controllers/Web/     # Thin routing controllers
│   │   ├── Requests/            # Validation boundaries
│   │   └── Middleware/          # Role-based access control
│   ├── Models/                  # Eloquent Models
│   └── Repositories/            # Database access and query isolation
├── resources/
│   ├── views/                   # Blade templates + Alpine.js components
│   └── css/                     # Tailwind directives
└── routes/
    ├── web.php                  # Optimized, grouped route definitions
    └── console.php              # Scheduled task definitions
```

## 🛠️ Installation & Setup

Follow these steps to get the project running in your local development environment.

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & npm
- MySQL or PostgreSQL database
- Redis (Optional, but recommended for caching)

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/event-booking-system.git
cd event-booking-system
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Configuration

Copy the sample environment file and generate a unique application key.

```bash
cp .env.example .env
php artisan key:generate
```

Open the `.env` file and configure your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=event_booking
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Database Migration & Seeding

Run the migrations to build the schema and seed the database with initial roles, an admin account, and dummy data.

```bash
php artisan migrate --seed
```

### 5. Compile Frontend Assets

Build the Tailwind CSS and external assets.

```bash
npm run build
# Or, for hot-reloading during development:
# npm run dev
```

### 6. Start the Server

Launch the Laravel development server.

```bash
php artisan serve
```

The application will now be live at `http://localhost:8000`.