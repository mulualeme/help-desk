<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import Checkbox from "@/Components/Checkbox.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useToast } from "@/Composables/useToast";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["close"]);
const toast = useToast();

const form = useForm({
    name: "",
    description: "",
    is_active: true,
});

const processing = ref(false);

const submit = () => {
    processing.value = true;
    form.post(route("departments.store"), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success("Department created successfully");
            form.reset();
            emit("close");
        },
        onError: () => {
            toast.error("Failed to create department");
        },
        onFinish: () => {
            processing.value = false;
        },
    });
};

const closeModal = () => {
    form.reset();
    emit("close");
};
</script>

<template>
    <Modal :show="show" @close="closeModal" maxWidth="md">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                Create Department
            </h2>

            <form @submit.prevent="submit">
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
                    <InputLabel for="description" value="Description" />
                    <textarea
                        id="description"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        v-model="form.description"
                        rows="3"
                    ></textarea>
                    <InputError
                        class="mt-2"
                        :message="form.errors.description"
                    />
                </div>

                <div class="mb-4 flex items-center">
                    <Checkbox
                        id="is_active"
                        v-model:checked="form.is_active"
                        name="is_active"
                    />
                    <InputLabel for="is_active" value="Active" class="ml-2" />
                </div>

                <div class="flex justify-end mt-6">
                    <button
                        type="button"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 mr-3"
                        @click="closeModal"
                        :disabled="processing"
                    >
                        Cancel
                    </button>
                    <PrimaryButton type="submit" :disabled="processing">
                        Create Department
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
