<script setup>
import VendorLayout from '@/Layouts/VendorLayout.vue';
import { Link } from '@inertiajs/vue3';
import { TrendingUp, ShoppingBag, Package, Award, FileText, Trophy } from 'lucide-vue-next';

defineProps({
    vendor: Object,
});
</script>

<template>
    <VendorLayout>
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Vendor Dashboard</h1>
            <p class="text-gray-600">Welcome back, {{ vendor?.name || 'Vendor' }}!</p>
        </div>

        <!-- Stats Grid (4 cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Rank Card -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Your Rank</p>
                        <p class="text-2xl font-bold text-gray-800">
                            #{{ vendor?.rank ?? 'N/A' }}
                        </p>
                    </div>
                    <Trophy class="w-8 h-8 text-blue-500" />
                </div>
            </div>

            <!-- Total Orders -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Orders</p>
                        <p class="text-2xl font-bold text-gray-800">{{ vendor?.totalOrders || 0 }}</p>
                    </div>
                    <ShoppingBag class="w-8 h-8 text-green-500" />
                </div>
            </div>

            <!-- Monthly Sales -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Monthly Sales</p>
                        <p class="text-2xl font-bold text-gray-800">
                            UGX {{ vendor?.monthlySales?.toLocaleString() || '0' }}
                        </p>
                    </div>
                    <TrendingUp class="w-8 h-8 text-yellow-500" />
                </div>
            </div>

            <!-- Pending Orders -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Pending Orders</p>
                        <p class="text-2xl font-bold text-gray-800">{{ vendor?.pendingOrders || 0 }}</p>
                    </div>
                    <Package class="w-8 h-8 text-red-500" />
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
            <Link href="/vendor/products" class="bg-blue-50 hover:bg-blue-100 p-4 rounded-lg text-center transition">
                <Package class="w-8 h-8 mx-auto text-blue-600" />
                <p class="text-sm font-medium text-gray-700 mt-2">Browse Products</p>
            </Link>
            <Link href="/vendor/orders" class="bg-green-50 hover:bg-green-100 p-4 rounded-lg text-center transition">
                <ShoppingBag class="w-8 h-8 mx-auto text-green-600" />
                <p class="text-sm font-medium text-gray-700 mt-2">View Orders</p>
            </Link>
            <Link href="/vendor/price-list" class="bg-yellow-50 hover:bg-yellow-100 p-4 rounded-lg text-center transition">
                <FileText class="w-8 h-8 mx-auto text-yellow-600" />
                <p class="text-sm font-medium text-gray-700 mt-2">Price List</p>
            </Link>
            <Link href="/vendor/rankings" class="bg-purple-50 hover:bg-purple-100 p-4 rounded-lg text-center transition">
                <TrendingUp class="w-8 h-8 mx-auto text-purple-600" />
                <p class="text-sm font-medium text-gray-700 mt-2">View Rankings</p>
            </Link>
        </div>

        <!-- Recent Orders (placeholder) -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Recent Orders</h3>
                <Link href="/vendor/orders" class="text-sm text-blue-600 hover:underline">View All</Link>
            </div>
            <div class="p-6 text-gray-500 text-sm">
                <p>Your recent orders will appear here once you start selling.</p>
            </div>
        </div>
    </VendorLayout>
</template>