<script setup>
import { computed } from "vue";

const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: "",
    },
    id: {
        type: String,
        default: "select",
    },
    name: String,
    required: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    class: {
        type: String,
        default: "",
    },
});

const emit = defineEmits(["update:modelValue"]);

const input = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        emit("update:modelValue", value);
    },
});
</script>

<template>
    <select
        :id="id"
        :name="name"
        :value="modelValue"
        :required="required"
        :disabled="disabled"
        class="border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm w-full"
        :class="class"
        @change="$emit('update:modelValue', $event.target.value)"
    >
        <slot></slot>
    </select>
</template>
