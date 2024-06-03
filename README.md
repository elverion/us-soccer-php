# us-soccer-test

## Tools and considerations

- Laravel - Most popular framework, production-ready, modern, and flexible.
- SQLite - lightest, easiest-to-use solution for the purposes of a code test, though obviously not ideal for a production system
  - Auto-increment IDs were used. This is a point of contention for many, but... this is a simple code test.
  - Location could be separated out into a separate DB table. Again, given that this is only a code test,
  and how closely the stadium's location is tied to the stadium itself for this usecase, I'm opting to
  just store it in a single table for now, although we will expose it as if it were a nested object in the REST API.

## Project layout - where to find what you're looking for

Given the requirements (DDD, functional programming, etc.), this project's structure may be quite different from what you are
used to seeing with Laravel. The core Laravel concerns have been moved from `app/` to `app/System`. Project domains, such
as `Stadium`, can be directly located under `app/` (ex: `app/Stadium`). Each domain will contain all business logic for
that domain, plus tests.

Under the `database` directory, you may find `database.sqlite` (if the SQLite database has been created -- automatic at startup),
as well as `migrations`. These database migration files contain imperitive instructions for creating the various database tables used.

The `.docker` directory contains files needed for building or configuring Docker layers and images, or to volume-mount as configurations
at runtime. Take, for example, nginx's `default.conf` which is volume-mounted at runtime: this is fine for development, but
probably should be baked-in to the image for a production environment; beyond the scope of this code test. The `entrypoint.sh`
is bound to root (`/`), and is used in place of hard-coding the container's command (`CMD`); a common pattern to ensure flexibility
for the future.

For API requests, all validation is done through each endpoints' matching Request. This ensures that all validation has completed
prior to handing off to the business side of things (the actual Controller). The Request may make use of custom validation Rules
to make the logic more reusable.
See `app/Stadium/Http/Requests/StoreStadiumRequest.php` and `app/Stadium/Validation/StadiumCsvRule.php` for example.

## Style guidelines

To conform closely to recommended PHP style guidelines, and that of the framework (Laravel) used, camel-casing is used largely throughout
the project. However, code dealing with REST API or database specifics, snake casing may be used to better align with those
external expectations.

Additionally, to prevent confusion, the naming of certain DDD concepts follow the Laravel naming convention rather than the
more general, widely-accepted DDD names. For example: Entities = Models, ValueObject = *Data, Interface = Contract, etc.

Finally, in regards to DDD, the _original_ concept is followed here in order to group logical pieces of an entity.

## Set up

For your convenience, this project was wrapped in a Docker container. We will assume you've already got Docker (or similar container runtime) installed.

If you've also got `docker-compose` installed (recommended):

```sh
docker-compose up
```

Alternatively, using raw Docker:

```sh
docker network create backend

# Build and run the app, attach to the network so it is reachable by nginx
docker build -t app_image -f .docker/php/Dockerfile .

docker run --rm -d \
  --name app \
  --network backend \
  -w /var/www \
  -e DB_CONNECTION=${DB_CONNECTION:-sqlite} \
  -v $(pwd):/var/www \
  app_image

# Start nginx container listening on port 8080
docker run -d \
  --name nginx \
  --network backend \
  -w /var/www \
  -v $(pwd):/var/www \
  -v $(pwd)/.docker/nginx/conf.d:/etc/nginx/conf.d/ \
  -p 8080:80 \
  nginx:alpine
```

## Ingesting CSV data

You have two options for ingesting a Stadiums CSV file into the system:

1. Upload file via API
2. Read from CLI

Either option will, essentially, result in the same behavior. The contents of the file will be validated,
then ingested into the database. Once ingested into the system, the stadiums may be accessed via the
REST API.

Download a file for testing file:

```sh
curl --remote-name https://raw.githubusercontent.com/jokecamp/FootballData/master/other/stadiums-with-GPS-coordinates.csv
```

### Upload file via API

Next, you need simply to POST the file to the `api/v1/stadiums` endpoint, key the file by `stadiums`. Example:

```sh
curl --location 'http://127.0.0.1:8080/api/v1/stadiums' \
--header 'Accept: application/json' \
--form 'stadiums=@"stadiums-with-GPS-coordinates.csv"'
```

### Read from CLI

#### If running from the host machine (assumes PHP 8.2 installed, ran `composer install`, etc.)

Assuming your CSV file is named `stadiums-with-GPS-coordinates.csv`:

```sh
php artisan stadium:ingest stadiums-with-GPS-coordinates.csv
```

#### Run on container (no dependencies needed on host)

```sh
# Download the file into the container
docker exec -it app curl --remote-name https://raw.githubusercontent.com/jokecamp/FootballData/master/other/stadiums-with-GPS-coordinates.csv

# Run the Artisan command to being processing it
docker exec -it app curl php artisan stadium:ingest stadiums-with-GPS-coordinates.csv
```

## Hitting the API

todo

## Running tests

On the host machine (assumes you've got PHP 8.2 installed, ran `composer install`, etc.):

```sh
php artisan test
```

Or, you can run tests within the container (don't need dependencies on host):

```sh
docker exec -it app php artisan test
```

## Limitations

- No caching was used. This would not be hard to layer over top of the existing code, but is beyond the needs for this code test.
