
# 📰 Laravel News Aggregator API

PHP Laravel application that fetches articles from multiple external sources (NewsAPI, The Guardian, New York Times) using scheduler and stores them in a local MySQL database. Retrieve the articles using REST API endpoint based on search keyword title or description and filter using article published date range, user preference using news sources and pagination.

> ✅ Follows **SOLID**, **DRY**, **KISS** principles  
> ✅ No user authentication (as per requirements)  
> ✅ REST API - JSON responses with pagination & validation

---

## ⚙️ Installation & Setup

### 1. Clone the Repository

```bash
git https://github.com/DeekshithDike/news-aggregator-api.git
cd news-aggregator
```
---

### 2. Install Dependencies

```bash
composer install
```
---

### 3. Copy `.env` File

```bash
cp .env.example .env
```
---

### 4. Generate App Key

```bash
php artisan key:generate
```
---

### .5 Database Setup

1. Configure MySQL database credentials in `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=your_user_name
DB_PASSWORD=your_password
```
2. Run the database migration to create the `articles` table:

```bash
php artisan migrate
```
---

### 6. Configure New Source (API Key & URL)

Update the `.env` file with news source api key & url for below properties:

```env
NEWS_API_KEY=your_newsapi_key_here
GUARDIAN_API_KEY=your_guardian_key_here
NYT_API_KEY=your_nyt_key_here

NEWS_API_BASE_URL=https://newsapi.org
GUARDIAN_API_BASE_URL=https://content.guardianapis.com
NYT_API_BASE_URL=https://api.nytimes.com/svc
```
---

### 7. Fetching Articles Manually (If you're not able to setup cron shown in next step using cron)

Manually run this command to fetch articles from NewsAPI, The Guardian, and NYTimes:

```bash
php artisan fetch:articles
```

This will:
- Fetch data from APIs
- Store new articles in the local DB

---

### 8. Schedule the Cron (Optional)

Then add this cron entry to your system's (Linux/Mac) crontab (e.g., using crontab -e)

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```
Refer Laravel Document: https://laravel.com/docs/11.x/homestead#configuring-cron-schedules

---

### 9. API Request Format

#### API Endpoint:
`GET /api/v1/articles`

#### Required Header:
`Accept: application/json`

#### Query Parameters:

| Parameter           | Type     | Description                                   |
|--------------------|----------|-----------------------------------------------|
| `keyword`           | string   | Search keyword (in title/description)         |
| `category` | string   | Filter by category: `Use any one category stored in Database -> articles table -> category column Or Use technology, health, business`  |
| `source` | string   | Filter by source: `Use any one news source - newsapi / guardian / nytimes`  |
| `preferred_sources` | string   | Comma-separated list: `newsapi,guardian,nytimes`  |
| `preferred_categories` | string   | Comma-separated list: `Use categories stored in Database -> articles table -> category column`  |
| `preferred_authors` | string   | Comma-separated list: `Use authors stored in Database -> articles table -> category column`  |
| `published_from`    | date     | Filter from date (YYYY-MM-DD)                 |
| `published_to`      | date     | Filter to date (YYYY-MM-DD)                   |
| `limit`             | integer  | Items per page (default: 20)                  |
| `page`              | integer  | Page number                                   |

---
