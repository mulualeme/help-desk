<template>
    <div
        class="bg-white overflow-visible shadow-sm sm:rounded-lg mb-6 p-6"
        style="position: relative; z-index: 30"
    >
        <div class="flex flex-wrap gap-4 items-end">
            <!-- Search -->
            <div class="w-full md:w-1/3">
                <InputLabel for="search" value="Search Knowledge Base" />
                <div class="mt-1 flex">
                    <TextInput
                        id="search"
                        v-model="filters.title"
                        type="text"
                        class="block w-full rounded-r-none"
                        placeholder="Search by article name..."
                        @keyup.enter="applyFiltersImmediately"
                    />
                    <PrimaryButton
                        @click="applyFiltersImmediately"
                        class="rounded-l-none"
                    >
                        Search
                    </PrimaryButton>
                </div>
            </div>

            <!-- Status Filter -->
            <div class="w-full md:w-1/4">
                <InputLabel value="Status" />
                <div class="relative" style="position: static">
                    <Dropdown align="left" width="48">
                        <template #trigger>
                            <SecondaryButton class="w-full justify-between">
                                {{ statusLabel }}
                                <svg
                                    class="ml-2 -mr-0.5 h-4 w-4"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </SecondaryButton>
                        </template>
                        <template #content>
                            <div>
                                <button
                                    class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    @click="updateStatus('')"
                                >
                                    All Statuses
                                </button>
                                <button
                                    class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    @click="updateStatus('draft')"
                                >
                                    Draft
                                </button>
                                <button
                                    class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    @click="updateStatus('published')"
                                >
                                    Published
                                </button>
                            </div>
                        </template>
                    </Dropdown>
                </div>
            </div>

            <!-- Sort Filter -->
            <div class="w-full md:w-1/4">
                <InputLabel value="Sort Order" />
                <div class="relative" style="position: static">
                    <Dropdown align="left" width="48">
                        <template #trigger>
                            <SecondaryButton class="w-full justify-between">
                                {{ sortLabel }}
                                <svg
                                    class="ml-2 -mr-0.5 h-4 w-4"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </SecondaryButton>
                        </template>
                        <template #content>
                            <div>
                                <button
                                    class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    @click="updateSort('newest')"
                                >
                                    Newest First
                                </button>
                                <button
                                    class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    @click="updateSort('oldest')"
                                >
                                    Oldest First
                                </button>
                                <button
                                    class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    @click="updateSort('a-z')"
                                >
                                    A-Z
                                </button>
                                <button
                                    class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    @click="updateSort('z-a')"
                                >
                                    Z-A
                                </button>
                            </div>
                        </template>
                    </Dropdown>
                </div>
            </div>

            <div class="w-full md:w-auto">
                <PrimaryButton @click="resetFilters">
                    Reset Filters
                </PrimaryButton>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from "vue";
import { router } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import Dropdown from "@/Components/Dropdown.vue";

const props = defineProps({
    initialFilters: {
        type: Object,
        default: () => ({
            title: "",
            status: "",
            sort: "newest",
        }),
    },
});

// Filters
const filters = ref({
    title: props.initialFilters?.title || "",
    status: props.initialFilters?.status || "",
    sort: props.initialFilters?.sort || "newest",
});

// Computed properties for labels
const statusLabel = computed(() => {
    const labels = {
        "": "All Statuses",
        draft: "Draft",
        published: "Published",
    };
    return labels[filters.value.status] || "All Statuses";
});

const sortLabel = computed(() => {
    const labels = {
        newest: "Newest First",
        oldest: "Oldest First",
        "a-z": "A-Z",
        "z-a": "Z-A",
    };
    return labels[filters.value.sort] || "Newest First";
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

// Update status filter
const updateStatus = (status) => {
    filters.value.status = status;
    performSearch();
};

// Update sort order
const updateSort = (sort) => {
    filters.value.sort = sort;
    performSearch();
};

// Reset all filters
const resetFilters = () => {
    filters.value = {
        title: "",
        status: "",
        sort: "newest",
    };
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

// Watch for filter changes - use dedicated watchers
watch(
    () => filters.value.title,
    (newValue, oldValue) => {
        if (newValue !== oldValue) {
            applyFilters();
        }
    }
);

onMounted(() => {
    // Initialize filters
});
</script>
