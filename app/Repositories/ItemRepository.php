<?php

namespace App\Repositories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ItemRepository implements ItemRepositoryInterface
{
    public function getAll(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = Item::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        }

        return $query->paginate($perPage);
    }


    public function getById($id): ?Item
    {
        return Item::find($id);
    }

    public function create(array $data): Item
    {
        return Item::create($data);
    }

    public function update($id, array $data): bool
    {
        $item = Item::findOrFail($id);
        return $item->update($data);
    }

    public function delete($id): bool
    {
        $item = Item::findOrFail($id);
        return $item->delete();
    }
}
