<template>
    <Head :title="`Edit ${department.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col space-y-4">
                <Breadcrumb
                    :items="[
                        {
                            label: 'Departments',
                            href: route('departments.index'),
                        },
                        {
                            label: department.name,
                            href: route('departments.show', department.id),
                        },
                        { label: 'Edit' },
                    ]"
                />
            </div>
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
import Breadcrumb from "@/Components/Breadcrumb.vue";

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
