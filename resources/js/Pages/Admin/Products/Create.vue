<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';

defineProps({
    categories: Array,
});

const form = useForm({
    code: '',
    name: '',
    description: '',
    price: '',
    category_id: '',
    images: [],
    is_active: true,
});

const submit = () => {
    form.post('/admin/products', {
        forceFormData: true, // required for file uploads
    });
};
</script>

<template>
    <AdminLayout>
        <div class="flex items-center mb-6">
            <Link href="/admin/products" class="text-gray-600 hover:text-gray-900 mr-4">
                <ArrowLeft class="w-6 h-6" />
            </Link>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Add Product</h1>
                <p class="text-gray-600">Create a new product listing</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Product Code</label>
                    <input type="text" v-model="form.code" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Name *</label>
                    <input type="text" v-model="form.name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                    <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea v-model="form.description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Price (UGX) *</label>
                    <input type="number" v-model="form.price" required min="0" step="0.01" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                    <p v-if="form.errors.price" class="text-red-500 text-sm mt-1">{{ form.errors.price }}</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Category *</label>
                    <select v-model="form.category_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select category</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                    </select>
                    <p v-if="form.errors.category_id" class="text-red-500 text-sm mt-1">{{ form.errors.category_id }}</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Images (max 5)</label>
                    <input type="file" multiple accept="image/*" @input="form.images = $event.target.files" class="mt-1 block w-full" />
                </div>
                <div class="mb-4 flex items-center">
                    <input type="checkbox" v-model="form.is_active" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" />
                    <label class="ml-2 block text-sm text-gray-900">Active</label>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg" :disabled="form.processing">
                        {{ form.processing ? 'Saving...' : 'Save Product' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>