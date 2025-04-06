<template>
    <Modal :show="show" @close="close">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Are you sure you want to delete this article?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                This action cannot be undone.
            </p>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="close">Cancel</SecondaryButton>

                <DangerButton class="ml-3" @click="confirmDelete">
                    Delete Article
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import { router } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";

const props = defineProps({
    show: Boolean,
    article: Object,
});

const emit = defineEmits(["close"]);

const close = () => {
    emit("close");
};

const confirmDelete = () => {
    if (props.article) {
        router.delete(
            route("knowledge-base.articles.destroy", props.article.id),
            {
                onSuccess: () => {
                    close();
                },
            }
        );
    }
};
</script>
