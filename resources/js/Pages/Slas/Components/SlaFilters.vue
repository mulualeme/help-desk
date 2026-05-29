<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Dropdown from "@/Components/Dropdown.vue";

const props = defineProps({
    filters: Object,
});

const searchInput = ref(props.filters.search || "");

// Submit search on button click
const submitSearch = () => {
    router.get(
        route("slas.index"),
        {
            search: searchInput.value,
            priority: props.filters.priority,
            status: props.filters.status,
        },
        { preserveState: true, replace: true }
    );
};

// Filter by priority
const filterByPriority = (priority) => {
    router.get(
        route("slas.index"),
        {
            search: searchInput.value,
            priority: priority,
            status: props.filters.status,
        },
        { preserveState: true, replace: true }
    );
};

// Filter by status
const filterByStatus = (status) => {
    router.get(
        route("slas.index"),
        {
            search: searchInput.value,
            priority: props.filters.priority,
            status: status,
        },
        { preserveState: true, replace: true }
    );
};

// Capitalize first letter
const capitalize = (string) => {
    if (!string) return "";
    return string.charAt(0).toUpperCase() + string.slice(1);
};
</script>

<template>
    <div
        class="bg-white overflow-visible shadow-sm sm:rounded-lg mb-6 p-6"
        style="position: relative; z-index: 30"
    >
        <div class="flex flex-wrap gap-4 items-end">
            <!-- Search -->
            <div class="w-full md:w-1/3">
                <InputLabel for="search" value="Search SLAs" />
                <div class="mt-1 flex">
                    <TextInput
                        id="search"
                        v-model="searchInput"
                        type="text"
                        class="block w-full rounded-r-none"
                        placeholder="Search by name or description"
                        @keyup.enter="submitSearch"
                    />
                    <PrimaryButton @click="submitSearch" class="rounded-l-none">
                        Search
                    </PrimaryButton>
                </div>
            </div>

            <!-- Priority Filter -->
            <div class="w-full md:w-1/4">
                <InputLabel value="Filter by Priority" />
                <div class="relative" style="position: static">
                    <Dropdown align="left" width="48">
                        <template #trigger>
                            <SecondaryButton class="w-full justify-between">
                                {{
                                    filters.priority
                                        ? capitalize(filters.priority)
                                        : "All Priorities"
                                }}
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
                                    @click="filterByPriority(null)"
                                >
                                    All Priorities
                                </button>
                                <button
                                    class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    @click="filterByPriority('low')"
                                >
                                    Low
                                </button>
                                <button
                                    class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    @click="filterByPriority('medium')"
                                >
                                    Medium
                                </button>
                                <button
                                    class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    @click="filterByPriority('high')"
                                >
                                    High
                                </button>
                                <button
                                    class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    @click="filterByPriority('urgent')"
                                >
                                    Urgent
                                </button>
                            </div>
                        </template>
                    </Dropdown>
                </div>
            </div>

            <!-- Status Filter -->
            <div class="w-full md:w-1/4">
                <InputLabel value="Filter by Status" />
                <div class="relative" style="position: static">
                    <Dropdown align="left" width="48">
                        <template #trigger>
                            <SecondaryButton class="w-full justify-between">
                                {{
                                    filters.status
                                        ? filters.status === "active"
                                            ? "Active"
                                            : "Inactive"
                                        : "All Statuses"
                                }}
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
                                    @click="filterByStatus(null)"
                                >
                                    All Statuses
                                </button>
                                <button
                                    class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    @click="filterByStatus('active')"
                                >
                                    Active
                                </button>
                                <button
                                    class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    @click="filterByStatus('inactive')"
                                >
                                    Inactive
                                </button>
                            </div>
                        </template>
                    </Dropdown>
                </div>
            </div>

            <div class="w-full md:w-auto">
                <PrimaryButton
                    @click="
                        router.visit(route('slas.index'), {
                            only: ['slas'],
                        })
                    "
                >
                    Reset Filters
                </PrimaryButton>
            </div>
        </div>
    </div>
</template>
