<template>
    <div v-if="hasActiveFilters" class="mb-4 flex items-center text-sm">
        <span class="mr-2 text-gray-600">Active filters:</span>
        <div
            v-if="filters.search"
            class="flex items-center bg-gray-100 px-3 py-1 rounded-full mr-2"
        >
            <span>Search: {{ filters.search }}</span>
            <button
                @click="clearSearch"
                class="ml-2 text-gray-500 hover:text-gray-700"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>
        </div>
        <div
            v-if="filters.status !== 'all'"
            class="flex items-center bg-gray-100 px-3 py-1 rounded-full mr-2"
        >
            <span>Status: {{ statusLabel }}</span>
            <button
                @click="clearStatusFilter"
                class="ml-2 text-gray-500 hover:text-gray-700"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>
        </div>
        <div
            v-if="filters.sort !== 'newest'"
            class="flex items-center bg-gray-100 px-3 py-1 rounded-full mr-2"
        >
            <span>Sort: {{ sortLabel }}</span>
            <button
                @click="clearSortOrder"
                class="ml-2 text-gray-500 hover:text-gray-700"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>
        </div>
        <button
            @click="resetAllFilters"
            class="ml-2 text-indigo-600 hover:text-indigo-800 text-sm"
        >
            Reset all
        </button>
    </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    filters: Object,
});

const emit = defineEmits([
    "clear-search",
    "clear-status",
    "clear-sort",
    "reset-all",
]);

const hasActiveFilters = computed(() => {
    return (
        props.filters.search ||
        props.filters.status !== "all" ||
        props.filters.sort !== "newest"
    );
});

const statusLabel = computed(() => {
    const labels = {
        active: "Active",
        inactive: "Inactive",
    };
    return labels[props.filters.status] || props.filters.status;
});

const sortLabel = computed(() => {
    const labels = {
        newest: "Newest First",
        oldest: "Oldest First",
        "a-z": "A-Z",
        "z-a": "Z-A",
    };
    return labels[props.filters.sort] || props.filters.sort;
});
</script>
