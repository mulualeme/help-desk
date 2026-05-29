<script setup>
import { router } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";

const props = defineProps({
    show: Boolean,
    sla: Object,
});

const emit = defineEmits(["close"]);

// Delete the SLA
const deleteSla = () => {
    if (!props.sla) return;

    router.delete(route("slas.destroy", props.sla.id), {
        preserveScroll: true,
        onSuccess: () => {
            emit("close");
        },
    });
};
</script>

<template>
    <Modal :show="show" @close="$emit('close')">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">Delete SLA</h2>
            <p class="mt-1 text-sm text-gray-600">
                Are you sure you want to delete this SLA? This action cannot be
                undone.
            </p>
            <div class="mt-6 flex justify-end space-x-3">
                <SecondaryButton @click="$emit('close')">
                    Cancel
                </SecondaryButton>
                <DangerButton @click="deleteSla" class="ml-3">
                    Delete SLA
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>
