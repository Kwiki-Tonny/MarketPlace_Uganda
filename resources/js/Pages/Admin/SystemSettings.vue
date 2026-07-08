<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    general: Object,
    paymentMethods: Array,
});

const generalForm = useForm({
    site_name: props.general.site_name || '',
    site_email: props.general.site_email || '',
    site_phone: props.general.site_phone || '',
    site_address: props.general.site_address || '',
});

const paymentForm = useForm({
    paymentMethods: props.paymentMethods || [],
});

const addPaymentMethod = () => {
    paymentForm.paymentMethods.push({
        name: '',
        number: '',
        instructions: '',
    });
};

const removePaymentMethod = (index) => {
    paymentForm.paymentMethods.splice(index, 1);
};

const submitGeneral = () => {
    generalForm.put(route('admin.system-settings.update-general'), {
        preserveScroll: true,
    });
};

const submitPayment = () => {
    paymentForm.put(route('admin.system-settings.update-payment'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AdminLayout>
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">System Settings</h1>
            <p class="text-gray-600">Configure general and payment settings</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- General Settings -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">General Settings</h2>
                <form @submit.prevent="submitGeneral">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Site Name</label>
                        <input type="text" v-model="generalForm.site_name" class="block w-full border-gray-300 rounded-md shadow-sm" />
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Site Email</label>
                        <input type="email" v-model="generalForm.site_email" class="block w-full border-gray-300 rounded-md shadow-sm" />
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" v-model="generalForm.site_phone" class="block w-full border-gray-300 rounded-md shadow-sm" />
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Address</label>
                        <input type="text" v-model="generalForm.site_address" class="block w-full border-gray-300 rounded-md shadow-sm" />
                    </div>
                    <button type="submit" :disabled="generalForm.processing" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        {{ generalForm.processing ? 'Saving...' : 'Save General Settings' }}
                    </button>
                </form>
            </div>

            <!-- Payment Settings -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Payment Methods</h2>
                <form @submit.prevent="submitPayment">
                    <div v-for="(method, index) in paymentForm.paymentMethods" :key="index" class="border-b pb-4 mb-4">
                        <div class="flex justify-between items-start">
                            <h4 class="font-medium text-gray-700">Method {{ index + 1 }}</h4>
                            <button type="button" @click="removePaymentMethod(index)" class="text-red-500 hover:text-red-700">
                                Remove
                            </button>
                        </div>
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" v-model="method.name" class="block w-full border-gray-300 rounded-md shadow-sm" />
                        </div>
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">Number</label>
                            <input type="text" v-model="method.number" class="block w-full border-gray-300 rounded-md shadow-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Instructions</label>
                            <textarea v-model="method.instructions" rows="2" class="block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                        </div>
                    </div>
                    <button type="button" @click="addPaymentMethod" class="text-sm text-blue-600 hover:underline">+ Add Payment Method</button>
                    <div class="mt-4">
                        <button type="submit" :disabled="paymentForm.processing" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            {{ paymentForm.processing ? 'Saving...' : 'Save Payment Settings' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>