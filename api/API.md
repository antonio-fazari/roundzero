# Resources

* [Tokens](#tokens)
  * [/v1/tokens/authenticate](#v1authenticate)
  * [/v1/tokens/:id](#v1tokensid)
* [Users](#users)
  * [/v1/users](#v1users)
  * [/v1/users/:id](#v1usersid)
* [Groups](#groups)
  * [/v1/groups](#v1groups)
  * [/v1/groups/:id](#v1groupsid)
* [Memberships](#memberships)
  * [/v1/memberships](#v1memberships)
  * [/v1/memberships/:id](#v1membershipsid)
* [Rounds](#rounds)
  * [/v1/rounds](#v1rounds)
  * [/v1/rounds/:id](#v1roundsid)
* [Orders](#orders)
  * [/v1/rounds/:roundId/orders](#v1roundsroundidorders)
  * [/v1/rounds/:roundId/orders/:id](#v1roundsroundidordersid)

## Tokens

### /v1/authenticate

#### POST

Create a token by authenticating with email address and password.

##### Request parameters

Name     | Description           | Required
-------- | --------------------- | --------
email    | Account email address | yes
password | Account password      | yes

##### Response

Token object.

### /v1/tokens/:id

#### GET

View token.

##### Response

Token object.

#### DELETE

Delete token.


## Users

### /v1/users

#### GET

List all users.

#### POST

Create new user.


### /v1/users/:id

#### GET

View user.

#### PUT

Update user.

#### DELETE

Delete user.


## Groups

### /v1/groups

#### GET

List all groups.

#### POST

Create new group.


### /v1/groups/:id

#### GET

View group.

#### PUT

Update group.

#### DELETE

Delete group.


## Memberships

### /v1/memberships

#### POST

Create new membership.

### /v1/memberships/:id

#### GET

View membership.

#### DELETE

Delete membership.


## Rounds

### /v1/rounds

#### POST

Create a new round.


### /v1/rounds/:id

#### GET

View round.

#### GET

Delete round.


## Orders

### /v1/rounds/:roundId/orders

#### GET

List all orders for round.

#### POST

Create new order for group.


### /v1/rounds/:roundId/orders/:id

#### GET

View order.

#### PUT

Update order.

#### DELETE

Delete order.


# Representations

## Token

```json
{
    "id": "fe08dbc787228acdd7541ba9f28f4078",
    "userId": "9",
    "created": "2014-03-27 16:12:09",
    "user": {
        "id": "9",
        "created": "2014-03-27 10:29:41",
        "changed": "2014-03-27 10:29:41",
        "name": "Graham",
        "email": "info@grahambates.com"
    }
}
```

## User

### Simple

```json
{
    "id": "2",
    "created": "2014-03-25 21:17:55",
    "changed": "2014-03-25 21:17:55",
    "name": "foo",
    "email": "foo@test.com"
}
```

### Detailed

```json
{
    "id": "2",
    "created": "2014-03-25 21:17:55",
    "changed": "2014-03-25 21:17:55",
    "name": "foo",
    "email": "foo@test.com",
    "memberships": [
        {
            "id": "4",
            "groupId": "2",
            "userId": "2",
            "joined": "2014-03-25 21:33:06",
            "group": {
                "id": "2",
                "created": "2014-03-25 21:17:55",
                "changed": "2014-03-25 21:17:55",
                "name": "foo",
                "email": "foo@test.com"
            },
            "made": 0,
            "received": 0,
            "balance": 0
        }
    ]
}
```

## Group

### Simple

```json
{
    "id": "2",
    "name": "foobarbaz",
    "created": "2014-03-27 02:49:24",
    "changed": "2014-03-27 15:14:55"
}
```

### Detailed

```json
{
    "id": "2",
    "name": "foobarbaz",
    "created": "2014-03-27 02:49:24",
    "changed": "2014-03-27 15:14:55",
    "memberships": [
        {
            "id": "4",
            "groupId": "2",
            "userId": "2",
            "joined": "0000-00-00 00:00:00",
            "user": {
                "id": "2",
                "created": "2014-03-25 21:17:55",
                "changed": "2014-03-25 21:17:55",
                "name": "foo",
                "email": "foo@test.com"
            },
            "made": 0,
            "received": 0,
            "balance": 0
        }
    ]
}
```

## Membership

```json
{
    "id": "4",
    "groupId": "2",
    "userId": "2",
    "joined": "0000-00-00 00:00:00"
}
```

## Round

```json
{
    "id": "4",
    "groupId": "2",
    "userId": "3",
    "created": "2014-03-27 22:31:09",
    "changed": "2014-03-27 22:31:09",
    "user": {
        "id": "3",
        "created": "2014-03-25 21:23:11",
        "changed": "2014-03-25 21:23:11",
        "name": "foobar",
        "email": "foo@example.com"
    },
    "group": {
        "id": "2",
        "name": "foobarbaz",
        "created": "2014-03-27 02:49:24",
        "changed": "2014-03-27 21:14:55"
    },
    "orders": [
        {
            "id": "1",
            "roundId": "3",
            "userId": "3",
            "type": "Coffee",
            "sugars": "1",
            "milk": "1",
            "notes": "",
            "created": "2014-03-27 21:38:08",
            "changed": "2014-03-27 21:38:08",
            "user": {
                "id": "3",
                "created": "2014-03-25 21:23:11",
                "changed": "2014-03-25 21:23:11",
                "name": "foobar",
                "email": "foo@example.com"
            }
        }
    ]
}
```

## Order

```json
{
    "id": "1",
    "roundId": "3",
    "userId": "3",
    "type": "Coffee",
    "sugars": "1",
    "milk": "1",
    "notes": "",
    "created": "2014-03-27 21:38:08",
    "changed": "2014-03-27 21:38:08",
    "user": {
        "id": "3",
        "created": "2014-03-25 21:23:11",
        "changed": "2014-03-25 21:23:11",
        "name": "foobar",
        "email": "foo@example.com"
    }
}
```
