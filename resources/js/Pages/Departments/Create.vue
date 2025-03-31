<template>
    <Head title="Create Department" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col space-y-4">
                <Breadcrumb
                    :items="[
                        {
                            label: 'Departments',
                            href: route('departments.index'),
                        },
                        { label: 'Create Department' },
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
                            submit-label="Create Department"
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

const form = useForm({
    name: "",
    description: "",
    is_active: true,
});

const submitForm = () => {
    form.post(route("departments.store"));
};
</script>
