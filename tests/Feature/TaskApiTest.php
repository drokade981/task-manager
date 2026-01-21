<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_task_successfully()
    {
        $payload = [
            'title' => 'Test Task',
            'status' => 'pending',
        ];

        $response = $this->postJson('/api/tasks', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'Test Task']);
        $this->assertDatabaseHas('tasks', ['title' => 'Test Task']);
    }

    public function test_it_fails_validation_when_creating_task()
    {
        $payload = [
            'title' => '', // invalid
            'status' => 'invalid_status',
        ];

        $response = $this->postJson('/api/tasks', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title', 'status']);
    }

    public function test_it_lists_all_tasks()
    {
        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    public function test_it_updates_a_task()
    {
        $task = Task::factory()->create(['status' => 'pending']);

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => $task->title,
            'status' => 'completed',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['status' => 'completed']);
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'status' => 'completed']);
    }

    public function test_it_deletes_a_task()
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Task deleted successfully']);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

}
