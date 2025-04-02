<template>
    <Head title="Agents Management" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Agents Management
                </h2>
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
                                            Role
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
                                        v-for="agent in agents"
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
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="{
                                                    'bg-green-100 text-green-800':
                                                        hasRole(agent, 'admin'),
                                                    'bg-blue-100 text-blue-800':
                                                        hasRole(agent, 'agent'),
                                                }"
                                            >
                                                {{ getRoleName(agent) }}
                                            </span>
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
                                    <tr v-if="agents.length === 0">
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
import { ref } from "vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";

defineProps({
    agents: Array,
    can: Object,
});

const showInviteModal = ref(false);
const deleteForm = useForm({});
const form = useForm({
    name: "",
    email: "",
});

const closeInviteModal = () => {
    showInviteModal.value = false;
    form.reset();
};

const submitInvite = () => {
    form.post(route("agents.store"), {
        onSuccess: () => {
            closeInviteModal();
        },
    });
};

const confirmDelete = (agent) => {
    if (confirm(`Are you sure you want to delete ${agent.name}?`)) {
        deleteForm.delete(route("agents.destroy", agent.id));
    }
};

const hasRole = (agent, roleName) => {
    return agent.roles.some((role) => role.name === roleName);
};

const getRoleName = (agent) => {
    if (hasRole(agent, "admin")) {
        return "Admin";
    }
    return "Agent";
};

const navigateToAgent = (id) => {
    router.visit(route("agents.show", id), {
        preserveState: true,
        preserveScroll: true,
        only: ["agent", "performance", "activities"],
    });
};
</script>
