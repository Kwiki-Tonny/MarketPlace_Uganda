<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { Plus, Edit, Trash2 } from 'lucide-vue-next';

// ✅ products is now a paginated object (has data, links, from, to, total, etc.)
defineProps({
    products: Object,
});

const deleteProduct = (id) => {
    if (confirm('Are you sure you want to delete this product?')) {
        router.delete('/admin/products/' + id);
    }
};
</script>

<template>
    <AdminLayout>
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Products</h1>
                <p class="text-gray-600">Manage your product catalog</p>
            </div>
            <Link href="/admin/products/create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <Plus class="w-5 h-5 mr-2" />
                Add Product
            </Link>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- ✅ Iterate over products.data -->
                    <tr v-for="product in products.data" :key="product.id">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ product.name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ product.code || '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ product.category?.name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">UGX {{ product.price.toLocaleString() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span v-if="product.is_active" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                            <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Inactive
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            <Link :href="'/admin/products/' + product.id + '/edit'" class="text-blue-600 hover:text-blue-800">
                                <Edit class="w-5 h-5 inline" />
                            </Link>
                            <button @click="deleteProduct(product.id)" class="text-red-600 hover:text-red-800">
                                <Trash2 class="w-5 h-5 inline" />
                            </button>
                        </td>
                    </tr>
                    <tr v-if="!products.data || products.data.length === 0">
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No products found. Add one!</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- ✅ Pagination -->
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
    </AdminLayout>
</template>