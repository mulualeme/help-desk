<template>
    <Head title="Customers" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <Breadcrumb :items="[{ label: 'Customers' }]" />
                <PrimaryButton
                    v-if="can.create"
                    @click="$inertia.visit(route('customers.create'))"
                >
                    Add Customer
                </PrimaryButton>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="mb-6 flex flex-col sm:flex-row gap-4">
                            <div class="w-full sm:w-1/2">
                                <input
                                    v-model="search"
                                    type="text"
                                    placeholder="Search customers..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                />
                            </div>
                            <div class="flex gap-2">
                                <Dropdown width="48">
                                    <template #trigger>
                                        <button
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-medium text-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                        >
                                            <span
                                                >Sort:
                                                {{ sortOrderLabel }}</span
                                            >
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
                                                @click="sortOrder = 'newest'"
                                                class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 rounded-md"
                                                :class="{
                                                    'bg-gray-100':
                                                        sortOrder === 'newest',
                                                }"
                                            >
                                                Newest First
                                            </button>
                                            <button
                                                @click="sortOrder = 'oldest'"
                                                class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 rounded-md"
                                                :class="{
                                                    'bg-gray-100':
                                                        sortOrder === 'oldest',
                                                }"
                                            >
                                                Oldest First
                                            </button>
                                            <button
                                                @click="sortOrder = 'a-z'"
                                                class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 rounded-md"
                                                :class="{
                                                    'bg-gray-100':
                                                        sortOrder === 'a-z',
                                                }"
                                            >
                                                A-Z
                                            </button>
                                            <button
                                                @click="sortOrder = 'z-a'"
                                                class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 rounded-md"
                                                :class="{
                                                    'bg-gray-100':
                                                        sortOrder === 'z-a',
                                                }"
                                            >
                                                Z-A
                                            </button>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Name
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Email
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Created
                                        </th>
                                        <th
                                            scope="col"
                                            class="relative px-6 py-3"
                                        >
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr v-if="filteredCustomers.length === 0">
                                        <td
                                            colspan="4"
                                            class="px-6 py-4 text-center text-gray-500"
                                        >
                                            No customers found.
                                        </td>
                                    </tr>
                                    <tr
                                        v-for="customer in filteredCustomers"
                                        :key="customer.id"
                                        @click="navigateToCustomer(customer)"
                                        class="hover:bg-gray-50 cursor-pointer"
                                    >
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ customer.name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                {{ customer.email }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                {{
                                                    formatDate(
                                                        customer.created_at
                                                    )
                                                }}
                                            </div>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                                            @click.stop
                                        >
                                            <Link
                                                v-if="can.edit"
                                                :href="
                                                    route(
                                                        'customers.edit',
                                                        customer.id
                                                    )
                                                "
                                                class="text-indigo-600 hover:text-indigo-900"
                                            >
                                                Edit
                                            </Link>
                                            <button
                                                v-if="can.delete"
                                                @click="confirmDelete(customer)"
                                                class="text-red-600 hover:text-red-900 ml-3"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <Pagination
                            v-if="!search && sortOrder === 'newest'"
                            :links="customers.links"
                            :meta="{
                                from: customers.from,
                                to: customers.to,
                                total: customers.total,
                            }"
                            itemName="customers"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Pagination from "@/Components/Pagination.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import { format } from "date-fns";
import Dropdown from "@/Components/Dropdown.vue";

const props = defineProps({
    customers: Object,
    can: Object,
});

const search = ref("");
const deleteForm = useForm({});
const sortOrder = ref("newest");

const filteredCustomers = computed(() => {
    if (!props.customers.data) return [];

    let filtered = props.customers.data.filter((customer) => {
        const searchLower = search.value.toLowerCase();
        return (
            customer.name.toLowerCase().includes(searchLower) ||
            customer.email.toLowerCase().includes(searchLower)
        );
    });

    // Apply sorting
    switch (sortOrder.value) {
        case "oldest":
            filtered.sort(
                (a, b) => new Date(a.created_at) - new Date(b.created_at)
            );
            break;
        case "newest":
            filtered.sort(
                (a, b) => new Date(b.created_at) - new Date(a.created_at)
            );
            break;
        case "a-z":
            filtered.sort((a, b) => a.name.localeCompare(b.name));
            break;
        case "z-a":
            filtered.sort((a, b) => b.name.localeCompare(a.name));
            break;
    }

    return filtered;
});

const navigateToCustomer = (customer) => {
    router.visit(route("customers.show", customer.id), {
        preserveState: true,
        preserveScroll: true,
    });
};

const confirmDelete = (customer) => {
    if (confirm(`Are you sure you want to delete ${customer.name}?`)) {
        deleteForm.delete(route("customers.destroy", customer.id));
    }
};

const formatDate = (dateString) => {
    if (!dateString) return "";

    const date = new Date(dateString);
    return format(date, "MMM d, yyyy");
};

const sortOrderLabel = computed(() => {
    switch (sortOrder.value) {
        case "newest":
            return "Newest First";
        case "oldest":
            return "Oldest First";
        case "a-z":
            return "A-Z";
        case "z-a":
            return "Z-A";
        default:
            return "Sort";
    }
});
</script>
