# Laravel API Skeleton

Install via:

```
composer create-project ragboyjr/laravel-api-skeleton {name}
```

Once installed, you'll want to run the following:

```
mv .editor/project.sublime-project .editor/acme-svc.sublime-project
find . -type f -exec sed -i 's/{project}/acme-svc/g' {} \;
find . -type f -exec sed -i 's/{project_underscore}/acme_svc/g' {} \;
find . -type f -exec sed -i 's/{project_title}/Acme Svc/g' {} \;
find . -type f -exec sed -i 's/{project_doctrine_ns}/Acme/g' {} \;
```

After you get composer installed, you'll want to set the app namespace via `./artisan app:name 'Acme\Svc'`.

Then you can run `./artisan doctrine:migrations:diff` to generate the initial migration.

## Development Setup

1. copy over all the example files: `make ignored-files`
2. Start the docker containers: `docker-compose up`
3. Exec into the `{project}` container via `docker exec -it {project} bash`
4. Run `make setup` to initialize the repo. This will initialize all aspects of the repo, you can see what this script does by viewing the `Makefile`.

At the end of `make setup`, it should have generated a long access token, you'll need to copy that into the .env and set the `INTEGRATION_API_TOKEN` value.

To verify that you've been setup correctly, you need to run `make test` and then `make refresh-db test-integration`. These will run the entire test suite. If any fail, then there was an issue with setup that will need to be resolved.

## Development

This app is built with Laravel and Doctrine.

### Migrations

From inside of the `{project}` container, run `./artisan doctrine:migrations:migrate` to load the database. You'll need to run this every time there is a new migration. You should be in a habit to just run migrations when you pull the latest code from `stg`.

## Generating Migrations

The best way to manage migrations is by configuring the metadata yaml files first, then simply running `./artisan doctrine:migrations:diff`. This will create a migration file that will take the diff of your current local db to the migration mapping files. Keep this in mind because if you forgot to run `./artisan doctrine:migrations:migrate` to bring your local db up to the latest, running the migrations-diff command could re-apply some database changes which will **break the migrations.**

## Testing

Unit Testing

```
make test
```

Integration Testing

```
make test-integration
```

Make sure all tests pass before pushing up.

### Workflow

The easiest way to develop with phpunit and testing is to create specific tests for whatever you are doing, and then run them individually with phpunit.

For example, if I'm working on the `tests/Integration/RepApiTest` `testApproveRepNotFound` method, I'll run inside of the docker container the following command to test only that method:

```
./vendor/bin/phpunit tests/Integration/RepAPITest.php --filter=testApproveRepNotFound
```

### Playground

`playground.php` is a file that allows you to quickly prototype/test code in the current app. It's just a simple console command, but the file is ignored, so that you don't have to worry about comitting those temp changes.

You can run the playground via: `./artisan playground`.

## Trouble Shooting

Here are some common errors you can get when developing and here's how to fix them.

### Status 500 in Tests

When asserting a status code in an integration test, and you get a status 500, the easiest way to start debugging is to just check the storage/logs/laravel.log and find the exception that occurred.

### Exception: Unresolvable dependency resolving [Parameter #0 [ <required> $entityName ]] in class Doctrine\ORM\Mapping\ClassMetadataInfo

This can occur when you create a Repository class for an entity, and then type hint for the specific repository class but don't register the repository class in a service provider. To resolve this issue, you need to register the repository in the module's service provider like this:

```
addEntityRepo($this->app, Rep::class);
```

The `addEntityRepo` expects the laravel app instance and the class name of the **entity** not the repository class.

You can see examples of register repositories in the service provider in the different modules in the `app/Model` folder.
