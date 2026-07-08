<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref } from 'vue';
import { Trophy, Users, TrendingUp, ShoppingBag, Package } from 'lucide-vue-next';

const props = defineProps({
    rankedVendors: Array,
    stats: Object,
});

const expandedVendor = ref(null);

const toggleExpand = (id) => {
    expandedVendor.value = expandedVendor.value === id ? null : id;
};

const getRankBadge = (rank) => {
    if (rank === 1) return '🥇';
    if (rank === 2) return '🥈';
    if (rank === 3) return '🥉';
    return `#${rank}`;
};
</script>

<template>
    <AdminLayout>
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Vendor Rankings</h1>
            <p class="text-gray-600">Top performing vendors based on revenue and orders</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Active Vendors</p>
                        <p class="text-2xl font-bold text-gray-800">{{ stats.total_vendors }}</p>
                    </div>
                    <Users class="w-8 h-8 text-blue-500" />
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Revenue</p>
                        <p class="text-2xl font-bold text-gray-800">UGX {{ stats.total_revenue?.toLocaleString() || 0 }}</p>
                    </div>
                    <TrendingUp class="w-8 h-8 text-green-500" />
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Orders</p>
                        <p class="text-2xl font-bold text-gray-800">{{ stats.total_orders }}</p>
                    </div>
                    <ShoppingBag class="w-8 h-8 text-purple-500" />
                </div>
            </div>
        </div>

        <!-- Leaderboard Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b flex items-center gap-2">
                <Trophy class="w-5 h-5 text-yellow-500" />
                <h3 class="text-lg font-semibold text-gray-800">🏆 Vendor Leaderboard</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items Sold</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Top Products</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="vendor in rankedVendors" :key="vendor.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">
                                <span :class="{ 'text-2xl': vendor.rank <= 3 }">
                                    {{ getRankBadge(vendor.rank) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ vendor.name }}</div>
                                <div class="text-xs text-gray-500">{{ vendor.email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ vendor.total_orders }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">UGX {{ vendor.total_revenue?.toLocaleString() || 0 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ vendor.total_quantity || 0 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">UGX {{ vendor.avg_order_value?.toLocaleString() || 0 }}</td>
                            <td class="px-6 py-4 text-sm">
                                <button @click="toggleExpand(vendor.id)" class="text-blue-600 hover:text-blue-800 flex items-center">
                                    <Package class="w-4 h-4 mr-1" />
                                    {{ expandedVendor === vendor.id ? 'Hide' : 'Show' }}
                                </button>
                                <div v-if="expandedVendor === vendor.id" class="mt-2 space-y-1">
                                    <div v-for="(product, idx) in vendor.top_products" :key="idx" class="text-xs text-gray-600 border-b py-1">
                                        <span class="font-medium">{{ product.product_name }}</span>
                                        <span class="ml-2">×{{ product.total_quantity }}</span>
                                        <span class="ml-2 text-green-600">UGX {{ product.total_revenue.toLocaleString() }}</span>
                                    </div>
                                    <div v-if="vendor.top_products.length === 0" class="text-xs text-gray-400">No products sold yet.</div>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="rankedVendors.length === 0">
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No vendors have completed orders yet.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>