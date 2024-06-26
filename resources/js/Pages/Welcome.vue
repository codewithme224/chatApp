<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="Welcome" />
    <div class="text-white bg-gradient-to-r from-blue-500 via-purple-600 to-purple-600">


                <main class="flex items-center justify-center min-h-screen fade-in">
                    <div class="text-center">
                        <h1 class="mb-4 text-6xl font-bold md:text-8xl">Welcome to ChatApp</h1>
                        <p class="mb-8 text-xl md:text-3xl">The best place to connect with your friends and family.</p>
                        <Link
                        v-if="canRegister"
                        :href="route('register')" class="px-8 py-3 font-semibold text-purple-600 transition transform bg-white rounded-full shadow-lg hover:bg-gray-100 hover:scale-105 bounce">Get Started</Link>
                        <p class="mt-4">
                            Already have an account?
                            <Link :href="route('login')" class="text-white underline hover:text-gray-200"> Login</Link>
                        </p>
                    </div>
                </main>
                <footer class="w-full py-6 bg-gradient-to-r from-red-500 via-purple-600 to-blue-500">
                    <div class="px-4 mx-auto text-center max-w-7xl">
                        <p>&copy; 2024 ChatApp. All rights reserved.</p>
                    </div>
                </footer>
    </div>
</template>

<style>
        .fade-in {
            animation: fadeIn 2s ease-in-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        .bounce {
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-30px);
            }
            60% {
                transform: translateY(-15px);
            }
        }
    </style>
