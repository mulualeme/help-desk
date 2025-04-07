<script setup>
import { ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SlaFilters from "./Components/SlaFilters.vue";
import SlaTable from "./Components/SlaTable.vue";
import DeleteModal from "./Components/DeleteModal.vue";

const props = defineProps({
    slas: Object,
    filters: Object,
    can: Object,
});

const showDeleteModal = ref(false);
const slaToDelete = ref(null);

// Show the delete confirmation modal
const handleShowDeleteModal = (sla) => {
    slaToDelete.value = sla;
    showDeleteModal.value = true;
};

// Close the delete modal
const handleCloseDeleteModal = () => {
    showDeleteModal.value = false;
    slaToDelete.value = null;
};
</script>

<template>
    <Head title="Service Level Agreements" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <Breadcrumb :items="[{ label: 'Service Level Agreements' }]" />

                <Link v-if="can && can.createSla" :href="route('slas.create')">
                    <PrimaryButton> Create SLA </PrimaryButton>
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Filters -->
                <SlaFilters :filters="filters" />

                <!-- SLAs Table -->
                <SlaTable
                    :slas="slas"
                    :can="can"
                    @show-delete-modal="handleShowDeleteModal"
                />
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <DeleteModal
            :show="showDeleteModal"
            :sla="slaToDelete"
            @close="handleCloseDeleteModal"
        />
    </AuthenticatedLayout>
</template>
