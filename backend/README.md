# Simple Social Media Api
This is a simple project with features of login, register with laravel sanctum api
Can create profile, page, posts, follow a person, follow a page, post by person, post by page, see followers

## POST `auth/login`
route to send login request

**Request Headers**

`Accept` `application/json`

**Body** JSON

    {
      "email": "test@example.org",
      "password": "password"
    }

**Response** JSON

    {
        "success": true,
        "data": {
            "user": {
                "id": 1,
                "first_name": "Prof. Einar Bogisich IV",
                "last_name": "Dorothy McKenzie",
                "email": "tito.kling@example.com",
                "followed_persons": "[]",
                "followed_pages": "[]",
                "followers": 0,
                "email_verified_at": "2022-06-15T22:12:07.000000Z",
                "created_at": "2022-06-15T22:12:07.000000Z",
                "updated_at": "2022-06-15T22:26:43.000000Z"
            },
            "token": "3|Xd3dVTgd7BAOk5JjL76jSbrRJrg9SL5lfWDptC9p"
        },
        "message": "Login Success"
    }

## POST `profile`
The route to get the loggedIn user

**Authorization** Bearer Token

`Token` `3|Xd3dVTgd7BAOk5JjL76jSbrRJrg9SL5lfWDptC9p`

**Request Headers**

`Accept` `application/json`

**Response** JSON

    {
        "id": 1,
        "first_name": "Prof. Einar Bogisich IV",
        "last_name": "Dorothy McKenzie",
        "email": "tito.kling@example.com",
        "followed_persons": "[]",
        "followed_pages": "[]",
        "followers": 0,
        "email_verified_at": "2022-06-15T22:12:07.000000Z",
        "created_at": "2022-06-15T22:12:07.000000Z",
        "updated_at": "2022-06-15T22:26:43.000000Z"
    }

## POST `auth/register`
Register new user

**Request Headers**

`Accept` `application/json`

**Body** JSON

    {
        "first_name" : "Test",
        "last_name" : "Name",
        "email" : "test@example.io",
        "password" : "12345678"
    }

**Response** JSON

    {
        "success": true,
        "data": {
            "user": {
                "first_name": "Test",
                "last_name": "Name",
                "email": "test@example.io",
                "followed_persons": "[]",
                "followed_pages": "[]",
                "updated_at": "2022-06-18T04:44:13.000000Z",
                "created_at": "2022-06-18T04:44:13.000000Z",
                "id": 8
            },
            "token": "5|HePsymgdWygKsWmoVhSox5rt9Iyd1ZDT0rQgku32"
        },
        "message": "Register Success"
    }

## POST `page/create`
create new page

**Authorization** Bearer Token

`Token` `3|Xd3dVTgd7BAOk5JjL76jSbrRJrg9SL5lfWDptC9p`

**Request Headers**

`Accept` `application/json`

**Body** JSON

    {
        "page_name" : "News Today"
    }

**Response** JSON

    {
        "success": true,
        "message": "Page Created Successfully"
    }

## POST `follow/person/{personId}`
follow a person by id

**Authorization** Bearer Token

`Token` `3|Xd3dVTgd7BAOk5JjL76jSbrRJrg9SL5lfWDptC9p`

**Request Headers**

`Accept` `application/json`

**Response** JSON

    {
        "success": true,
        "message": "Followed"
    }

## POST `unfollow/person/{personId}`
unfollow a followed person by id

**Authorization** Bearer Token

`Token` `3|Xd3dVTgd7BAOk5JjL76jSbrRJrg9SL5lfWDptC9p`

**Request Headers**

`Accept` `application/json`

**Response** JSON

    {
        "success": true,
        "message": "Unfollowed"
    }

## POST `/follow/page/{pageId}`
follow a page by id

**Authorization** Bearer Token

`Token` `3|Xd3dVTgd7BAOk5JjL76jSbrRJrg9SL5lfWDptC9p`

**Request Headers**

`Accept` `application/json`

**Response** JSON

    {
        "success": true,
        "message": "Followed"
    }

## POST `unfollow/page/{pageId}`
unfollow a followed page by id

**Authorization** Bearer Token

`Token` `3|Xd3dVTgd7BAOk5JjL76jSbrRJrg9SL5lfWDptC9p`

**Request Headers**

`Accept` `application/json`

**Response** JSON

    {
        "success": true,
        "message": "Unfollowed"
    }

## POST `person/attach-post`
create a post by user

**Authorization** Bearer Token

`Token` `3|Xd3dVTgd7BAOk5JjL76jSbrRJrg9SL5lfWDptC9p`

**Request Headers**

`Accept` `application/json`

**Body** JSON

    {
        "post_content" : 
        {
            "title" : "Bangladesh is in problem",
            "body" : "There is a big problem is going to occure with Bangladeshi finance, The continous inreases in prices of daily necessaries..."
        }
    }

**Response** JSON

    {
        "success": true,
        "message": "Post Created"
    }

## POST `page/{pageId}/attach-post`
create a post by pageId

**Authorization** Bearer Token

`Token` `3|Xd3dVTgd7BAOk5JjL76jSbrRJrg9SL5lfWDptC9p`

**Request Headers**

`Accept` `application/json`

**Body** JSON

    {
        "post_content" : 
        {
            "title" : "Bangladesh is in problem",
            "body" : "There is a big problem is going to occure with Bangladeshi finance, The continous inreases in prices of daily necessaries..."
        }
    }

**Response** JSON

    {
        "success": true,
        "message": "Post Created"
    }

## POST `person/feed`
get feeds for the loggedIn user

**Authorization** Bearer Token

`Token` `3|Xd3dVTgd7BAOk5JjL76jSbrRJrg9SL5lfWDptC9p`

**Request Headers**

`Accept` `application/json`

**Response** JSON

    {
        "success": true,
        "data": [
            {
                "title": "Extreme weather",
                "body": "The lastest update from weather office confirmed that the weather condition will by extremely rainy for some days",
                "user_id": null,
                "page_id": 1,
                "created_at": "2022-06-18 04:57:17"
            },
            {
                "title": "Bangladesh is in problem",
                "body": "There is a big problem is going to occure with Bangladeshi finance, The continous inreases in prices of daily necessaries...",
                "user_id": 1,
                "page_id": null,
                "created_at": "2022-06-18 04:55:54"
            },
        ]
    }