<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <!-- Brand Header -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-6">
            <Link href="/" class="text-4xl font-bold text-blue-700">
                MarketPlace
                <span class="text-blue-500">Uganda</span>
            </Link>
            <p class="mt-1 text-sm text-gray-600">Empowering Young Entrepreneurs</p>
        </div>

        <!-- Card Container -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-xl sm:rounded-lg sm:px-10">
                <slot />
            </div>
        </div>
    </div>
</template>