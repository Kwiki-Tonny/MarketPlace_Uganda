<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Confirm Password" />

        <div class="mb-4 text-center">
            <h2 class="text-2xl font-bold text-gray-800">Confirm Password</h2>
            <p class="text-sm text-gray-600">Enter your password to continue</p>
        </div>

        <div class="mb-4 text-sm text-gray-600 text-center">
            This is a secure area of the application. Please confirm your
            password before continuing.
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    autofocus
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <PrimaryButton
                class="mt-4 w-full justify-center"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Confirm
            </PrimaryButton>
        </form>

        <div class="mt-6 text-center">
            <Link :href="route('login')" class="text-sm text-blue-600 hover:underline">
                Back to login
            </Link>
        </div>
    </GuestLayout>
</template>