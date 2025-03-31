<template>
    <Head title="Edit Department" />

    <AuthenticatedLayout>
        <template #header>
            <Breadcrumb :items="[{ label: 'Edit Department' }]" />
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <DepartmentForm
                            :form="form"
                            submit-label="Update Department"
                            @submitted="submitForm"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DepartmentForm from "./Partials/DepartmentForm.vue";

const props = defineProps({
    department: Object,
});

const form = useForm({
    name: props.department.name,
    description: props.department.description,
    is_active: props.department.is_active,
});

const submitForm = () => {
    form.patch(route("departments.update", props.department.id));
};
</script>
