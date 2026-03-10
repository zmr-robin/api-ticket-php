# Emails Endpoint

## Functions

### GET

| Function      | Description |
|---------------|-------------|
| [/emails/]()                  | Get all emails
| [/emails/{id}/]               | Get email {id} data
| [/emails/{id}/whitelist]      | Check if email {id} is whitelisted
| [/emails/{id}/blacklist]      | Check if email {id} is blacklisted

### POST

| Function      | Description |
|---------------|-------------|
| [/emails/]()                  | Create new email
| [/emails/{id}/whitelist]      | Whitelist a email {id}
| [/emails/{id}/blacklist]      | Blacklist a email {id}
| [/emails/{id}/update/{TicketID}/] | Send update to email {id} of ticket {ticketid}

### DELETE

| Function      | Description |
|---------------|-------------|
| [/emails/{id}]()              | Delete email
| [/emails/{id}/whitelist]      | Remove a email {id} from whitelist 
| [/emails/{id}/blacklist]      | Remove a email {id} from blacklist

### UPDATE

| Function      | Description |
|---------------|-------------|
| [/emails/{id}/]()             | Update email-adress for {id}           
