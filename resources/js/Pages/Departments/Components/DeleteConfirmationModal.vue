<template>
    <Modal :show="show" @close="close">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">Delete Department</h2>

            <p class="mt-1 text-sm text-gray-600">
                Are you sure you want to delete {{ department?.name }}? This
                action cannot be undone.
            </p>

            <div class="mt-6 flex justify-end">
                <button
                    type="button"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                    @click="close"
                >
                    Cancel
                </button>

                <button
                    type="button"
                    class="ml-3 inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    @click="confirmDelete"
                >
                    Delete
                </button>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import Modal from "@/Components/Modal.vue";

const props = defineProps({
    show: Boolean,
    department: Object,
});

const emit = defineEmits(["close", "confirm"]);

const close = () => {
    emit("close");
};

const confirmDelete = () => {
    emit("confirm", props.department);
    close();
};
</script>
