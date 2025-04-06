<template>
    <div>
        <!-- Search and Filter Controls -->
        <div class="mb-6 flex flex-col sm:flex-row gap-4">
            <div class="w-full sm:w-1/2 flex">
                <input
                    v-model="search"
                    @keyup.enter="applyFilters"
                    type="text"
                    placeholder="Search by department name..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-l-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                />
                <PrimaryButton @click="applyFilters" class="rounded-l-none">
                    Search
                </PrimaryButton>
            </div>
            <div class="flex gap-2">
                <Dropdown width="48">
                    <template #trigger>
                        <button
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-medium text-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            <span>Status: {{ statusFilterLabel }}</span>
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
                        </button>
                    </template>

                    <template #content>
                        <div class="p-1">
                            <button
                                @click="updateStatusFilter('all')"
                                class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 rounded-md"
                                :class="{
                                    'bg-gray-100': statusFilter === 'all',
                                }"
                            >
                                All Statuses
                            </button>
                            <button
                                @click="updateStatusFilter('active')"
                                class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 rounded-md"
                                :class="{
                                    'bg-gray-100': statusFilter === 'active',
                                }"
                            >
                                Active
                            </button>
                            <button
                                @click="updateStatusFilter('inactive')"
                                class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 rounded-md"
                                :class="{
                                    'bg-gray-100': statusFilter === 'inactive',
                                }"
                            >
                                Inactive
                            </button>
                        </div>
                    </template>
                </Dropdown>

                <Dropdown width="48">
                    <template #trigger>
                        <button
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-medium text-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            <span>Sort: {{ sortOrderLabel }}</span>
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
                        </button>
                    </template>

                    <template #content>
                        <div class="p-1">
                            <button
                                @click="updateSortOrder('newest')"
                                class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 rounded-md"
                                :class="{
                                    'bg-gray-100': sortOrder === 'newest',
                                }"
                            >
                                Newest First
                            </button>
                            <button
                                @click="updateSortOrder('oldest')"
                                class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 rounded-md"
                                :class="{
                                    'bg-gray-100': sortOrder === 'oldest',
                                }"
                            >
                                Oldest First
                            </button>
                            <button
                                @click="updateSortOrder('a-z')"
                                class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 rounded-md"
                                :class="{
                                    'bg-gray-100': sortOrder === 'a-z',
                                }"
                            >
                                A-Z
                            </button>
                            <button
                                @click="updateSortOrder('z-a')"
                                class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 rounded-md"
                                :class="{
                                    'bg-gray-100': sortOrder === 'z-a',
                                }"
                            >
                                Z-A
                            </button>
                        </div>
                    </template>
                </Dropdown>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Dropdown from "@/Components/Dropdown.vue";

const props = defineProps({
    initialFilters: Object,
});

const emit = defineEmits(["update:filters", "apply"]);

const search = ref(props.initialFilters?.search || "");
const statusFilter = ref(props.initialFilters?.status || "all");
const sortOrder = ref(props.initialFilters?.sort || "newest");

const statusFilterLabel = computed(() => {
    const labels = {
        all: "All Statuses",
        active: "Active",
        inactive: "Inactive",
    };
    return labels[statusFilter.value];
});

const sortOrderLabel = computed(() => {
    const labels = {
        newest: "Newest First",
        oldest: "Oldest First",
        "a-z": "A-Z",
        "z-a": "Z-A",
    };
    return labels[sortOrder.value];
});

// Update the parent component with new filter values
const updateFilters = () => {
    emit("update:filters", {
        search: search.value,
        status: statusFilter.value,
        sort: sortOrder.value,
    });
};

// Apply filters and trigger parent fetch
const applyFilters = () => {
    updateFilters();
    emit("apply");
};

// Handle status filter changes
const updateStatusFilter = (status) => {
    statusFilter.value = status;
    applyFilters();
};

// Handle sort order changes
const updateSortOrder = (sort) => {
    sortOrder.value = sort;
    applyFilters();
};

// Watch for changes to update parent component state
watch([search, statusFilter, sortOrder], () => {
    updateFilters();
});
</script>
