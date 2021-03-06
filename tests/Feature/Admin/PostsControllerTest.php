<?php

namespace Tests\Feature\Admin;

use App\Post;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostsControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->actingAs(
            factory(User::class)->create()
        );
    }

    public function testIndex()
    {
        $response = $this->get('/admin/posts');

        $response->assertStatus(200);
    }

    public function testCreate()
    {
        $response = $this->get('/admin/posts/create');

        $response->assertStatus(200);
    }

    public function testStore()
    {
        $post = factory(Post::class)->make(['series_id' => null, 'slug' => 'foo']);

        $response = $this->post('/admin/posts', $post->getAttributes());

        $response->assertRedirect('/admin/posts/foo');
    }

    public function testStoreWithTags()
    {
        $this->markTestIncomplete();
    }

    public function testStoreFails()
    {
        $response = $this->from('/admin/posts/create')
            ->post('/admin/posts', []);

        $response->assertRedirect('/admin/posts/create');
    }

    public function testShow()
    {
        $user = factory(User::class)->create();

        $post = $user->posts()->save(factory(Post::class)->make());

        $response = $this->get("/admin/posts/{$post->slug}");

        $response->assertStatus(200)
            ->assertSee($post->title);
    }

    public function testEdit()
    {
        $user = factory(User::class)->create();

        $post = $user->posts()->save(factory(Post::class)->make());

        $response = $this->get("admin/posts/{$post->slug}/edit");

        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $user = factory(User::class)->create();

        $post = $user->posts()->save(factory(Post::class)->make([
            'slug' => 'foo'
        ]));

        $response = $this->from('/admin/posts/foo/edit')
            ->put('/admin/posts/foo', ['slug' => 'bar']);

        $response->assertRedirect('/admin/posts/bar');
    }

    public function testUpdateWithTags()
    {
        $this->markTestIncomplete();
    }

    public function testUpdateFails()
    {
        $user = factory(User::class)->create();

        $post = $user->posts()->save(factory(Post::class)->make([
            'slug' => 'foo'
        ]));

        $response = $this->from('/admin/posts/foo/edit')
            ->put('/admin/posts/foo', ['series_id' => 'invalid']);

        $response->assertRedirect('/admin/posts/foo/edit');
    }

    public function testDestroy()
    {
        $user = factory(User::class)->create();

        $post = $user->posts()->save(factory(Post::class)->make());

        $response = $this->delete("/admin/posts/{$post->slug}");

        $response->assertRedirect('/admin/posts');

        $this->assertEquals(0, Post::count());
    }
}
