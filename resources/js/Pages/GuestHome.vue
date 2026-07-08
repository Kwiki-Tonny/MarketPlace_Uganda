<script setup>
import { Link, usePage } from '@inertiajs/vue3';

const user = usePage().props.auth.user;
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Hero Section -->
        <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white">
            <div class="container mx-auto px-4 py-20 text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6">Market Place Uganda</h1>
                <p class="text-xl md:text-2xl mb-8 max-w-2xl mx-auto">
                    Empowering young entrepreneurs to start and grow online businesses with quality products at affordable prices.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <!-- Show Login/Register when logged out -->
                    <template v-if="!user">
                        <Link
                            :href="route('register')"
                            class="px-8 py-3 bg-white text-blue-700 font-semibold rounded-lg hover:bg-gray-100 transition duration-200"
                        >
                            Get Started
                        </Link>
                        <Link
                            :href="route('login')"
                            class="px-8 py-3 bg-transparent border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-blue-700 transition duration-200"
                        >
                            Login
                        </Link>
                    </template>

                    <!-- Show Dashboard when logged in -->
                    <template v-else>
                        <!-- For admin -->
                        <Link
                            v-if="user.role === 'admin'"
                            :href="route('admin.dashboard')"
                            class="px-8 py-3 bg-white text-blue-700 font-semibold rounded-lg hover:bg-gray-100 transition duration-200"
                        >
                            Go to Dashboard
                        </Link>
                        <!-- For approved vendor -->
                        <Link
                            v-else-if="user.role === 'vendor' && user.is_approved"
                            :href="route('vendor.dashboard')"
                            class="px-8 py-3 bg-white text-blue-700 font-semibold rounded-lg hover:bg-gray-100 transition duration-200"
                        >
                            Go to Dashboard
                        </Link>
                        <!-- For pending vendor -->
                        <Link
                            v-else-if="user.role === 'vendor' && !user.is_approved"
                            :href="route('pending.approval')"
                            class="px-8 py-3 bg-white text-blue-700 font-semibold rounded-lg hover:bg-gray-100 transition duration-200"
                        >
                            Check Approval Status
                        </Link>

                        <!-- Logout button (using POST method) -->
                        <form method="POST" :action="route('logout')" class="inline">
                            <input type="hidden" name="_token" :value="$page.props.csrf_token" />
                            <button type="submit" class="px-8 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition duration-200">
                                Logout
                            </button>
                        </form>
                    </template>
                </div>
            </div>
        </div>

        <!-- Features Section (unchanged) -->
        <div class="container mx-auto px-4 py-16">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <div class="text-4xl mb-4">🛍️</div>
                    <h3 class="text-xl font-bold mb-2">Quality Products</h3>
                    <p class="text-gray-600">Access a wide range of electronics, household items, and accessories.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <div class="text-4xl mb-4">📈</div>
                    <h3 class="text-xl font-bold mb-2">Grow Your Business</h3>
                    <p class="text-gray-600">Sell at your own prices, earn profits, and track your performance.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <div class="text-4xl mb-4">🤝</div>
                    <h3 class="text-xl font-bold mb-2">Support & Community</h3>
                    <p class="text-gray-600">Join a network of entrepreneurs and get 24/7 support from our team.</p>
                </div>
            </div>
        </div>

        <!-- Call to Action (only show when logged out) -->
        <div v-if="!user" class="bg-blue-600 text-white py-16">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-3xl font-bold mb-4">Ready to start your journey?</h2>
                <p class="text-lg mb-8">Join thousands of entrepreneurs already selling on Market Place Uganda.</p>
                <Link
                    :href="route('register')"
                    class="inline-block px-8 py-3 bg-white text-blue-700 font-semibold rounded-lg hover:bg-gray-100 transition duration-200"
                >
                    Register Now
                </Link>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8">
            <div class="container mx-auto px-4 text-center">
                <p>&copy; 2026 Market Place Uganda. All rights reserved.</p>
            </div>
        </footer>
    </div>
</template>