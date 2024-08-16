<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * It can display all posts
     */
    public function test_it_can_display_all_posts(): void
    {
        Post::factory()->count(10)->create();

        $response = $this->getJson(route('posts.index'));

        $response
            ->assertStatus(200)
            ->assertJsonCount(10, 'data');
    }

    /**
     * It can create new post
     */
    public function test_it_can_create_new_post()
    {
        $post = Post::factory()->make();

        $response =
            $this->postJson(route('posts.store'), [
                'title' => $post->title,
                'author' => $post->author,
                'body' => $post->body
            ]);

        $this->assertDatabaseHas('posts', [
            'title' => $post->title,
            'author' => $post->author,
            'body' => $post->body
        ]);
    }

    /**
     * Title is required
     */
    public function test_title_is_required() {
        $post = Post::factory()->make([
            'author' => fake()->name,
            'body' => fake()->paragraph
        ]);

        $response =
            $this->postJson(route('posts.store'), [
                'author' => $post->author,
                'body' => $post->body
            ]);

        $response->assertStatus(422);
    }

    /**
     * it can return one post
     */
    public function test_it_can_return_one_post()
    {
        $post = Post::factory()->create();
        $response =
            $this->getJson(route('posts.show', $post));
        
        $response
            ->assertStatus(200);
    }

     /**
     * it can update a post
     */
    public function test_it_can_update_a_post()
    {
        $post = Post::factory()->create();

        $response =
            $this->putJson(route('posts.update', $post), [
                'title' => 'new title',
                'author' => 'new author',
                'body' => 'new body'
            ]);
        
        // Check the API
        $post->refresh(); // reload the model instance with fresh data from the database.
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'title' => 'new title',
                    'author' => 'new author',
                    'body' => 'new body',
                ]
            ]);

        $this->assertEquals('new title', $post->title);
        $this->assertEquals('new author', $post->author);
        $this->assertEquals('new body', $post->body);

        // Assert Database - extra
        $this->assertDatabaseHas('posts', [
            'title' => 'new title',
            'author' => 'new author',
            'body' => 'new body'
        ]);
    }

    public function test_it_can_delete_a_post()
    {
        $post = Post::factory()->create();

        $response =
            $this->deleteJson(route('posts.destroy', $post));
        
        $response->assertStatus(204);

        $this->assertDatabaseMissing('posts', [
            'title' => $post->title,
            'author' => $post->author,
            'body' => $post->body
        ]);
    }

}
