@extends('layouts.admin')

@section('title', 'Edit Offer')
@section('subtitle', 'Update offer details')

@section('content')
<div class="main-content">
    <main class="p-6">
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.offers.update', $offer->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="block mb-2 text-sm font-medium text-gray-700" for="title">Offer Title *</label>
                <input type="text" name="title" id="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required
                    value="{{ old('title', $offer->title) }}" placeholder="Enter offer title (e.g., Summer Sale)">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label class="block mb-2 text-sm font-medium text-gray-700" for="discount_type">Discount Type *</label>
                    <select name="discount_type" id="discount_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" onchange="updateDiscountLabel()">
                        <option value="percentage" {{ old('discount_type', $offer->discount_type) == 'percentage' ? 'selected' : '' }}>Percentage Discount</option>
                        <option value="fixed" {{ old('discount_type', $offer->discount_type) == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="block mb-2 text-sm font-medium text-gray-700" id="discount_value_label" for="discount_value">Discount Value (%) *</label>
                    <input type="number" name="discount_value" id="discount_value" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required step="0.01" min="0"
                        value="{{ old('discount_value', $offer->discount_value) }}" placeholder="Enter value">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label class="block mb-2 text-sm font-medium text-gray-700" for="start_date">Start Date *</label>
                    <input type="date" name="start_date" id="start_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required
                        value="{{ old('start_date', $offer->start_date->format('Y-m-d')) }}">
                </div>

                <div class="form-group">
                    <label class="block mb-2 text-sm font-medium text-gray-700" for="end_date">End Date *</label>
                    <input type="date" name="end_date" id="end_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required
                        value="{{ old('end_date', $offer->end_date->format('Y-m-d')) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="block mb-2 text-sm font-medium text-gray-700" for="status">Status *</label>
                <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="active" {{ old('status', $offer->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $offer->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="form-group">
                <label class="block mb-2 text-sm font-medium text-gray-700" for="description">Description</label>
                <textarea name="description" id="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Describe the offer...">{{ old('description', $offer->description) }}</textarea>
            </div>

            <div class="flex justify-end gap-4 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.offers.index') }}" class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 no-underline">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Offer
                </button>
            </div>
        </form>
    </main>
</div>
@endsection

@section('scripts')
<script>
    function updateDiscountLabel() {
        const type = document.getElementById('discount_type').value;
        const label = document.getElementById('discount_value_label');
        if (type === 'percentage') {
            label.textContent = 'Discount Value (%) *';
        } else {
            label.textContent = 'Discount Amount (₹) *';
        }
    }

    // Initialize
    updateDiscountLabel();
</script>
@endsection
