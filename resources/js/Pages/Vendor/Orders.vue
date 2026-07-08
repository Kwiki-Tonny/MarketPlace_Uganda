<script setup>
import { onMounted, onUnmounted, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import VendorLayout from '@/Layouts/VendorLayout.vue';
import { RefreshCw } from 'lucide-vue-next';
import axios from 'axios';

const props = defineProps({
    orders: {
        type: Array,
        default: () => [],
    },
});

const loading = ref(false);
let pollInterval = null;
let lastKnownTimestamp = ref(null);

// Fetch the last update timestamp from the server
const fetchLastUpdated = async () => {
    try {
        const response = await axios.get(route('vendor.orders.last-updated'));
        const newTimestamp = response.data.last_updated;
        if (lastKnownTimestamp.value && newTimestamp && newTimestamp !== lastKnownTimestamp.value) {
            // Timestamp changed → reload orders
            router.reload({ only: ['orders'], preserveState: true });
        }
        lastKnownTimestamp.value = newTimestamp;
    } catch (error) {
        // Silent fail
    }
};

// Manual refresh
const refreshOrders = () => {
    loading.value = true;
    router.reload({
        only: ['orders'],
        onFinish: () => { loading.value = false; }
    });
};

// Start polling every 5 seconds
const startPolling = () => {
    // Initial fetch to set the baseline timestamp
    fetchLastUpdated();
    pollInterval = setInterval(fetchLastUpdated, 5000); // 5 seconds
};

const stopPolling = () => {
    if (pollInterval) {
        clearInterval(pollInterval);
        pollInterval = null;
    }
};

onMounted(() => {
    startPolling();
});

onUnmounted(() => {
    stopPolling();
});

// Pause polling when tab is hidden (optional)
document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        stopPolling();
    } else {
        startPolling();
    }
});
</script>

<template>
    <VendorLayout>
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">My Orders</h1>
                <p class="text-gray-600">View your order history and status</p>
            </div>
            <button 
                @click="refreshOrders" 
                :disabled="loading"
                class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg disabled:opacity-50"
            >
                <RefreshCw class="w-5 h-5" :class="{ 'animate-spin': loading }" />
                {{ loading ? 'Refreshing...' : 'Refresh' }}
            </button>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ordered At</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="order in orders" :key="order.id">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ order.product.name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ order.quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">UGX {{ order.total_price.toLocaleString() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span :class="{
                                'bg-yellow-100 text-yellow-800': order.status === 'pending',
                                'bg-blue-100 text-blue-800': order.status === 'processing',
                                'bg-green-100 text-green-800': order.status === 'completed',
                                'bg-red-100 text-red-800': order.status === 'cancelled',
                            }" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                {{ order.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ new Date(order.created_at).toLocaleString() }}</td>
                    </tr>
                    <tr v-if="!orders || orders.length === 0">
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                            No orders yet. Place your first order from the product catalog.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </VendorLayout>
</template>