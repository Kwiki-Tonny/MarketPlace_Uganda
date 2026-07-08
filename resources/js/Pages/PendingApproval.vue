<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const user = usePage().props.auth.user;
</script>

<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
        <Head title="Account Pending Approval" />

        <div class="max-w-md w-full bg-white rounded-lg shadow-xl overflow-hidden">
            <!-- Header with brand gradient -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-8 text-center">
                <h1 class="text-2xl font-bold text-white">MarketPlace Uganda</h1>
                <p class="text-blue-100 text-sm mt-1">Empowering Young Entrepreneurs</p>
            </div>

            <!-- Content -->
            <div class="p-8 text-center">
                <!-- Checkmark icon -->
                <div class="mx-auto w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 mb-2">Thanks for Registering!</h2>
                <p class="text-gray-600 mb-6">
                    Your account is pending approval by an administrator.<br />
                    You'll receive an email once your account is activated.
                </p>

                <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 text-sm text-blue-800 mb-6">
                    <strong>Next steps:</strong> Check your email for updates.
                </div>

                <!-- Logout button -->
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200"
                >
                    Logout
                </Link>
            </div>
        </div>
    </div>
</template>