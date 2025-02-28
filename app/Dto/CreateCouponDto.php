<?php

namespace App\Dto;

class CreateCouponDto
{
    public string $name;
    public float $percentageDiscount;


    public function __construct(string $name, float $percentageDiscount)
    {
        $this->name = $name;
        $this->percentageDiscount = $percentageDiscount;
    }

    /**
     * Create an instance of CouponDTO from the incoming request.
     *
     * @param $request
     * @return CreateCouponDto
     */
    public static function fromRequest($request): CreateCouponDto
    {
        return new self(
            $request->input('name'),
            $request->input('percentageDiscount')
        );
    }
}
