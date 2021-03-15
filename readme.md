# Awesome LampDev

# Installation

Clone the repo locally:

Install PHP dependencies:

```sh
composer install
```

Install NPM dependencies:

```sh
npm ci
```

Build assets:

```sh
npm run dev
```

Setup configuration:

```sh
cp .env.example .env
```

Generate application key:

```sh
php artisan key:generate
```

Run database migrations:

```sh
php artisan migrate
```

## Setting up Passport

1. `php artisan passport:keys`
2. `php artisan passport:client --personal`

## Setting up Scout

1. Make a new [Algolia app](https://www.algolia.com/)
2. From your Algolia app, copy the Admin API key and ID into your `.env`.
3. In your Algolia app, create a new index called "packages".
4. After seeding your database, run `php artisan scout:import "App\Package"`

## Seeding the Database

1. `php artisan db:seed`

## Setting up GitHub Authentication

1. Make a new [GitHub OAuth application](https://github.com/settings/tokens)
2. Set `https://{projectname}/login/github/callback` as the Authorized Callback URL
3. Copy the GitHub app id and secret to `GITHUB_CLIENT_ID` and `GITHUB_CLIENT_SECRET` in the `.env` file.

## Setting up the Filesystem for Screenshots

1. Run `php artisan storage:link`

## Testing

Some of the tests in this suite depend on an active internet connection!!!
For convenience, these tests have been added to the `integration` group. If you would like to exlude these tests from running, you may do so by using phpunit's `--exclude-group` option:

```
phpunit --exclude-group=integration
```
