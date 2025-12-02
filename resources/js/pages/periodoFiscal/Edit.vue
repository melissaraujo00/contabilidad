<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { object, string, boolean } from 'yup';
import { ref, computed } from 'vue';

interface Empresa { id: number; nombre: string; }
interface PeriodoFiscal {
    id: number;
    empresa_id: number;
    fecha_inicio: string;
    fecha_cierre: string;
    ejercicio_fiscal: string;
    esta_cerrado: boolean;
}
interface Props {
    empresas: Empresa[];
    periodoFiscal: PeriodoFiscal;
}

const props = defineProps<Props>();
const form = useForm({
    empresa_id: props.periodoFiscal.empresa_id.toString(),
    fecha_inicio: props.periodoFiscal.fecha_inicio,
    fecha_cierre: props.periodoFiscal.fecha_cierre,
    ejercicio_fiscal: props.periodoFiscal.ejercicio_fiscal,
    esta_cerrado: Boolean(props.periodoFiscal.esta_cerrado),
});

const schema = object({
    empresa_id: string().required('La empresa es obligatoria.'),
    fecha_inicio: string().required('La fecha de inicio es obligatoria.')
        .test('valida', 'Fecha inválida.', v => v && !isNaN(new Date(v).getTime())),
    fecha_cierre: string().required('La fecha de cierre es obligatoria.')
        .test('valida', 'Fecha inválida.', v => v && !isNaN(new Date(v).getTime()))
        .test('posterior', 'Debe ser posterior a la fecha de inicio.', function(v) {
            return !v || !this.parent.fecha_inicio || new Date(v) > new Date(this.parent.fecha_inicio);
        }),
    ejercicio_fiscal: string().required('El ejercicio fiscal es obligatorio.').max(45),
    esta_cerrado: boolean()
});

const errors = ref<Record<string, string>>({});

const validate = async (field?: string) => {
    try {
        if (field) {
            await schema.validateAt(field, form.data());
            delete errors.value[field];
        } else {
            await schema.validate(form.data(), { abortEarly: false });
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

const autoGenerar = () => {
    if (form.fecha_inicio && form.fecha_cierre) {
        const [y1, y2] = [new Date(form.fecha_inicio).getFullYear(), new Date(form.fecha_cierre).getFullYear()];
        form.ejercicio_fiscal = y1 === y2 ? `Ejercicio Fiscal ${y1}` : `Ejercicio Fiscal ${y1}-${y2}`;
    }
};

const submit = async () => {
    if (!(await validate())) return;
    form.transform(d => ({ ...d, empresa_id: Number(d.empresa_id), esta_cerrado: d.esta_cerrado ? 1 : 0 }))
        .put(`/periodo-fiscal/${props.periodoFiscal.id}`, {
            onSuccess: () => { errors.value = {}; },
            preserveScroll: true,
        });
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Períodos Fiscales', href: '/periodo-fiscal' },
    { title: 'Editar Período Fiscal', href: `/periodo-fiscal/${props.periodoFiscal.id}/edit` },
];

const today = new Date().toISOString().split('T')[0];
const isValid = computed(() => !Object.keys(errors.value).length &&
    form.empresa_id && form.fecha_inicio && form.fecha_cierre && form.ejercicio_fiscal);
const duracion = computed(() => form.fecha_inicio && form.fecha_cierre ?
    Math.ceil((new Date(form.fecha_cierre).getTime() - new Date(form.fecha_inicio).getTime()) / 86400000) + 1 : 0);
const formatFecha = (f: string) => f ? new Date(f + 'T00:00:00').toLocaleDateString('es-ES',
    { day: '2-digit', month: 'long', year: 'numeric' }) : '';
</script>

<template>
    <Head title="Editar Período Fiscal" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Período Fiscal</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Modifica la información del período fiscal</p>
                </div>
                <Link href="/periodo-fiscal" class="px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-md text-sm font-medium">
                    ← Volver
                </Link>
            </div>

            <div v-if="$page.props.flash?.success" class="bg-green-100 dark:bg-green-900/20 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded">
                {{ $page.props.flash.success }}
            </div>

            <div v-if="form.fecha_inicio && form.fecha_cierre" class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="text-sm text-blue-600 dark:text-blue-400 font-medium mb-2">Resumen del Período</div>
                <div class="grid grid-cols-3 gap-4">
                    <div><div class="text-xs text-gray-500 dark:text-gray-400">Inicio</div><div class="font-medium text-gray-900 dark:text-white">{{ formatFecha(form.fecha_inicio) }}</div></div>
                    <div><div class="text-xs text-gray-500 dark:text-gray-400">Fin</div><div class="font-medium text-gray-900 dark:text-white">{{ formatFecha(form.fecha_cierre) }}</div></div>
                    <div><div class="text-xs text-gray-500 dark:text-gray-400">Duración</div><div class="font-medium text-gray-900 dark:text-white">{{ duracion }} días</div></div>
                </div>
            </div>

            <div class="max-w-2xl mx-auto w-full">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-900 dark:text-white">Empresa <span class="text-red-500">*</span></label>
                            <select v-model="form.empresa_id" @blur="validate('empresa_id')" class="w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500" :class="errors.empresa_id || form.errors.empresa_id ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'">
                                <option value="" disabled>Seleccione una empresa...</option>
                                <option v-for="e in props.empresas" :key="e.id" :value="e.id">{{ e.nombre }}</option>
                            </select>
                            <div v-if="errors.empresa_id || form.errors.empresa_id" class="text-red-600 dark:text-red-400 text-sm">{{ errors.empresa_id || form.errors.empresa_id }}</div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-900 dark:text-white">Fecha de Inicio <span class="text-red-500">*</span></label>
                            <input v-model="form.fecha_inicio" @blur="validate('fecha_inicio')" @change="autoGenerar(); validate('fecha_cierre')" type="date" min="2000-01-01" :max="today" class="w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500" :class="errors.fecha_inicio || form.errors.fecha_inicio ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'" />
                            <div v-if="errors.fecha_inicio || form.errors.fecha_inicio" class="text-red-600 dark:text-red-400 text-sm">{{ errors.fecha_inicio || form.errors.fecha_inicio }}</div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-900 dark:text-white">Fecha de Cierre <span class="text-red-500">*</span></label>
                            <input v-model="form.fecha_cierre" @blur="validate('fecha_cierre')" @change="autoGenerar(); validate('fecha_cierre')" type="date" :min="form.fecha_inicio || '2000-01-01'" :max="today" class="w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500" :class="errors.fecha_cierre || form.errors.fecha_cierre ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'" />
                            <div v-if="errors.fecha_cierre || form.errors.fecha_cierre" class="text-red-600 dark:text-red-400 text-sm">{{ errors.fecha_cierre || form.errors.fecha_cierre }}</div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-900 dark:text-white">Ejercicio Fiscal <span class="text-red-500">*</span> <span class="text-xs text-gray-500 dark:text-gray-400">(Auto-generado)</span></label>
                            <input v-model="form.ejercicio_fiscal" @blur="validate('ejercicio_fiscal')" type="text" maxlength="45" placeholder="Ej: Ejercicio Fiscal 2024" class="w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500" :class="errors.ejercicio_fiscal || form.errors.ejercicio_fiscal ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'" />
                            <div class="flex justify-between">
                                <div v-if="errors.ejercicio_fiscal || form.errors.ejercicio_fiscal" class="text-red-600 dark:text-red-400 text-sm">{{ errors.ejercicio_fiscal || form.errors.ejercicio_fiscal }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 ml-auto">{{ form.ejercicio_fiscal?.length || 0 }}/45</div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-900 dark:text-white">Estado del Período</label>
                            <div class="flex gap-4">
                                <label class="flex items-center cursor-pointer">
                                    <input v-model="form.esta_cerrado" :value="false" type="radio" class="w-4 h-4 text-blue-600" />
                                    <span class="ml-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">✓ Abierto</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input v-model="form.esta_cerrado" :value="true" type="radio" class="w-4 h-4 text-blue-600" />
                                    <span class="ml-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">✕ Cerrado</span>
                                </label>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Un período "Abierto" permite registrar movimientos.</div>
                        </div>

                        <div class="flex gap-3 pt-6">
                            <button type="submit" :disabled="form.processing || !isValid" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg v-if="form.processing" class="inline animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ form.processing ? 'Guardando...' : 'Actualizar Período Fiscal' }}
                            </button>
                            <Link href="/periodo-fiscal" class="px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-md text-sm font-medium">Cancelar</Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
