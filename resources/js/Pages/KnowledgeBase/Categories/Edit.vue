<template>
    <Head title="Edit Category" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <Breadcrumb
                    :items="[
                        {
                            label: 'Knowledge Base',
                            href: route('knowledge-base.index'),
                        },
                        { label: 'Edit: ' + category.name },
                    ]"
                />
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="mb-6">
                                <InputLabel for="name" value="Name" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                    autofocus
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.name"
                                />
                            </div>

                            <div class="mb-6">
                                <InputLabel
                                    for="description"
                                    value="Description (optional)"
                                />
                                <TextArea
                                    id="description"
                                    class="mt-1 block w-full"
                                    v-model="form.description"
                                    rows="4"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.description"
                                />
                            </div>

                            <div class="mb-6">
                                <InputLabel for="order" value="Display Order" />
                                <TextInput
                                    id="order"
                                    type="number"
                                    class="mt-1 block w-full"
                                    :model-value="form.order.toString()"
                                    @update:model-value="
                                        (value) => (form.order = Number(value))
                                    "
                                    min="0"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.order"
                                />
                            </div>

                            <div
                                class="flex items-center justify-between gap-4"
                            >
                                <div class="flex items-center gap-4">
                                    <SecondaryButton
                                        @click="
                                            router.visit(
                                                route('knowledge-base.index')
                                            )
                                        "
                                    >
                                        Cancel
                                    </SecondaryButton>
                                    <DangerButton
                                        type="button"
                                        @click="confirmCategoryDeletion"
                                        class="ml-4"
                                    >
                                        Delete
                                    </DangerButton>
                                </div>
                                <PrimaryButton
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Update
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <Modal :show="confirmingCategoryDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete this category?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    This will also delete all articles in this category. This
                    action cannot be undone.
                </p>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">
                        Cancel
                    </SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteCategory"
                    >
                        Delete Category
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";

const props = defineProps({
    category: Object,
});

const form = useForm({
    name: props.category.name,
    description: props.category.description,
    order: props.category.order,
});

const submit = () => {
    form.put(route("knowledge-base.categories.update", props.category.id));
};

const confirmingCategoryDeletion = ref(false);

const confirmCategoryDeletion = () => {
    confirmingCategoryDeletion.value = true;
};

const closeModal = () => {
    confirmingCategoryDeletion.value = false;
};

const deleteCategory = () => {
    form.delete(route("knowledge-base.categories.destroy", props.category.id));
};
</script>
