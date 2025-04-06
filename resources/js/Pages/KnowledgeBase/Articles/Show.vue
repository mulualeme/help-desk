<template>
    <Head :title="article.title" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <Breadcrumb
                    :items="[
                        {
                            label: 'Knowledge Base',
                            href: route('knowledge-base.index'),
                        },
                        {
                            label: article.category.name,
                            href: route(
                                'knowledge-base.categories.show',
                                article.category.id
                            ),
                        },
                        { label: article.title },
                    ]"
                />
                <div class="flex space-x-2" v-if="can.update">
                    <Link
                        :href="
                            route('knowledge-base.articles.edit', article.id)
                        "
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        Edit Article
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                    <!-- Article Header -->
                    <div class="px-6 py-8 border-b">
                        <div class="flex justify-between border-b pb-4">
                            <div class="flex items-center space-x-4">
                                <div>
                                    <h3
                                        class="text-xl font-semibold text-gray-900"
                                    >
                                        {{ article.title }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        By {{ article.author.name }} on
                                        {{ formatDate(article.created_at) }}
                                    </p>
                                </div>
                            </div>
                            <div
                                v-if="
                                    $page.props.auth.user.permissions.includes(
                                        'create kb article'
                                    ) ||
                                    $page.props.auth.user.permissions.includes(
                                        'edit kb article'
                                    )
                                "
                            >
                                <span
                                    class="px-2 py-1 rounded-full text-xs"
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

                    <!-- Article Content -->
                    <div class="p-6">
                        <article
                            class="prose prose-sm sm:prose lg:prose-lg max-w-none"
                            v-html="article.content"
                        ></article>
                    </div>
                </div>

                <!-- Related Articles -->
                <div class="mt-8" v-if="relatedArticles.length > 0">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Related Articles
                    </h3>
                    <div
                        class="bg-white shadow-sm sm:rounded-lg overflow-hidden"
                    >
                        <div class="divide-y">
                            <div
                                v-for="relatedArticle in relatedArticles"
                                :key="relatedArticle.id"
                                class="p-4 hover:bg-gray-50"
                            >
                                <Link
                                    :href="
                                        route(
                                            'knowledge-base.articles.show',
                                            relatedArticle.id
                                        )
                                    "
                                    class="font-medium text-blue-600 hover:text-blue-800"
                                >
                                    {{ relatedArticle.title }}
                                </Link>
                                <p
                                    class="mt-1 text-sm text-gray-500"
                                    v-if="relatedArticle.excerpt"
                                >
                                    {{ relatedArticle.excerpt }}
                                </p>
                            </div>
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
    article: Object,
    relatedArticles: Array,
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
