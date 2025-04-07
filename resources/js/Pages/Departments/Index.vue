<template>
    <Head title="Departments" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col space-y-4">
                <div class="flex justify-between items-center">
                    <Breadcrumb :items="[{ label: 'Departments' }]" />
                    <PrimaryButton
                        v-if="can.create"
                        @click="showCreateModal = true"
                    >
                        Create Department
                    </PrimaryButton>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Search and Filter Components -->
                <SearchFilters
                    :initial-filters="filters"
                    @update:filters="updateFilters"
                    @apply="applyFilters"
                />

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Departments Table Component -->
                        <DepartmentTable
                            :departments="departments"
                            :can="can"
                            :is-loading="isLoading"
                            @openDeleteModal="openDeleteModal"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Department Modal -->
        <CreateDepartmentModal
            :show="showCreateModal"
            @close="showCreateModal = false"
        />

        <!-- Delete Department Modal -->
        <DeleteConfirmationModal
            :show="showDeleteModal"
            :department="departmentToDelete"
            @close="closeDeleteModal"
            @confirm="confirmDelete"
        />
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from "vue";
import { Head, router } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import { useToast } from "@/Composables/useToast";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import CreateDepartmentModal from "./Partials/CreateDepartmentModal.vue";
import DeleteConfirmationModal from "./Components/DeleteConfirmationModal.vue";
import SearchFilters from "./Components/SearchFilters.vue";
import DepartmentTable from "./Components/DepartmentTable.vue";

const props = defineProps({
    departments: Object,
    can: Object,
    filters: Object,
});

const form = useForm({});
const showCreateModal = ref(false);
const toast = useToast();
const isLoading = ref(false);
const filters = ref({
    search: props.filters?.search || "",
    status: props.filters?.status || "all",
    sort: props.filters?.sort || "newest",
});

// Delete modal state
const showDeleteModal = ref(false);
const departmentToDelete = ref(null);

// Update filter values without triggering a request
const updateFilters = (newFilters) => {
    filters.value = { ...newFilters };
};

// Apply filters and fetch data from the server
const applyFilters = () => {
    isLoading.value = true;

    router.get(
        route("departments.index"),
        {
            search: filters.value.search,
            status: filters.value.status,
            sort: filters.value.sort,
            page: 1, // Reset to first page on filter change
        },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                isLoading.value = false;
            },
            onError: (error) => {
                isLoading.value = false;
                toast.error("Error applying filters");
            },
        }
    );
};

// Clear search filter
const clearSearch = () => {
    filters.value.search = "";
    applyFilters();
};

// Clear status filter
const clearStatusFilter = () => {
    filters.value.status = "all";
    applyFilters();
};

// Clear sort filter
const clearSortOrder = () => {
    filters.value.sort = "newest";
    applyFilters();
};

// Reset all filters
const resetAllFilters = () => {
    filters.value = {
        search: "",
        status: "all",
        sort: "newest",
    };
    applyFilters();
};

// Open delete confirmation modal
const openDeleteModal = (department) => {
    departmentToDelete.value = department;
    showDeleteModal.value = true;
};

// Close delete modal
const closeDeleteModal = () => {
    showDeleteModal.value = false;
    setTimeout(() => {
        departmentToDelete.value = null;
    }, 300);
};

// Confirm delete action
const confirmDelete = (department) => {
    form.delete(route("departments.destroy", department.id), {
        onSuccess: () => {
            toast.success(`${department.name} was deleted successfully`);
        },
        onError: (error) => {
            toast.error(error);
        },
    });
};
</script>
