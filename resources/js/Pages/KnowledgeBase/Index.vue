<template>
    <Head title="Knowledge Base" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <Breadcrumb :items="[{ label: 'Knowledge Base' }]" />
                <div class="flex space-x-2">
                    <PrimaryButton
                        v-if="can.createCategory"
                        @click="
                            router.visit(
                                route('knowledge-base.categories.create')
                            )
                        "
                    >
                        Create Category
                    </PrimaryButton>
                    <PrimaryButton
                        v-if="can.createArticle"
                        @click="
                            router.visit(
                                route('knowledge-base.articles.create')
                            )
                        "
                    >
                        Create Article
                    </PrimaryButton>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Search and Filter -->
                <SearchFilters :initial-filters="filters" />

                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Categories and Articles -->
                        <CategoryList
                            :categories="categories"
                            :can="can"
                            @delete="confirmDelete"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <DeleteConfirmation
            :show="deleteModal"
            :article="articleToDelete"
            @close="closeModal"
        />
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from "vue";
import { Head } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import SearchFilters from "./Components/SearchFilters.vue";
import CategoryList from "./Components/CategoryList.vue";
import DeleteConfirmation from "./Components/DeleteConfirmation.vue";

const props = defineProps({
    categories: Object,
    can: Object,
    filters: Object,
});

// Delete modal state
const deleteModal = ref(false);
const articleToDelete = ref(null);

// Delete article confirmation
const confirmDelete = (article) => {
    articleToDelete.value = article;
    deleteModal.value = true;
};

// Close modal
const closeModal = () => {
    deleteModal.value = false;
    setTimeout(() => {
        articleToDelete.value = null;
    }, 200);
};
</script>
