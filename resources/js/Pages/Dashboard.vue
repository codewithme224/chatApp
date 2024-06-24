<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import { useDialog } from 'primevue/usedialog';
import { useToast } from 'primevue/usetoast';
import Button from 'primevue/button';
import DynamicDialog from 'primevue/dynamicdialog';
import { usePage } from '@inertiajs/vue3';
import Chat from '@/Components/Chat.vue'
const dialog = useDialog();
const toast = useToast();
const props = defineProps({
    users: Array
});

const { props: pageProps } = usePage();
console.log('Page Props: ', pageProps);
const authUser = pageProps.auth.user;

if (authUser) {
    console.log('Auth User: ', authUser);
} else {
    console.log('Auth User not found');
}

const showChat = (user) => {
    console.log('Show Chat clicked', user);
    console.log('Auth User Data: ', authUser);
    const dialogRef = dialog.open(Chat, {
        data: {
            user: user,
            authUser: authUser
        },
        props: {
            header: 'Chat',
            style: {
                width: '60vw',
                background: '',
                color: 'white'
            },
            breakpoints: { '1199px': '75vw', '575px': '90vw' },
            modal: true,
            maximizable: true,
            product: null,
            mode: 'create',
            schema: 'vehicle_movements',
            submitRoute: 'test'
        },
        onClose: (options) => {
            const data = options.data;
            if (data) {
                toast.add({ severity: 'success', summary: 'Vehicle Movement Open', detail: data.name, life: 3000 });
            }
        },
    });
    dialogRef.onSave = (product) => {
        // handle saving the product
        dialogRef.close({ data: product });
    };

    dialogRef.onCancel = () => {
        dialogRef.close();
    };
};


</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <template v-for="user in users" :key="user.id">

                                <div class="max-w-sm overflow-hidden bg-white rounded shadow-lg cursor-pointer" >
                                    <div class="px-6 py-4">
                                        <div class="mb-2 text-xl font-bold">{{ user.name }}</div>
                                        <p class="text-base text-gray-700">
                                            {{ user.email }}
                                        </p>
                                    </div>

                                    <div class="px-6 pt-4 pb-2">
                                        <Button label="Chat" icon="pi pi-comments" @click="showChat(user)" />
                                    </div>
                                </div>

                        </template>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <Toast />
        <DynamicDialog />
    </AuthenticatedLayout>
</template>
