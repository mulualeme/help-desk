<template>
    <Head :title="`Agent: ${agent.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <Breadcrumb
                        :items="[
                            { label: 'Agents', href: route('agents.index') },
                            { label: agent.name },
                        ]"
                    />
                </div>
                <div class="flex space-x-2">
                    <PrimaryButton
                        v-if="can.edit_roles"
                        @click="router.visit(route('agents.edit', agent.id))"
                    >
                        Change Role
                    </PrimaryButton>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Agent Info Card -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6"
                >
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Agent Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="text-sm font-medium text-gray-500">
                                    Name
                                </div>
                                <div class="mt-1 text-sm text-gray-900">
                                    {{ agent.name }}
                                </div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500">
                                    Email
                                </div>
                                <div class="mt-1 text-sm text-gray-900">
                                    {{ agent.email }}
                                </div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500">
                                    Departments
                                </div>
                                <div class="mt-1 text-sm text-gray-900">
                                    <span
                                        v-if="
                                            agent.departments &&
                                            agent.departments.length > 0
                                        "
                                    >
                                        <span
                                            v-for="(
                                                department, index
                                            ) in agent.departments"
                                            :key="department.id"
                                            class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800 mr-2"
                                        >
                                            {{ department.name }}
                                        </span>
                                    </span>
                                    <span v-else class="text-gray-500"
                                        >No departments assigned</span
                                    >
                                </div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500">
                                    Member Since
                                </div>
                                <div class="mt-1 text-sm text-gray-900">
                                    {{ formatDate(agent.created_at) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Metrics -->
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6"
                >
                    <!-- Total Tickets -->
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                    >
                        <div class="p-6">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 bg-indigo-500 rounded-md p-3"
                                >
                                    <svg
                                        class="h-6 w-6 text-white"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"
                                        ></path>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt
                                            class="text-sm font-medium text-gray-500 truncate"
                                        >
                                            Total Tickets
                                        </dt>
                                        <dd>
                                            <div
                                                class="text-lg font-medium text-gray-900"
                                            >
                                                {{ performance.totalTickets }}
                                            </div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Resolved Tickets -->
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                    >
                        <div class="p-6">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 bg-green-500 rounded-md p-3"
                                >
                                    <svg
                                        class="h-6 w-6 text-white"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 13l4 4L19 7"
                                        ></path>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt
                                            class="text-sm font-medium text-gray-500 truncate"
                                        >
                                            Resolved Tickets
                                        </dt>
                                        <dd>
                                            <div
                                                class="text-lg font-medium text-gray-900"
                                            >
                                                {{
                                                    performance.resolvedTickets
                                                }}
                                            </div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Average Response Time -->
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                    >
                        <div class="p-6">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 bg-yellow-500 rounded-md p-3"
                                >
                                    <svg
                                        class="h-6 w-6 text-white"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                        ></path>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt
                                            class="text-sm font-medium text-gray-500 truncate"
                                        >
                                            Avg. Response Time
                                        </dt>
                                        <dd>
                                            <div
                                                class="text-lg font-medium text-gray-900"
                                            >
                                                {{
                                                    performance.avgResponseTime
                                                }}
                                            </div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Satisfaction -->
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                    >
                        <div class="p-6">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 bg-red-500 rounded-md p-3"
                                >
                                    <svg
                                        class="h-6 w-6 text-white"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                        ></path>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt
                                            class="text-sm font-medium text-gray-500 truncate"
                                        >
                                            Customer Satisfaction
                                        </dt>
                                        <dd>
                                            <div
                                                class="text-lg font-medium text-gray-900"
                                            >
                                                {{ performance.satisfaction }}
                                            </div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Recent Activity
                        </h3>

                        <div v-if="activities.length > 0" class="flow-root">
                            <ul class="-mb-8">
                                <li
                                    v-for="(activity, index) in activities"
                                    :key="activity.id"
                                >
                                    <div class="relative pb-8">
                                        <span
                                            v-if="
                                                index !== activities.length - 1
                                            "
                                            class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200"
                                            aria-hidden="true"
                                        ></span>
                                        <div
                                            class="relative flex items-start space-x-3"
                                        >
                                            <div class="relative">
                                                <div
                                                    class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center ring-8 ring-white"
                                                >
                                                    <svg
                                                        class="h-5 w-5 text-gray-500"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"
                                                        ></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div>
                                                    <div
                                                        class="text-sm font-medium text-gray-900"
                                                    >
                                                        {{ activity.title }}
                                                    </div>
                                                    <p
                                                        class="mt-0.5 text-sm text-gray-500"
                                                    >
                                                        {{
                                                            activity.description
                                                        }}
                                                    </p>
                                                </div>
                                                <div
                                                    class="mt-2 text-sm text-gray-500"
                                                >
                                                    <p>
                                                        {{
                                                            formatDate(
                                                                activity.date
                                                            )
                                                        }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div v-else class="text-center py-4 text-gray-500">
                            No recent activity found
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
import Breadcrumb from "@/Components/Breadcrumb.vue";
import { formatDistance, format } from "date-fns";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    agent: Object,
    performance: Object,
    activities: Array,
    can: Object,
});

const formatDate = (dateString) => {
    if (!dateString) return "";

    const date = new Date(dateString);
    return format(date, "MMM d, yyyy 'at' h:mm a");
};
</script>
