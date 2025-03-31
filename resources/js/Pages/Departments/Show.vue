<template>
    <Head :title="department.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col space-y-4">
                <div class="flex justify-between items-center">
                    <Breadcrumb
                        :items="[
                            {
                                label: 'Departments',
                                href: route('departments.index'),
                            },
                            { label: department.name },
                        ]"
                    />

                    <Link
                        v-if="can.edit"
                        :href="route('departments.edit', department.id)"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Edit Department
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row gap-6">
                    <!-- Main Content - Agent Assignment Section -->
                    <div class="md:w-2/3">
                        <AgentAssignment
                            :department-id="department.id"
                            :agents="department.agents"
                            :can="can"
                            @agent-assigned="refreshData"
                            @agent-removed="refreshData"
                        />
                    </div>

                    <!-- Sidebar - Department Details -->
                    <div class="md:w-1/3">
                        <div
                            class="bg-white overflow-hidden shadow-sm sm:rounded-lg sticky top-6"
                        >
                            <div class="p-6 text-gray-900">
                                <h3
                                    class="text-lg font-medium text-gray-900 mb-4"
                                >
                                    Department Information
                                </h3>
                                <div class="space-y-4">
                                    <div class="flex flex-col">
                                        <dt
                                            class="text-sm font-medium text-gray-500"
                                        >
                                            Name
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ department.name }}
                                        </dd>
                                    </div>

                                    <div class="flex flex-col">
                                        <dt
                                            class="text-sm font-medium text-gray-500"
                                        >
                                            Description
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{
                                                department.description ||
                                                "No description provided"
                                            }}
                                        </dd>
                                    </div>

                                    <div class="flex flex-col">
                                        <dt
                                            class="text-sm font-medium text-gray-500"
                                        >
                                            Status
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">
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
                                        </dd>
                                    </div>

                                    <div class="flex flex-col">
                                        <dt
                                            class="text-sm font-medium text-gray-500"
                                        >
                                            Total Agents
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ department.agents.length }}
                                        </dd>
                                    </div>
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
import { Head, Link, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import AgentAssignment from "./Partials/AgentAssignment.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";

const props = defineProps({
    department: Object,
    can: Object,
});

const refreshData = () => {
    router.reload({ only: ["department"] });
};
</script>
