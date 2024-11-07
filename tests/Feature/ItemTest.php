<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Repositories\ItemRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class ItemTest extends TestCase
{
    public function test_get_all_items_with_pagination()
    {
        $mockItems = collect();
        for ($i = 1; $i <= 50; $i++) {
            $mockItems->push(new Item([
                'id' => $i,
                'name' => 'Product ' . $i,
                'price' => 1000 * $i,
                'stock' => 100,
                'description' => 'Description for product ' . $i,
            ]));
        }

        $paginator = new LengthAwarePaginator(
            $mockItems,
            50,
            10,
            1,
            ['path' => url('/api/items')]
        );

        $this->mock(ItemRepositoryInterface::class, function ($mock) use ($paginator) {
            $mock->shouldReceive('getAll')->once()->andReturn($paginator);
        });

        $response = $this->getJson('/api/items?page=1&per_page=10');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['name', 'price', 'stock', 'description']
                ],
                'current_page',
                'per_page',
                'total',
                'last_page'
            ])
            ->assertJson([
                'current_page' => 1,
                'per_page' => 10,
                'total' => 50,
            ]);
    }

    public function test_store_creates_new_item()
    {
        $data = [
            'name' => 'Cola',
            'description' => 'A soft drink',
            'price' => 7000,
            'stock' => 120,
        ];

        $this->mock(ItemRepositoryInterface::class, function ($mock) use ($data) {
            $mock->shouldReceive('create')->once()->with($data)->andReturn(new Item($data));
        });

        $response = $this->postJson('/api/items', $data);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Cola',
                'price' => 7000,
                'stock' => 120,
            ]);
    }

    public function test_create_item_with_missing_fields()
    {
        $data = [
            'name' => '',
            'price' => '',
            'stock' => '',
        ];

        $response = $this->postJson('/api/items', $data);

        $response->assertStatus(422)
            ->assertJsonFragment([
                'name' => ['The name field is required.'],
                'price' => ['The price field is required.'],
                'stock' => ['The stock field is required.'],
            ]);
    }

    public function test_show_returns_single_item()
    {
        $item = new Item(['name' => 'Milo', 'price' => 9000, 'stock' => 80]);

        $this->mock(ItemRepositoryInterface::class, function ($mock) use ($item) {
            $mock->shouldReceive('getById')->with(1)->once()->andReturn($item);
        });

        $response = $this->getJson('/api/items/1');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Milo',
                'price' => 9000,
                'stock' => 80,
            ]);
    }

    public function test_update_modifies_existing_item()
    {
        $data = [
            'name' => 'Milo Updated',
            'price' => 9500,
            'stock' => 75,
        ];

        $this->mock(ItemRepositoryInterface::class, function ($mock) use ($data) {
            $mock->shouldReceive('update')->with(1, $data)->once()->andReturn(true);
        });

        $response = $this->putJson('/api/items/1', $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Item updated successfully']);
    }

    public function test_update_item_with_invalid_data()
    {
        $data = [
            'name' => '',
            'price' => 'invalid_price',
            'stock' => -10,
        ];

        $response = $this->putJson("/api/items/1", $data);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'name',
                'price',
                'stock',
            ]);
    }

    public function test_update_non_existent_item()
    {
        $data = [
            'name' => 'Updated Product',
            'price' => 6000,
            'stock' => 15,
            'description' => 'Updated description',
        ];

        $response = $this->putJson('/api/items/999', $data);

        $response->assertStatus(404)->assertJsonFragment(['message' => 'Record not found.']);
    }

    public function test_destroy_deletes_item()
    {
        $this->mock(ItemRepositoryInterface::class, function ($mock) {
            $mock->shouldReceive('delete')->with(1)->once()->andReturn(true);
        });

        $response = $this->deleteJson('/api/items/1');

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Item deleted successfully']);
    }

    public function test_delete_non_existent_item()
    {
        $response = $this->deleteJson('/api/items/999');

        $response->assertStatus(404)->assertJsonFragment(['message' => 'Record not found.']);
    }
}
