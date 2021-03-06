<?php

namespace Tests\Feature\Admin;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PagesControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->actingAs(new User)->get('/admin');

        $response->assertStatus(200);
    }

    public function testReports()
    {
        $response = $this->actingAs(new User)->get('/admin/reports');

        $response->assertStatus(200);
    }
}
