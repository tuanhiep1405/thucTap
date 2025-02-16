<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Customer::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => ['required', 'max:255', Rule::unique('customers')],
            'phone' => ['required', 'max:20', Rule::unique('customers')],
            'address' => 'nullable',
            'is_active' => ['nullable', Rule::in(0, 1)],
            'image' => 'nullable|image|max:2048',
        ]);
        $product = Customer::create($request->all());
        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $data = Customer::find($id);
            return response()->json([
                'message' => 'Chi tiết sản phẩm có id = ' . $id,
                'data' => $data,
                200
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Không có sản phẩm có id = ' . $id,
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'email' => ['required', 'max:255', Rule::unique('customers')->ignore($id)],
            'phone' => ['required', 'max:20', Rule::unique('customers')->ignore($id)],
            'address' => 'nullable',
            'is_active' => ['nullable', Rule::in(0, 1)],
            'image' => 'nullable|image|max:2048',
        ]);
        $data['is_active'] ??= 0;
        $customer = Customer::find($id);
        try {
            if ($request->hasFile('image')) {
                $data['image'] = Storage::put('customers', $request->file('image'));
            }
            $xoaanh = $customer->image;
            $customer->update($data);
            if ($request->hasFile('image') && !empty($xoaanh) && Storage::exists($xoaanh)) {
                Storage::delete($xoaanh);
            }
            return response()->json([
                'message' => 'Cập nhập thành công sản phẩm có id = ' . $id,
                'data' => $data,
                200
            ]);
        } catch (\Throwable $th) {
            if (!empty($data['image']) && Storage::exists($data['image'])) {
                Storage::delete($data['image']);
            }
            Log::error(
                __CLASS__ . '@' . __FUNCTION__,
                ['error' => $th->getMessage()]
            );
            return response()->json([
                'message' => 'Cập nhập không thành công sản phẩm có id = ' . $id,
                404

            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Customer::find($id);
        if (!$product) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $product->delete();
        return response()->json(['message' => 'Customer deleted'], 200);
    }
}
