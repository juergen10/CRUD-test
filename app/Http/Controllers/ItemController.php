<?php

namespace App\Http\Controllers;

use App\Repositories\ItemRepositoryInterface;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    protected $itemRepository;

    public function __construct(ItemRepositoryInterface $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $search = $request->input('q');

        $items = $this->itemRepository->getAll($perPage, $search);

        return response()->json($items);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:items',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:2000',
            'stock' => 'required|integer|min:0',
        ]);

        $item = $this->itemRepository->create($validated);
        return response()->json($item, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $item = $this->itemRepository->getById($id);
        return $item ? response()->json($item) : response()->json(['error' => 'Item not found'], 404);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:items,name,' . $id,
            'description' => 'nullable|string',
            'price' => 'required|integer|min:2000',
            'stock' => 'required|integer|min:0',
        ]);

        $updated = $this->itemRepository->update($id, $validated);
        return $updated ? response()->json(['message' => 'Item updated successfully']) : response()->json(['error' => 'Update failed'], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $deleted = $this->itemRepository->delete($id);
        return $deleted ? response()->json(['message' => 'Item deleted successfully']) : response()->json(['error' => 'Delete failed'], 400);
    }
}
