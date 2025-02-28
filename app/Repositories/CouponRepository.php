<?php
namespace App\Repositories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Collection;

class CouponRepository implements CouponRepositoryInterface
{
    // Get all coupons
    public function all(): Collection
    {
        return Coupon::all();
    }

    // Find a coupon by its ID-name
    public function find(String $name): Coupon
    {
        return Coupon::query()->findOrFail($name); // Returns the coupon or throws a ModelNotFoundException
    }

    // Create a new coupon
    public function create(array $data): Coupon
    {
        return Coupon::query()->create($data); // Mass assigns the data to create a new coupon
    }

    // Update an existing coupon
    public function update(Coupon $coupon, array $data): Coupon
    {
        $coupon->update($data); // Updates the coupon with the provided data
        return $coupon; // Returns the updated coupon
    }

    // Delete a coupon
    public function delete(Coupon $coupon): bool
    {
        return $coupon->delete(); // Deletes the coupon
    }
}
