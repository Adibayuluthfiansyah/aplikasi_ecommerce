<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderItemController extends Controller
{
    public function index(): JsonResponse
    {
        $dataOrderItem = OrderItem::with(['order.customer', 'product'])->get();
        return response()->json($dataOrderItem, 200);
    }

    public function show($id): JsonResponse
    {
        try {
            $orderItem = OrderItem::with(['order.customer', 'product'])->findOrFail($id);
            return response()->json($orderItem, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order Item tidak ditemukan'], 404);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|exists:order,id',
            'product_id' => 'required|exists:product,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|integer|min:0',
        ]);

        $orderItem = OrderItem::create([
            'order_id' => $request->order_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
        ]);

        return response()->json([
            'message' => 'Order Item berhasil ditambahkan.',
            'data' => $orderItem->load(['order.customer', 'product'])
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $orderItem = OrderItem::findOrFail($id);

            $request->validate([
                'order_id' => 'sometimes|exists:order,id',
                'product_id' => 'sometimes|exists:product,id',
                'quantity' => 'sometimes|integer|min:1',
                'price' => 'sometimes|integer|min:0',
            ]);

            $data = $request->only(['order_id', 'product_id', 'quantity', 'price']);
            $orderItem->update($data);

            return response()->json([
                'message' => $orderItem->wasChanged()
                    ? 'Order Item berhasil diupdate.'
                    : 'Tidak ada perubahan pada data order item.',
                'data' => $orderItem->load(['order.customer', 'product'])
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order Item tidak ditemukan'], 404);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $orderItem = OrderItem::findOrFail($id);
            $orderItem->delete();

            return response()->json(['message' => 'Order Item berhasil dihapus.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order Item tidak ditemukan.'], 404);
        }
    }
}
