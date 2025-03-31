<template>
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-0.5 md:space-x-1">
            <li
                v-for="(item, index) in items"
                :key="index"
                class="inline-flex items-center"
            >
                <div v-if="index > 0" class="flex items-center">
                    <span class="text-gray-400 mx-1 text-2xl">/</span>
                </div>

                <template v-if="item.href && index !== items.length - 1">
                    <Link
                        :href="item.href"
                        class="inline-flex items-center text-xl font-medium text-gray-600 hover:text-gray-900"
                    >
                        {{ item.label }}
                    </Link>
                </template>
                <template v-else>
                    <span
                        class="inline-flex items-center text-xl font-medium"
                        :class="[
                            index === items.length - 1
                                ? 'text-gray-900 font-semibold'
                                : 'text-gray-600 hover:text-gray-900',
                        ]"
                    >
                        {{ item.label }}
                    </span>
                </template>
            </li>
        </ol>
    </nav>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    items: {
        type: Array,
        required: true,
        // Each item should have: { label: string, href?: string }
    },
});
</script>
