<template>
    <Modal :show="show" @close="closeModal" maxWidth="md">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Invite Agent</h2>

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
                        @click="closeModal"
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
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["close", "success"]);

const form = useForm({
    name: "",
    email: "",
});

const closeModal = () => {
    form.reset();
    emit("close");
};

const submitInvite = () => {
    form.post(route("agents.store"), {
        onSuccess: () => {
            closeModal();
            emit("success", "Agent invitation has been sent successfully");
        },
        onError: () => {
            emit("error", "There was an error sending the invitation");
        },
    });
};
</script>
