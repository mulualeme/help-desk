<template>
    <Head :title="category.name" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <Breadcrumb
                    :items="[
                        {
                            label: 'Knowledge Base',
                            href: route('knowledge-base.index'),
                        },
                        { label: category.name },
                    ]"
                />
                <div class="flex space-x-2">
                    <Link
                        v-if="can.update"
                        :href="
                            route('knowledge-base.categories.edit', category.id)
                        "
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                    >
                        Edit Category
                    </Link>
                    <Link
                        v-if="
                            $page.props.auth.user.permissions.includes(
                                'create kb article'
                            )
                        "
                        :href="
                            route('knowledge-base.articles.create', {
                                category_id: category.id,
                            })
                        "
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        New Article
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Category Description -->
                <div
                    v-if="category.description"
                    class="bg-white shadow-sm sm:rounded-lg overflow-hidden mb-8"
                >
                    <div class="p-6">
                        <p class="text-gray-700">{{ category.description }}</p>
                    </div>
                </div>

                <!-- Subcategories -->
                <div
                    v-if="
                        category.subcategories &&
                        category.subcategories.length > 0
                    "
                    class="mb-8"
                >
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Subcategories
                    </h3>
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"
                    >
                        <div
                            v-for="subcategory in category.subcategories"
                            :key="subcategory.id"
                            class="bg-white shadow-sm sm:rounded-lg overflow-hidden hover:shadow-md transition duration-150"
                        >
                            <Link
                                :href="
                                    route(
                                        'knowledge-base.categories.show',
                                        subcategory.id
                                    )
                                "
                                class="block p-6"
                            >
                                <h4 class="text-lg font-medium text-gray-900">
                                    {{ subcategory.name }}
                                </h4>
                                <p
                                    v-if="subcategory.description"
                                    class="mt-2 text-sm text-gray-500 line-clamp-2"
                                >
                                    {{ subcategory.description }}
                                </p>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Articles -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Articles
                    </h3>
                    <div
                        v-if="category.articles && category.articles.length > 0"
                        class="bg-white shadow-sm sm:rounded-lg overflow-hidden"
                    >
                        <div class="divide-y">
                            <div
                                v-for="article in category.articles"
                                :key="article.id"
                                class="p-4 hover:bg-gray-50"
                            >
                                <div class="flex justify-between items-start">
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
                                        <p
                                            v-if="article.excerpt"
                                            class="mt-1 text-sm text-gray-500"
                                        >
                                            {{ article.excerpt }}
                                        </p>
                                        <div
                                            class="mt-2 flex items-center text-xs text-gray-500"
                                        >
                                            <div class="flex items-center">
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-3 w-3 mr-1"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                                    />
                                                </svg>
                                                {{ article.author.name }}
                                            </div>
                                            <span class="mx-2">·</span>
                                            <div class="flex items-center">
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-3 w-3 mr-1"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                    />
                                                </svg>
                                                {{
                                                    formatDate(
                                                        article.published_at ||
                                                            article.created_at
                                                    )
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="flex items-center space-x-2"
                                        v-if="can.update"
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        v-else
                        class="bg-white shadow-sm sm:rounded-lg p-6 text-center text-gray-500"
                    >
                        No articles in this category yet.
                        <div
                            class="mt-4"
                            v-if="
                                $page.props.auth.user.permissions.includes(
                                    'create kb article'
                                )
                            "
                        >
                            <Link
                                :href="
                                    route('knowledge-base.articles.create', {
                                        category_id: category.id,
                                    })
                                "
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Create First Article
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";

const props = defineProps({
    category: Object,
    can: Object,
});

// Format date helper
const formatDate = (date) => {
    if (!date) return "";
    return new Date(date).toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};
</script>
