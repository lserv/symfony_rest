Symfony RESTful API
============

### The following bundles are used :
- https://github.com/FriendsOfSymfony/FOSRestBundle
- https://github.com/FriendsOfSymfony/FOSUserBundle
- https://github.com/FriendsOfSymfony/FOSOAuthServerBundle
- https://github.com/nelmio/alice

### Load fixtures:
- php app/console doctrine:schema:update --force
- php app/console doctrine:fixtures:load

http util see [HTTPie library](https://github.com/jakubroztocil/httpie)

#### PUBLIC:
- http GET http://127.0.0.1:8000
- http GET http://127.0.0.1:8000/users
- http GET http://127.0.0.1:8000/users/show/2

#### Get access token:
http POST http://127.0.0.1:8000/oauth/v2/token grant_type=password client_id=1_3va1d8h5i2gw0csw0wgkscsswsgw08wkck04ko0g80kkgs8gss client_secret=5cgmv2jtyxog0sogwoss4o44s0wss8skkwksg8kcw044wggwkw username=admin password=admin

where client_id and client_secret see in oauth2_clients table

#### Users(private only admin)
- http POST http://127.0.0.1:8000/users/create username="Test User1" email=test1@mail.ru password=qwe "Authorization:Bearer MzNiOTgxNjQ4YTIzMDczMjZmYjUzNWU5NWIzNTk4N2I4ODIxYzFiZTY1Njg5ZTFhMWQyMTkxNDM4ZTc1MmQ5Zg"
- http PUT http://127.0.0.1:8000/users/update/12 username="Test User2" "Authorization:Bearer MzNiOTgxNjQ4YTIzMDczMjZmYjUzNWU5NWIzNTk4N2I4ODIxYzFiZTY1Njg5ZTFhMWQyMTkxNDM4ZTc1MmQ5Zg"
- http DELETE http://127.0.0.1:8000/users/delete/12 "Authorization:Bearer MzNiOTgxNjQ4YTIzMDczMjZmYjUzNWU5NWIzNTk4N2I4ODIxYzFiZTY1Njg5ZTFhMWQyMTkxNDM4ZTc1MmQ5Zg"

#### Tag
- http POST http://127.0.0.1:8000/tags/create title="Test title" "Authorization:Bearer MzNiOTgxNjQ4YTIzMDczMjZmYjUzNWU5NWIzNTk4N2I4ODIxYzFiZTY1Njg5ZTFhMWQyMTkxNDM4ZTc1MmQ5Zg"
- http PUT http://127.0.0.1:8000/tags/update/11 title="Test title 11" "Authorization:Bearer MzNiOTgxNjQ4YTIzMDczMjZmYjUzNWU5NWIzNTk4N2I4ODIxYzFiZTY1Njg5ZTFhMWQyMTkxNDM4ZTc1MmQ5Zg"
- http DELETE http://127.0.0.1:8000/tags/delete/11 "Authorization:Bearer MzNiOTgxNjQ4YTIzMDczMjZmYjUzNWU5NWIzNTk4N2I4ODIxYzFiZTY1Njg5ZTFhMWQyMTkxNDM4ZTc1MmQ5Zg"

#### Category
- http POST http://127.0.0.1:8000/categories/create title="Test title" "Authorization:Bearer MzNiOTgxNjQ4YTIzMDczMjZmYjUzNWU5NWIzNTk4N2I4ODIxYzFiZTY1Njg5ZTFhMWQyMTkxNDM4ZTc1MmQ5Zg"
- http PUT http://127.0.0.1:8000/categories/update/11 title="Test title 11" "Authorization:Bearer MzNiOTgxNjQ4YTIzMDczMjZmYjUzNWU5NWIzNTk4N2I4ODIxYzFiZTY1Njg5ZTFhMWQyMTkxNDM4ZTc1MmQ5Zg"
- http DELETE http://127.0.0.1:8000/categories/delete/11 "Authorization:Bearer MzNiOTgxNjQ4YTIzMDczMjZmYjUzNWU5NWIzNTk4N2I4ODIxYzFiZTY1Njg5ZTFhMWQyMTkxNDM4ZTc1MmQ5Zg"

#### Products
- http POST http://127.0.0.1:8000/products/create title="Test title" category=1 "Authorization:Bearer MzNiOTgxNjQ4YTIzMDczMjZmYjUzNWU5NWIzNTk4N2I4ODIxYzFiZTY1Njg5ZTFhMWQyMTkxNDM4ZTc1MmQ5Zg"
- http PUT http://127.0.0.1:8000/products/update/11 title="Test title 11" "Authorization:Bearer MzNiOTgxNjQ4YTIzMDczMjZmYjUzNWU5NWIzNTk4N2I4ODIxYzFiZTY1Njg5ZTFhMWQyMTkxNDM4ZTc1MmQ5Zg"
- http DELETE http://127.0.0.1:8000/products/delete/11 "Authorization:Bearer MzNiOTgxNjQ4YTIzMDczMjZmYjUzNWU5NWIzNTk4N2I4ODIxYzFiZTY1Njg5ZTFhMWQyMTkxNDM4ZTc1MmQ5Zg"
