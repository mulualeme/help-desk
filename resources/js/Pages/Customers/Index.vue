<template>
    <Head title="Customers" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <Breadcrumb :items="[{ label: 'Customers' }]" />
                <PrimaryButton
                    v-if="can.create"
                    @click="router.visit(route('customers.create'))"
                >
                    Add Customer
                </PrimaryButton>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Search and Filter Controls -->
                        <SearchFilters
                            :initial-filters="filters"
                            @update:filters="updateFilters"
                            @apply="applyFilters"
                        />

                        <!-- Customer Table -->
                        <CustomerTable
                            :customers="customers"
                            :can="can"
                            :is-loading="isLoading"
                            :search-query="filters.search"
                            @delete="confirmDelete"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { Head, router, useForm, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import SearchFilters from "./Components/SearchFilters.vue";
import CustomerTable from "./Components/CustomerTable.vue";
import toast from "@/utils/toast";

const props = defineProps({
    customers: Object,
    can: Object,
    filters: Object,
});

const page = usePage();
const deleteForm = useForm({});
const isLoading = ref(false);
const filters = ref({
    search: props.filters?.search || "",
    sort: props.filters?.sort || "newest",
});

// Display flash messages as toasts
onMounted(() => {
    if (page.props.flash?.success) {
        toast.success(page.props.flash.success);
    }
    if (page.props.flash?.error) {
        toast.error(page.props.flash.error);
    }
});

// Update filter values
const updateFilters = (newFilters) => {
    // Only update if values have actually changed to prevent recursive updates
    if (
        filters.value.search !== newFilters.search ||
        filters.value.sort !== newFilters.sort
    ) {
        filters.value = { ...newFilters };
    }
};

// Apply filters and fetch data from the server
const applyFilters = () => {
    isLoading.value = true;

    // Ensure search parameter is properly included in the request
    const params = {
        search: filters.value.search,
        sort: filters.value.sort,
        page: 1, // Reset to first page on filter change
    };

    // Using Inertia.js router to make the request with search parameters
    router.visit(route("customers.index"), {
        data: params,
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            isLoading.value = false;
        },
        onError: (errors) => {
            isLoading.value = false;
            toast.error("Error applying filters");
        },
    });
};

const confirmDelete = (customer) => {
    if (confirm(`Are you sure you want to delete ${customer.name}?`)) {
        deleteForm.delete(route("customers.destroy", customer.id), {
            onSuccess: () => {
                toast.success(`${customer.name} was deleted successfully`);
            },
            onError: (error) => {
                toast.error(error);
            },
        });
    }
};
</script>
