<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";

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

const isVisible = ref(false);

onMounted(() => {
    isVisible.value = true;
});
</script>

<template>
    <Head title="Help Desk | Omishtu Joy Tech Solution" />

    <!-- Hero section with animated background -->
    <div
        class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black overflow-hidden relative"
    >
        <!-- Animated background elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -inset-[10px] opacity-50">
                <div
                    class="absolute top-0 -left-4 w-72 h-72 bg-green-500 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"
                ></div>
                <div
                    class="absolute top-0 -right-4 w-72 h-72 bg-emerald-500 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000"
                ></div>
                <div
                    class="absolute -bottom-8 left-20 w-72 h-72 bg-green-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-4000"
                ></div>
                <div
                    class="absolute -top-20 right-20 w-72 h-72 bg-teal-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-6000"
                ></div>
                <div
                    class="absolute -bottom-40 -right-10 w-80 h-80 bg-green-400 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-8000"
                ></div>
            </div>
        </div>

        <!-- Grid pattern overlay -->
        <div
            class="absolute inset-0 bg-grid-white/[0.05] bg-[length:30px_30px]"
        ></div>

        <div class="relative z-10">
            <!-- Navbar -->
            <nav class="container mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="text-white font-bold text-2xl">
                        <span class="text-green-400">Omishtu</span> Joy
                    </div>
                    <div v-if="canLogin" class="space-x-2">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-gray-900 transition-all"
                        >
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="px-4 py-2 text-sm font-medium text-green-400 border border-green-400 rounded-lg hover:bg-green-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-gray-900 transition-all"
                            >
                                Log in
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-gray-900 transition-all ml-2"
                            >
                                Register
                            </Link>
                        </template>
                    </div>
                </div>
            </nav>

            <!-- Hero content -->
            <div
                class="container mx-auto px-6 py-16 h-[80vh] flex items-center justify-center"
            >
                <div
                    :class="[
                        'transition-all duration-1000 transform w-full text-center',
                        isVisible
                            ? 'opacity-100 translate-y-0'
                            : 'opacity-0 translate-y-8',
                    ]"
                >
                    <h1
                        class="text-5xl md:text-6xl font-extrabold tracking-tight text-white mb-6"
                    >
                        <span
                            class="bg-clip-text text-transparent bg-gradient-to-r from-green-300 via-green-400 to-emerald-500"
                        >
                            Technical Support
                        </span>
                        <span class="block mt-2">Made Simple</span>
                    </h1>
                    <p class="text-xl text-gray-300 max-w-2xl mx-auto mb-12">
                        Your one-stop solution for all your technical support
                        needs with instant access to knowledge resources and
                        expert assistance.
                    </p>
                    <div
                        class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4"
                    >
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="px-8 py-4 text-base font-medium text-white bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl hover:from-green-600 hover:to-emerald-700 shadow-lg hover:shadow-green-500/30 transition-all duration-300"
                        >
                            Go to Dashboard
                        </Link>
                        <Link
                            v-else
                            :href="route('login')"
                            class="px-8 py-4 text-base font-medium text-white bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl hover:from-green-600 hover:to-emerald-700 shadow-lg hover:shadow-green-500/30 transition-all duration-300"
                        >
                            Get Started Now
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div
                class="container mx-auto px-6 pb-8 text-center text-sm text-gray-400"
            >
                &copy; {{ new Date().getFullYear() }} Omishtu Joy Tech Solution.
                All rights reserved.
            </div>
        </div>
    </div>
</template>

<style>
/* Animation classes */
@keyframes blob {
    0% {
        transform: translate(0px, 0px) scale(1);
    }
    33% {
        transform: translate(30px, -50px) scale(1.2);
    }
    66% {
        transform: translate(-20px, 20px) scale(0.8);
    }
    100% {
        transform: translate(0px, 0px) scale(1);
    }
}

.animate-blob {
    animation: blob 15s infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

.animation-delay-4000 {
    animation-delay: 4s;
}

.animation-delay-6000 {
    animation-delay: 6s;
}

.animation-delay-8000 {
    animation-delay: 8s;
}

/* Grid pattern */
.bg-grid-white {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.15)'/%3E%3C/svg%3E");
}
</style>
