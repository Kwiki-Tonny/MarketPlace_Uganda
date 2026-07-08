<script setup>
import VendorLayout from '@/Layouts/VendorLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { Search, Download, ShoppingCart, FileText } from 'lucide-vue-next';
import { ref } from 'vue';

// ✅ products is now a paginated object (data, links, from, to, total, etc.)
const props = defineProps({
    products: Object,
    categories: Array,
    filters: Object,
});

const search = ref(props.filters.search || '');
const category = ref(props.filters.category || '');

const applyFilters = () => {
    router.get('/vendor/products', {
        search: search.value || undefined,
        category: category.value || undefined,
    }, { preserveState: true });
};

const clearFilters = () => {
    search.value = '';
    category.value = '';
    applyFilters();
};

// Order modal state
const orderModal = ref({
    show: false,
    productId: null,
    productName: '',
    quantity: 1,
});

const openOrderModal = (product) => {
    orderModal.value.productId = product.id;
    orderModal.value.productName = product.name;
    orderModal.value.quantity = 1;
    orderModal.value.show = true;
};

const submitOrder = () => {
    router.post(route('vendor.orders.store'), {
        product_id: orderModal.value.productId,
        quantity: orderModal.value.quantity,
    }, {
        onSuccess: () => {
            orderModal.value.show = false;
            // Optionally show a success toast
        },
        onError: (errors) => {
            console.error(errors);
        }
    });
};
</script>

<template>
    <VendorLayout>
        <!-- ===== HEADER (static, outside scroll) ===== -->
        <template #page-header>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex justify-between items-center flex-wrap gap-4 mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Product Catalog</h1>
                        <p class="text-gray-600">Browse available products and download price list</p>
                    </div>
                    <div class="flex gap-2">
                        <a
                            :href="route('vendor.price-list.download-pdf')"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center gap-2"
                        >
                            <FileText class="w-5 h-5" />
                            PDF
                        </a>
                        <a
                            :href="route('vendor.price-list.download')"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2"
                        >
                            <Download class="w-5 h-5" />
                            Excel
                        </a>
                    </div>
                </div>

                <!-- Filters -->
                <div class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700">Search</label>
                        <div class="relative">
                            <Search class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" />
                            <input
                                type="text"
                                v-model="search"
                                @keyup.enter="applyFilters"
                                placeholder="Search by name or code..."
                                class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            />
                        </div>
                    </div>
                    <div class="min-w-[180px]">
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <select
                            v-model="category"
                            @change="applyFilters"
                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="">All Categories</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                {{ cat.name }}
                            </option>
                        </select>
                    </div>
                    <button @click="clearFilters" class="text-sm text-gray-500 hover:text-gray-700">
                        Clear Filters
                    </button>
                </div>
            </div>
        </template>

        <!-- ===== PRODUCT GRID (scrollable) ===== -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- ✅ Iterate over products.data -->
            <div v-for="product in products.data" :key="product.id" class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                <div class="h-48 bg-gray-100 flex items-center justify-center">
                    <img
                        v-if="product.images && product.images[0]"
                        :src="product.images[0]"
                        :alt="product.name"
                        class="h-full w-full object-cover"
                    />
                    <div v-else class="text-gray-400 text-sm">No image</div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800 truncate">{{ product.name }}</h3>
                    <p class="text-sm text-gray-500">{{ product.code || '-' }}</p>
                    <p class="text-sm text-gray-500">{{ product.category?.name || 'Uncategorized' }}</p>
                    <p class="text-lg font-bold text-blue-600 mt-2">UGX {{ product.price.toLocaleString() }}</p>
                    <button @click="openOrderModal(product)" class="mt-3 w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg flex items-center justify-center gap-2">
                        <ShoppingCart class="w-4 h-4" />
                        Order Now
                    </button>
                </div>
            </div>
            <!-- ✅ Check for empty data -->
            <div v-if="!products.data || products.data.length === 0" class="col-span-full text-center text-gray-500 py-12">
                No products match your filters.
            </div>
        </div>

        <!-- ✅ Pagination Links -->
        <div class="mt-6 flex flex-wrap justify-between items-center gap-2">
            <div class="text-sm text-gray-500">
                Showing {{ products.from || 0 }} to {{ products.to || 0 }} of {{ products.total || 0 }} products
            </div>
            <div class="flex gap-1">
                <Link
                    v-for="link in products.links"
                    :key="link.label"
                    :href="link.url || '#'"
                    v-html="link.label"
                    class="px-3 py-1 rounded border hover:bg-gray-100 text-sm"
                    :class="{
                        'bg-blue-600 text-white border-blue-600': link.active,
                        'opacity-50 cursor-not-allowed pointer-events-none': !link.url
                    }"
                    preserve-scroll
                />
            </div>
        </div>

        <!-- ===== ORDER MODAL ===== -->
        <div v-if="orderModal.show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <h3 class="text-lg font-bold mb-4">Order Product</h3>
                <p class="mb-2">Product: <strong>{{ orderModal.productName }}</strong></p>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input type="number" v-model.number="orderModal.quantity" min="1" class="w-full border rounded px-3 py-2" />
                </div>
                <div class="flex justify-end gap-2">
                    <button @click="orderModal.show = false" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                    <button @click="submitOrder" class="px-4 py-2 bg-blue-600 text-white rounded">Place Order</button>
                </div>
            </div>
        </div>
    </VendorLayout>
</template>