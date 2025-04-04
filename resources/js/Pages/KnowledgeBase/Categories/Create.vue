<template>
    <Head title="Create Category" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <Breadcrumb
                    :items="[
                        {
                            label: 'Knowledge Base',
                            href: route('knowledge-base.index'),
                        },
                        { label: 'Create Category' },
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
                                <SecondaryButton
                                    @click="
                                        router.visit(
                                            route('knowledge-base.index')
                                        )
                                    "
                                >
                                    Cancel
                                </SecondaryButton>
                                <PrimaryButton
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Create Category
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const form = useForm({
    name: "",
    description: "",
    order: 0,
});

const submit = () => {
    form.post(route("knowledge-base.categories.store"));
};
</script>
