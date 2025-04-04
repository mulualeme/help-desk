<template>
    <Head :title="customer.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <Breadcrumb
                    :items="[
                        { label: 'Customers', href: route('customers.index') },
                        { label: customer.name },
                    ]"
                />
                <div v-if="can.edit">
                    <PrimaryButton
                        @click="
                            router.visit(route('customers.edit', customer.id))
                        "
                    >
                        Edit Customer
                    </PrimaryButton>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-medium mb-4">
                                    Customer Information
                                </h3>
                                <div class="mb-4">
                                    <span
                                        class="block text-sm font-medium text-gray-500"
                                        >Name</span
                                    >
                                    <span class="block mt-1">{{
                                        customer.name
                                    }}</span>
                                </div>
                                <div class="mb-4">
                                    <span
                                        class="block text-sm font-medium text-gray-500"
                                        >Email</span
                                    >
                                    <span class="block mt-1">{{
                                        customer.email
                                    }}</span>
                                </div>
                                <div class="mb-4">
                                    <span
                                        class="block text-sm font-medium text-gray-500"
                                        >Created</span
                                    >
                                    <span class="block mt-1">{{
                                        formatDate(customer.created_at)
                                    }}</span>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium mb-4">
                                    Recent Tickets
                                </h3>
                                <div
                                    v-if="tickets.length > 0"
                                    class="space-y-4"
                                >
                                    <div
                                        v-for="ticket in tickets"
                                        :key="ticket.id"
                                        class="border p-4 rounded-md"
                                    >
                                        <div
                                            class="flex justify-between items-start"
                                        >
                                            <div>
                                                <h4 class="font-medium">
                                                    {{ ticket.title }}
                                                </h4>
                                                <p
                                                    class="text-sm text-gray-500"
                                                >
                                                    {{
                                                        formatDate(
                                                            ticket.created_at
                                                        )
                                                    }}
                                                </p>
                                            </div>
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                :class="{
                                                    'bg-yellow-100 text-yellow-800':
                                                        ticket.status ===
                                                        'open',
                                                    'bg-blue-100 text-blue-800':
                                                        ticket.status ===
                                                        'in_progress',
                                                    'bg-green-100 text-green-800':
                                                        ticket.status ===
                                                        'resolved',
                                                    'bg-red-100 text-red-800':
                                                        ticket.status ===
                                                        'closed',
                                                }"
                                            >
                                                {{
                                                    formatStatus(ticket.status)
                                                }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="text-gray-500 italic">
                                    No tickets found for this customer.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import { onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useToast } from "@/Composables/useToast";

const props = defineProps({
    customer: Object,
    tickets: Array,
    can: Object,
});

const page = usePage();
const toast = useToast();

// Display flash messages if present
onMounted(() => {
    if (page.props.flash?.success) {
        toast.success(page.props.flash.success);
    }
    if (page.props.flash?.error) {
        toast.error(page.props.flash.error);
    }
});

const formatDate = (dateString) => {
    const options = { year: "numeric", month: "long", day: "numeric" };
    return new Date(dateString).toLocaleDateString(undefined, options);
};

const formatStatus = (status) => {
    const statusMap = {
        open: "Open",
        in_progress: "In Progress",
        resolved: "Resolved",
        closed: "Closed",
    };

    return statusMap[status] || status;
};
</script>
