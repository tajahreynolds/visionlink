## About

This web application was created to display a set of points, and allow adding, editing, and deleting points.

## Getting Started

Requirements:

- PostgreSQL
- PHP
- Composer
- NPM

1. Download the source code, then navigate to it's location.
2. Copy the .env.example file to .env and update the following to match your database connection info:

- DB_HOST
- DB_PORT
- DB_DATABASE
- DB_USERNAME
- DB_PASSWORD

3. Install composer dependencies with `composer install`
4. Install npm dependencies with `npm install`
5. Set the application key with `php artisan key:generate`
6. Build the project with `npm run dev`
7. Start the application with `php artisan serve`

That's it! You can now visit http://localhost:8000 in your browser to interact with the app.

## Considerations

This application assumes that a PostgreSQL database already contains the table of points with the following schema:

```
CREATE TABLE point (
    id SERIAL PRIMARY KEY,
    name text NOT NULL,
    x integer NOT NULL,
    y integer NOT NULL
);
```

If you do not have a `point` table in your database, you can create one by running the command

```
php artisan migrate --seed
```

This will create a table named `point` in your database with sample data to get you started.
