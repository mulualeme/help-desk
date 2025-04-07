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
                <!-- Search and Filter Controls -->
                <SearchFilters
                    :initial-filters="filters"
                    @update:filters="updateFilters"
                    @apply="applyFilters"
                />

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Agent Table -->
                        <AgentTable
                            :agents="agents"
                            :can="can"
                            :is-loading="isLoading"
                            @delete="openDeleteModal"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Invite Agent Modal -->
        <InviteAgentModal
            :show="showInviteModal"
            @close="closeInviteModal"
            @success="handleInviteSuccess"
        />

        <!-- Delete Agent Confirmation Modal -->
        <DeleteConfirmationModal
            :show="showDeleteModal"
            :agent="agentToDelete"
            @close="closeDeleteModal"
            @confirm="confirmDelete"
        />
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { Head, useForm, router, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import SearchFilters from "./Components/SearchFilters.vue";
import AgentTable from "./Components/AgentTable.vue";
import InviteAgentModal from "./Components/InviteAgentModal.vue";
import DeleteConfirmationModal from "./Components/DeleteConfirmationModal.vue";
import toast from "@/utils/toast";

const props = defineProps({
    agents: Object,
    can: Object,
    filters: Object,
});

const page = usePage();
const showInviteModal = ref(false);
const showDeleteModal = ref(false);
const agentToDelete = ref(null);
const deleteForm = useForm({});
const isLoading = ref(false);
const filters = ref({
    search: props.filters?.search || "",
    sort: "newest",
});

// Display flash messages as toasts
onMounted(() => {
    if (page.props.flash?.success) {
        toast.success(page.props.flash.success);
    }
    if (page.props.flash?.error) {
        toast.error(page.props.flash.error);
    }
});

// Update filter values
const updateFilters = (newFilters) => {
    filters.value = { ...newFilters };
};

// Apply filters and fetch data from the server
const applyFilters = () => {
    isLoading.value = true;

    router.get(
        route("agents.index"),
        {
            search: filters.value.search,
            sort: filters.value.sort,
            page: 1, // Reset to first page on filter change
        },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                isLoading.value = false;
            },
            onError: (error) => {
                isLoading.value = false;
                toast.error("Error applying filters");
            },
        }
    );
};

// Invite Modal
const closeInviteModal = () => {
    showInviteModal.value = false;
};

const handleInviteSuccess = (message) => {
    toast.success(message);
};

// Delete Modal
const openDeleteModal = (agent) => {
    agentToDelete.value = agent;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    setTimeout(() => {
        agentToDelete.value = null;
    }, 300);
};

const confirmDelete = () => {
    if (agentToDelete.value) {
        deleteForm.delete(route("agents.destroy", agentToDelete.value.id), {
            onSuccess: () => {
                toast.success(
                    `${agentToDelete.value.name} was deleted successfully`
                );
                closeDeleteModal();
            },
            onError: (error) => {
                toast.error(error);
                closeDeleteModal();
            },
        });
    }
};
</script>
