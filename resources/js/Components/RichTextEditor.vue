<template>
    <div class="rich-text-editor">
        <input
            type="hidden"
            :id="props.id"
            :name="props.name"
            :value="modelValue"
        />
        <div v-if="editor" class="menu-bar">
            <button
                type="button"
                @click="editor.chain().focus().toggleBold().run()"
                :class="{ 'is-active': editor.isActive('bold') }"
                class="menu-button"
                title="Bold"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="24"
                    height="24"
                    class="h-5 w-5"
                >
                    <path fill="none" d="M0 0h24v24H0z" />
                    <path
                        d="M8 11h4.5a2.5 2.5 0 1 0 0-5H8v5zm10 4.5a4.5 4.5 0 0 1-4.5 4.5H6V4h6.5a4.5 4.5 0 0 1 3.256 7.613A4.5 4.5 0 0 1 18 15.5zM8 13v5h5.5a2.5 2.5 0 1 0 0-5H8z"
                    />
                </svg>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleItalic().run()"
                :class="{ 'is-active': editor.isActive('italic') }"
                class="menu-button"
                title="Italic"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="24"
                    height="24"
                    class="h-5 w-5"
                >
                    <path fill="none" d="M0 0h24v24H0z" />
                    <path
                        d="M15 20H7v-2h2.927l2.116-12H9V4h8v2h-2.927l-2.116 12H15z"
                    />
                </svg>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleStrike().run()"
                :class="{ 'is-active': editor.isActive('strike') }"
                class="menu-button"
                title="Strikethrough"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="24"
                    height="24"
                    class="h-5 w-5"
                >
                    <path fill="none" d="M0 0h24v24H0z" />
                    <path
                        d="M17.154 14c.23.516.346 1.09.346 1.72 0 1.342-.524 2.392-1.571 3.147C14.88 19.622 13.433 20 11.586 20c-1.64 0-3.263-.381-4.87-1.144V16.6c1.52.877 3.075 1.316 4.666 1.316 2.551 0 3.83-.732 3.839-2.197a2.21 2.21 0 0 0-.648-1.603l-.12-.117H3v-2h18v2h-3.846zm-4.078-3H7.629a4.086 4.086 0 0 1-.481-.522C6.716 9.92 6.5 9.246 6.5 8.452c0-1.236.466-2.287 1.397-3.153C8.83 4.433 10.271 4 12.222 4c1.471 0 2.879.328 4.222.984v2.152c-1.2-.687-2.515-1.03-3.946-1.03-2.48 0-3.719.782-3.719 2.346 0 .42.218.786.654 1.099.436.313.974.562 1.613.75.62.18 1.297.414 2.03.699z"
                    />
                </svg>
            </button>
            <div class="divider"></div>
            <button
                type="button"
                @click="
                    editor.chain().focus().toggleHeading({ level: 1 }).run()
                "
                :class="{
                    'is-active': editor.isActive('heading', { level: 1 }),
                }"
                class="menu-button"
                title="Heading 1"
            >
                H1
            </button>
            <button
                type="button"
                @click="
                    editor.chain().focus().toggleHeading({ level: 2 }).run()
                "
                :class="{
                    'is-active': editor.isActive('heading', { level: 2 }),
                }"
                class="menu-button"
                title="Heading 2"
            >
                H2
            </button>
            <button
                type="button"
                @click="
                    editor.chain().focus().toggleHeading({ level: 3 }).run()
                "
                :class="{
                    'is-active': editor.isActive('heading', { level: 3 }),
                }"
                class="menu-button"
                title="Heading 3"
            >
                H3
            </button>
            <div class="divider"></div>
            <button
                type="button"
                @click="editor.chain().focus().toggleBulletList().run()"
                :class="{ 'is-active': editor.isActive('bulletList') }"
                class="menu-button"
                title="Bullet List"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="24"
                    height="24"
                    class="h-5 w-5"
                >
                    <path fill="none" d="M0 0h24v24H0z" />
                    <path
                        d="M8 4h13v2H8V4zM4.5 6.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm0 7a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm0 6.9a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zM8 11h13v2H8v-2zm0 7h13v2H8v-2z"
                    />
                </svg>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleOrderedList().run()"
                :class="{ 'is-active': editor.isActive('orderedList') }"
                class="menu-button"
                title="Ordered List"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="24"
                    height="24"
                    class="h-5 w-5"
                >
                    <path fill="none" d="M0 0h24v24H0z" />
                    <path
                        d="M8 4h13v2H8V4zM5 3v3h1v1H3V6h1V4H3V3h2zm-2 9h3v1H3v-1zm0 4h3v3H3v-1h2v-1H3v-1zm8-7h13v2H11v-2zm0 7h13v2H11v-2z"
                    />
                </svg>
            </button>
            <div class="divider"></div>
            <button
                type="button"
                @click="editor.chain().focus().toggleBlockquote().run()"
                :class="{ 'is-active': editor.isActive('blockquote') }"
                class="menu-button"
                title="Blockquote"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="24"
                    height="24"
                    class="h-5 w-5"
                >
                    <path fill="none" d="M0 0h24v24H0z" />
                    <path
                        d="M4.583 17.321C3.553 16.227 3 15 3 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 0 1-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179zm10 0C13.553 16.227 13 15 13 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 0 1-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179z"
                    />
                </svg>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().setHorizontalRule().run()"
                class="menu-button"
                title="Horizontal Rule"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="24"
                    height="24"
                    class="h-5 w-5"
                >
                    <path fill="none" d="M0 0h24v24H0z" />
                    <path d="M2 11h2v2H2v-2zm4 0h12v2H6v-2zm14 0h2v2h-2v-2z" />
                </svg>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().undo().run()"
                :disabled="!editor.can().chain().focus().undo().run()"
                class="menu-button"
                title="Undo"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="24"
                    height="24"
                    class="h-5 w-5"
                >
                    <path fill="none" d="M0 0h24v24H0z" />
                    <path
                        d="M5.828 7l2.536 2.536L6.95 10.95 2 6l4.95-4.95 1.414 1.414L5.828 5H13a8 8 0 1 1 0 16H4v-2h9a6 6 0 1 0 0-12H5.828z"
                    />
                </svg>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().redo().run()"
                :disabled="!editor.can().chain().focus().redo().run()"
                class="menu-button"
                title="Redo"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="24"
                    height="24"
                    class="h-5 w-5"
                >
                    <path fill="none" d="M0 0h24v24H0z" />
                    <path
                        d="M18.172 7l-2.536 2.536 1.414 1.414L22 6l-4.95-4.95-1.414 1.414L18.172 5H11a8 8 0 1 0 0 16h9v-2h-9a6 6 0 1 1 0-12h7.172z"
                    />
                </svg>
            </button>
        </div>

        <div class="editor-content" :style="contentStyle">
            <EditorContent
                v-if="editor"
                :editor="editor"
                :id="props.id"
                :name="props.name"
            />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, watch, computed } from "vue";
import { useEditor, EditorContent } from "@tiptap/vue-3";
import StarterKit from "@tiptap/starter-kit";

const props = defineProps({
    modelValue: {
        type: String,
        default: "",
    },
    height: {
        type: String,
        default: "300px",
    },
    placeholder: {
        type: String,
        default: "Write your content here...",
    },
    id: {
        type: String,
        default: null,
    },
    name: {
        type: String,
        default: null,
    },
});

const emit = defineEmits(["update:modelValue"]);

const contentStyle = computed(() => ({
    height: props.height,
}));

// Note: useEditor already returns a ref
const editor = useEditor({
    content: props.modelValue,
    extensions: [StarterKit],
    onUpdate: ({ editor }) => {
        emit("update:modelValue", editor.getHTML());
    },
    editorProps: {
        attributes: {
            class: "prose prose-sm sm:prose focus:outline-none",
            id: props.id,
            name: props.name,
        },
    },
});

// Watch for external changes to the modelValue prop
watch(
    () => props.modelValue,
    (newValue) => {
        // Only update if the editor exists and the content is different
        if (editor.value && newValue !== undefined) {
            const currentContent = editor.value.getHTML();
            if (currentContent !== newValue) {
                editor.value.commands.setContent(newValue, false);
            }
        }
    }
);

// Cleanup editor on component unmount
onBeforeUnmount(() => {
    if (editor.value) {
        editor.value.destroy();
    }
});
</script>

<style scoped>
.rich-text-editor {
    @apply w-full border border-gray-300 rounded-md overflow-hidden;
}

.menu-bar {
    @apply flex flex-wrap items-center bg-gray-50 border-b border-gray-300 px-2 py-1 space-x-1 overflow-x-auto;
}

.menu-button {
    @apply p-1.5 rounded hover:bg-gray-200 transition-colors text-gray-700;
}

.menu-button.is-active {
    @apply bg-blue-100 text-blue-700;
}

.divider {
    @apply w-px h-6 bg-gray-300 mx-2;
}

.editor-content {
    @apply w-full;
}

/* Override TipTap default styles */
:deep(.ProseMirror) {
    @apply min-h-[200px] outline-none p-4 w-full;
}

:deep(.ProseMirror p.is-editor-empty:first-child::before) {
    content: attr(data-placeholder);
    @apply float-left h-0 pointer-events-none text-gray-400;
}
</style>
