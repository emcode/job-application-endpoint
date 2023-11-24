# job-application-endpoint
REST endpoint example based on Symfony

## how to run when in development

Run db in docker compose. Then run PHP dev server using symfony CLI
directly in your OS. See `.justfile` for examples. Optionally use
[just](https://github.com/casey/just) to run commands defined there.

## how to run tests

```bash 
# run the containers
docker compose up
# read .env.test
# (optionally customize values by creating .env.test.local)

# generate access token signing key for test environment
 ./bin/console app:setup:generate-signing-key
 # paste the token to the .env.test.local file:
 ACCESS_TOKEN_SIGNING_KEY=<value-here>
 

# setup test database
./bin/console --env=test doctrine:database:create
./bin/console --env=test doctrine:migrations:migrate --no-interaction
# run tests
./bin/phpunit
```

## about ./http directory

It contains request examples / configurations for development using
HTTP client tool existing in the Intellij IDEs.

Override dynamic parameters by creating `./http/http-client.private.env.json`
file and defining same keys as they exist in the `./http/http-client.env.json`
