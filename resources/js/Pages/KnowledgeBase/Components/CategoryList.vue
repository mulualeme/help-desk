<template>
    <div>
        <!-- Categories and Articles -->
        <div v-if="categories.data.length > 0" class="space-y-2">
            <div
                v-for="category in categories.data"
                :key="category.id"
                class="border rounded-md overflow-hidden"
            >
                <!-- Category Header -->
                <div
                    class="flex justify-between items-center p-4 bg-gray-50 cursor-pointer"
                    @click="toggleCategory(category.id)"
                >
                    <div class="flex items-center">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2 text-yellow-500"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                v-if="expandedCategories.includes(category.id)"
                                fill-rule="evenodd"
                                d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1H8a3 3 0 00-3 3v1.5a1.5 1.5 0 01-3 0V6z"
                                clip-rule="evenodd"
                            />
                            <path
                                v-else
                                d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                            />
                        </svg>
                        <h3 class="font-medium">
                            <Link
                                :href="
                                    route(
                                        'knowledge-base.categories.show',
                                        category.id
                                    )
                                "
                                class="hover:text-blue-600 transition-colors"
                                @click.stop
                            >
                                {{ category.name }}
                            </Link>
                            <span class="text-sm text-gray-500 ml-2">
                                ({{ category.articles.length }}
                                {{
                                    category.articles.length === 1
                                        ? "Article"
                                        : "Articles"
                                }})
                            </span>
                        </h3>
                    </div>
                    <div class="flex items-center space-x-2" @click.stop>
                        <Link
                            v-if="can.createArticle"
                            :href="
                                route('knowledge-base.articles.create', {
                                    category_id: category.id,
                                })
                            "
                            class="text-blue-600 hover:text-blue-800"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4v16m8-8H4"
                                />
                            </svg>
                        </Link>
                        <Link
                            v-if="can.createCategory"
                            :href="
                                route(
                                    'knowledge-base.categories.edit',
                                    category.id
                                )
                            "
                            class="text-gray-600 hover:text-gray-800"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                />
                            </svg>
                        </Link>
                    </div>
                </div>

                <!-- Articles List -->
                <div v-show="expandedCategories.includes(category.id)">
                    <div
                        v-for="article in category.articles"
                        :key="article.id"
                        class="border-t p-4 hover:bg-gray-50 pl-8"
                    >
                        <div class="flex justify-between items-center">
                            <div class="flex items-start">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mr-2 text-gray-400 mt-0.5"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                <div>
                                    <Link
                                        :href="
                                            route(
                                                'knowledge-base.articles.show',
                                                article.id
                                            )
                                        "
                                        class="font-medium text-blue-600 hover:text-blue-800"
                                    >
                                        {{ article.title }}
                                    </Link>
                                    <div class="text-sm text-gray-500">
                                        <span>{{
                                            article.author
                                                ? article.author.name
                                                : "Unknown"
                                        }}</span>
                                        <span class="mx-1">·</span>
                                        <span>{{
                                            formatDate(
                                                article.published_at ||
                                                    article.created_at
                                            )
                                        }}</span>
                                        <span class="mx-1">·</span>
                                        <span
                                            class="px-2 py-0.5 rounded-full text-xs capitalize"
                                            :class="
                                                article.is_published
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-yellow-100 text-yellow-800'
                                            "
                                        >
                                            {{
                                                article.is_published
                                                    ? "Published"
                                                    : "Draft"
                                            }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flex items-center space-x-2"
                                v-if="can.createArticle"
                            >
                                <Link
                                    :href="
                                        route(
                                            'knowledge-base.articles.edit',
                                            article.id
                                        )
                                    "
                                    class="text-gray-600 hover:text-gray-800"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                        />
                                    </svg>
                                </Link>
                                <button
                                    @click="onDeleteClick(article)"
                                    class="text-red-600 hover:text-red-800"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="category.articles.length === 0"
                        class="border-t p-4 text-center text-gray-500"
                    >
                        No articles found matching your filters
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <Pagination
                :links="categories.links"
                :meta="{
                    from: categories.from,
                    to: categories.to,
                    total: categories.total,
                }"
                itemName="categories"
            />
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-16 w-16 mx-auto text-gray-400"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">
                No categories yet
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                Get started by creating a new category.
            </p>
            <div class="mt-6">
                <PrimaryButton
                    v-if="can.createCategory"
                    @click="
                        router.visit(route('knowledge-base.categories.create'))
                    "
                >
                    Create Category
                </PrimaryButton>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { Link } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    categories: Object,
    can: Object,
});

const emit = defineEmits(["delete"]);

// Tracking expanded categories
const expandedCategories = ref([]);

// Toggle category expansion
const toggleCategory = (categoryId) => {
    const index = expandedCategories.value.indexOf(categoryId);
    if (index > -1) {
        expandedCategories.value.splice(index, 1);
    } else {
        expandedCategories.value.push(categoryId);
    }
};

// Format date helper
const formatDate = (date) => {
    if (!date) return "";
    return new Date(date).toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};

// Delete article event
const onDeleteClick = (article) => {
    emit("delete", article);
};

// Expand first category by default if categories exist
onMounted(() => {
    if (props.categories.data.length > 0) {
        expandedCategories.value.push(props.categories.data[0].id);
    }
});
</script>
