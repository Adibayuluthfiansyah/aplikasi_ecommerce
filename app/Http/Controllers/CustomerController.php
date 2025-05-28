<?php
// File: app/Http/Controllers/CustomerController.php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CustomerController extends Controller
{
    public function index(): JsonResponse
    {
        $dataCustomer = Customer::all();
        return response()->json($dataCustomer, 200);
    }

    public function show($id): JsonResponse
    {
        try {
            $customer = Customer::findOrFail($id);
            return response()->json($customer, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Customer tidak ditemukan'], 404);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:50|unique:customer,email',
            'password' => 'required|string|min:6|max:50',
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return response()->json([
            'message' => 'Customer berhasil ditambahkan.',
            'data' => $customer
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $customer = Customer::findOrFail($id);

            $request->validate([
                'name' => 'sometimes|string|max:50',
                'email' => 'sometimes|email|max:50|unique:customer,email,' . $id,
                'password' => 'sometimes|string|min:6|max:50',
                'phone' => 'sometimes|string|max:15',
                'address' => 'sometimes|string',
            ]);

            $data = $request->only(['name', 'email', 'phone', 'address']);

            if ($request->has('password')) {
                $data['password'] = bcrypt($request->password);
            }

            $customer->update($data);

            return response()->json([
                'message' => $customer->wasChanged()
                    ? 'Customer berhasil diupdate.'
                    : 'Tidak ada perubahan pada data customer.',
                'data' => $customer
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Customer tidak ditemukan'], 404);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();

            return response()->json(['message' => 'Customer berhasil dihapus.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Customer tidak ditemukan.'], 404);
        }
    }
}
