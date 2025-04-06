<template>
    <div class="flex flex-col md:flex-row gap-4 mb-6">
        <div class="flex-1 flex">
            <input
                type="text"
                placeholder="Search by article name..."
                class="w-full border-gray-300 rounded-l-md shadow-sm"
                v-model="filters.title"
                @keyup.enter="applyFiltersImmediately"
            />
            <PrimaryButton
                @click="applyFiltersImmediately"
                class="rounded-l-none"
            >
                Search
            </PrimaryButton>
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
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";

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
        performSearch();
    }, 800);
};

// Apply filters immediately (for search button click and enter key)
const applyFiltersImmediately = () => {
    clearTimeout(filterTimeout);
    performSearch();
};

// Perform the actual search
const performSearch = () => {
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
</script>
