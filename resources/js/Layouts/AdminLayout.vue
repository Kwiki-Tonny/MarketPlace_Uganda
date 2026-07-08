<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { 
    LayoutDashboard, 
    Users, 
    Package, 
    ShoppingBag, 
    Settings, 
    LogOut,
    UserCircle,
    Trophy,
    Menu,
    X,
    Bell
} from 'lucide-vue-next';
import { ref } from 'vue';
import Toast from '@/Components/Toast.vue';

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
        <Toast />
        <!-- Sidebar (desktop) - fixed -->
        <aside class="w-64 bg-gradient-to-b from-blue-800 to-blue-900 text-white flex-shrink-0 h-screen sticky top-0 overflow-y-auto hidden md:block">
            <div class="p-6">
                <Link href="/" class="text-2xl font-bold tracking-tight">
                    MarketPlace
                    <span class="text-blue-300">Uganda</span>
                </Link>
                <p class="text-xs text-blue-300 mt-1">Admin Panel</p>
            </div>
            <nav class="mt-8">
                <Link 
                    href="/admin/dashboard" 
                    class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                    :class="{ 'bg-blue-700 text-white border-r-4 border-white': isActive('/admin/dashboard') }"
                >
                    <LayoutDashboard class="w-5 h-5 mr-3" />
                    Dashboard
                </Link>
                <Link 
                    href="/admin/vendors" 
                    class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                    :class="{ 'bg-blue-700 text-white border-r-4 border-white': isActive('/admin/vendors') }"
                >
                    <Users class="w-5 h-5 mr-3" />
                    Vendors
                </Link>
                <Link 
                    href="/admin/notifications/create" 
                    class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                    :class="{ 'bg-blue-700 text-white border-r-4 border-white': isActive('/admin/notifications/create') }"
                >
                    <Bell class="w-5 h-5 mr-3" />
                    Notifications
                </Link>
                <Link 
                    href="/admin/products" 
                    class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                    :class="{ 'bg-blue-700 text-white border-r-4 border-white': isActive('/admin/products') }"
                >
                    <Package class="w-5 h-5 mr-3" />
                    Products
                </Link>
                <Link 
                    href="/admin/orders" 
                    class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                    :class="{ 'bg-blue-700 text-white border-r-4 border-white': isActive('/admin/orders') }"
                >
                    <ShoppingBag class="w-5 h-5 mr-3" />
                    Orders
                </Link>
                <Link 
                    href="/admin/rankings" 
                    class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                    :class="{ 'bg-blue-700 text-white border-r-4 border-white': isActive('/admin/rankings') }"
                >
                    <Trophy class="w-5 h-5 mr-3" />
                    Rankings
                </Link>
                <Link 
                    href="/admin/settings" 
                    class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                    :class="{ 'bg-blue-700 text-white border-r-4 border-white': isActive('/admin/settings') }"
                >
                    <Settings class="w-5 h-5 mr-3" />
                    Settings
                </Link>
                <Link 
                    href="/admin/system-settings" 
                    class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                    :class="{ 'bg-blue-700 text-white border-r-4 border-white': isActive('/admin/system-settings') }"
                >
                    <Settings class="w-5 h-5 mr-3" />
                    System Settings
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

        <!-- Main content wrapper with scroll -->
        <div class="flex-1 flex flex-col min-h-screen">
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

            <!-- Mobile Sidebar Overlay -->
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
                    <p class="text-xs text-blue-300 mt-1">Admin Panel</p>
                </div>
                <nav class="mt-8">
                    <Link 
                        href="/admin/dashboard" 
                        class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                        :class="{ 'bg-blue-700 text-white border-r-4 border-white': isActive('/admin/dashboard') }"
                        @click="toggleMobileMenu"
                    >
                        <LayoutDashboard class="w-5 h-5 mr-3" />
                        Dashboard
                    </Link>
                    <Link 
                        href="/admin/vendors" 
                        class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                        @click="toggleMobileMenu"
                    >
                        <Users class="w-5 h-5 mr-3" />
                        Vendors
                    </Link>
                    <Link 
                        href="/admin/notifications/create" 
                        class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                        :class="{ 'bg-blue-700 text-white border-r-4 border-white': isActive('/admin/notifications/create') }"
                    >
                        <Bell class="w-5 h-5 mr-3" />
                        Notifications
                    </Link>
                    <Link 
                        href="/admin/products" 
                        class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                        @click="toggleMobileMenu"
                    >
                        <Package class="w-5 h-5 mr-3" />
                        Products
                    </Link>
                    <Link 
                        href="/admin/orders" 
                        class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                        @click="toggleMobileMenu"
                    >
                        <ShoppingBag class="w-5 h-5 mr-3" />
                        Orders
                    </Link>
                    <Link 
                        href="/admin/rankings" 
                        class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                        :class="{ 'bg-blue-700 text-white border-r-4 border-white': isActive('/admin/rankings') }"
                    >
                        <Trophy class="w-5 h-5 mr-3" />
                        Rankings
                    </Link>
                    <Link 
                        href="/admin/settings" 
                        class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                        @click="toggleMobileMenu"
                    >
                        <Settings class="w-5 h-5 mr-3" />
                        Settings
                    </Link>
                    <Link 
                        href="/admin/system-settings" 
                        class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition-colors"
                        :class="{ 'bg-blue-700 text-white border-r-4 border-white': isActive('/admin/system-settings') }"
                    >
                        <Settings class="w-5 h-5 mr-3" />
                        System Settings
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

            <!-- Main Content (scrollable) -->
            <main class="flex-1 p-4 md:p-8 overflow-y-auto">
                <slot />
            </main>
        </div>
    </div>
</template>