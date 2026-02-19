# Laravel JSONPlaceholder Importer

This Laravel application fetches large datasets from an external API (JSONPlaceholder), stores them efficiently in a local MySQL database, and displays them using Blade with pagination.

## How to Set Up and Run the Application

Clone the Project:

```bash
git clone <your-repo-url>
cd project-folder
```

Install Dependencies:

```bash
composer install
```

Configure Environment:

```bash
cp .env.example .env
```

Update database configuration inside .env:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

Generate App Key:

```bash
php artisan key:generate
```

Run Migrations:

```bash
php artisan migrate
```

Start the Application:

```bash
php artisan serve
```

Visit:

```
http://localhost:8000
```

Import Data:

Click the **Import Posts from jsonplaceholder.com** button OR visit:

```
http://localhost:8000/import
```

## Handling Large Data Fetching & Optimization

To efficiently handle large API datasets, the following strategies were used:

Chunk Processing:

Instead of inserting all records at once, the data is processed in chunks:

```php
collect($posts)->chunk(500)
```

This prevents memory overflow and improves performance.

Bulk Upsert Operation:

Used Laravel’s upsert() method:

- Prevents duplicate entries
- Respects unique constraint (placeholder_id)
- Updates existing records
- Inserts new records efficiently

This is significantly faster than updateOrCreate() inside loops.

Database-Level Unique Constraint:
A unique index was added to placeholder_id to ensure data integrity.

Pagination:

Data is displayed using Laravel pagination:

```php
Post::paginate(10);
```

This ensures:

- Only limited records load per page
- Faster rendering
- Better user experience

## Challenges Faced & Solutions

Duplicate Entry Error:

Problem:
Using insert() caused UniqueConstraintViolationException.

Solution:
Replaced insert() with upsert() to safely handle duplicate records.

Pagination Styling Issues:

Problem:
Pagination design was broken due to CSS framework mismatch (Tailwind vs Bootstrap).

Solution:
Configured Laravel to use Bootstrap pagination:

```php
Paginator::useBootstrapFive();
```

Route Not Defined Error:

Problem:
Route [import] not defined

Solution:
Added named routes in web.php:

```php
->name('import');
```

Null Variable in Blade:

Problem:
count(): Argument must be Countable|array

Solution:
Redirected after import instead of returning view directly:

```php
return redirect()->route('posts.index');
```

## Architecture Decisions

- Clean controller structure
- Named routes
- Bulk database operations
- Blade layout + components
- Server-side pagination

## Future Improvements

- Queue-based background importing
- Scheduled auto-sync (cron job)
- API pagination handling
- Caching layer for API responses
- Loading indicators during import

## Tech Stack

- Laravel 12
- PHP 8.3
- MySQL
- Blade Template Engine
- Bootstrap 5

Thank you for reviewing my project!
