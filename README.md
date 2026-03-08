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
  --header 'User-Agent: Thunder Client (https://www.thunderclient.com)' \
  --header 'Authorization: Bearer {api_key}'
```

> List data from one user by id

```bash
curl  -X GET \
  'http://localhost/api-ticket-php/public/users/data/1' \
  --header 'Accept: */*' \
  --header 'User-Agent: Thunder Client (https://www.thunderclient.com)' \
  --header 'Authorization: Bearer {api_key}'
```

#### POST

> Create a user
``` bash
curl  -X POST \
  'http://localhost/api-ticket-php/public/users/create' \
  --header 'Accept: */*' \
  --header 'User-Agent: Thunder Client (https://www.thunderclient.com)' \
  --header 'Authorization: Bearer 2jtx1UeYhcotcW90KLic_BExp6_zodCqvchys4r8TyE' \
  --header 'Content-Type: application/json' \
  --data-raw '{
  "Email" : "email@example.com",
  "Password" : "password",
  "FirstName" : "Max",
  "LastName" : "Mustermann"
  }'
```

> Invite a user
```bash
curl  -X POST \
  'http://localhost/api-ticket-php/public/users/invite' \
  --header 'Accept: */*' \
  --header 'User-Agent: Thunder Client (https://www.thunderclient.com)' \
  --header 'Authorization: Bearer {api_key}' \
  --header 'Content-Type: application/json' \
  --data-raw '{"Email" : "email@example.com"}'
```

#### PUT

> Change user role
```bash
curl  -X PUT \
  'http://localhost/api-ticket-php/public/users/role/28' \
  --header 'Accept: */*' \
  --header 'User-Agent: Thunder Client (https://www.thunderclient.com)' \
  --header 'Authorization: Bearer {api_key}' \
  --header 'Content-Type: application/json' \
  --data-raw '{
  "Role" : "0"
} 
```

### Auth

#### Get

> Get new API Key
``` bash
curl  -X GET \
  'http://localhost/api-ticket-php/public/auth' \
  --header 'Accept: */*' \
  --header 'User-Agent: Thunder Client (https://www.thunderclient.com)' \
  --header 'Content-Type: application/json' \
  --data-raw '{
  "Email" : "email@email.com",
  "Password" : "password"
}'
```