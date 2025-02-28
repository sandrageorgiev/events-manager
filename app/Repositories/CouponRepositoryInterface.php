<?php

namespace App\Repositories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Collection;
use PhpParser\Node\Scalar\String_;

interface CouponRepositoryInterface
{
    public function all(): Collection;

    public function find(String $name): Coupon;

    public function create(array $data): Coupon;

    public function update(Coupon $coupon, array $data): Coupon;

    public function delete(Coupon $coupon): bool;
}
