<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification" />

        <div class="mb-4 text-center">
            <h2 class="text-2xl font-bold text-gray-800">Verify Your Email</h2>
            <p class="text-sm text-gray-600">Confirm your email address to get started</p>
        </div>

        <div class="mb-4 text-sm text-gray-600 text-center">
            Thanks for signing up! Before getting started, could you verify your
            email address by clicking on the link we just emailed to you? If you
            didn't receive the email, we will gladly send you another.
        </div>

        <div
            class="mb-4 text-sm font-medium text-green-600 text-center"
            v-if="verificationLinkSent"
        >
            ✅ A new verification link has been sent to your email address.
        </div>

        <form @submit.prevent="submit">
            <PrimaryButton
                class="w-full justify-center"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Resend Verification Email
            </PrimaryButton>

            <div class="mt-4 text-center">
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="text-sm text-gray-600 hover:text-gray-900 underline"
                >
                    Logout
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>