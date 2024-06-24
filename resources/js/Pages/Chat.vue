<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import Button from "primevue/button"
const props = defineProps({
    friend: Object
});
</script>

<template>
    <Head title="Chat" />

    <!-- <AuthenticatedLayout> -->
        <!-- <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Chat</h2>
        </template> -->

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            {{ friend.name }}
                            <div class="flex justify-center card">
                                <Button label="Check" icon="pi pi-check" />
                            </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- </AuthenticatedLayout> -->
</template>
