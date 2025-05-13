# NYT Best Sellers JSON API Wrapper

A Laravel 12 application that provides a clean, versioned, cache-aware, and testable JSON API wrapper around the New York Times Best Sellers History API.

---

## Features
- Versioned API endpoint: `/api/v1/best-sellers`
- Query parameters: `author`, `title`, `isbn`, `offset`
- Caches responses for 1 hour (supports Redis if `CACHE_STORE=redis`)
- Graceful error handling
- Fully tested (unit and feature)
- **Swagger/OpenAPI documentation available**

---

## Setup Instructions

1. **Clone the repository:**
   ```bash
   git clone <your-repo-url>
   cd nyt-api-wrapper
   ```
2. **Install dependencies:**
   ```bash
   composer install
   ```
3. **Copy and configure environment:**
   ```bash
   cp .env.example .env
   # Add your NYT API key to .env
   NYT_API_KEY=your_key_here
   # To use Redis for caching, set:
   CACHE_STORE=redis
   # And ensure Redis is running (see below)
   ```
4. **Generate app key:**
   ```bash
   php artisan key:generate
   ```
5. **Run migrations (if needed):**
   ```bash
   php artisan migrate
   ```
6. **Start the server:**
   ```bash
   php artisan serve
   ```
7. **(Optional) Generate Swagger/OpenAPI docs:**
   ```bash
   php artisan l5-swagger:generate
   ```
   - View the Swagger UI at: [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)

**Redis Note:**
- If you set `CACHE_STORE=redis`, make sure you have Redis installed and running locally.
- Default Redis settings are used (`127.0.0.1:6379`).
- You can test Redis caching in Laravel with:
  ```bash
  php artisan tinker
  >>> Cache::put('foo', 'bar', 600);
  >>> Cache::get('foo');
  // Should return 'bar'
  ```

---

## Example Requests

**GET /api/v1/best-sellers**

Query by author:
```
GET /api/v1/best-sellers?author=Diana%20Gabaldon
```
Query by title:
```
GET /api/v1/best-sellers?title=I%20Give%20You%20My%20Body
```
Query by ISBN:
```
GET /api/v1/best-sellers?isbn=9780399178573
```
Query with offset:
```
GET /api/v1/best-sellers?author=Diana%20Gabaldon&offset=20
```

**Response:**
```json
{
  "results": [
    {
      "title": "\"I GIVE YOU MY BODY ...\"",
      "description": "The author of the Outlander novels gives tips on writing sex scenes, drawing on examples from the books.",
      "contributor": "by Diana Gabaldon",
      "author": "Diana Gabaldon",
      "publisher": "Dell",
      "isbns": [
        {
          "isbn10": "0399178570",
          "isbn13": "9780399178573"
        }
      ],
      "ranks_history": [
        {
          "primary_isbn10": "0399178570",
          "primary_isbn13": "9780399178573",
          "rank": 8,
          "list_name": "Advice How-To and Miscellaneous",
          "display_name": "Advice, How-To & Miscellaneous",
          "published_date": "2016-09-04",
          "bestsellers_date": "2016-08-20",
          "weeks_on_list": 1,
          "rank_last_week": 0
        }
      ]
    }
  ]
}
```

---

## Running Tests

```bash
php artisan test
```

- Unit tests cover the NYT service (request, transformation, error handling)
- Feature tests cover the API endpoint, validation, and error scenarios

---

## Decisions & Notes
- API versioning is implemented via route prefix (`/api/v1`)
- Service layer (`NYTService`) is separated for easy extension (future `/api/v2`)
- Caching uses the full query string hash for cache keys
- Error responses are structured and logged
- All external HTTP calls are faked in tests

---

## License

MIT
