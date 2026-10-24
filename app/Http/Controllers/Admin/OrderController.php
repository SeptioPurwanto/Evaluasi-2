<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index()
    {
        return view('admin.pesanan.index', [
            'title' => 'Daftar Pesanan',
            'orders' => Order::withCount('items')->orderByDesc('order_id')->paginate(10),
        ]);
    }

    public function show(Order $order)
    {
        return view('admin.pesanan.show', [
            'title' => 'Pesanan ' . $order->code,
            'order' => $order->load('items'),
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(Order::STATUSES)],
        ]);

        $order->update($data);

        return redirect()->route('admin.orders.show', $order)->with('status', 'Status pesanan diperbarui.');
    }
}
