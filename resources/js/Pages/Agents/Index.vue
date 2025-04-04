<template>
    <Head title="Agent Management" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <Breadcrumb :items="[{ label: 'Agents' }]" />
                <PrimaryButton
                    v-if="can.invite"
                    @click="showInviteModal = true"
                >
                    Invite Agent
                </PrimaryButton>
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
                                    placeholder="Search agents..."
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
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Name
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Email
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Created At
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
                                        v-for="agent in filteredAgents"
                                        :key="agent.id"
                                        class="hover:bg-gray-50 cursor-pointer"
                                        @click="navigateToAgent(agent.id)"
                                    >
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ agent.name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                {{ agent.email }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                {{
                                                    formatDate(agent.created_at)
                                                }}
                                            </div>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                                            @click.stop
                                        >
                                            <Link
                                                v-if="can.edit_roles"
                                                :href="
                                                    route(
                                                        'agents.edit',
                                                        agent.id
                                                    )
                                                "
                                                class="text-indigo-600 hover:text-indigo-900"
                                            >
                                                Change Role
                                            </Link>
                                            <button
                                                v-if="can.delete"
                                                @click="confirmDelete(agent)"
                                                class="text-red-600 hover:text-red-900 ml-2"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="filteredAgents.length === 0">
                                        <td
                                            colspan="4"
                                            class="px-6 py-4 text-center text-sm text-gray-500"
                                        >
                                            No agents found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <Pagination
                            v-if="!search && sortOrder === 'newest'"
                            :links="agents.links"
                            :meta="{
                                from: agents.from,
                                to: agents.to,
                                total: agents.total,
                            }"
                            itemName="agents"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Invite Agent Modal -->
        <Modal :show="showInviteModal" @close="closeInviteModal" maxWidth="md">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    Invite Agent
                </h2>

                <form @submit.prevent="submitInvite">
                    <div class="mb-4">
                        <InputLabel for="name" value="Name" />
                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            required
                            autofocus
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="mb-4">
                        <InputLabel for="email" value="Email" />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <button
                            type="button"
                            class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 mr-3"
                            @click="closeInviteModal"
                            :disabled="form.processing"
                        >
                            Cancel
                        </button>
                        <PrimaryButton
                            type="submit"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Send Invitation
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { Head, Link, useForm, router, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import Pagination from "@/Components/Pagination.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import Dropdown from "@/Components/Dropdown.vue";
import { format } from "date-fns";
import toast from "@/utils/toast";

const props = defineProps({
    agents: Object,
    can: Object,
});

const page = usePage();
const showInviteModal = ref(false);
const deleteForm = useForm({});
const form = useForm({
    name: "",
    email: "",
});
const search = ref("");
const sortOrder = ref("newest");

// Display flash messages as toasts
onMounted(() => {
    if (page.props.flash?.success) {
        toast.success(page.props.flash.success);
    }
    if (page.props.flash?.error) {
        toast.error(page.props.flash.error);
    }
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

const filteredAgents = computed(() => {
    // First, filter the agents by search term
    let result = [...props.agents.data];

    if (search.value) {
        result = result.filter((agent) => {
            return (
                agent.name.toLowerCase().includes(search.value.toLowerCase()) ||
                agent.email.toLowerCase().includes(search.value.toLowerCase())
            );
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

const closeInviteModal = () => {
    showInviteModal.value = false;
    form.reset();
};

const submitInvite = () => {
    form.post(route("agents.store"), {
        onSuccess: () => {
            closeInviteModal();
            toast.success("Agent invitation has been sent successfully");
        },
        onError: (errors) => {
            toast.error("There was an error sending the invitation");
        },
    });
};

const confirmDelete = (agent) => {
    if (confirm(`Are you sure you want to delete ${agent.name}?`)) {
        deleteForm.delete(route("agents.destroy", agent.id), {
            onSuccess: () => {
                toast.success(`${agent.name} was deleted successfully`);
            },
            onError: (error) => {
                toast.error(error);
            },
        });
    }
};

const navigateToAgent = (id) => {
    router.visit(route("agents.show", id), {
        preserveState: true,
        preserveScroll: true,
        only: ["agent", "performance", "activities"],
    });
};

const formatDate = (dateString) => {
    if (!dateString) return "";
    return format(new Date(dateString), "MMM d, yyyy");
};
</script>
