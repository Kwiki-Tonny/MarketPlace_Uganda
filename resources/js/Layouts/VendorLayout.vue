<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { 
    LayoutDashboard, 
    Package, 
    ShoppingBag, 
    FileText, 
    TrendingUp, 
    Settings,
    Menu,
    X,
    LogOut,
    UserCircle
} from 'lucide-vue-next';
import { ref } from 'vue';
import Toast from '@/Components/Toast.vue'; // 👈 Import Toast

const user = usePage().props.auth.user;
const currentUrl = usePage().url;
const isMobileMenuOpen = ref(false);

const isActive = (url) => {
    return currentUrl === url || currentUrl.startsWith(url + '/');
};

const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
};
</script>

<template>
    <div class="min-h-screen bg-gray-100 flex">
        <!-- ===== TOAST NOTIFICATION ===== -->
        <Toast />  <!-- 👈 Toast component rendered here -->

        <!-- Sidebar (desktop) -->
        <aside class="w-64 bg-gradient-to-b from-blue-800 to-blue-900 text-white flex-shrink-0 h-screen sticky top-0 overflow-y-auto hidden md:block">
            <div class="p-6">
                <Link href="/" class="text-2xl font-bold tracking-tight">
                    MarketPlace
                    <span class="text-blue-300">Uganda</span>
                </Link>
                <p class="text-xs text-blue-300 mt-1">Vendor Panel</p>
            </div>
            <nav class="mt-8">
                <Link 
                    href="/vendor/dashboard" 
                    class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                    :class="{ 'bg-blue-700 text-white border-r-4 border-white': isActive('/vendor/dashboard') }"
                >
                    <LayoutDashboard class="w-5 h-5 mr-3" />
                    Dashboard
                </Link>
                <Link 
                    href="/vendor/products" 
                    class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                    :class="{ 'bg-blue-700 text-white border-r-4 border-white': isActive('/vendor/products') }"
                >
                    <Package class="w-5 h-5 mr-3" />
                    Products
                </Link>
                <Link 
                    href="/vendor/orders" 
                    class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                    :class="{ 'bg-blue-700 text-white border-r-4 border-white': isActive('/vendor/orders') }"
                >
                    <ShoppingBag class="w-5 h-5 mr-3" />
                    Orders
                </Link>
                <Link 
                    href="/vendor/price-list" 
                    class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                    :class="{ 'bg-blue-700 text-white border-r-4 border-white': isActive('/vendor/price-list') }"
                >
                    <FileText class="w-5 h-5 mr-3" />
                    Price List
                </Link>
                <Link 
                    href="/vendor/rankings" 
                    class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                    :class="{ 'bg-blue-700 text-white border-r-4 border-white': isActive('/vendor/rankings') }"
                >
                    <TrendingUp class="w-5 h-5 mr-3" />
                    Rankings
                </Link>
                <Link 
                    href="/vendor/settings" 
                    class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                    :class="{ 'bg-blue-700 text-white border-r-4 border-white': isActive('/vendor/settings') }"
                >
                    <Settings class="w-5 h-5 mr-3" />
                    Settings
                </Link>
            </nav>
            <div class="absolute bottom-0 w-64 p-6 border-t border-blue-700">
                <form method="POST" :action="route('logout')">
                    <input type="hidden" name="_token" :value="$page.props.csrf_token" />
                    <button type="submit" class="flex items-center text-blue-200 hover:text-white w-full">
                        <LogOut class="w-5 h-5 mr-3" />
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- ===== RIGHT PANEL (fixed height, no scroll) ===== -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            <!-- Mobile Top Bar -->
            <header class="bg-white shadow-sm flex items-center justify-between p-4 md:hidden">
                <button @click="toggleMobileMenu" class="text-gray-700">
                    <Menu v-if="!isMobileMenuOpen" class="w-6 h-6" />
                    <X v-else class="w-6 h-6" />
                </button>
                <Link href="/" class="text-xl font-bold text-blue-700">
                    MarketPlace Uganda
                </Link>
                <div class="flex items-center space-x-3">
                    <span class="text-sm text-gray-600 hidden sm:inline">{{ user?.name }}</span>
                    <UserCircle class="w-8 h-8 text-gray-400" />
                </div>
            </header>

            <!-- Mobile Sidebar (overlay) -->
            <div 
                v-if="isMobileMenuOpen" 
                class="fixed inset-0 bg-gray-800 bg-opacity-50 z-40 md:hidden"
                @click="toggleMobileMenu"
            ></div>
            <aside 
                v-if="isMobileMenuOpen" 
                class="fixed top-0 left-0 w-64 h-full bg-gradient-to-b from-blue-800 to-blue-900 text-white z-50 md:hidden overflow-y-auto"
            >
                <div class="p-6">
                    <Link href="/" class="text-2xl font-bold tracking-tight">
                        MarketPlace
                        <span class="text-blue-300">Uganda</span>
                    </Link>
                    <p class="text-xs text-blue-300 mt-1">Vendor Panel</p>
                </div>
                <nav class="mt-8">
                    <Link 
                        href="/vendor/dashboard" 
                        class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                        :class="{ 'bg-blue-700 text-white border-r-4 border-white': isActive('/vendor/dashboard') }"
                        @click="toggleMobileMenu"
                    >
                        <LayoutDashboard class="w-5 h-5 mr-3" />
                        Dashboard
                    </Link>
                    <Link 
                        href="/vendor/products" 
                        class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                        @click="toggleMobileMenu"
                    >
                        <Package class="w-5 h-5 mr-3" />
                        Products
                    </Link>
                    <Link 
                        href="/vendor/orders" 
                        class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                        @click="toggleMobileMenu"
                    >
                        <ShoppingBag class="w-5 h-5 mr-3" />
                        Orders
                    </Link>
                    <Link 
                        href="/vendor/price-list" 
                        class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                        @click="toggleMobileMenu"
                    >
                        <FileText class="w-5 h-5 mr-3" />
                        Price List
                    </Link>
                    <Link 
                        href="/vendor/rankings" 
                        class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                        @click="toggleMobileMenu"
                    >
                        <TrendingUp class="w-5 h-5 mr-3" />
                        Rankings
                    </Link>
                    <Link 
                        :href="route('vendor.settings')"
                        class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                        @click="toggleMobileMenu"
                    >
                        <Settings class="w-5 h-5 mr-3" />
                        Settings
                    </Link>
                </nav>
                <div class="absolute bottom-0 w-64 p-6 border-t border-blue-700">
                    <form method="POST" :action="route('logout')">
                        <input type="hidden" name="_token" :value="$page.props.csrf_token" />
                        <button type="submit" class="flex items-center text-blue-200 hover:text-white w-full">
                            <LogOut class="w-5 h-5 mr-3" />
                            Logout
                        </button>
                    </form>
                </div>
            </aside>

            <!-- ===== STATIC PAGE HEADER (outside scroll) ===== -->
            <div class="bg-gray-100 p-4 md:p-8 pt-4 md:pt-6 flex-shrink-0">
                <slot name="page-header" />
            </div>

            <!-- ===== SCROLLABLE CONTENT ===== -->
            <main class="flex-1 overflow-y-auto p-4 md:p-8 pt-0">
                <slot />
            </main>
        </div>
    </div>
</template>