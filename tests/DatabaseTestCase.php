<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * A variant of TestCase specific to tests that will interact
 * with the database. This ensures that each test will use
 * a fresh database to prevent side-effects from other tests.
 */
class DatabaseTestCase extends TestCase
{
    use CreatesApplication;
    use DatabaseMigrations;
}
