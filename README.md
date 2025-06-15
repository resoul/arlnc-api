
``
docker-compose up --build
``

``
# POST
curl -X POST http://localhost:8080/users -H "Content-Type: application/json" -d '{"username":"alice","display_name":"Alice","avatar_url":"https://img"}'

# GET
curl http://localhost:8080/users/1

# PUT
curl -X PUT http://localhost:8080/users/1 -H "Content-Type: application/json" -d '{"username":"alice2","display_name":"Alice Updated","avatar_url":"https://img2"}'

# DELETE
curl -X DELETE http://localhost:8080/users/1

``