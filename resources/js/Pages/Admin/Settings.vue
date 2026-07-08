<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { Plus, Edit2, Trash2, X } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps({
    categories: Array,
});

const editing = ref(null);
const showCreate = ref(false);

const createForm = useForm({
    name: '',
    description: '',
});

const editForm = useForm({
    name: '',
    description: '',
});

const startEdit = (category) => {
    editing.value = category.id;
    editForm.name = category.name;
    editForm.description = category.description || '';
};

const cancelEdit = () => {
    editing.value = null;
    editForm.reset();
};

const submitCreate = () => {
    createForm.post(route('admin.categories.store'), {
        preserveScroll: true,
        onSuccess: () => {
            createForm.reset();
            showCreate.value = false;
        },
    });
};

const submitUpdate = (category) => {
    editForm.put(route('admin.categories.update', category.id), {
        preserveScroll: true,
        onSuccess: () => {
            editing.value = null;
            editForm.reset();
        },
    });
};

const deleteCategory = (category) => {
    if (confirm(`Delete category "${category.name}"? This cannot be undone.`)) {
        useForm().delete(route('admin.categories.destroy', category.id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <AdminLayout>
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
                <p class="text-gray-600">Manage product categories</p>
            </div>
            <button
                @click="showCreate = true"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2"
            >
                <Plus class="w-5 h-5" /> Add Category
            </button>
        </div>

        <!-- Create Form -->
        <div v-if="showCreate" class="bg-white rounded-lg shadow p-4 mb-6 border border-blue-200">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-800">New Category</h3>
                <button @click="showCreate = false" class="text-gray-400 hover:text-gray-600">
                    <X class="w-5 h-5" />
                </button>
            </div>
            <form @submit.prevent="submitCreate" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" v-model="createForm.name" class="block w-full border-gray-300 rounded-md shadow-sm" />
                    <p v-if="createForm.errors.name" class="text-red-500 text-sm">{{ createForm.errors.name }}</p>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <input type="text" v-model="createForm.description" class="block w-full border-gray-300 rounded-md shadow-sm" />
                </div>
                <button type="submit" :disabled="createForm.processing" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                    {{ createForm.processing ? 'Adding...' : 'Add' }}
                </button>
            </form>
        </div>

        <!-- Category List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Products</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="category in categories" :key="category.id">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div v-if="editing !== category.id" class="text-sm font-medium text-gray-900">{{ category.name }}</div>
                            <div v-else>
                                <input type="text" v-model="editForm.name" class="block w-full border-gray-300 rounded-md shadow-sm" />
                                <p v-if="editForm.errors.name" class="text-red-500 text-sm">{{ editForm.errors.name }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ category.slug }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ category.products_count }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            <template v-if="editing !== category.id">
                                <button @click="startEdit(category)" class="text-blue-600 hover:text-blue-800">
                                    <Edit2 class="w-5 h-5 inline" />
                                </button>
                                <button @click="deleteCategory(category)" class="text-red-600 hover:text-red-800">
                                    <Trash2 class="w-5 h-5 inline" />
                                </button>
                            </template>
                            <template v-else>
                                <button @click="submitUpdate(category)" :disabled="editForm.processing" class="text-green-600 hover:text-green-800 font-medium">
                                    Save
                                </button>
                                <button @click="cancelEdit" class="text-gray-500 hover:text-gray-700 font-medium">Cancel</button>
                            </template>
                        </td>
                    </tr>
                    <tr v-if="categories.length === 0">
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No categories yet.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>