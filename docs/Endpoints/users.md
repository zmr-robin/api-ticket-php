# Users Endpoint

## Functions

### GET

| Function      | Description |
|---------------|-------------|
| [/users/]()               | Get all users          
| [/users/{id}/]()          | Get data of user {id}
| [/users/{id}/role]()      | Get role of user {id}
| [/users/{id}/email]()     | Get email of user {id}
| [/users/{id}/auth]()      | Get auth key of user (sha256)
| [/users/{id}/level]()     | Get trust level of user 
| [/users/{id}/archive]()   | Get tickets user archived 

### POST

| Function      | Description |
|---------------|-------------|
| [/users/create]()    | Create a new supporter account         
| [/users/invite]()    | Invite a new supporter

### DELETE

| Function      | Description |
|---------------|-------------|
| [/users/{id}]()           | Delete user {id}      


### UPDATE

| Function      | Description |
|---------------|-------------|
| [/users/{id}/role]()    | Update role         
