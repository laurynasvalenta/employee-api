# Employee API Application

Welcome to the Employee API Application. It serves as a basic set of REST endpoints for managing the Employee entity. Symfony framework has been used to make the development more straight-forward.

## Setting up the Application and Running the Tests

To launch the application please run the following commands:
```
git clone https://github.com/laurynasvalenta/employee-api/
cd employee-api
docker-compose up
```

The application should now be accessible on http://localhost.

The application contains two test suites: Showcase test suite and Unit test suite. These tests can be launched by running the following command:
```
docker exec -it -u project employee-api_php 'bin/phpunit'
```

## Endpoint Summary

The application provides endpoints for standard CRUD operations to interact with the Employee entity. It is more convenient to try these endpoints after the Showcase test suite is run.

#### GET /employee
Returns a list of employees. Sample request:
```
curl -v 'http://localhost/employee?lastname=Lastname&firstname=Firstname&boss_id=2fb34876-ba97-4f06-a319-b46b32418485&birthdate_from=1994-05-1&birthdate_to=1994-05-11&role=CTO'
```

#### GET /employee/identifier
Returns a single employee. Sample request:
```
curl -v 'http://localhost/employee/12ea7db0-5937-4f08-96e3-dfce1bfd7833'
```

#### POST /employee
Creates a single employee. Sample request:
```
curl -v -X POST 'http://localhost/employee' -d '{"firstname":"Gerda","lastname":"Padberg","birthdate":"1994-05-10T00:00:00+00:00","employmentDate":"2021-02-07T00:00:00+00:00","bossId":null,"homeAddressLine1":"5939 Barton Courts","homeAddressLine2":"Apt. 441","homeAddressZip":"25846","homeAddressCity":"Port Conrad","homeAddressCountry":"SVK","roleName":"CEO"}'
```

#### PUT /employee/identifier
Updates a single employee. Sample request:
```
curl -v -X PUT 'http://localhost/employee/12ea7db0-5937-4f08-96e3-dfce1bfd7833' -d '{"firstname":"Gerda","lastname":"Padberg","birthdate":"1994-05-10T00:00:00+00:00","employmentDate":"2021-02-07T00:00:00+00:00","bossId":null,"homeAddressLine1":"5939 Barton Courts","homeAddressLine2":"Apt. 441","homeAddressZip":"25846","homeAddressCity":"Port Conrad","homeAddressCountry":"SVK","roleName":"CEO"}'
```

#### DELETE /employee/identifier
Deletes a single employee. Sample request:
```
curl -v -X DELETE 'http://localhost/employee/12ea7db0-5937-4f08-96e3-dfce1bfd7833'
```

## Points for Improval

- The code is not yet fully Unit-test-covered.
- Validation messages are to be improved.
