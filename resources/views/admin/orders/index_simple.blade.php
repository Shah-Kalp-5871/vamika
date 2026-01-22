@extends('layouts.admin')

@section('title', 'Manage Orders')

@php
$pageConfig = [
    'showBottomNav' => true,
];
@endphp

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Orders Management</h1>
    
    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-sm text-gray-600">Total Orders</h3>
            <p class="text-2xl font-bold">{{ $orders->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-sm text-gray-600">Pending</h3>
            <p class="text-2xl font-bold">{{ $orders->where('status', 'pending')->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-sm text-gray-600">Delivered</h3>
            <p class="text-2xl font-bold">{{ $orders->where('status', 'delivered')->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-sm text-gray-600">Cancelled</h3>
            <p class="text-2xl font-bold">{{ $orders->where('status', 'cancelled')->count() }}</p>
        </div>
    </div>

    <!-- Orders List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-4 py-3 border-b">
            <h2 class="text-lg font-semibold">Orders List</h2>
        </div>
        
        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shop</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Salesperson</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($orders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->shop->name ?? 'Unknown' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->salesperson->name ?? 'Unknown' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹{{ number_format($order->total_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                                    @elseif($order->status === 'processing') bg-purple-100 text-purple-800
                                    @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">No orders found.</p>
            </div>
        @endif
    </div>
</div>
@endsection