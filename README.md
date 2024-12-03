Still building the workspace index, response may be less accurate.

# News Aggregator PoC

This project is a Proof of Concept (PoC) for a news aggregator application. It demonstrates how to pull news from various sources, categorize them, and store them in a database. The project uses the NewsApi as an example source for fetching news articles.
This App is part of a test case for a job application.

## Features

- Pull news from multiple sources
- Categorize news articles
- Store news articles in a database
- Schedule daily news pulling at 00:00
- Command-line interface for pulling news on demand

## Daily Schedule

The application is configured to run a daily schedule that pulls news from all configured sources at 00:00. This is achieved using Laravel's scheduling feature. The schedule is defined in the 

AppServiceProvider

 class:

```php
use Illuminate\Support\Facades\Schedule;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Schedule::command(PullNews::class)->dailyAt('00:00');
    }
}
```

## PullNews Command

The `PullNews` command allows you to pull news from specified sources, categories, and authors within a given date range. It supports pagination and allows limiting the number of news items pulled. The command can be executed from the command line using the following syntax:

```sh
php artisan app:pull-news --sources=source1,source2 --categories=category1,category2 --authors=author1,author2 --from=2023-01-01 --to=2023-12-31 --query=example --page=1 --per-page=100 --max-count=500
```

### Command Options

- `--sources`: The sources to pull the news from (comma-separated).
- `--categories`: The categories to pull the news from (comma-separated).
- `--authors`: The authors to pull the news from (comma-separated).
- `--from`: The date to pull the news from (YYYY-MM-DD format).
- `--to`: The date to pull the news to (YYYY-MM-DD format).
- `--query`: The query to search for.
- `--page`: The page number (default: 1).
- `--per-page`: The number of news per page (default: 100).
- `--max-count`: The maximum number of news to pull.

### Example Usage

```sh
php artisan app:pull-news --sources=news_api --categories=technology,science --authors=john_doe --from=2023-01-01 --to=2023-12-31 --query=latest --page=1 --per-page=50 --max-count=200
```

## Getting Started

To get started with the project, follow these steps:

1. Clone the repository:
    ```sh
    git clone https://github.com/mexk1/news-aggregator.git
    ```

2. Install dependencies:
    ```sh
    composer install
    npm install
    ```

3. Set up the environment variables:
    ```sh
    cp .env.example .env
    ```

4. Run the migrations:
    ```sh
    php artisan migrate
    ```

5. Run the application:
    ```sh
    php artisan serve
    ```

## Getting Started with Docker

1. Build and start the containers:
    ```sh
    docker-compose up --build -d
    ```

2. Access the application:
    Open your browser and navigate to `http://localhost:8000`.

3. Run migrations inside the Docker container:
    ```sh
    docker-compose exec app php artisan migrate
    ```

4. (Optional) To run the `PullNews` command inside the Docker container:
    ```sh
    docker-compose exec app php artisan app:pull-news --sources=source1,source2 --categories=category1,category2 --authors=author1,author2 --from=2023-01-01 --to=2023-12-31 --query=example --page=1 --per-page=100 --max-count=500
    ```

## Running Tests

To run the tests, use the following command:

```sh
composer test
```

or 
    
```sh
docker-compose exec app "composer" "test"
```

## License

This project is licensed under the MIT License. See the LICENSE file for details.
