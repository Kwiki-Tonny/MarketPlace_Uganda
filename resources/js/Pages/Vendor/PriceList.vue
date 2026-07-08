<script setup>
import VendorLayout from '@/Layouts/VendorLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { Search, Download, FileText } from 'lucide-vue-next';
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
    router.get('/vendor/price-list', {
        search: search.value || undefined,
        category: category.value || undefined,
    }, { preserveState: true });
};

const clearFilters = () => {
    search.value = '';
    category.value = '';
    applyFilters();
};
</script>

<template>
    <VendorLayout>
        <!-- ===== HEADER (static, outside scroll) ===== -->
        <template #page-header>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex justify-between items-center flex-wrap gap-4 mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Price List</h1>
                        <p class="text-gray-600">Download the complete product catalog with prices</p>
                    </div>
                    <div class="flex gap-2">
                        <!-- PDF Download -->
                        <a
                            :href="route('vendor.price-list.download-pdf')"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center gap-2"
                        >
                            <FileText class="w-5 h-5" />
                            PDF
                        </a>
                        <!-- Excel Download -->
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

        <!-- ===== PRICE LIST TABLE (scrollable) ===== -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price (UGX)</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- ✅ Iterate over products.data -->
                    <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img
                                v-if="product.images && product.images[0]"
                                :src="product.images[0]"
                                :alt="product.name"
                                class="h-10 w-10 object-cover rounded"
                            />
                            <div v-else class="h-10 w-10 bg-gray-200 rounded"></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ product.code || '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ product.name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ product.category?.name || 'Uncategorized' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">UGX {{ product.price.toLocaleString() }}</td>
                    </tr>
                    <!-- ✅ Check for empty data -->
                    <tr v-if="!products.data || products.data.length === 0">
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No products found.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- ✅ Pagination Links (corrected) -->
        <div class="mt-4 flex flex-wrap justify-between items-center gap-2">
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
    </VendorLayout>
</template>