<template>
    <div class="mb-6 flex flex-col sm:flex-row gap-4">
        <div class="w-full sm:w-1/2 flex">
            <input
                v-model="filters.search"
                @keyup.enter="applyFilters"
                type="text"
                placeholder="Search agents by name or email..."
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
                        <span>Sort: Newest First</span>
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
                            class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 rounded-md bg-gray-100"
                        >
                            Newest First
                        </button>
                        <button
                            @click="updateSortOrder('oldest')"
                            class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 rounded-md"
                            :class="{
                                'bg-gray-100': filters.sort === 'oldest',
                            }"
                        >
                            Oldest First
                        </button>
                        <button
                            @click="updateSortOrder('a-z')"
                            class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 rounded-md"
                            :class="{
                                'bg-gray-100': filters.sort === 'a-z',
                            }"
                        >
                            A-Z
                        </button>
                        <button
                            @click="updateSortOrder('z-a')"
                            class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 rounded-md"
                            :class="{
                                'bg-gray-100': filters.sort === 'z-a',
                            }"
                        >
                            Z-A
                        </button>
                    </div>
                </template>
            </Dropdown>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
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

// Ensure we have a valid sort value
const getValidSort = (sortValue) => {
    const validSorts = ["newest", "oldest", "a-z", "z-a"];
    return validSorts.includes(sortValue) ? sortValue : "newest";
};

const filters = ref({
    search: props.initialFilters?.search || "",
    sort: getValidSort(props.initialFilters?.sort || "newest"),
});

// Initialize filters on mount to ensure they're set correctly
onMounted(() => {
    // Force sort to be 'newest' by default
    filters.value.sort = "newest";
    emit("update:filters", filters.value);
});

// Update local filters when props change
watch(
    () => props.initialFilters,
    (newFilters) => {
        if (newFilters) {
            filters.value = {
                search: newFilters.search || "",
                sort: getValidSort(newFilters.sort),
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

    // Make sure to return a default value if current sort isn't in the labels object
    return labels[filters.value.sort] || "Newest First";
});

const updateSortOrder = (newSortOrder) => {
    filters.value.sort = newSortOrder;
    emit("update:filters", filters.value);
    applyFilters();
};

const applyFilters = () => {
    emit("apply");
};
</script>
