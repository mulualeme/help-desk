<template>
    <div>
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
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="customers.data.length === 0">
                        <td
                            colspan="4"
                            class="px-6 py-4 text-center text-sm text-gray-500"
                        >
                            <div
                                v-if="isLoading"
                                class="flex justify-center items-center"
                            >
                                <svg
                                    class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-500"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"
                                    ></circle>
                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                    ></path>
                                </svg>
                                Loading customers...
                            </div>
                            <div v-else-if="hasSearchQuery">
                                <p>
                                    No customers match your search "<span
                                        class="font-medium"
                                        >{{ searchQuery }}</span
                                    >"
                                </p>
                                <p class="mt-2">
                                    Try adjusting your search or filter criteria
                                </p>
                            </div>
                            <div v-else>No customers found.</div>
                        </td>
                    </tr>
                    <tr
                        v-for="customer in customers.data"
                        :key="customer.id"
                        @click="navigateToCustomer(customer.id)"
                        class="hover:bg-gray-50 cursor-pointer"
                    >
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
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
                                {{ formatDate(customer.created_at) }}
                            </div>
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                            @click.stop
                        >
                            <Link
                                v-if="can.edit"
                                :href="route('customers.edit', customer.id)"
                                class="text-indigo-600 hover:text-indigo-900"
                            >
                                Edit
                            </Link>
                            <button
                                v-if="can.delete"
                                @click.stop="$emit('delete', customer)"
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
            :links="customers.links"
            :meta="{
                from: customers.from,
                to: customers.to,
                total: customers.total,
            }"
            itemName="customers"
        />
    </div>
</template>

<script setup>
import { Link, router } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import { format } from "date-fns";
import { computed } from "vue";

const props = defineProps({
    customers: {
        type: Object,
        required: true,
    },
    can: {
        type: Object,
        required: true,
    },
    isLoading: {
        type: Boolean,
        default: false,
    },
    searchQuery: {
        type: String,
        default: "",
    },
});

const hasSearchQuery = computed(() => {
    return props.searchQuery && props.searchQuery.trim().length > 0;
});

defineEmits(["delete"]);

const navigateToCustomer = (id) => {
    router.visit(route("customers.show", id), {
        preserveState: true,
        preserveScroll: true,
    });
};

const formatDate = (dateString) => {
    if (!dateString) return "";
    return format(new Date(dateString), "MMM d, yyyy");
};
</script>
