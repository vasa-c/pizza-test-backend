# Tests

There are no automatic tests on the frontend.

For backend used phpunit.

```
./vendor/bin/phpunit -c ./phpunit.xml path/to/test
```

Or more simply:

* `./unit.sh path/to/test`
* `./unit.sh` - all tests

Tests:

* `tests/Unit` - unit tests of classes (models and services)
* `tests/Feature`
    * `Console` - test of artisan commands
    * `HTTP` - test of API requests
