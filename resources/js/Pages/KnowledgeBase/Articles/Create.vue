<template>
    <Head title="Create Article" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <Breadcrumb
                    :items="[
                        {
                            label: 'Knowledge Base',
                            href: route('knowledge-base.index'),
                        },
                        { label: 'Create Article' },
                    ]"
                />
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="mb-6">
                                <InputLabel for="title" value="Title" />
                                <TextInput
                                    id="title"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.title"
                                    required
                                    autofocus
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.title"
                                />
                            </div>

                            <div class="mb-6">
                                <InputLabel
                                    for="category_id"
                                    value="Category"
                                />
                                <select
                                    id="category_id"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    v-model="form.category_id"
                                    required
                                >
                                    <option :value="null" disabled>
                                        Select a category
                                    </option>
                                    <option
                                        v-for="category in categories"
                                        :key="category.id"
                                        :value="category.id"
                                    >
                                        {{ category.name }}
                                    </option>
                                </select>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.category_id"
                                />
                            </div>

                            <div class="mb-6">
                                <InputLabel
                                    for="excerpt"
                                    value="Excerpt (optional)"
                                />
                                <TextArea
                                    id="excerpt"
                                    class="mt-1 block w-full"
                                    v-model="form.excerpt"
                                    rows="2"
                                />
                                <p class="mt-1 text-sm text-gray-500">
                                    A brief summary of the article (max 500
                                    characters)
                                </p>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.excerpt"
                                />
                            </div>

                            <div class="mb-6">
                                <InputLabel for="content" value="Content" />
                                <RichTextEditor
                                    id="content"
                                    name="content"
                                    v-model="form.content"
                                    height="400px"
                                    class="mt-1"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.content"
                                />
                            </div>

                            <div
                                class="flex flex-col sm:flex-row sm:justify-between space-y-4 sm:space-y-0 sm:space-x-4 mb-6"
                            >
                                <div
                                    class="flex items-center"
                                    v-if="canPublish"
                                >
                                    <Checkbox
                                        id="is_published"
                                        v-model:checked="form.is_published"
                                    />
                                    <InputLabel
                                        for="is_published"
                                        value="Publish immediately"
                                        class="ml-2"
                                    />
                                </div>
                                <div class="flex items-center" v-else>
                                    <div class="text-sm text-gray-500">
                                        <span class="font-medium">Note:</span>
                                        Only admin users can publish articles.
                                        Your article will be saved as a draft.
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <Checkbox
                                        id="featured"
                                        v-model:checked="form.featured"
                                    />
                                    <InputLabel
                                        for="featured"
                                        value="Featured article"
                                        class="ml-2"
                                    />
                                </div>
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
                                    Create Article
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
import { onMounted } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Checkbox from "@/Components/Checkbox.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import RichTextEditor from "@/Components/RichTextEditor.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
    categories: Array,
    preselectedCategory: Number,
    canPublish: Boolean,
});

const form = useForm({
    title: "",
    category_id: props.preselectedCategory || null,
    excerpt: "",
    content: "",
    is_published: false,
    featured: false,
});

const submit = () => {
    form.post(route("knowledge-base.articles.store"));
};

onMounted(() => {
    if (props.preselectedCategory) {
        form.category_id = props.preselectedCategory;
    }
});
</script>
