<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from "@inertiajs/vue3";

const form = useForm({
    nombre: "",
    tipo_empresa: "",
});

const tipos = [
    { value: "empresa", label: "Empresa" },
    { value: "sociedad", label: "Sociedad" },
    { value: "sociedad_anonima", label: "Sociedad Anónima" },
    { value: "sociedad_variable", label: "Sociedad de Capital Variable" },
    { value: "sociedad_colectivo", label: "Sociedad Colectiva" },
];

const breadcrumbs = [
    {
        title: 'Empresas',
        href: '/empresa',
    },
    {
        title: 'Crear Empresa',
        href: '/empresa/create',
    },
];

const submit = () => {
    form.post("/empresa");
};
</script>

<template>
    <Head title="Crear Empresa" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Crear Nueva Empresa</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Complete la información para registrar una nueva empresa</p>
                </div>
            </div>

            <!-- Form Container -->
            <div class="max-w-2xl mx-auto w-full">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Nombre Field -->
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-900 dark:text-white">
                                Nombre de la Empresa
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.nombre"
                                type="text"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                placeholder="Ingrese el nombre de la empresa"
                                :class="{ 'border-red-500': form.errors.nombre }"
                            />
                            <div v-if="form.errors.nombre" class="text-red-600 dark:text-red-400 text-sm">
                                {{ form.errors.nombre }}
                            </div>
                        </div>

                        <!-- Tipo de Empresa Field -->
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-900 dark:text-white">
                                Tipo de Empresa
                                <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="form.tipo_empresa"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                :class="{ 'border-red-500': form.errors.tipo_empresa }"
                            >
                                <option value="" disabled class="text-gray-500 dark:text-gray-400">Seleccione un tipo...</option>
                                <option
                                    v-for="t in tipos"
                                    :key="t.value"
                                    :value="t.value"
                                    class="text-gray-900 dark:text-white"
                                >
                                    {{ t.label }}
                                </option>
                            </select>
                            <div v-if="form.errors.tipo_empresa" class="text-red-600 dark:text-red-400 text-sm">
                                {{ form.errors.tipo_empresa }}
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 pt-4">
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="form.processing"
                            >
                                <span v-if="form.processing" class="animate-spin mr-2">⟳</span>
                                {{ form.processing ? 'Guardando...' : 'Crear Empresa' }}
                            </button>

                            <Link
                                href="/empresa"
                                class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-md text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >
                                Cancelar
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
