<template>
    <Head title="Departments" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col space-y-4">
                <div class="flex justify-between items-center">
                    <Breadcrumb :items="[{ label: 'Departments' }]" />
                    <Link
                        v-if="can.create"
                        :href="route('departments.create')"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Create Department
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
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
                                        v-for="department in departments"
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    departments: Array,
    can: Object,
});

const form = useForm({});

const deleteDepartment = (department) => {
    if (confirm(`Are you sure you want to delete ${department.name}?`)) {
        form.delete(route("departments.destroy", department.id));
    }
};
</script>
