<?php

namespace Tests\Feature\Todo;

use App\Models\ToDo;
use App\Services\PaginationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase;
    private $todoList;

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user     = $this->createAuthUser();
        $this->todoList = $this->createTodoList(1, [
            'title'       => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'user_id'     => $this->user->id
        ]);
    }

    public function test_if_user_can_get_all_todos()
    {
        $this->createTodoList(3);
        $response = $this->getJson(route('todos.index'), ['perPage' => PaginationService::DEFAULT_PER_PAGE_VALUE])
            ->json('payload.data');
        $this->assertCount(PaginationService::DEFAULT_PER_PAGE_VALUE, $response);
        $this->assertCount($this->user->todos()->count(), array_merge($response, $this->todoList->toArray()));
        $this->assertEquals($this->todoList->first()->title, $response[0]['title']);
    }

    public function test_if_user_can_store_new_todo()
    {
        $todo = ToDo::factory()->make();
        $response = $this->postJson(route('todos.store'), [
            'title' => $todo->title,
            'description' => $todo->description,
            'user_id' => $todo->user->id,
        ])
            ->assertCreated()
            ->json('payload');

        $this->assertEquals($todo->title, $response['title']);
        $this->assertDatabaseHas('todos', ['title' => $todo->title]);
        $this->assertTrue($this->user->todos()->exists());
    }

    public function test_if_user_can_delete_todo()
    {
        $this->deleteJson(route('todos.destroy', $this->todoList->first()->id))
            ->assertNoContent();

        $this->assertDatabaseMissing('todos', ['id' => $this->todoList->first()->id]);
    }

    public function test_if_user_can_update_todo()
    {
        $todo = ToDo::factory()->make();
        $this->patchJson(route('todos.update', $this->todoList->first()->id), [
            'title' => $todo->title,
            'description' => $todo->description,
            'user_id' => $this->user->id,
        ])
            ->assertStatus(202);

        $this->assertDatabaseHas('todos', ['id' => $this->todoList->first()->id, 'title' => $todo->title]);
    }

    public function test_when_storing_todo_title_field_is_required()
    {
        $this->postJson(route('todos.store'))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['title']);
    }
}
