<script setup>
import { Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const user = usePage().props.auth.user;
</script>

<template>
    <div>
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link href="/" class="font-bold text-xl">Market Place Uganda</Link>
                    </div>
                    <div class="flex items-center space-x-4">
                        <Link v-if="!user" :href="route('login')" class="text-gray-700 hover:text-gray-900">Login</Link>
                        <Link v-if="!user" :href="route('register')" class="text-gray-700 hover:text-gray-900">Register</Link>
                        <div v-if="user" class="flex items-center space-x-4">
                            <Link v-if="user.role === 'admin'" :href="route('admin.dashboard')" class="text-gray-700 hover:text-gray-900">Dashboard</Link>
                            <Link v-else-if="user.role === 'vendor' && user.is_approved" :href="route('vendor.dashboard')" class="text-gray-700 hover:text-gray-900">Dashboard</Link>
                            <Link v-else :href="route('pending.approval')" class="text-gray-700 hover:text-gray-900">Status</Link>
                            <!-- Logout using Inertia Link with POST method -->
                            <form method="POST" action="/logout" class="inline">
                                <input type="hidden" name="_token" :value="$page.props.csrf_token" />
                                <button type="submit" class="text-gray-700 hover:text-gray-900">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <header class="bg-white shadow" v-if="$slots.header">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <main>
            <slot />
        </main>
    </div>
</template>