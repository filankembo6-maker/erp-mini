<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_la_racine_redirige_vers_le_login()
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }
}