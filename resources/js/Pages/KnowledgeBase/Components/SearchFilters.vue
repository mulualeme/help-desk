<template>
    <div class="flex flex-col md:flex-row gap-4 mb-6">
        <div class="flex-1">
            <input
                type="text"
                placeholder="Title"
                class="w-full border-gray-300 rounded-md shadow-sm"
                v-model="filters.title"
            />
        </div>
        <div class="w-48">
            <select
                v-model="filters.status"
                class="w-full border-gray-300 rounded-md shadow-sm"
            >
                <option value="">Status</option>
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </select>
        </div>
        <div class="w-48">
            <select
                v-model="filters.sort"
                class="w-full border-gray-300 rounded-md shadow-sm"
            >
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="a-z">A-Z</option>
                <option value="z-a">Z-A</option>
            </select>
        </div>
        <div class="flex items-center">
            <button
                class="bg-white text-gray-500 px-4 py-2 rounded-md shadow border hover:bg-gray-50"
                @click="resetFilters"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                    />
                </svg>
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    initialFilters: Object,
});

// Filters
const filters = ref({
    title: props.initialFilters.title || "",
    status: props.initialFilters.status || "",
    sort: props.initialFilters.sort || "newest",
});

// Apply filters with debounce
let filterTimeout;
const applyFilters = () => {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
        router.get(
            route("knowledge-base.index"),
            {
                title: filters.value.title,
                status: filters.value.status,
                sort: filters.value.sort,
                page: 1, // Reset to first page on filter change
            },
            {
                preserveState: true,
                preserveScroll: true,
                only: ["categories"],
            }
        );
    }, 800);
};

// Watch for filter changes - use dedicated watchers instead of watching the entire object
watch(
    () => filters.value.title,
    (newValue, oldValue) => {
        if (newValue !== oldValue) {
            applyFilters();
        }
    }
);

watch(
    () => filters.value.status,
    (newValue, oldValue) => {
        if (newValue !== oldValue) {
            applyFilters();
        }
    }
);

watch(
    () => filters.value.sort,
    (newValue, oldValue) => {
        if (newValue !== oldValue) {
            applyFilters();
        }
    }
);

// Reset filters
const resetFilters = () => {
    filters.value = {
        title: "",
        status: "",
        sort: "newest",
    };
    // The watch will trigger applyFilters
};
</script>
