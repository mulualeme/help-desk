<template>
    <div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Name
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Description
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Agents
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Status
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                        v-for="department in departments.data"
                        :key="department.id"
                        class="group hover:bg-gray-50 cursor-pointer"
                    >
                        <Link
                            v-if="can.view"
                            :href="route('departments.show', department.id)"
                            class="contents"
                        >
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ department.name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ department.description }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ department.agents_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="[
                                        'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                        department.is_active
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800',
                                    ]"
                                >
                                    {{
                                        department.is_active
                                            ? "Active"
                                            : "Inactive"
                                    }}
                                </span>
                            </td>
                        </Link>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                        >
                            <Link
                                v-if="can.edit"
                                :href="route('departments.edit', department.id)"
                                class="text-indigo-600 hover:text-indigo-900 mr-3"
                            >
                                Edit
                            </Link>
                            <button
                                v-if="can.delete"
                                @click.stop="
                                    $emit('openDeleteModal', department)
                                "
                                class="text-red-600 hover:text-red-900"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                    <tr v-if="departments.data.length === 0">
                        <td
                            colspan="5"
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
                                Loading departments...
                            </div>
                            <div v-else>No departments found.</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination
            :links="departments.links"
            :meta="{
                from: departments.from,
                to: departments.to,
                total: departments.total,
            }"
            itemName="departments"
        />
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";

defineProps({
    departments: Object,
    can: Object,
    isLoading: Boolean,
});

defineEmits(["delete"]);
</script>
