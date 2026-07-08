<script setup>
import { ref, onMounted } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { router, usePage, Link } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import { Trash2, AlertCircle, CheckCircle, Clock } from 'lucide-vue-next';

const pendingVendors = ref([]);
const approvedVendors = ref([]);

// Modal state
const showSuspendModal = ref(false);
const suspendVendorId = ref(null);
const suspendVendorName = ref('');
const suspendForm = ref({
    suspended_until: '',
    suspended_reason: '',
});
const suspending = ref(false);

const fetchPending = async () => {
    const res = await axios.get('/admin/vendors/pending');
    pendingVendors.value = res.data;
};

const fetchApproved = async () => {
    const res = await axios.get('/admin/vendors/approved');
    approvedVendors.value = res.data;
};

const approveVendor = async (id) => {
    if (confirm('Approve this vendor?')) {
        await axios.put(`/admin/vendors/${id}/approve`);
        await fetchPending();
        await fetchApproved();
    }
};

const rejectVendor = async (id) => {
    if (confirm('Reject this vendor? This will delete their account.')) {
        await axios.delete(`/admin/vendors/${id}`);
        await fetchPending();
    }
};

const deleteVendor = async (id) => {
    if (confirm('Delete this vendor permanently? This will also delete all their orders and data.')) {
        await axios.delete(`/admin/vendors/${id}`);
        await fetchPending();
        await fetchApproved();
    }
};

const openSuspendModal = (vendor) => {
    suspendVendorId.value = vendor.id;
    suspendVendorName.value = vendor.name;
    suspendForm.value = { suspended_until: '', suspended_reason: '' };
    showSuspendModal.value = true;
};

const submitSuspension = async () => {
    if (!suspendForm.value.suspended_until) {
        alert('Please select a suspension end date.');
        return;
    }
    suspending.value = true;
    try {
        await axios.put(`/admin/vendors/${suspendVendorId.value}/suspend`, suspendForm.value);
        showSuspendModal.value = false;
        await fetchPending();
        await fetchApproved();
    } catch (error) {
        console.error('Suspension error:', error.response?.data);
        alert('Failed to suspend vendor: ' + (error.response?.data?.error || error.response?.data?.message || 'Unknown error.'));
    } finally {
        suspending.value = false;
    }
};

const unsuspendVendor = async (id) => {
    if (confirm('Unsuspend this vendor?')) {
        await axios.put(`/admin/vendors/${id}/unsuspend`);
        await fetchPending();
        await fetchApproved();
    }
};

const isVendorSuspended = (vendor) => {
    return vendor.suspended_until && new Date(vendor.suspended_until) > new Date();
};

const getSuspensionStatus = (vendor) => {
    if (!vendor.suspended_until) return null;
    const until = new Date(vendor.suspended_until);
    if (until > new Date()) {
        return `Suspended until ${until.toLocaleDateString()}`;
    }
    return null;
};

onMounted(() => {
    fetchPending();
    fetchApproved();
});
</script>

<template>
    <AdminLayout>
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Vendor Management</h1>
            <p class="text-gray-600">Approve, suspend, or remove vendors</p>
        </div>

        <!-- Pending Vendors -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Pending Vendors ({{ pendingVendors.length }})</h3>
            </div>
            <div class="p-4">
                <div v-if="pendingVendors.length === 0" class="text-gray-500 text-sm">
                    No pending vendors.
                </div>
                <div v-else>
                    <div v-for="vendor in pendingVendors" :key="vendor.id"
                         class="flex items-center justify-between border-b py-3 last:border-0">
                        <div>
                            <p class="font-bold">{{ vendor.name }}</p>
                            <p class="text-sm text-gray-600">{{ vendor.email }} | {{ vendor.phone }} | {{ vendor.district }}</p>
                            <p class="text-xs text-gray-400">Registered: {{ new Date(vendor.created_at).toLocaleDateString() }}</p>
                        </div>
                        <div class="space-x-2 flex items-center">
                            <button @click="approveVendor(vendor.id)" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Approve</button>
                            <button @click="rejectVendor(vendor.id)" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Reject</button>
                            <button @click="deleteVendor(vendor.id)" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 flex items-center gap-1">
                                <Trash2 class="w-4 h-4" /> Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approved Vendors -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Approved Vendors ({{ approvedVendors.length }})</h3>
            </div>
            <div class="p-4">
                <div v-if="approvedVendors.length === 0" class="text-gray-500 text-sm">
                    No approved vendors yet.
                </div>
                <div v-else>
                    <div v-for="vendor in approvedVendors" :key="vendor.id"
                         class="flex items-center justify-between border-b py-3 last:border-0">
                        <div>
                            <p class="font-bold">{{ vendor.name }}</p>
                            <p class="text-sm text-gray-600">{{ vendor.email }} | {{ vendor.phone }}</p>
                            <div v-if="isVendorSuspended(vendor)" class="flex items-center gap-1 mt-1">
                                <Clock class="w-4 h-4 text-red-500" />
                                <span class="text-xs text-red-600 font-medium">{{ getSuspensionStatus(vendor) }}</span>
                                <span v-if="vendor.suspended_reason" class="text-xs text-gray-500 ml-2">Reason: {{ vendor.suspended_reason }}</span>
                            </div>
                        </div>
                        <div class="space-x-2 flex items-center">
                            <span v-if="isVendorSuspended(vendor)" class="text-red-600 text-sm font-medium">⛔ Suspended</span>
                            <span v-else class="text-green-600 text-sm">✓ Active</span>

                            <button v-if="!isVendorSuspended(vendor)"
                                    @click="openSuspendModal(vendor)"
                                    class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 flex items-center gap-1">
                                <AlertCircle class="w-4 h-4" /> Suspend
                            </button>
                            <button v-else
                                    @click="unsuspendVendor(vendor.id)"
                                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 flex items-center gap-1">
                                <CheckCircle class="w-4 h-4" /> Unsuspend
                            </button>
                            <button @click="deleteVendor(vendor.id)" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 flex items-center gap-1">
                                <Trash2 class="w-4 h-4" /> Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== SUSPENSION MODAL ===== -->
        <div v-if="showSuspendModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <h3 class="text-lg font-bold mb-4">Suspend Vendor</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Suspend <strong>{{ suspendVendorName }}</strong> for a period of time.
                </p>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Suspension End Date &amp; Time *</label>
                    <input type="datetime-local" v-model="suspendForm.suspended_until" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Reason (optional)</label>
                    <textarea v-model="suspendForm.suspended_reason" rows="3" placeholder="Why is this vendor being suspended?" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button @click="showSuspendModal = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400" :disabled="suspending">Cancel</button>
                    <button @click="submitSuspension" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600" :disabled="suspending">
                        {{ suspending ? 'Suspending...' : 'Suspend Vendor' }}
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>