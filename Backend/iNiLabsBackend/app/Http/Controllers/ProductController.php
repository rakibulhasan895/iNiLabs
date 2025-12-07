<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    private ProductService $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 15);
        $products = $this->service->listForUser($request->user(), $perPage);

        return response()->json([
            'success' => true,
            'message' => 'Products retrieved successfully',
            'data' => ProductResource::collection($products),
        ], 200);
    }

    public function store(StoreProductRequest $request)
    {
        DB::beginTransaction();

        try {
            $product = $this->service->createForUser($request->user(), $request->validated());
            DB::commit();

            return (new ProductResource($product))
                ->additional([
                    'success' => true,
                    'message' => 'Product created successfully',
                ])
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create product',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function show(Request $request, $id)
    {
        $product = $this->service->findForUser($request->user(), $id);

        if (! $product) {
            return response()->json(['message' => 'Product Not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'message' => 'Products retrieved successfully',
            'data' => new ProductResource($product),
        ], 200);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = $this->service->findForUser($request->user(), $id);
        if (! $product) {
            return response()->json(['message' => 'Product Not found'], Response::HTTP_NOT_FOUND);
        }
        DB::beginTransaction();
        try {
            $product = $this->service->updateForUser($product, $request->validated());

            DB::commit();

            return (new ProductResource($product))
                ->additional([
                    'success' => true,
                    'message' => 'Product updated successfully',
                ])
                ->response()
                ->setStatusCode(Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update product',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Request $request, $id)
    {
        $product = $this->service->findForUser($request->user(), $id);

        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $this->service->delete($product);

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully',
        ], Response::HTTP_OK);
    }
}
