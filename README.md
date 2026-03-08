# API for php-ticket
> API for my php-ticket project.

The API helps to access the tickets via web-app *([php-ticket](https://github.com/zmr-robin))* and the dektop client *([php-ticket-client](https://github.com/zmr-robin))*.

## Doc

### Users

#### GET

> List **all** users 
```bash
curl  -X GET \
  'http://localhost/api-ticket-php/public/users' \
  --header 'Accept: */*' \
  --header 'User-Agent: Thunder Client (https://www.thunderclient.com)'
```

> List data from one user by id

```bash
curl  -X GET \
  'http://localhost/api-ticket-php/public/users/data/1' \
  --header 'Accept: */*' \
  --header 'User-Agent: Thunder Client (https://www.thunderclient.com)'
```

#### POST

> Create a user
```

```