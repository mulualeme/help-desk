<template>
    <Head title="Edit Article" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <Breadcrumb
                    :items="[
                        {
                            label: 'Knowledge Base',
                            href: route('knowledge-base.index'),
                        },
                        ...(article.category
                            ? [
                                  {
                                      label: article.category.name,
                                      href: route(
                                          'knowledge-base.categories.show',
                                          article.category.id
                                      ),
                                  },
                              ]
                            : []),
                        { label: 'Edit: ' + article.title },
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
                                    v-if="canPublish || form.is_published"
                                >
                                    <Checkbox
                                        id="is_published"
                                        v-model:checked="form.is_published"
                                        :disabled="!canPublish"
                                    />
                                    <InputLabel
                                        for="is_published"
                                        value="Published"
                                        class="ml-2"
                                    />
                                    <span
                                        v-if="!canPublish"
                                        class="ml-2 text-xs text-gray-500"
                                    >
                                        (Only admin users can change publish
                                        status)
                                    </span>
                                </div>
                                <div class="flex items-center" v-else>
                                    <div class="text-sm text-gray-500">
                                        <span class="font-medium">Note:</span>
                                        Only admin users can publish articles.
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
                                        @click="confirmArticleDeletion"
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
        <Modal :show="confirmingArticleDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete this article?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    This action cannot be undone.
                </p>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">
                        Cancel
                    </SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteArticle"
                    >
                        Delete Article
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
import Checkbox from "@/Components/Checkbox.vue";
import Modal from "@/Components/Modal.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import RichTextEditor from "@/Components/RichTextEditor.vue";

const props = defineProps({
    article: Object,
    categories: Array,
    canPublish: Boolean,
});

const form = useForm({
    title: props.article.title,
    category_id: props.article.category_id,
    excerpt: props.article.excerpt,
    content: props.article.content,
    is_published: props.article.is_published,
    featured: props.article.featured,
    published_at: props.article.published_at,
});

const submit = () => {
    form.put(route("knowledge-base.articles.update", props.article.id));
};

const confirmingArticleDeletion = ref(false);

const confirmArticleDeletion = () => {
    confirmingArticleDeletion.value = true;
};

const closeModal = () => {
    confirmingArticleDeletion.value = false;
};

const deleteArticle = () => {
    form.delete(route("knowledge-base.articles.destroy", props.article.id));
};
</script>
