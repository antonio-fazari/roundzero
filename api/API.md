* [/v1/authorize](#v1authorize)
* [/v1/unauthorize](#v1unauthorize)
* [/v1/users](#v1users)
* [/v1/users/:id](#v1usersid)
* [/v1/users/:id/memberships](#v1usersidmemberships)
* [/v1/users/:id/rounds](#v1usersidrounds)
* [/v1/groups](#v1groups)
* [/v1/groups/:id](#v1groupsid)
* [/v1/groups/:id/memberships](#v1groupsidmemberships)
* [/v1/groups/:id/memberships/:userId](#v1groupsidmembershipsuserId)
* [/v1/groups/:id/rounds](#v1groupsidrounds)
* [/v1/rounds/:id](#v1roundsid)
* [/v1/rounds/:id/recipients](#v1roundsidrecipients)
* [/v1/rounds/:id/recipients/:userId](#v1roundsidrecipientsuserId)

/v1/authorize
=============

## POST

authorize using user credentials to generate an API token.

### Parameters

Name       | Value      | Description          | Required
---------- | ---------- | -------------------- | -------- 
email      | string     | User's email address | Yes
password   | string     | Plain text password  | Yes

### Response

API token and authorised account details.

```json
{
    "tokenId": "52035133d6745e42fe45d32094580554",
    "user": {
        "id": 3,
        "created": "2014-03-20T18:12:01+0000",
        "changed": "-0001-11-30T00:00:00+0000",
        "name": "Leon",
        "email": "l.kessler@catchdigital.com"
    }
}
```

/v1/unauthorize
===============

## POST

Unauthorise an existing API token.

### Parameters

Name       | Value      | Description          | Required
---------- | ---------- | -------------------- | -------- 
token      | string     | API token            | Yes

### Response

Empty


# /v1/users

## GET

List all users.

### Parameters

Name       | Value      | Description          | Required
---------- | ---------- | -------------------- | -------- 
token      | string     | API token            | Yes

### Response

Array of user objects.

## POST

Create new user.

### Parameters

Name       | Value      | Description          | Required
---------- | ---------- | -------------------- | -------- 
token      | string     | API token            | Yes
name       | string     | User's name          | Yes
email      | string     | User's email address | Yes
password   | string     | New password in plain text | Yes

### Response

Newly created user object.

/v1/users/:id
=============

## GET

View a single user account by ID.

### Parameters

Name       | Value      | Description          | Required
---------- | ---------- | -------------------- | --------
token      | string     | API token            | Yes

### Response

User object

## PUT

Update existing user account.

### Parameters

Name       | Value      | Description          | Required
---------- | ---------- | -------------------- | -------- 
token      | string     | API token            | Yes
name       | string     | User's name          | No
email      | string     | User's email address | No
password   | string     | New password in plain text | No

### Response

Updated user object.

## DELETE

Delete a user account.

### Parameters

Name       | Value      | Description          | Required
---------- | ---------- | -------------------- | -------- 
token      | string     | API token            | Yes

### Response

Empty


# /v1/users/:id/memberships

## GET

List all groups that a user belongs to.

### Parameters

Name       | Value      | Description          | Required
---------- | ---------- | -------------------- | -------- 
token      | string     | API token            | Yes

### Response

Array of group objects and membership statistics

```json
[
    {
        "group": {
            "id": 1,
            "created": "-0001-11-30T00:00:00+0000",
            "changed": "2014-03-21T19:27:47+0000",
            "name": "My Group"
        },
        "stats": {
            "made": 0,
            "received": 1,
            "balance": -1
        }
    },
    {
        "group": {
            "id": 2,
            "created": "2014-03-21T17:34:16+0000",
            "changed": "-0001-11-30T00:00:00+0000",
            "name": "Another Group"
        },
        "stats": {
            "made": 3,
            "received": 2,
            "balance": 1
        }
    }
]
```

# /v1/users/:id/rounds

## GET

List all rounds that a user has made.

### Parameters

Name       | Value      | Description          | Required
---------- | ---------- | -------------------- | -------- 
token      | string     | API token            | Yes

### Response

Array of round objects

# /v1/groups

## GET

List all groups

### Parameters

Name       | Value      | Description          | Required
---------- | ---------- | -------------------- | -------- 
token      | string     | API token            | Yes

### Response

Array of group objects

## POST

Create a new group.

### Parameters

Name       | Value      | Description          | Required
---------- | ---------- | -------------------- | -------- 
token      | string     | API token            | Yes
name       | string     | Name of group        | Yes

### Response

Newly created group object


# /v1/groups/:id

## GET

Show an individual group by ID.

### Parameters

Name       | Value      | Description          | Required
---------- | ---------- | -------------------- | -------- 
token      | string     | API token            | Yes

### Response

Group object

## PUT

Update (rename) a group.

### Parameters

Name       | Value      | Description          | Required
---------- | ---------- | -------------------- | -------- 
token      | string     | API token            | Yes
name       | string     | Name of group        | Yes

### Response

Updated group object

## DELETE

Delete a group.

### Parameters

Name       | Value      | Description          | Required
---------- | ---------- | -------------------- | -------- 
token      | string     | API token            | Yes

### Response

Empty

# /v1/groups/:id/memberships

## GET

List all users that belong to a group.

### Parameters

Name       | Value      | Description          | Required
---------- | ---------- | -------------------- | -------- 
token      | string     | API token            | Yes

### Response

Array of user objects and their membership stats.

```json
[
    {
        "user": {
            "id": 3,
            "created": "2014-03-20T18:12:01+0000",
            "changed": "2014-03-24T10:21:29+0000",
            "name": "Leon",
            "email": "l.kessler@catchdigital.com"
        },
        "stats": {
            "made": "0",
            "received": "1",
            "balance": -1
        }
    },
    {
        "user": {
            "id": 4,
            "created": "2014-03-20T18:12:10+0000",
            "changed": "-0001-11-30T00:00:00+0000",
            "name": "Fred",
            "email": "f.parke@catchdigital.com"
        },
        "stats": {
            "made": "4",
            "received": "1",
            "balance": 3
        }
    }
]
```


# /v1/groups/:id/memberships/:userId

## PUT

Add a user to  a group

### Parameters

Name       | Value      | Description          | Required
---------- | ---------- | -------------------- | -------- 
token      | string     | API token            | Yes

### Response

Empty

## DELETE

Remvoe a user from a group

### Parameters

Name       | Value      | Description          | Required
---------- | ---------- | -------------------- | -------- 
token      | string     | API token            | Yes

### Response

Empty



### Response

Newly created group object


# /v1/rounds/:id

## GET

Show an individual round by ID.

### Parameters

Name       | Value      | Description          | Required
---------- | ---------- | -------------------- | -------- 
token      | string     | API token            | Yes

### Response

Round object


## DELETE

Delete a round.

### Parameters

Name       | Value      | Description          | Required
---------- | ---------- | -------------------- | -------- 
token      | string     | API token            | Yes

### Response

Empty


# /v1/rounds/:id/recipients

## GET

List all users who received a drink in a given round.

### Parameters

Name       | Value      | Description          | Required
---------- | ---------- | -------------------- | -------- 
token      | string     | API token            | Yes

### Response

Array of user objects


# /v1/rounds/:id/recipients/:userId

## PUT

Add a user as a recipient of a round

### Parameters

Name       | Value      | Description          | Required
---------- | ---------- | -------------------- | -------- 
token      | string     | API token            | Yes

### Response

Empty

## DELETE

Remove a user as a recipient of a round

### Parameters

Name       | Value      | Description          | Required
---------- | ---------- | -------------------- | -------- 
token      | string     | API token            | Yes

### Response

Empty
