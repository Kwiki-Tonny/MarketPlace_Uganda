<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="mb-4 text-center">
            <h2 class="text-2xl font-bold text-gray-800">Reset Your Password</h2>
            <p class="text-sm text-gray-600">Enter your email to receive a reset link</p>
        </div>

        <div class="mb-4 text-sm text-gray-600 text-center">
            Forgot your password? No problem. Just let us know your email
            address and we will email you a password reset link that will allow
            you to choose a new one.
        </div>

        <div
            v-if="status"
            class="mb-4 text-sm font-medium text-green-600 text-center"
        >
            ✅ {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <PrimaryButton
                class="mt-4 w-full justify-center"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Email Password Reset Link
            </PrimaryButton>
        </form>

        <div class="mt-6 text-center">
            <Link :href="route('login')" class="text-sm text-blue-600 hover:underline">
                Back to login
            </Link>
        </div>
    </GuestLayout>
</template>