<template>
    <div
        class="bg-white overflow-visible shadow-sm sm:rounded-lg mb-6 p-6"
        style="position: relative; z-index: 30"
    >
        <div class="flex flex-wrap gap-4 items-end">
            <!-- Search -->
            <div class="w-full md:w-1/3">
                <InputLabel for="search" value="Search Customers" />
                <div class="mt-1 flex">
                    <TextInput
                        id="search"
                        v-model="filters.search"
                        type="text"
                        class="block w-full rounded-r-none"
                        placeholder="Search by name or email..."
                        @keyup.enter="applyFilters"
                    />
                    <PrimaryButton @click="applyFilters" class="rounded-l-none">
                        Search
                    </PrimaryButton>
                </div>
            </div>

            <!-- Sort Filter -->
            <div class="w-full md:w-1/4">
                <InputLabel value="Sort Order" />
                <div class="relative" style="position: static">
                    <Dropdown align="left" width="48">
                        <template #trigger>
                            <SecondaryButton class="w-full justify-between">
                                {{ sortOrderLabel }}
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
                                    @click="updateSortOrder('newest')"
                                >
                                    Newest First
                                </button>
                                <button
                                    class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    @click="updateSortOrder('oldest')"
                                >
                                    Oldest First
                                </button>
                                <button
                                    class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    @click="updateSortOrder('a-z')"
                                >
                                    A-Z
                                </button>
                                <button
                                    class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                    @click="updateSortOrder('z-a')"
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
            search: "",
            sort: "newest",
        }),
    },
});

const emit = defineEmits(["update:filters", "apply"]);

const filters = ref({
    search: props.initialFilters.search || "",
    sort: props.initialFilters.sort || "newest",
});

// Initialize filters on mount
onMounted(() => {
    emit("update:filters", filters.value);
});

// Update local filters when props change
watch(
    () => props.initialFilters,
    (newFilters) => {
        if (newFilters) {
            filters.value = {
                search: newFilters.search || "",
                sort: newFilters.sort || "newest",
            };
        }
    },
    { deep: true }
);

const sortOrderLabel = computed(() => {
    const labels = {
        newest: "Newest First",
        oldest: "Oldest First",
        "a-z": "A-Z",
        "z-a": "Z-A",
    };
    return labels[filters.value.sort];
});

const updateSortOrder = (newSortOrder) => {
    filters.value.sort = newSortOrder;
    emit("update:filters", filters.value);
    applyFilters();
};

const applyFilters = () => {
    emit("update:filters", filters.value);
    emit("apply");
};

// Reset filters
const resetFilters = () => {
    filters.value = {
        search: "",
        sort: "newest",
    };
    emit("update:filters", filters.value);
    emit("apply");
};
</script>
