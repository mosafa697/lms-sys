### LMS System

## Installation

Follow these steps to set up the application on your local machine.

### 1. Clone the Repository

Clone the repository to your local machine:

```bash
git clone https://github.com/mosafa697/lms-sys.get
cd lms-sys
```

### 2. Install Dependencies

Install PHP dependencies using Composer:

```bash
composer i
```

Install JavaScript dependencies using npm:

```bash
npm i
```

### 3. Set Up Environment File

Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

Generate an application key:

```bash
php artisan key:generate
```

### 4. Run Migrations and Seeders

Run the database migrations to create the necessary tables:

```bash
php artisan migrate
```

Seed the database with initial data (e.g., roles, permissions, and a default admin user):

```bash
php artisan db:seed
```

### 6. Start the Development Server

```bash
php artisan serve
```

Visit the application in your browser at [http://localhost:8000](http://localhost:8000).

## Usage

Access the application:

-   open postman.json file with postman app.