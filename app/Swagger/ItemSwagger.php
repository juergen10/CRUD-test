<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Item Management API",
 *     description="API for managing items in a repository, with CRUD operations, pagination, and validation."
 * )
 */

class ItemSwagger
{
    /**
     * @OA\Tag(
     *     name="Items",
     *     description="Operations related to items"
     * )
     */

    /**
     * @OA\Schema(
     *     schema="Item",
     *     type="object",
     *     properties={
     *         @OA\Property(property="id", type="integer", example=1),
     *         @OA\Property(property="name", type="string", example="Aqua"),
     *         @OA\Property(property="description", type="string", example="Bottled mineral water"),
     *         @OA\Property(property="price", type="integer", example=2000),
     *         @OA\Property(property="stock", type="integer", example=100)
     *     }
     * )
     */

    /**
     * @OA\Schema(
     *     schema="ValidationError",
     *     type="object",
     *     properties={
     *         @OA\Property(property="errors", type="object", additionalProperties=@OA\Property(type="array", @OA\Items(type="string"))),
     *         @OA\Property(property="message", type="string", example="The given data was invalid.")
     *     }
     * )
     */

    /**
     * @OA\Get(
     *     path="/api/items",
     *     tags={"Items"},
     *     summary="Retrieve paginated list of items",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A paginated list of items",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="per_page", type="integer"),
     *             @OA\Property(property="total", type="integer"),
     *             @OA\Property(property="last_page", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function getItem() {}

    /**
     * @OA\Post(
     *     path="/api/items",
     *     tags={"Items"},
     *     summary="Create a new item",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "price", "stock"},
     *             @OA\Property(property="name", type="string", example="Aqua"),
     *             @OA\Property(property="description", type="string", example="Bottled mineral water"),
     *             @OA\Property(property="price", type="integer", example=2000),
     *             @OA\Property(property="stock", type="integer", example=100)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Item created successfully"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function items() {}

    /**
     * @OA\Get(
     *     path="/api/items/{id}",
     *     tags={"Items"},
     *     summary="Retrieve a specific item by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the item to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Item retrieved successfully"),
     *     @OA\Response(response=404, description="Item not found")
     * )
     */
    public function item() {}

    /**
     * @OA\Put(
     *     path="/api/items/{id}",
     *     tags={"Items"},
     *     summary="Update an existing item",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the item to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "price", "stock"},
     *             @OA\Property(property="name", type="string", example="Updated Name"),
     *             @OA\Property(property="description", type="string", example="Updated Description"),
     *             @OA\Property(property="price", type="integer", example=3000),
     *             @OA\Property(property="stock", type="integer", example=50)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Item updated successfully"),
     *     @OA\Response(response=404, description="Item not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update() {}

    /**
     * @OA\Delete(
     *     path="/api/items/{id}",
     *     tags={"Items"},
     *     summary="Delete an item",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the item to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Item deleted successfully"),
     *     @OA\Response(response=404, description="Item not found")
     * )
     */
    public function destroy() {}
}
