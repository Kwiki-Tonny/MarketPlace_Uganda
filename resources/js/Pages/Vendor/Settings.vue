<script setup>
import VendorLayout from '@/Layouts/VendorLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { User, Phone, MapPin, Lock } from 'lucide-vue-next';

const props = defineProps({
    user: Object,
});

const profileForm = useForm({
    name: props.user.name,
    phone: props.user.phone || '',
    district: props.user.district || '',
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const submitProfile = () => {
    profileForm.put(route('vendor.settings.update-profile'), {
        preserveScroll: true,
        onSuccess: () => {
            // optional: success toast
        },
    });
};

const submitPassword = () => {
    passwordForm.put(route('vendor.settings.update-password'), {
        preserveScroll: true,
        onSuccess: () => {
            passwordForm.reset();
        },
    });
};

const preferencesForm = useForm({
    preferences: {
        order_status: props.user.preferences?.order_status ?? true,
        promotions: props.user.preferences?.promotions ?? true,
    },
});

const submitPreferences = () => {
    preferencesForm.put(route('vendor.settings.update-preferences'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <VendorLayout>
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
            <p class="text-gray-600">Update your profile and password</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Profile Form -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Profile Information</h2>
                <form @submit.prevent="submitProfile">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Full Name</label>
                        <div class="relative">
                            <User class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" />
                            <input
                                type="text"
                                v-model="profileForm.name"
                                class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            />
                        </div>
                        <p v-if="profileForm.errors.name" class="text-red-500 text-sm mt-1">{{ profileForm.errors.name }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <div class="relative">
                            <Phone class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" />
                            <input
                                type="text"
                                v-model="profileForm.phone"
                                class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            />
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">District</label>
                        <div class="relative">
                            <MapPin class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" />
                            <input
                                type="text"
                                v-model="profileForm.district"
                                class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            />
                        </div>
                    </div>
                    <button
                        type="submit"
                        :disabled="profileForm.processing"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg disabled:opacity-50"
                    >
                        {{ profileForm.processing ? 'Saving...' : 'Update Profile' }}
                    </button>
                </form>
            </div>

            <!-- Password Form -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Change Password</h2>
                <form @submit.prevent="submitPassword">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Current Password</label>
                        <div class="relative">
                            <Lock class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" />
                            <input
                                type="password"
                                v-model="passwordForm.current_password"
                                class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            />
                        </div>
                        <p v-if="passwordForm.errors.current_password" class="text-red-500 text-sm mt-1">{{ passwordForm.errors.current_password }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">New Password</label>
                        <input
                            type="password"
                            v-model="passwordForm.password"
                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        />
                        <p v-if="passwordForm.errors.password" class="text-red-500 text-sm mt-1">{{ passwordForm.errors.password }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input
                            type="password"
                            v-model="passwordForm.password_confirmation"
                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        />
                    </div>
                    <button
                        type="submit"
                        :disabled="passwordForm.processing"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg disabled:opacity-50"
                    >
                        {{ passwordForm.processing ? 'Updating...' : 'Update Password' }}
                    </button>
                </form>
            </div>

            <!-- Notification Preferences -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Notification Preferences</h2>
                <form @submit.prevent="submitPreferences">
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" v-model="preferencesForm.order_status" class="rounded border-gray-300 text-blue-600 shadow-sm" />
                            <span class="ml-2 text-sm text-gray-700">Order status updates</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" v-model="preferencesForm.promotions" class="rounded border-gray-300 text-blue-600 shadow-sm" />
                            <span class="ml-2 text-sm text-gray-700">Promotions and offers</span>
                        </label>
                    </div>
                    <button type="submit" :disabled="preferencesForm.processing" class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg disabled:opacity-50">
                        {{ preferencesForm.processing ? 'Saving...' : 'Save Preferences' }}
                    </button>
                </form>
            </div>
        </div>
    </VendorLayout>
</template>