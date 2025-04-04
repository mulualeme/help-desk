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
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Search and Filter Controls -->
                        <div class="mb-6 flex flex-col sm:flex-row gap-4">
                            <div class="w-full sm:w-1/2">
                                <input
                                    v-model="search"
                                    type="text"
                                    placeholder="Search departments..."
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
                                                >Status:
                                                {{ statusFilterLabel }}</span
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
                                                @click="statusFilter = 'all'"
                                                class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 rounded-md"
                                                :class="{
                                                    'bg-gray-100':
                                                        statusFilter === 'all',
                                                }"
                                            >
                                                All Statuses
                                            </button>
                                            <button
                                                @click="statusFilter = 'active'"
                                                class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 rounded-md"
                                                :class="{
                                                    'bg-gray-100':
                                                        statusFilter ===
                                                        'active',
                                                }"
                                            >
                                                Active
                                            </button>
                                            <button
                                                @click="
                                                    statusFilter = 'inactive'
                                                "
                                                class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 rounded-md"
                                                :class="{
                                                    'bg-gray-100':
                                                        statusFilter ===
                                                        'inactive',
                                                }"
                                            >
                                                Inactive
                                            </button>
                                        </div>
                                    </template>
                                </Dropdown>

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
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr
                                        v-for="department in filteredDepartments"
                                        :key="department.id"
                                        class="group hover:bg-gray-50 cursor-pointer"
                                    >
                                        <Link
                                            v-if="can.view"
                                            :href="
                                                route(
                                                    'departments.show',
                                                    department.id
                                                )
                                            "
                                            class="contents"
                                        >
                                            <td
                                                class="px-6 py-4 whitespace-nowrap"
                                            >
                                                {{ department.name }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ department.description }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap"
                                            >
                                                {{ department.agents_count }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap"
                                            >
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
                                                :href="
                                                    route(
                                                        'departments.edit',
                                                        department.id
                                                    )
                                                "
                                                class="text-indigo-600 hover:text-indigo-900 mr-3"
                                            >
                                                Edit
                                            </Link>
                                            <button
                                                v-if="can.delete"
                                                @click.stop="
                                                    deleteDepartment(department)
                                                "
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="filteredDepartments.length === 0">
                                        <td
                                            colspan="5"
                                            class="px-6 py-4 text-center text-sm text-gray-500"
                                        >
                                            No departments found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <Pagination
                            v-if="
                                !search &&
                                statusFilter === 'all' &&
                                sortOrder === 'newest'
                            "
                            :links="departments.links"
                            :meta="{
                                from: departments.from,
                                to: departments.to,
                                total: departments.total,
                            }"
                            itemName="departments"
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
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm } from "@inertiajs/vue3";
import CreateDepartmentModal from "./Partials/CreateDepartmentModal.vue";
import Dropdown from "@/Components/Dropdown.vue";
import { useToast } from "@/Composables/useToast";

const props = defineProps({
    departments: Object,
    can: Object,
});

const form = useForm({});
const showCreateModal = ref(false);
const search = ref("");
const statusFilter = ref("all");
const sortOrder = ref("newest");
const toast = useToast();

const statusFilterLabel = computed(() => {
    const labels = {
        all: "All Statuses",
        active: "Active",
        inactive: "Inactive",
    };
    return labels[statusFilter.value];
});

const sortOrderLabel = computed(() => {
    const labels = {
        newest: "Newest First",
        oldest: "Oldest First",
        "a-z": "A-Z",
        "z-a": "Z-A",
    };
    return labels[sortOrder.value];
});

const filteredDepartments = computed(() => {
    // First, filter the departments
    let result = [...props.departments.data];

    if (search.value || statusFilter.value !== "all") {
        result = result.filter((department) => {
            // Filter by search term
            const matchesSearch =
                !search.value ||
                department.name
                    .toLowerCase()
                    .includes(search.value.toLowerCase()) ||
                (department.description &&
                    department.description
                        .toLowerCase()
                        .includes(search.value.toLowerCase()));

            // Filter by status
            const matchesStatus =
                statusFilter.value === "all" ||
                (statusFilter.value === "active" && department.is_active) ||
                (statusFilter.value === "inactive" && !department.is_active);

            return matchesSearch && matchesStatus;
        });
    }

    // Then, sort the filtered results
    switch (sortOrder.value) {
        case "a-z":
            result.sort((a, b) => a.name.localeCompare(b.name));
            break;
        case "z-a":
            result.sort((a, b) => b.name.localeCompare(a.name));
            break;
        case "oldest":
            result.sort(
                (a, b) => new Date(a.created_at) - new Date(b.created_at)
            );
            break;
        case "newest":
        default:
            result.sort(
                (a, b) => new Date(b.created_at) - new Date(a.created_at)
            );
            break;
    }

    return result;
});

const deleteDepartment = (department) => {
    if (confirm(`Are you sure you want to delete ${department.name}?`)) {
        form.delete(route("departments.destroy", department.id), {
            onSuccess: () => {
                toast.success(`${department.name} was deleted successfully`);
            },
            onError: (error) => {
                toast.error(error);
            },
        });
    }
};
</script>
