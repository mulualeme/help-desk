<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Badge from "@/Components/Badge.vue";
import Modal from "@/Components/Modal.vue";

const props = defineProps({
    sla: Object,
    can: Object,
});

// Priority badge colors
const priorityColors = {
    low: "green",
    medium: "blue",
    high: "yellow",
    urgent: "red",
};

// Capitalize first letter
const capitalize = (string) => {
    if (!string) return "";
    return string.charAt(0).toUpperCase() + string.slice(1);
};

// Delete modal state
const showDeleteModal = ref(false);

// Toggle SLA status
const toggleStatus = () => {
    router.patch(
        route("slas.toggle-status", props.sla.id),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                // Success handled by flash messages
            },
        }
    );
};

// Delete the SLA
const deleteSla = () => {
    router.delete(route("slas.destroy", props.sla.id), {
        onSuccess: () => {
            // Redirect is handled by the controller
        },
    });
};
</script>

<template>
    <Head :title="sla.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <Breadcrumb
                    :items="[
                        { label: 'SLAs', href: route('slas.index') },
                        { label: sla.name },
                    ]"
                />

                <div class="flex space-x-2">
                    <Link
                        v-if="can.updateSla"
                        :href="route('slas.edit', sla.id)"
                    >
                        <PrimaryButton> Edit SLA </PrimaryButton>
                    </Link>
                    <SecondaryButton v-if="can.updateSla" @click="toggleStatus">
                        {{ sla.is_active ? "Deactivate" : "Activate" }}
                    </SecondaryButton>
                    <DangerButton
                        v-if="can.deleteSla"
                        @click="showDeleteModal = true"
                    >
                        Delete
                    </DangerButton>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-1 md:col-span-2">
                                <div class="flex items-center justify-between">
                                    <h2
                                        class="text-2xl font-semibold text-gray-800"
                                    >
                                        {{ sla.name }}
                                    </h2>
                                    <div class="flex space-x-2 items-center">
                                        <Badge
                                            :color="
                                                priorityColors[sla.priority]
                                            "
                                            class="text-sm"
                                        >
                                            {{
                                                capitalize(sla.priority)
                                            }}
                                            Priority
                                        </Badge>
                                        <Badge
                                            :color="
                                                sla.is_active ? 'green' : 'gray'
                                            "
                                            class="text-sm"
                                        >
                                            {{
                                                sla.is_active
                                                    ? "Active"
                                                    : "Inactive"
                                            }}
                                        </Badge>
                                    </div>
                                </div>
                                <p
                                    v-if="sla.description"
                                    class="mt-2 text-gray-600"
                                >
                                    {{ sla.description }}
                                </p>
                            </div>

                            <div>
                                <h3
                                    class="text-lg font-medium text-gray-800 mb-2"
                                >
                                    SLA Details
                                </h3>
                                <div
                                    class="bg-gray-50 rounded-lg p-4 space-y-4"
                                >
                                    <div>
                                        <div
                                            class="text-sm font-medium text-gray-500"
                                        >
                                            Response Time
                                        </div>
                                        <div class="text-base text-gray-900">
                                            {{ sla.formatted_response_time }}
                                        </div>
                                    </div>
                                    <div>
                                        <div
                                            class="text-sm font-medium text-gray-500"
                                        >
                                            Resolution Time
                                        </div>
                                        <div class="text-base text-gray-900">
                                            {{ sla.formatted_resolution_time }}
                                        </div>
                                    </div>
                                    <div>
                                        <div
                                            class="text-sm font-medium text-gray-500"
                                        >
                                            Customer
                                        </div>
                                        <div class="text-base text-gray-900">
                                            {{
                                                sla.customer
                                                    ? sla.customer.name
                                                    : "Global SLA (All Customers)"
                                            }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3
                                    class="text-lg font-medium text-gray-800 mb-2"
                                >
                                    Additional Information
                                </h3>
                                <div
                                    class="bg-gray-50 rounded-lg p-4 space-y-4"
                                >
                                    <div>
                                        <div
                                            class="text-sm font-medium text-gray-500"
                                        >
                                            Created By
                                        </div>
                                        <div class="text-base text-gray-900">
                                            {{
                                                sla.creator
                                                    ? sla.creator.name
                                                    : "Unknown"
                                            }}
                                        </div>
                                    </div>
                                    <div>
                                        <div
                                            class="text-sm font-medium text-gray-500"
                                        >
                                            Created At
                                        </div>
                                        <div class="text-base text-gray-900">
                                            {{
                                                new Date(
                                                    sla.created_at
                                                ).toLocaleString()
                                            }}
                                        </div>
                                    </div>
                                    <div>
                                        <div
                                            class="text-sm font-medium text-gray-500"
                                        >
                                            Last Updated
                                        </div>
                                        <div class="text-base text-gray-900">
                                            {{
                                                new Date(
                                                    sla.updated_at
                                                ).toLocaleString()
                                            }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <Modal :show="showDeleteModal" @close="showDeleteModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">Delete SLA</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Are you sure you want to delete this SLA? This action cannot
                    be undone.
                </p>
                <div class="mt-6 flex justify-end space-x-3">
                    <SecondaryButton @click="showDeleteModal = false">
                        Cancel
                    </SecondaryButton>
                    <DangerButton @click="deleteSla" class="ml-3">
                        Delete SLA
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
