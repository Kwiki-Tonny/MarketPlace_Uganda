<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    vendorCount: Number,
});

const form = useForm({
    subject: '',
    message: '',
    recipients: 'approved',
});

const submit = () => {
    form.post(route('admin.notifications.send'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('subject', 'message');
        },
    });
};
</script>

<template>
    <AdminLayout>
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Send Notification</h1>
            <p class="text-gray-600">Send an email to all vendors</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Subject</label>
                    <input type="text" v-model="form.subject" class="block w-full border-gray-300 rounded-md shadow-sm" />
                    <p v-if="form.errors.subject" class="text-red-500 text-sm">{{ form.errors.subject }}</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Message</label>
                    <textarea v-model="form.message" rows="6" class="block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    <p v-if="form.errors.message" class="text-red-500 text-sm">{{ form.errors.message }}</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Recipients</label>
                    <select v-model="form.recipients" class="block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="all">All Vendors ({{ vendorCount }})</option>
                        <option value="approved">Approved Vendors</option>
                        <option value="pending">Pending Vendors</option>
                    </select>
                </div>
                <button type="submit" :disabled="form.processing" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    {{ form.processing ? 'Sending...' : 'Send Email' }}
                </button>
            </form>
        </div>
    </AdminLayout>
</template>