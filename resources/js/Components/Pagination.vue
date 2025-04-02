<template>
    <div class="mt-6" v-if="links && links.length > 0">
        <div class="flex justify-between flex-col md:flex-row gap-4">
            <div class="text-sm text-gray-700">
                Showing {{ meta.from }} to {{ meta.to }} of {{ meta.total }}
                {{ itemName }}
            </div>
            <div class="flex flex-wrap gap-1">
                <Link
                    v-for="(link, i) in links"
                    :key="i"
                    :href="link.url"
                    class="px-4 py-2 border text-sm rounded-md"
                    :class="{
                        'bg-gray-900 text-white': link.active,
                        'bg-white text-gray-700 hover:bg-gray-50': !link.active,
                        'opacity-50 cursor-not-allowed': !link.url,
                    }"
                    v-html="link.label"
                    :disabled="!link.url"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    links: {
        type: Array,
        required: true,
    },
    meta: {
        type: Object,
        required: true,
    },
    itemName: {
        type: String,
        default: "items",
    },
});
</script>
