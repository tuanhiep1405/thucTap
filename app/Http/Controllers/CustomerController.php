<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Jobs\SendWelcomeEmail;
use App\Models\User;

class CustomerController extends Controller
{
    public function register(Request $request)
{
    $user = User::create($request->all());

    // Đẩy Job vào queue
    SendWelcomeEmail::dispatch($user);

    return response()->json(['message' => 'User registered, email will be sent!']);
}
    const PATH_VIEW = 'customers.';
    public function index()
    {
        $data = Customer::latest('id')->paginate(5);
        return view(self::PATH_VIEW . __FUNCTION__, compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view(self::PATH_VIEW . __FUNCTION__);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'name' => 'required|max:255',
            'email' => ['required', 'max:255', Rule::unique('customers')],
            'phone' => ['required', 'max:20', Rule::unique('customers')],
            'address' => 'nullable',
            'is_active' => ['nullable', Rule::in(0, 1)],
            'image' => 'nullable|image|max:2048',
        ]);
        $data['is_active'] ??= 0;
        try {
            if ($request->hasFile('image')) {
                $data['image'] = Storage::put('customers', $request->file('image'));
            }
            Customer::query()->create($data);
            return redirect()
                ->route('customers.index')
                ->with('sucsses', true);
        } catch (\Throwable $th) {
            if (!empty($data['image']) && Storage::exists($data['image'])) {
                Storage::delete($data['image']);
            }
            return back()
                ->with('sucsses', false)
                ->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $data = Customer::find($customer->id);
        return view(self::PATH_VIEW . __FUNCTION__, compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        $data = Customer::find($customer->id);
        return view(self::PATH_VIEW . __FUNCTION__, compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'email' => ['required', 'max:255', Rule::unique('customers')->ignore($customer->id)],
            'phone' => ['required', 'max:20', Rule::unique('customers')->ignore($customer->id)],
            'address' => 'nullable',
            'is_active' => ['nullable', Rule::in(0, 1)],
            'image' => 'nullable|image|max:2048',
        ]);
        $data['is_active'] ??= 0;
        $customer = Customer::find($customer->id);
        try {
            if ($request->hasFile('image')) {
                $data['image'] = Storage::put('customers', $request->file('image'));
            }
            $xoaanh = $customer->image;
            $customer->update($data);
            if ($request->hasFile('image') && !empty($xoaanh) && Storage::exists($xoaanh)) {
                Storage::delete($xoaanh);
            }
            return redirect()->back()->with('success', 'updated successfully.');
        } catch (\Throwable $th) {
            if (!empty($data['image']) && Storage::exists($data['image'])) {
                Storage::delete($data['image']);
            }
            return redirect()->back()->with('error', 'update failed.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer = Customer::find($customer->id);

        if (!$customer) {
            return redirect()->back()->with('error', 'Khách hàng không tồn tại.');
        }
        $xoaanh = $customer->image;
        if ($customer->image) {
            Storage::delete($xoaanh);
        }
    
        $customer->delete();
    
        return redirect()->route('customers.index')->with('success', 'Khách hàng đã được xóa.');
    }
}
