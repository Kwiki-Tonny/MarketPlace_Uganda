<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';
import { Users, Package, ShoppingBag, Award, TrendingUp, CheckCircle, Clock } from 'lucide-vue-next';

defineProps({
    stats: Object,
    vendors: Array,
    categoryCounts: Array,
    ranking: Array,
});
</script>

<template>
    <AdminLayout>
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-600">Overview of your marketplace activity</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Vendors</p>
                        <p class="text-2xl font-bold text-gray-800">{{ stats.totalVendors }}</p>
                    </div>
                    <Users class="w-8 h-8 text-blue-500" />
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Pending Vendors</p>
                        <p class="text-2xl font-bold text-gray-800">{{ stats.pendingVendors }}</p>
                    </div>
                    <Clock class="w-8 h-8 text-yellow-500" />
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Products</p>
                        <p class="text-2xl font-bold text-gray-800">{{ stats.totalProducts }}</p>
                    </div>
                    <Package class="w-8 h-8 text-green-500" />
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Categories</p>
                        <p class="text-2xl font-bold text-gray-800">{{ stats.totalCategories }}</p>
                    </div>
                    <Award class="w-8 h-8 text-purple-500" />
                </div>
            </div>
        </div>

        <!-- Two-column: Vendor Analytics & Product Categories -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Vendor Analytics -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Recent Vendors</h3>
                    <Link href="/admin/vendors" class="text-sm text-blue-600 hover:underline">View All</Link>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="vendor in vendors.slice(0, 5)" :key="vendor.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ vendor.name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ vendor.email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span v-if="vendor.status === 'Approved'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Approved
                                    </span>
                                    <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ vendor.registered_at }}</td>
                            </tr>
                            <tr v-if="vendors.length === 0">
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No vendors registered yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Product Categories -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Products per Category</h3>
                </div>
                <div class="p-6">
                    <div v-if="categoryCounts.length === 0" class="text-sm text-gray-500">
                        No categories or products yet.
                    </div>
                    <div v-else>
                        <div v-for="category in categoryCounts" :key="category.id" class="flex justify-between items-center border-b py-2 last:border-0">
                            <span class="text-sm text-gray-700">{{ category.name }}</span>
                            <span class="text-sm font-semibold text-gray-900">{{ category.products_count }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ranking Section (Top Vendors) -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Vendor Ranking</h3>
                <p class="text-xs text-gray-500">Based on registration date (for now)</p>
            </div>
            <div class="p-6">
                <div v-if="ranking.length === 0" class="text-sm text-gray-500">
                    No vendors to rank.
                </div>
                <div v-else>
                    <div v-for="vendor in ranking" :key="vendor.rank" class="flex items-center justify-between py-2 border-b last:border-0">
                        <div class="flex items-center">
                            <span class="w-6 text-sm font-bold text-gray-400">#{{ vendor.rank }}</span>
                            <span class="ml-4 text-sm font-medium text-gray-900">{{ vendor.name }}</span>
                            <span class="ml-4 text-xs text-gray-500">{{ vendor.email }}</span>
                        </div>
                        <span v-if="vendor.status === 'Approved'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Approved
                        </span>
                        <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>