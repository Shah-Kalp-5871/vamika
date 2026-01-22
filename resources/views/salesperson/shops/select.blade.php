@extends('layouts.salesperson')

@section('content')
<style>
    .area-card:hover,
    .shop-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .area-card.active {
        border-color: #4F46E5;
        background-color: #F5F3FF;
    }

    .shop-card.selected {
        border-color: #4F46E5;
        background-color: #F5F3FF;
    }

    .search-container {
        position: relative;
    }

    .search-input {
        padding-left: 2.5rem;
    }

    .search-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9CA3AF;
    }

    .pagination-btn {
        padding: 0.375rem 0.75rem;
        border: 1px solid #D1D5DB;
        background: white;
        color: #374151;
        border-radius: 0.375rem;
        transition: all 0.2s;
    }

    .pagination-btn:hover:not(:disabled) {
        background: #F3F4F6;
        border-color: #9CA3AF;
    }

    .pagination-btn.active {
        background: #4F46E5;
        color: white;
        border-color: #4F46E5;
    }

    .pagination-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .items-per-page {
        border: 1px solid #D1D5DB;
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
        background: white;
    }

    @media (max-width: 640px) {
        .search-input {
            padding-left: 2rem;
            font-size: 0.875rem;
        }

        .search-icon {
            left: 0.5rem;
        }
    }
</style>

<!-- Mobile/Tablet View -->
<div class="block xl:hidden">
    <div class="min-h-screen pb-24 bg-white">
        <main class="p-3 sm:p-4 md:p-6 space-y-4 sm:space-y-6">
            <!-- Area Selection - Mobile/Tablet -->
            <section id="areaSectionMobile">
                <!-- Search Bar -->
                <div class="bg-white">
                    <div class="mb-3 sm:mb-4 ">
                        <div class="search-container">
                            <iconify-icon icon="lucide:search" width="18" class="search-icon"></iconify-icon>
                            <input type="text" id="areaSearchMobile" placeholder="Search areas by name or pincode..."
                                class="search-input w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                                oninput="filterAreas('Mobile')">
                        </div>
                    </div>

                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <h3 class="text-sm sm:text-base font-semibold text-slate-900">Select Area</h3>
                        <span id="areaCountMobile" class="text-xs sm:text-sm text-slate-500">0 areas</span>
                    </div>
                </div>
                <div id="areaListMobile" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                    <!-- Areas will be loaded here -->
                    <div class="col-span-full p-8 text-center">
                        <div class="animate-spin inline-block w-6 h-6 border-[3px] border-current border-t-transparent text-slate-400 rounded-full"
                            role="status" aria-label="loading"></div>
                    </div>
                </div>

                <!-- Area Pagination - Mobile -->
                <div id="areaPaginationMobile"
                    class="flex items-center justify-between mt-4 sm:mt-6 pt-4 border-t border-slate-200">
                    <button onclick="changeAreaPage('Mobile', -1)" class="pagination-btn flex items-center gap-1">
                        <iconify-icon icon="lucide:chevron-left" width="16"></iconify-icon>
                    </button>

                    <div class="flex items-center gap-2">
                        <select id="areaItemsPerPageMobile" class="items-per-page text-xs sm:text-sm"
                            onchange="changeAreaItemsPerPage('Mobile')">
                            <option value="10">10 per page</option>
                            <option value="20">20 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </select>
                        <span id="areaPageInfoMobile" class="text-xs sm:text-sm text-slate-500">
                            Page 1 of 1
                        </span>
                    </div>

                    <button onclick="changeAreaPage('Mobile', 1)" class="pagination-btn flex items-center gap-1">
                        <iconify-icon icon="lucide:chevron-right" width="16"></iconify-icon>
                    </button>
                </div>
            </section>

            <!-- Shop Selection - Mobile/Tablet (Hidden by default) -->
            <section id="shopSectionMobile" class="hidden">
                <!-- Search Bar -->
                <div class="mb-3 sm:mb-4">
                    <div class="search-container">
                        <iconify-icon icon="lucide:search" width="18" class="search-icon"></iconify-icon>
                        <input type="text" id="shopSearchMobile" placeholder="Search shops by name, owner, or phone..."
                            class="search-input w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                            oninput="filterShops('Mobile')">
                    </div>
                </div>

                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="flex-1 min-w-0">
                        <button onclick="goBackToAreas()"
                            class="flex items-center gap-1 text-xs sm:text-sm text-indigo-600 mb-1 hover:text-indigo-700">
                            <iconify-icon icon="lucide:chevron-left" width="12"></iconify-icon>
                            Back to areas
                        </button>
                        <h3 class="text-sm sm:text-base font-semibold text-slate-900">Select Shop</h3>
                        <p class="text-xs sm:text-sm text-slate-500 truncate" id="selectedAreaNameMobile">South Mumbai</p>
                    </div>
                    <span id="shopCountMobile" class="text-xs sm:text-sm text-slate-500 ml-2">0 shops</span>
                </div>

                <div id="shopListMobile" class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                    <!-- Shops will be loaded here -->
                </div>

                <!-- Empty State -->
                <div id="shopEmptyStateMobile"
                    class="hidden flex flex-col items-center justify-center py-12 text-slate-400">
                    <iconify-icon icon="lucide:store" width="48" class="mb-3 opacity-50"></iconify-icon>
                    <p class="text-sm font-medium">No shops found</p>
                    <p class="text-xs mt-1">Try a different search or select a different area</p>
                </div>

                <!-- Shop Pagination - Mobile -->
                <div id="shopPaginationMobile"
                    class="flex items-center justify-between mt-4 sm:mt-6 pt-4 border-t border-slate-200">
                    <button onclick="changeShopPage('Mobile', -1)" class="pagination-btn flex items-center gap-1">
                        <iconify-icon icon="lucide:chevron-left" width="16"></iconify-icon>
                    </button>

                    <div class="flex items-center gap-2">
                        <select id="shopItemsPerPageMobile" class="items-per-page text-xs sm:text-sm"
                            onchange="changeShopItemsPerPage('Mobile')">
                            <option value="10">10 per page</option>
                            <option value="20">20 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </select>
                        <span id="shopPageInfoMobile" class="text-xs sm:text-sm text-slate-500">
                            Page 1 of 1
                        </span>
                    </div>

                    <button onclick="changeShopPage('Mobile', 1)" class="pagination-btn flex items-center gap-1">
                        <iconify-icon icon="lucide:chevron-right" width="16"></iconify-icon>
                    </button>
                </div>
            </section>
        </main>
    </div>
</div>

<!-- Desktop View -->
<div class="hidden xl:block">
    <div class="max-w-7xl mx-auto min-h-screen py-6 px-4">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
            <!-- Header - Desktop -->
            <div class="p-6 lg:p-8 border-b border-slate-100">
                <div class="flex flex-col 2xl:flex-row items-start 2xl:items-center justify-between gap-4 mb-4">
                    <div>
                        <h1 class="text-xl lg:text-2xl font-semibold text-slate-900">Create New Order</h1>
                        <p class="text-sm lg:text-base text-slate-500 mt-1">Step 1 of 4 - Select area and shop</p>
                    </div>
                    <div class="flex items-center gap-2 lg:gap-4 overflow-x-auto w-full 2xl:w-auto">
                        <!-- Step Indicator - Desktop -->
                        <div class="flex items-center gap-1 lg:gap-2">
                            <div
                                class="h-9 w-9 lg:h-10 lg:w-10 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-semibold flex-shrink-0">
                                1
                            </div>
                            <div class="text-xs lg:text-sm font-medium text-indigo-600 whitespace-nowrap">Select Shop</div>
                        </div>
                        <iconify-icon icon="lucide:chevron-right" width="16"
                            class="text-slate-300 flex-shrink-0"></iconify-icon>
                        <div class="flex items-center gap-1 lg:gap-2">
                            <div
                                class="h-9 w-9 lg:h-10 lg:w-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-sm font-semibold flex-shrink-0">
                                2
                            </div>
                            <div class="text-xs lg:text-sm text-slate-500 whitespace-nowrap">Add Products</div>
                        </div>
                        <iconify-icon icon="lucide:chevron-right" width="16"
                            class="text-slate-300 flex-shrink-0"></iconify-icon>
                        <div class="flex items-center gap-1 lg:gap-2">
                            <div
                                class="h-9 w-9 lg:h-10 lg:w-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-sm font-semibold flex-shrink-0">
                                3
                            </div>
                            <div class="text-xs lg:text-sm text-slate-500 whitespace-nowrap">Review</div>
                        </div>
                        <iconify-icon icon="lucide:chevron-right" width="16"
                            class="text-slate-300 flex-shrink-0"></iconify-icon>
                        <div class="flex items-center gap-1 lg:gap-2">
                            <div
                                class="h-9 w-9 lg:h-10 lg:w-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-sm font-semibold flex-shrink-0">
                                4
                            </div>
                            <div class="text-xs lg:text-sm text-slate-500 whitespace-nowrap">Invoice</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content - Desktop -->
            <div class="p-6 lg:p-8">
                <div class="grid grid-cols-1 2xl:grid-cols-2 gap-6 lg:gap-8">
                    <!-- Left Column: Area Selection -->
                    <div>
                        <div class="bg-white border border-slate-200 rounded-xl p-5 lg:p-6">
                            <!-- Search Bar -->
                            <div class="mb-5 lg:mb-6">
                                <div class="search-container">
                                    <iconify-icon icon="lucide:search" width="18" class="search-icon"></iconify-icon>
                                    <input type="text" id="areaSearchDesktop"
                                        placeholder="Search areas by name or pincode..."
                                        class="search-input w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                                        oninput="filterAreas('Desktop')">
                                </div>
                            </div>

                            <div class="flex items-center justify-between mb-5 lg:mb-6">
                                <h3 class="text-base lg:text-lg font-semibold text-slate-900">Select Area</h3>
                                <span id="areaCountDesktop" class="text-sm text-slate-500">0 areas</span>
                            </div>

                            <div id="areaListDesktop" class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
                                <!-- Areas will be loaded here -->
                                <div class="col-span-full p-12 text-center">
                                    <div class="animate-spin inline-block w-8 h-8 border-[3px] border-current border-t-transparent text-slate-400 rounded-full"
                                        role="status" aria-label="loading"></div>
                                </div>
                            </div>

                            <!-- Area Pagination - Desktop -->
                            <div id="areaPaginationDesktop"
                                class="flex items-center justify-between mt-6 pt-6 border-t border-slate-200">
                                <button onclick="changeAreaPage('Desktop', -1)"
                                    class="pagination-btn flex items-center gap-1">
                                    <iconify-icon icon="lucide:chevron-left" width="16"></iconify-icon>
                                    Previous
                                </button>

                                <div class="flex items-center gap-2">
                                    <select id="areaItemsPerPageDesktop" class="items-per-page text-sm"
                                        onchange="changeAreaItemsPerPage('Desktop')">
                                        <option value="10">10 per page</option>
                                        <option value="20">20 per page</option>
                                        <option value="50">50 per page</option>
                                        <option value="100">100 per page</option>
                                    </select>
                                    <span id="areaPageInfoDesktop" class="text-sm text-slate-500">
                                        Page 1 of 1
                                    </span>
                                </div>

                                <button onclick="changeAreaPage('Desktop', 1)"
                                    class="pagination-btn flex items-center gap-1">
                                    Next
                                    <iconify-icon icon="lucide:chevron-right" width="16"></iconify-icon>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Shop Selection -->
                    <div>
                        <div class="bg-white border border-slate-200 rounded-xl p-5 lg:p-6 sticky top-6">
                            <!-- Search Bar -->
                            <div class="mb-5 lg:mb-6">
                                <div class="search-container">
                                    <iconify-icon icon="lucide:search" width="18" class="search-icon"></iconify-icon>
                                    <input type="text" id="shopSearchDesktop"
                                        placeholder="Search shops by name, owner, or phone..."
                                        class="search-input w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                                        oninput="filterShops('Desktop')" disabled>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mb-5 lg:mb-6">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-base lg:text-lg font-semibold text-slate-900">Select Shop</h3>
                                    <p class="text-sm text-slate-500 truncate" id="selectedAreaNameDesktop">Select an area first</p>
                                </div>
                                <span id="shopCountDesktop" class="text-sm text-slate-500 ml-2">0 shops</span>
                            </div>

                            <div id="shopListDesktop" class="space-y-4 max-h-[400px] overflow-y-auto pr-2">
                                <!-- Shops will be loaded here -->
                                <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                                    <iconify-icon icon="lucide:map-pin" width="48"
                                        class="mb-3 opacity-50"></iconify-icon>
                                    <p class="text-sm font-medium">Select an area to view shops</p>
                                    <p class="text-xs mt-1">Choose from the left panel</p>
                                </div>
                            </div>

                            <!-- Empty State -->
                            <div id="shopEmptyStateDesktop"
                                class="hidden flex flex-col items-center justify-center py-12 text-slate-400">
                                <iconify-icon icon="lucide:store" width="48" class="mb-3 opacity-50"></iconify-icon>
                                <p class="text-sm font-medium">No shops found</p>
                                <p class="text-xs mt-1">Try a different search or select a different area</p>
                            </div>

                            <!-- Shop Pagination - Desktop -->
                            <div id="shopPaginationDesktop"
                                class="flex items-center justify-between mt-6 pt-6 border-t border-slate-200">
                                <button onclick="changeShopPage('Desktop', -1)"
                                    class="pagination-btn flex items-center gap-1" disabled>
                                    <iconify-icon icon="lucide:chevron-left" width="16"></iconify-icon>
                                    Previous
                                </button>

                                <div class="flex items-center gap-2">
                                    <select id="shopItemsPerPageDesktop" class="items-per-page text-sm"
                                        onchange="changeShopItemsPerPage('Desktop')" disabled>
                                        <option value="10">10 per page</option>
                                        <option value="20">20 per page</option>
                                        <option value="50">50 per page</option>
                                        <option value="100">100 per page</option>
                                    </select>
                                    <span id="shopPageInfoDesktop" class="text-sm text-slate-500">
                                        Page 1 of 1
                                    </span>
                                </div>

                                <button onclick="changeShopPage('Desktop', 1)"
                                    class="pagination-btn flex items-center gap-1" disabled>
                                    Next
                                    <iconify-icon icon="lucide:chevron-right" width="16"></iconify-icon>
                                </button>
                            </div>

                            <!-- Continue Button - Desktop -->
                            <button id="continueBtnDesktop" onclick="proceedToProducts()"
                                class="w-full mt-6 px-6 py-3 lg:py-3.5 rounded-xl bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 text-sm lg:text-base"
                                disabled>
                                <iconify-icon icon="lucide:arrow-right" width="18"></iconify-icon>
                                Continue to Products
                            </button>
                            <p class="text-xs text-slate-400 text-center mt-3">
                                Step 1 of 4 • Select an area and shop to continue
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Continue Button - Mobile/Tablet (Fixed at bottom) -->
<div
    class="fixed bottom-16 sm:bottom-16 left-0 right-0 bg-white border-t border-slate-200 p-3 sm:p-4 xl:hidden z-20 safe-area-inset-bottom">
    <div class="max-w-2xl mx-auto">
        <button id="continueBtnMobile" onclick="proceedToProducts()"
            class="w-full px-4 sm:px-6 py-3 sm:py-3.5 rounded-xl bg-indigo-600 text-white font-medium hover:bg-indigo-700 active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-md disabled:shadow-none flex items-center justify-center gap-2 text-sm sm:text-base">
            <iconify-icon icon="lucide:arrow-right" width="18"></iconify-icon>
            Continue to Products
        </button>
        <p class="text-xs text-slate-400 text-center mt-2 sm:mt-3">
            Step 1 of 4 • Select an area and shop to continue
        </p>
    </div>
</div>
@endsection

@push('pageConfig')
@php
    $pageConfig = [
        'title' => 'Create New Order',
        'subtitle' => 'Select area and shop to continue',
        'showBack' => true,
        'backUrl' => route('salesperson.dashboard'),
        'showBottomNav' => true
    ];
@endphp
@endpush

@section('scripts')
<script>
    const dummyData = {
        areas: @json($areas),
        shops: @json($shops)
    };

    // State management
    let selectedArea = null;
    let selectedShop = null;

    // Pagination state for each view
    const state = {
        Mobile: {
            areas: {
                currentPage: 1,
                itemsPerPage: 10,
                searchQuery: '',
                filteredData: [],
                totalPages: 1
            },
            shops: {
                currentPage: 1,
                itemsPerPage: 10,
                searchQuery: '',
                filteredData: [],
                totalPages: 1
            }
        },
        Desktop: {
            areas: {
                currentPage: 1,
                itemsPerPage: 10,
                searchQuery: '',
                filteredData: [],
                totalPages: 1
            },
            shops: {
                currentPage: 1,
                itemsPerPage: 10,
                searchQuery: '',
                filteredData: [],
                totalPages: 1
            }
        }
    };

    function initializePage() {
        loadAreas('Mobile');
        loadAreas('Desktop');

        // Initialize buttons
        document.getElementById('continueBtnMobile').disabled = true;
        document.getElementById('continueBtnDesktop').disabled = true;
    }

    function loadAreas(version) {
        const areaContainer = document.getElementById(`areaList${version}`);
        const areaCountElement = document.getElementById(`areaCount${version}`);
        const paginationContainer = document.getElementById(`areaPagination${version}`);
        const pageInfo = document.getElementById(`areaPageInfo${version}`);

        if (!areaContainer) return;

        // Filter areas based on search query
        const searchQuery = state[version].areas.searchQuery.toLowerCase();
        let filteredAreas = dummyData.areas;

        if (searchQuery) {
            filteredAreas = dummyData.areas.filter(area =>
                (area.name || '').toLowerCase().includes(searchQuery) ||
                (area.pincode || '').toLowerCase().includes(searchQuery) ||
                (area.code || '').toLowerCase().includes(searchQuery)
            );
        }

        // Update state
        state[version].areas.filteredData = filteredAreas;
        state[version].areas.totalPages = Math.ceil(filteredAreas.length / state[version].areas.itemsPerPage);

        // Ensure current page is valid
        if (state[version].areas.currentPage > state[version].areas.totalPages) {
            state[version].areas.currentPage = Math.max(1, state[version].areas.totalPages);
        }

        // Calculate pagination
        const startIndex = (state[version].areas.currentPage - 1) * state[version].areas.itemsPerPage;
        const endIndex = startIndex + state[version].areas.itemsPerPage;
        const currentAreas = filteredAreas.slice(startIndex, endIndex);

        // Clear container
        areaContainer.innerHTML = '';

        // Update count
        areaCountElement.textContent = `${filteredAreas.length} areas`;

        if (filteredAreas.length === 0) {
            areaContainer.innerHTML = `
                <div class="col-span-full p-8 text-center text-slate-400">
                    <iconify-icon icon="lucide:map-pin" width="32" class="mx-auto mb-3"></iconify-icon>
                    <p class="text-sm font-medium">No areas found</p>
                    <p class="text-xs mt-1">Try a different search term</p>
                </div>
            `;

            if (paginationContainer) paginationContainer.classList.add('hidden');
            return;
        }

        if (paginationContainer) paginationContainer.classList.remove('hidden');

        // Render areas
        currentAreas.forEach(area => {
            const areaCard = document.createElement('div');
            areaCard.className = 'area-card p-3 sm:p-4 rounded-xl border border-slate-200 bg-white transition-all cursor-pointer';
            areaCard.onclick = () => selectArea(area, version);

            areaCard.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="h-11 w-11 sm:h-12 sm:w-12 lg:h-14 lg:w-14 rounded-full bg-indigo-50 flex items-center justify-center border border-indigo-100 flex-shrink-0">
                        <iconify-icon icon="lucide:map-pin" width="${version === 'Mobile' ? '18' : '20'}" class="text-indigo-600"></iconify-icon>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm sm:text-base font-semibold text-slate-900 truncate">${area.name}</h4>
                        <div class="flex items-center gap-3 mt-1.5 sm:mt-2">
                            <span class="text-xs sm:text-sm text-slate-500 flex items-center gap-1.5">
                                <iconify-icon icon="lucide:store" width="${version === 'Mobile' ? '12' : '14'}"></iconify-icon>
                                ${area.shops_count || 0} shops
                            </span>
                            <span class="text-xs sm:text-sm text-slate-400">•</span>
                            <span class="text-xs sm:text-sm text-slate-500">${area.code || ''}</span>
                        </div>
                        ${version === 'Desktop' ? `<p class="text-xs lg:text-sm text-indigo-600 mt-2 lg:mt-3 font-medium">Click to view shops</p>` : ''}
                    </div>
                    <iconify-icon icon="lucide:chevron-right" width="${version === 'Mobile' ? '16' : '20'}" class="text-slate-400 flex-shrink-0"></iconify-icon>
                </div>
            `;

            areaContainer.appendChild(areaCard);
        });

        // Update pagination controls
        updatePaginationControls('areas', version);
    }

    function filterAreas(version) {
        const searchInput = document.getElementById(`areaSearch${version}`);
        state[version].areas.searchQuery = searchInput.value;
        state[version].areas.currentPage = 1;
        loadAreas(version);
    }

    function changeAreaPage(version, direction) {
        const newPage = state[version].areas.currentPage + direction;

        if (newPage >= 1 && newPage <= state[version].areas.totalPages) {
            state[version].areas.currentPage = newPage;
            loadAreas(version);
        }
    }

    function changeAreaItemsPerPage(version) {
        const select = document.getElementById(`areaItemsPerPage${version}`);
        state[version].areas.itemsPerPage = parseInt(select.value);
        state[version].areas.currentPage = 1;
        loadAreas(version);
    }

    function selectArea(area, version) {
        selectedArea = area;
        selectedShop = null; // Reset shop selection

        // Update UI for mobile
        if (version === 'Mobile') {
            document.querySelectorAll('#areaListMobile .area-card').forEach(card => {
                card.classList.remove('active');
            });
            event.currentTarget.classList.add('active');

            // Show shop section, hide area section
            document.getElementById('areaSectionMobile').classList.add('hidden');
            document.getElementById('shopSectionMobile').classList.remove('hidden');
            document.getElementById('selectedAreaNameMobile').textContent = area.name;

            // Load shops
            loadShops(area.id, 'Mobile');

            // Disable continue button until shop is selected
            document.getElementById('continueBtnMobile').disabled = true;
        }

        // Update UI for desktop
        if (version === 'Desktop') {
            document.querySelectorAll('#areaListDesktop .area-card').forEach(card => {
                card.classList.remove('active');
            });
            event.currentTarget.classList.add('active');

            // Update area name
            document.getElementById('selectedAreaNameDesktop').textContent = area.name;

            // Enable shop search
            document.getElementById('shopSearchDesktop').disabled = false;

            // Enable shop pagination
            document.getElementById('shopItemsPerPageDesktop').disabled = false;
            document.querySelectorAll('#shopPaginationDesktop button').forEach(btn => {
                btn.disabled = false;
            });

            // Load shops
            loadShops(area.id, 'Desktop');

            // Disable continue button until shop is selected
            document.getElementById('continueBtnDesktop').disabled = true;
        }
    }

    function goBackToAreas() {
        // For mobile view only
        document.getElementById('areaSectionMobile').classList.remove('hidden');
        document.getElementById('shopSectionMobile').classList.add('hidden');
        selectedArea = null;
        selectedShop = null;

        // Reset continue button
        document.getElementById('continueBtnMobile').disabled = true;
        document.getElementById('continueBtnMobile').innerHTML = `
            <iconify-icon icon="lucide:arrow-right" width="18"></iconify-icon>
            Continue to Products
        `;

        // Reset selection
        document.querySelectorAll('#areaListMobile .area-card').forEach(card => {
            card.classList.remove('active');
        });
    }

    function loadShops(areaId, version) {
        const shopList = document.getElementById(`shopList${version}`);
        const shopEmptyState = document.getElementById(`shopEmptyState${version}`);
        const shopCountElement = document.getElementById(`shopCount${version}`);
        const paginationContainer = document.getElementById(`shopPagination${version}`);
        const pageInfo = document.getElementById(`shopPageInfo${version}`);

        if (!shopList) return;

        // Filter shops by area
        const area = dummyData.areas.find(a => a.id === areaId);
        const areaName = area ? area.name : '';
        let filteredShops = dummyData.shops.filter(shop => shop.area_id === areaId);

        // Apply search filter
        const searchQuery = state[version].shops.searchQuery.toLowerCase();
        if (searchQuery) {
            filteredShops = filteredShops.filter(shop =>
                (shop.name || '').toLowerCase().includes(searchQuery) ||
                (shop.owner || '').toLowerCase().includes(searchQuery) ||
                (shop.phone || '').includes(searchQuery)
            );
        }

        // Update state
        state[version].shops.filteredData = filteredShops;
        state[version].shops.totalPages = Math.ceil(filteredShops.length / state[version].shops.itemsPerPage);

        // Ensure current page is valid
        if (state[version].shops.currentPage > state[version].shops.totalPages) {
            state[version].shops.currentPage = Math.max(1, state[version].shops.totalPages);
        }

        // Calculate pagination
        const startIndex = (state[version].shops.currentPage - 1) * state[version].shops.itemsPerPage;
        const endIndex = startIndex + state[version].shops.itemsPerPage;
        const currentShops = filteredShops.slice(startIndex, endIndex);

        // Clear container
        shopList.innerHTML = '';

        // Update count
        shopCountElement.textContent = `${filteredShops.length} shops`;

        if (filteredShops.length === 0) {
            shopList.classList.add('hidden');
            if (shopEmptyState) {
                shopEmptyState.classList.remove('hidden');
                shopEmptyState.querySelector('p').textContent = searchQuery ?
                    'No shops found matching your search' :
                    'No shops in this area';
            }
            if (paginationContainer) paginationContainer.classList.add('hidden');
            return;
        }

        shopList.classList.remove('hidden');
        if (shopEmptyState) shopEmptyState.classList.add('hidden');
        if (paginationContainer) paginationContainer.classList.remove('hidden');

        // Render shops
        currentShops.forEach(shop => {
            const shopCard = document.createElement('div');
            shopCard.className = 'shop-card p-3 sm:p-4 rounded-xl border border-slate-200 bg-white transition-all cursor-pointer';
            shopCard.onclick = () => selectShop(shop, version);

            shopCard.innerHTML = `
                <div class="flex items-start gap-3 sm:gap-4">
                    <div class="h-11 w-11 sm:h-12 sm:w-12 lg:h-14 lg:w-14 rounded-lg bg-slate-50 flex items-center justify-center border border-slate-200 flex-shrink-0">
                        <iconify-icon icon="lucide:store" width="${version === 'Mobile' ? '20' : '22'}" class="text-slate-600"></iconify-icon>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm sm:text-base font-semibold text-slate-900 truncate">${shop.name}</h4>
                                <p class="text-xs sm:text-sm text-slate-500 truncate">${shop.owner}</p>
                            </div>
                            <span class="px-2 sm:px-3 py-1 sm:py-1.5 rounded-full text-xs sm:text-sm font-medium ${shop.status === 'active' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-slate-100 text-slate-600 border border-slate-200'} flex-shrink-0">
                                ${shop.status === 'active' ? 'Active' : 'Inactive'}
                            </span>
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-3 sm:gap-4 mt-2 sm:mt-3">
                            <div class="flex items-center gap-1 sm:gap-2 text-xs sm:text-sm text-slate-600">
                                <iconify-icon icon="lucide:phone" width="${version === 'Mobile' ? '12' : '14'}" class="text-slate-400 flex-shrink-0"></iconify-icon>
                                <span class="truncate">${shop.phone}</span>
                            </div>
                            <div class="flex items-center gap-1 sm:gap-2 text-xs sm:text-sm text-slate-600">
                                ${shop.orders_count || 0} orders
                            </div>
                        </div>
                    </div>
                    <iconify-icon icon="lucide:check-circle" width="${version === 'Mobile' ? '20' : '22'}" class="text-slate-300 flex-shrink-0"></iconify-icon>
                </div>
            `;

            shopList.appendChild(shopCard);
        });

        // Update pagination controls
        updatePaginationControls('shops', version);
    }

    function filterShops(version) {
        const searchInput = document.getElementById(`shopSearch${version}`);
        state[version].shops.searchQuery = searchInput.value;
        state[version].shops.currentPage = 1;
        loadShops(selectedArea.id, version);
    }

    function changeShopPage(version, direction) {
        const newPage = state[version].shops.currentPage + direction;

        if (newPage >= 1 && newPage <= state[version].shops.totalPages) {
            state[version].shops.currentPage = newPage;
            loadShops(selectedArea.id, version);
        }
    }

    function changeShopItemsPerPage(version) {
        const select = document.getElementById(`shopItemsPerPage${version}`);
        state[version].shops.itemsPerPage = parseInt(select.value);
        state[version].shops.currentPage = 1;
        loadShops(selectedArea.id, version);
    }

    function updatePaginationControls(type, version) {
        const stateObj = state[version][type];
        const pageInfo = document.getElementById(`${type}PageInfo${version}`);
        const prevBtn = document.querySelector(`#${type}Pagination${version} button:first-child`);
        const nextBtn = document.querySelector(`#${type}Pagination${version} button:last-child`);

        if (pageInfo) {
            pageInfo.textContent = `Page ${stateObj.currentPage} of ${stateObj.totalPages}`;
        }

        if (prevBtn) {
            prevBtn.disabled = stateObj.currentPage === 1;
        }

        if (nextBtn) {
            nextBtn.disabled = stateObj.currentPage === stateObj.totalPages;
        }
    }

    function selectShop(shop, version) {
        selectedShop = shop;

        // Update UI for mobile
        if (version === 'Mobile') {
            document.querySelectorAll('#shopListMobile .shop-card').forEach(card => {
                card.classList.remove('selected');
                const icon = card.querySelector('.text-slate-300, .text-indigo-600');
                if (icon) {
                    icon.classList.add('text-slate-300');
                    icon.classList.remove('text-indigo-600');
                }
            });

            event.currentTarget.classList.add('selected');
            const icon = event.currentTarget.querySelector('.text-slate-300');
            if (icon) {
                icon.classList.remove('text-slate-300');
                icon.classList.add('text-indigo-600');
            }

            // Enable continue button
            document.getElementById('continueBtnMobile').disabled = false;

            // Update button text
            const btn = document.getElementById('continueBtnMobile');
            btn.innerHTML = `<iconify-icon icon="lucide:arrow-right" width="18"></iconify-icon> <span class="truncate">Continue with ${shop.name}</span>`;
        }

        // Update UI for desktop
        if (version === 'Desktop') {
            document.querySelectorAll('#shopListDesktop .shop-card').forEach(card => {
                card.classList.remove('selected');
                const icon = card.querySelector('.text-slate-300, .text-indigo-600');
                if (icon) {
                    icon.classList.add('text-slate-300');
                    icon.classList.remove('text-indigo-600');
                }
            });

            event.currentTarget.classList.add('selected');
            const icon = event.currentTarget.querySelector('.text-slate-300');
            if (icon) {
                icon.classList.remove('text-slate-300');
                icon.classList.add('text-indigo-600');
            }

            // Enable continue button
            document.getElementById('continueBtnDesktop').disabled = false;

            // Update button text
            const btn = document.getElementById('continueBtnDesktop');
            btn.innerHTML = `<iconify-icon icon="lucide:arrow-right" width="18"></iconify-icon> Continue with ${shop.name}`;
        }
    }

    function proceedToProducts() {
        if (selectedArea && selectedShop) {
            // Navigate to create order page with shop_id
            window.location.href = `{{ route('salesperson.orders.create') }}?shop_id=${selectedShop.id}`;
        }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', initializePage);
</script>
@endsection