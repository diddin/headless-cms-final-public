# Laravel Headless CMS with Block Editor and REST API

A Laravel-based content management system for creating, editing, and managing pages with flexible, block-based content using Livewire. Includes a full-featured REST API for seamless integration with external services or frontend applications.

---

## Site Url
    - http://13.210.72.244

## Auth Web Login
- Please using this test account:
    email: test@example.com
    password: password
then will redirect to dashboard

## Auth API Login
    - Please using this test account:
        email: test@example.com
        password: password
then will get generated token access response. This using for auth api routes.
    Authorization: Bearer <your token here>



## Features
- **Post management**: Title, slug, content, excerpt, image, status, published_at.
- **Category Management**: Name, slug.
- **Page Management**: Title, slug, status, and dynamic body.
- **Modular Block Editor**: Supports heading, paragraph, and image blocks.
- **Automatic Slug Generation**: Derived from the title.
- **Livewire-Powered Interface**: Forms and modals for smooth user experience.
- **REST API with Authentication**: Powered by Laravel Sanctum.
- **Flexible Content Structure**: JSON-based body for rich and dynamic content.

---

## Requirements

- PHP >= 8.1
- Composer
- Node.js and NPM
- MySQL or compatible database
- Laravel 10 or later
- Laravel Livewire
- Laravel Sanctum

---

## Installation

1. **Clone the Repository**:
    ```bash
    git clone https://github.com/yourusername/your-project.git
    cd your-project
    ```

2. **Install Dependencies**:
    ```bash
    composer install
    npm install && npm run build
    ```

3. **Configure Environment**:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    Edit `.env` file to match your local database settings.

4. **Run Migrations and Seeders**:
    ```bash
    php artisan migrate --seed
    ```

5. **Start Development Server**:
    ```bash
    php artisan serve
    ```

---

## API Authentication

This project uses **Laravel Sanctum** for API token authentication.

To generate an access token:
```php
// Run in Laravel Tinker
$user = \App\Models\User::factory()->create();
$token = $user->createToken('TestToken')->plainTextToken;
echo $token;
```

Use the token in your API requests:
```http
Authorization: Bearer {your_token}
Accept: application/json
```

---

## REST API Endpoints

| Method | Endpoint               | Description         |
|--------|------------------------|---------------------|
| GET    | `/api/v1/pages`        | Get all pages       |
| GET    | `/api/v1/pages/{id}`   | Get a single page   |
| POST   | `/api/v1/pages`        | Create new page     |
| PUT    | `/api/v1/pages/{id}`   | Update a page       |
| DELETE | `/api/v1/pages/{id}`   | Delete a page       |

### Example JSON Body for Creating/Updating a Page:
```json
{
  "title": "About Us",
  "body": [
     {
        "type": "heading",
        "data": { "text": "About Us", "level": 2 }
     },
     {
        "type": "paragraph",
        "data": { "text": "Welcome to our company." }
     },
     {
        "type": "image",
        "data": { "url": "https://via.placeholder.com/800x400", "alt": "Placeholder image" }
     }
  ],
  "status": "published"
}
```

---

## Running Tests

Run all tests:
```bash
php artisan test
```

API feature tests are located under:
`tests/Feature/PageApiTest.php`

---

## Page Body Format

Pages use a **block-based structure** stored as a JSON array in the `body` column. Each block must have a `type` and a `data` object.

### Supported Blocks:
- **Heading**: Contains `text` and `level`.
- **Paragraph**: Contains `text`.
- **Image**: Contains `url` and `alt`.

---