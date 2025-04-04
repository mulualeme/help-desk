<template>
    <div class="mt-6" v-if="links && links.length > 0">
        <div class="flex justify-between flex-col md:flex-row gap-4">
            <div class="text-sm text-gray-700">
                Showing {{ meta.from }} to {{ meta.to }} of {{ meta.total }}
                {{ itemName }}
            </div>
            <div class="flex flex-wrap gap-1">
                <template v-for="(link, i) in links" :key="i">
                    <Link
                        v-if="link.url"
                        :href="link.url"
                        class="px-4 py-2 border text-sm rounded-md"
                        :class="{
                            'bg-gray-900 text-white': link.active,
                            'bg-white text-gray-700 hover:bg-gray-50':
                                !link.active,
                        }"
                        v-html="link.label"
                        :preserve-scroll="true"
                        :preserve-state="true"
                        :only="[itemName]"
                    />
                    <span
                        v-else
                        class="px-4 py-2 border text-sm rounded-md bg-white text-gray-700 opacity-50 cursor-not-allowed"
                        v-html="link.label"
                    />
                </template>
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
