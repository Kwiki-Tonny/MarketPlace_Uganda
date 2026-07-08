<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { router } from '@inertiajs/vue3';

defineProps({
    orders: Array,
});

const updateStatus = (orderId, status) => {
    router.put(`/admin/orders/${orderId}/status`, { status });
};
</script>

<template>
    <AdminLayout>
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Orders</h1>
            <p class="text-gray-600">Manage all vendor orders</p>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="order in orders" :key="order.id">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ order.vendor.name }}</td>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <select @change="updateStatus(order.id, $event.target.value)" class="border rounded px-2 py-1">
                                <option value="pending" :selected="order.status === 'pending'">Pending</option>
                                <option value="processing" :selected="order.status === 'processing'">Processing</option>
                                <option value="completed" :selected="order.status === 'completed'">Completed</option>
                                <option value="cancelled" :selected="order.status === 'cancelled'">Cancelled</option>
                            </select>
                        </td>
                    </tr>
                    <tr v-if="orders.length === 0">
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No orders yet.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>