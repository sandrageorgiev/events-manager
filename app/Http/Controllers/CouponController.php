<?php

namespace App\Http\Controllers;

use App\Models\Coupon;

use App\Repositories\CouponRepositoryInterface;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    protected CouponRepositoryInterface $couponRepository;

    public function __construct(CouponRepositoryInterface $couponRepository){
        $this->couponRepository = $couponRepository;
    }


    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->couponRepository->all();
    }

//    /**
//     * Show the form for creating a new resource.
//     */
//    public function create(Request $request): \Illuminate\Http\JsonResponse
//    {
//        $coupon = $this->couponRepository->create($request->all());
//
//        return response()->json($coupon, 201);
//    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $coupon = $this->couponRepository->create($request->all());

        return response()->json($coupon, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $name)
    {
        $coupon = $this->couponRepository->find($name);

        return response()->json($coupon, 201);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $name)
    {
        $coupon = $this->couponRepository->find($name);
        $coupon = $this->couponRepository->update($coupon, $request->all());

        return response()->json($coupon, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $name)
    {
        $coupon = $this->couponRepository->find($name);
        $this->couponRepository->delete($coupon);

        return response()->json(null, 204);
    }
}
