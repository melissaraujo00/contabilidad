<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from "@inertiajs/vue3";
import { object, string } from 'yup';
import { ref, computed } from 'vue';

interface Empresa {
    id: number;
    nombre: string;
    tipo_empresa: string;
}

interface Props {
    empresa: Empresa;
    tiposEmpresa: string[];
}

const props = defineProps<Props>();

const form = useForm({
    nombre: props.empresa.nombre,
    tipo_empresa: props.empresa.tipo_empresa,
});

const schema = object({
    nombre: string()
        .required('El nombre de la empresa es obligatorio.')
        .max(100, 'El nombre no debe exceder los 100 caracteres.')
        .matches(/^[^\d]+$/, 'El nombre no debe contener números.'),
    tipo_empresa: string()
        .required('El tipo de empresa es obligatorio.')
});

const errors = ref<Record<string, string>>({});

const validate = async (field?: string) => {
    try {
        const data = { nombre: form.nombre, tipo_empresa: form.tipo_empresa };

        if (field) {
            await schema.validateAt(field, data);
            delete errors.value[field];
        } else {
            await schema.validate(data, { abortEarly: false });
            errors.value = {};
            return true;
        }
    } catch (e: any) {
        if (field) {
            errors.value[field] = e.message;
        } else {
            errors.value = e.inner.reduce((acc: any, err: any) =>
                ({ ...acc, [err.path]: err.message }), {});
            return false;
        }
    }
};

const tipos = [
    { value: "Empresa", label: "Empresa" },
    { value: "Sociedad", label: "Sociedad" },
    { value: "Sociedad Anónima", label: "Sociedad Anónima" },
    { value: "Sociedades Anónimas de Capital Variable", label: "Sociedad de Capital Variable" },
    { value: "Sociedad en Nombre Colectivo", label: "Sociedad Colectiva" },
];

const breadcrumbs = [
    { title: 'Empresas', href: '/empresa' },
    { title: 'Editar Empresa', href: `/empresa/${props.empresa.id}/edit` },
];

const submit = async () => {
    if (!(await validate())) return;
    form.put(`/empresa/${props.empresa.id}`, {
        onSuccess: () => { errors.value = {}; },
        preserveScroll: true,
    });
};

const isValid = computed(() => !Object.keys(errors.value).length && form.nombre && form.tipo_empresa);
</script>

<template>
    <Head title="Editar Empresa" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Empresa</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Modifica la información de la empresa</p>
                </div>
                <Link href="/empresa" class="px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-md text-sm font-medium">
                    ← Volver
                </Link>
            </div>

            <div v-if="$page.props.flash?.success" class="bg-green-100 dark:bg-green-900/20 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded">
                {{ $page.props.flash.success }}
            </div>

            <div class="max-w-2xl mx-auto w-full">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-900 dark:text-white">
                                Nombre de la Empresa <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.nombre"
                                @blur="validate('nombre')"
                                type="text"
                                maxlength="100"
                                placeholder="Ingrese el nombre de la empresa"
                                class="w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:ring-2 focus:ring-blue-500"
                                :class="errors.nombre || form.errors.nombre ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'"
                            />
                            <div class="flex justify-between items-start">
                                <div v-if="errors.nombre || form.errors.nombre" class="text-red-600 dark:text-red-400 text-sm">
                                    {{ errors.nombre || form.errors.nombre }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 ml-auto">
                                    {{ form.nombre?.length || 0 }}/100
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-900 dark:text-white">
                                Tipo de Empresa <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="form.tipo_empresa"
                                @change="validate('tipo_empresa')"
                                class="w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500"
                                :class="errors.tipo_empresa || form.errors.tipo_empresa ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'"
                            >
                                <option value="" disabled>Seleccione un tipo...</option>
                                <option v-for="t in tipos" :key="t.value" :value="t.value">{{ t.label }}</option>
                            </select>
                            <div v-if="errors.tipo_empresa || form.errors.tipo_empresa" class="text-red-600 dark:text-red-400 text-sm">
                                {{ errors.tipo_empresa || form.errors.tipo_empresa }}
                            </div>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button
                                type="submit"
                                :disabled="form.processing || !isValid"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            >
                                <svg v-if="form.processing" class="inline animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ form.processing ? 'Guardando...' : 'Actualizar Empresa' }}
                            </button>
                            <Link href="/empresa" class="px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-md text-sm font-medium transition-colors">
                                Cancelar
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
