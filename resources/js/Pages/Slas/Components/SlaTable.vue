<script setup>
import { Link, router } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import Badge from "@/Components/Badge.vue";

const props = defineProps({
    slas: Object,
    can: Object,
});

const emit = defineEmits(["showDeleteModal"]);

// Navigate to SLA detail page
const navigateToSla = (id) => {
    router.visit(route("slas.show", id), {
        preserveState: true,
        preserveScroll: true,
    });
};

// Toggle SLA status
const toggleStatus = (sla, event) => {
    // Prevent the row click event from firing
    event.stopPropagation();

    router.patch(
        route("slas.toggle-status", sla.id),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                // Display success message
            },
        }
    );
};

// Request deletion confirmation
const confirmDelete = (sla, event) => {
    // Prevent the row click event from firing
    event.stopPropagation();

    emit("showDeleteModal", sla);
};

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
</script>

<template>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th
                                class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Name
                            </th>
                            <th
                                class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Priority
                            </th>
                            <th
                                class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Response Time
                            </th>
                            <th
                                class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Resolution Time
                            </th>
                            <th
                                class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Customer
                            </th>
                            <th
                                class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Status
                            </th>
                            <th
                                class="py-3 px-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr
                            v-for="sla in slas.data"
                            :key="sla.id"
                            class="hover:bg-gray-50 cursor-pointer"
                            @click="navigateToSla(sla.id)"
                        >
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ sla.name }}
                                </div>
                                <div
                                    class="text-sm text-gray-500 truncate max-w-xs"
                                >
                                    {{ sla.description }}
                                </div>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <Badge :color="priorityColors[sla.priority]">
                                    {{ capitalize(sla.priority) }}
                                </Badge>
                            </td>
                            <td
                                class="py-4 px-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                {{ sla.formatted_response_time }}
                            </td>
                            <td
                                class="py-4 px-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                {{ sla.formatted_resolution_time }}
                            </td>
                            <td
                                class="py-4 px-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                {{
                                    sla.customer
                                        ? sla.customer.name
                                        : "Global SLA"
                                }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <Badge
                                    :color="sla.is_active ? 'green' : 'gray'"
                                >
                                    {{ sla.is_active ? "Active" : "Inactive" }}
                                </Badge>
                            </td>
                            <td
                                class="py-4 px-4 whitespace-nowrap text-right text-sm font-medium"
                                @click.stop
                            >
                                <div class="flex justify-end space-x-2">
                                    <Link
                                        v-if="can && can.updateSla"
                                        :href="route('slas.edit', sla.id)"
                                        class="text-blue-600 hover:text-blue-900"
                                        @click.stop
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        v-if="can && can.updateSla"
                                        @click="toggleStatus(sla, $event)"
                                        class="text-indigo-600 hover:text-indigo-900"
                                    >
                                        {{
                                            sla.is_active
                                                ? "Deactivate"
                                                : "Activate"
                                        }}
                                    </button>
                                    <button
                                        v-if="can && can.deleteSla"
                                        @click="confirmDelete(sla, $event)"
                                        class="text-red-600 hover:text-red-900"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="slas.data.length === 0">
                            <td
                                colspan="7"
                                class="py-4 px-4 text-center text-gray-500"
                            >
                                No SLAs found.
                                <Link
                                    v-if="can && can.createSla"
                                    :href="route('slas.create')"
                                    class="text-blue-600 hover:underline"
                                    >Create one</Link
                                >.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between mt-6 text-gray-600">
                <p class="text-sm">
                    Showing {{ slas.from }} to {{ slas.to }} of
                    {{ slas.total }} SLAs
                </p>

                <div class="flex gap-1">
                    <Link
                        v-if="slas.prev_page_url"
                        :href="slas.prev_page_url"
                        class="px-4 py-2 border text-sm rounded-md bg-white text-gray-700 hover:bg-gray-50"
                        preserve-scroll
                    >
                        « Previous
                    </Link>
                    <span
                        v-else
                        class="px-4 py-2 border text-sm rounded-md bg-white text-gray-400 opacity-50 cursor-not-allowed"
                    >
                        « Previous
                    </span>

                    <div
                        class="flex px-4 py-2 border text-sm rounded-md bg-gray-900 text-white"
                    >
                        {{ slas.current_page }}
                    </div>

                    <Link
                        v-if="slas.next_page_url"
                        :href="slas.next_page_url"
                        class="px-4 py-2 border text-sm rounded-md bg-white text-gray-700 hover:bg-gray-50"
                        preserve-scroll
                    >
                        Next »
                    </Link>
                    <span
                        v-else
                        class="px-4 py-2 border text-sm rounded-md bg-white text-gray-400 opacity-50 cursor-not-allowed"
                    >
                        Next »
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>
