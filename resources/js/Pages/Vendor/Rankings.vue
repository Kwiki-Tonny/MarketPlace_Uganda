<script setup>
import VendorLayout from '@/Layouts/VendorLayout.vue';
import { Trophy } from 'lucide-vue-next';

const props = defineProps({
    myRank: {
        type: Object,
        default: null,
    },
    topVendors: {
        type: Array,
        default: () => [],
    },
    totalVendors: {
        type: Number,
        default: 0,
    },
});
</script>

<template>
    <VendorLayout>
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Vendor Rankings</h1>
            <p class="text-gray-600">See how you compare with other vendors</p>
        </div>

        <!-- Your Rank -->
        <div v-if="myRank" class="bg-gradient-to-r from-blue-500 to-blue-700 rounded-lg shadow-lg p-6 mb-8 text-white">
            <div class="flex flex-wrap items-center justify-between">
                <div>
                    <p class="text-sm text-blue-100">Your Rank</p>
                    <p class="text-4xl font-bold flex items-center gap-2">
                        #{{ myRank.rank }}
                        <span v-if="myRank.rank === 1" class="text-3xl">👑</span>
                    </p>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                    <div>
                        <p class="text-sm text-blue-100">Orders</p>
                        <p class="text-xl font-bold">{{ myRank.total_orders }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-blue-100">Revenue</p>
                        <p class="text-xl font-bold">UGX {{ myRank.total_revenue?.toLocaleString() || 0 }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-blue-100">Items Sold</p>
                        <p class="text-xl font-bold">{{ myRank.total_quantity || 0 }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-blue-100">Avg Order</p>
                        <p class="text-xl font-bold">UGX {{ myRank.avg_order_value?.toLocaleString() || 0 }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8 text-yellow-800">
            You haven't completed any orders yet. Start selling to see your rank!
        </div>

        <!-- Top Vendors Leaderboard -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b flex items-center gap-2">
                <Trophy class="w-5 h-5 text-yellow-500" />
                <h3 class="text-lg font-semibold text-gray-800">Top Vendors</h3>
                <span class="ml-auto text-sm text-gray-500">Total: {{ totalVendors }} vendors</span>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Orders</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Revenue</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Avg Order</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="vendor in topVendors" :key="vendor.id"
                        :class="{ 'bg-blue-50': vendor.id === myRank?.id }">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-gray-900">#{{ vendor.rank }}</span>
                            <span v-if="vendor.rank === 1" class="ml-1">🥇</span>
                            <span v-else-if="vendor.rank === 2" class="ml-1">🥈</span>
                            <span v-else-if="vendor.rank === 3" class="ml-1">🥉</span>
                            <span v-if="vendor.id === myRank?.id" class="ml-2 text-xs bg-blue-200 text-blue-800 px-2 py-0.5 rounded-full">You</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ vendor.name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ vendor.total_orders }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">UGX {{ vendor.total_revenue?.toLocaleString() || 0 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">UGX {{ vendor.avg_order_value?.toLocaleString() || 0 }}</td>
                    </tr>
                    <tr v-if="topVendors.length === 0">
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No vendors have completed orders yet.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </VendorLayout>
</template>