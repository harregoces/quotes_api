## Quote management API

This is an API for managing quotes. It is built using Laravel 10.10 and PHP 8.1. It uses a Sqlite database for storage.

### Installation

1. Clone the repository
2. Run `composer install`
3. Run `php artisan migrate`
4. Run `php artisan serve`
5. The API is now available at http://localhost:8000


### Usage

The API has the following endpoints:

#### - GET `/api/today`
Returns a quote for non-authenticated user

#### - GET `/api/today/new`
clear cache and returns a quote for non-authenticated user

#### - GET `/api/secure-quotes`
Returns quotes for authenticated user

#### - GET `/api/secure-quotes`
Returns quotes for authenticated user

#### - GET `/api/secure-quotes/new`
clear cache and returns quotes for authenticated user

#### - GET `/api/quotes`
Returns five random quotes for non-authenticated user and authenticated user

#### - GET `/api/quotes/new`
clear cache and returns five random quotes for non-authenticated user and authenticated user

#### - GET `/api/favorite-quotes`
Returns favorite quotes for authenticated user

#### - POST `/api/favorite-quotes`
Save a quote as favorite for authenticated user

#### - GET `/api/report-favorite-quotes`
Returns a report of the all user and the favorite quotes

#### - GET `/api/favorite-quotes/{id}`
Get specific favorite quote for authenticated user

#### - DELETE `/api/favorite-quotes/{id}`
Delete specific favorite quote for authenticated user


### Testing
Run `php artisan test` to run the tests

### Caching
The API uses caching to improve performance. It can also be cleared manually by calling the `/api/today/new` endpoint for non-authenticated user and `/api/secure-quotes/new` for authenticated user.

### Authentication
The API uses Laravel Sanctum for authentication. To authenticate, send a POST request to `/api/login` with the following body:
```
{
    "email": "test@test.com",
    "password": "password"
}
```
The API will return a token that can be used to authenticate future requests. To authenticate future requests, add the following header to the request:
```
{
    "user": {
        "id": 1,
        "name": "Test User",
        "email": "test@test.com",
        "created_at": "2021-10-31T15:00:00.000000Z",
        "updated_at": "2021-10-31T15:00:00.000000Z"
    },
    "token": "nnu5PxuJ1xplBbdJrtNM2Q==0s5CpRd6HH0ba0Qm"
}
```
