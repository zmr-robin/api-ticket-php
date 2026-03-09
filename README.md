# API for php-ticket
> API for my php-ticket project.

The API helps to access the tickets via web-app *([php-ticket](https://github.com/zmr-robin))* and the dektop client *([php-ticket-client](https://github.com/zmr-robin))*.

## Endpoints

| Endpoint                                  | Funktion            |
|-------------------------------------------|---------------------|
| [/users/](./docs/Endpoints/users.md)      | Supporter Accounts  |
| [/emails/](./docs/Endpoints/emails.md)    | Emails              |
| [/auth/](./docs/Endpoints/auth.md)        | Authentification    |
| [/tickets/](./docs/Endpoints/tickets.md)  | Tickets             |
| [/tags/](./docs/Endpoints/tags.md)        | Tags                |
| [/roles/](./docs/Endpoints/roles.md)      | Roles               |


## Response status

| Status  | Meaning                         |
|---------|---------------------------------|
| 400     | Bad Request                     |         
| 401     | Unauthorized: API Key invalid   |
| 403     | Forbidden: Trust level to low   |
| 404     | Service not found               |
| 409     | Conflict: Data already exist    |
| 429     | To many requests                |
| 503     | Service unavailable             |