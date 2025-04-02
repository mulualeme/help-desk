<template>
    <Head :title="`Edit ${agent.name}'s Role`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit {{ agent.name }}'s Role
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3
                                        class="text-lg font-medium text-gray-900"
                                    >
                                        Agent Information
                                    </h3>
                                </div>
                            </div>
                            <div
                                class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6"
                            >
                                <div>
                                    <InputLabel value="Name" />
                                    <div class="mt-1 font-medium">
                                        {{ agent.name }}
                                    </div>
                                </div>
                                <div>
                                    <InputLabel value="Email" />
                                    <div class="mt-1">{{ agent.email }}</div>
                                </div>
                            </div>
                        </div>

                        <form @submit.prevent="submit">
                            <h4 class="font-medium text-gray-900 mb-2">
                                Change Role
                            </h4>
                            <div class="mb-4">
                                <div class="mt-2 grid gap-4">
                                    <div
                                        v-for="role in roles"
                                        :key="role.id"
                                        class="flex items-center"
                                    >
                                        <input
                                            type="radio"
                                            :id="role.name"
                                            :value="role.name"
                                            v-model="form.role"
                                            :checked="hasRole(agent, role.name)"
                                            class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                        />
                                        <label
                                            :for="role.name"
                                            class="ml-3 block text-sm font-medium text-gray-700"
                                        >
                                            {{ formatRoleName(role.name) }}
                                        </label>
                                    </div>
                                </div>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.role"
                                />
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <Link
                                    :href="route('agents.index')"
                                    class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 mr-3"
                                >
                                    Cancel
                                </Link>
                                <PrimaryButton
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Update Role
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { onMounted } from "vue";
import { useForm, Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    agent: Object,
    roles: Array,
});

const form = useForm({
    role: "",
});

onMounted(() => {
    // Set the initial role
    if (hasRole(props.agent, "admin")) {
        form.role = "admin";
    } else {
        form.role = "agent";
    }
});

const hasRole = (agent, roleName) => {
    return agent.roles.some((role) => role.name === roleName);
};

const formatRoleName = (roleName) => {
    return roleName.charAt(0).toUpperCase() + roleName.slice(1);
};

const submit = () => {
    form.put(route("agents.update", props.agent.id));
};
</script>
