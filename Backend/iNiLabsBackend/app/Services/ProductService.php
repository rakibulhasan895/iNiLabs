<?php
namespace App\Services;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProductService
{
    public function listForUser($user, $perPage = 15): LengthAwarePaginator
    {
        return Product::where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function findForUser($user, $id): ?Product
    {
        return Product::where('user_id', $user->id)->find($id);
    }

    public function createForUser($user, array $data): Product
    {
        $data['user_id'] = $user->id;
        return Product::create($data);
    }

    public function updateForUser(Product $product, array $data): Product
    {
        $product->update($data);
        return $product;
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }
}
