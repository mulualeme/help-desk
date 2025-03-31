<template>
    <form @submit.prevent="handleSubmit">
        <div class="mt-4">
            <InputLabel for="name" value="Name" />
            <TextInput
                id="name"
                type="text"
                class="mt-1 block w-full"
                v-model="form.name"
                required
                autofocus
            />
            <InputError class="mt-2" :message="form.errors.name" />
        </div>

        <div class="mt-4">
            <InputLabel for="description" value="Description" />
            <TextArea
                id="description"
                class="mt-1 block w-full"
                v-model="form.description"
                rows="3"
            />
            <InputError class="mt-2" :message="form.errors.description" />
        </div>

        <div class="mt-4">
            <label class="flex items-center">
                <Checkbox v-model:checked="form.is_active" name="is_active" />
                <span class="ms-2 text-sm text-gray-600">Active</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <Link
                :href="route('departments.index')"
                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                Cancel
            </Link>

            <PrimaryButton
                class="ms-4"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                {{ submitLabel }}
            </PrimaryButton>
        </div>
    </form>
</template>

<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import Checkbox from "@/Components/Checkbox.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    form: Object,
    submitLabel: {
        type: String,
        default: "Save",
    },
});

const emit = defineEmits(["submitted"]);

const handleSubmit = () => {
    emit("submitted");
};
</script>
