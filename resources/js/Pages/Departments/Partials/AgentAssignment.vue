<template>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">
                    Assigned Agents
                </h3>
                <PrimaryButton v-if="can.assign_agent" @click="openAssignModal">
                    Assign New Agent
                </PrimaryButton>
            </div>

            <!-- Agents Table -->
            <div class="mt-6">
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
                                v-if="can.assign_agent"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="agent in agents" :key="agent.id">
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ agent.name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ agent.email }}
                            </td>
                            <td
                                v-if="can.assign_agent"
                                class="px-6 py-4 whitespace-nowrap"
                            >
                                <button
                                    @click="removeAgent(agent)"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Remove
                                </button>
                            </td>
                        </tr>
                        <tr v-if="agents.length === 0">
                            <td
                                colspan="3"
                                class="px-6 py-4 text-center text-gray-500"
                            >
                                No agents assigned to this department
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Assignment Modal -->
    <Modal :show="showModal" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Assign Agent to Department
            </h2>

            <div class="mt-6">
                <form @submit.prevent="assignAgent">
                    <div>
                        <InputLabel for="agent" value="Select Agent" />
                        <select
                            id="agent"
                            v-model="selectedAgent"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            required
                        >
                            <option value="">Select an agent</option>
                            <option
                                v-for="agent in availableAgents"
                                :key="agent.id"
                                :value="agent.id"
                            >
                                {{ agent.name }} ({{ agent.email }})
                            </option>
                        </select>
                        <InputError
                            :message="form.errors.user_id"
                            class="mt-2"
                        />
                    </div>

                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeModal" class="mr-3">
                            Cancel
                        </SecondaryButton>
                        <PrimaryButton
                            :disabled="form.processing || !selectedAgent"
                        >
                            Assign Agent
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useToast } from "@/Composables/useToast";

const props = defineProps({
    departmentId: {
        type: Number,
        required: true,
    },
    agents: {
        type: Array,
        required: true,
    },
    can: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["agentAssigned", "agentRemoved"]);
const toast = useToast();

const showModal = ref(false);
const selectedAgent = ref("");
const availableAgents = ref([]);
const form = useForm({
    user_id: "",
});

const fetchAvailableAgents = async () => {
    try {
        const response = await fetch(
            route("departments.available-agents", props.departmentId)
        );
        availableAgents.value = await response.json();
    } catch (error) {
        console.error("Error fetching available agents:", error);
        toast.error("Error fetching available agents");
    }
};

const openAssignModal = () => {
    selectedAgent.value = "";
    fetchAvailableAgents();
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    selectedAgent.value = "";
    form.reset();
};

const assignAgent = () => {
    form.user_id = selectedAgent.value;

    // Find the agent name for the toast message
    const agentName =
        availableAgents.value.find(
            (agent) => agent.id === parseInt(selectedAgent.value)
        )?.name || "Agent";

    form.post(route("departments.assign-agent", props.departmentId), {
        onSuccess: () => {
            closeModal();
            emit("agentAssigned");
            toast.success(
                `${agentName} was assigned to the department successfully`
            );
        },
        onError: () => {
            toast.error("There was an error assigning the agent");
        },
    });
};

const removeAgent = (agent) => {
    if (
        confirm(
            `Are you sure you want to remove ${agent.name} from this department?`
        )
    ) {
        form.delete(
            route("departments.remove-agent", {
                department: props.departmentId,
                user: agent.id,
            }),
            {
                preserveScroll: true,
                onSuccess: () => {
                    emit("agentRemoved");
                    toast.success(
                        `${agent.name} was removed from the department successfully`
                    );
                },
                onError: () => {
                    toast.error(
                        `There was an error removing ${agent.name} from the department`
                    );
                },
            }
        );
    }
};
</script>
