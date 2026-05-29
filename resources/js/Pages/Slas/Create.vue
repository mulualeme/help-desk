<script setup>
import { ref } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextArea from "@/Components/TextArea.vue";
import Checkbox from "@/Components/Checkbox.vue";
import Select from "@/Components/Select.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";

const props = defineProps({
    customers: Array,
    priorities: Array,
});

const form = useForm({
    name: "",
    description: "",
    response_time_minutes: "",
    resolution_time_minutes: "",
    customer_id: "",
    priority: "medium",
    is_active: true,
});

// Convert hours to minutes
const responseHours = ref("");
const resolutionHours = ref("");

const updateResponseTime = () => {
    const hours = parseFloat(responseHours.value);
    if (!isNaN(hours)) {
        form.response_time_minutes = Math.round(hours * 60);
    }
};

const updateResolutionTime = () => {
    const hours = parseFloat(resolutionHours.value);
    if (!isNaN(hours)) {
        form.resolution_time_minutes = Math.round(hours * 60);
    }
};

const submit = () => {
    updateResponseTime();
    updateResolutionTime();
    form.post(route("slas.store"));
};
</script>

<template>
    <Head title="Create SLA" />

    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb
                :items="[
                    { label: 'SLAs', href: route('slas.index') },
                    { label: 'Create SLA' },
                ]"
            />
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submit" class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <InputLabel for="name" value="SLA Name" />
                                <TextInput
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    autofocus
                                />
                                <InputError
                                    :message="form.errors.name"
                                    class="mt-2"
                                />
                            </div>

                            <!-- Priority -->
                            <div>
                                <InputLabel for="priority" value="Priority" />
                                <Select
                                    id="priority"
                                    v-model="form.priority"
                                    class="mt-1 block w-full"
                                >
                                    <option
                                        v-for="priority in priorities"
                                        :key="priority"
                                        :value="priority"
                                    >
                                        {{
                                            priority.charAt(0).toUpperCase() +
                                            priority.slice(1)
                                        }}
                                    </option>
                                </Select>
                                <InputError
                                    :message="form.errors.priority"
                                    class="mt-2"
                                />
                            </div>

                            <!-- Response Time -->
                            <div>
                                <InputLabel
                                    for="response_time"
                                    value="Response Time (Hours)"
                                />
                                <TextInput
                                    id="response_time"
                                    v-model="responseHours"
                                    type="number"
                                    step="0.1"
                                    min="0.1"
                                    class="mt-1 block w-full"
                                    placeholder="e.g., 1 for 1 hour, 0.5 for 30 minutes"
                                    required
                                    @blur="updateResponseTime"
                                />
                                <InputError
                                    :message="form.errors.response_time_minutes"
                                    class="mt-2"
                                />
                            </div>

                            <!-- Resolution Time -->
                            <div>
                                <InputLabel
                                    for="resolution_time"
                                    value="Resolution Time (Hours)"
                                />
                                <TextInput
                                    id="resolution_time"
                                    v-model="resolutionHours"
                                    type="number"
                                    step="0.1"
                                    min="0.1"
                                    class="mt-1 block w-full"
                                    placeholder="e.g., 8 for 8 hours, 24 for 1 day"
                                    required
                                    @blur="updateResolutionTime"
                                />
                                <InputError
                                    :message="
                                        form.errors.resolution_time_minutes
                                    "
                                    class="mt-2"
                                />
                            </div>

                            <!-- Customer -->
                            <div>
                                <InputLabel
                                    for="customer_id"
                                    value="Customer (Leave empty for global SLA)"
                                />
                                <Select
                                    id="customer_id"
                                    v-model="form.customer_id"
                                    class="mt-1 block w-full"
                                >
                                    <option value="">
                                        Global SLA (All Customers)
                                    </option>
                                    <option
                                        v-for="customer in customers"
                                        :key="customer.id"
                                        :value="customer.id"
                                    >
                                        {{ customer.name }}
                                    </option>
                                </Select>
                                <InputError
                                    :message="form.errors.customer_id"
                                    class="mt-2"
                                />
                            </div>

                            <!-- Active Status -->
                            <div class="flex items-center mt-6">
                                <Checkbox
                                    id="is_active"
                                    v-model:checked="form.is_active"
                                    name="is_active"
                                />
                                <InputLabel
                                    for="is_active"
                                    value="Active"
                                    class="ml-2"
                                />
                                <InputError
                                    :message="form.errors.is_active"
                                    class="mt-2"
                                />
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mt-6">
                            <InputLabel for="description" value="Description" />
                            <TextArea
                                id="description"
                                v-model="form.description"
                                class="mt-1 block w-full"
                                rows="4"
                            />
                            <InputError
                                :message="form.errors.description"
                                class="mt-2"
                            />
                        </div>

                        <div
                            class="flex items-center justify-end mt-6 space-x-4"
                        >
                            <SecondaryButton :href="route('slas.index')">
                                Cancel
                            </SecondaryButton>
                            <PrimaryButton :disabled="form.processing">
                                Create SLA
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
