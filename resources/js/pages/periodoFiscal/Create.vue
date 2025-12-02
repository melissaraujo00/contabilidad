<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { object, string, boolean } from 'yup';
import { ref, computed, watch } from 'vue';

interface Empresa {
    id: number;
    nombre: string;
}

interface Props {
    empresas: Empresa[];
}
const props = defineProps<Props>();
const form = useForm({
    empresa_id: '',
    fecha_inicio: '',
    fecha_cierre: '',
    ejercicio_fiscal: '',
    esta_cerrado: false,
});

const validationSchema = object({
    empresa_id: string()
        .required('La empresa es obligatoria.'),
    fecha_inicio: string()
        .required('La fecha de inicio es obligatoria.')
        .test(
            'fecha-valida',
            'La fecha de inicio debe ser válida.',
            (value) => {
                if (!value) return false;
                const fecha = new Date(value);
                return !isNaN(fecha.getTime());
            }
        ),
    fecha_cierre: string()
        .required('La fecha de cierre es obligatoria.')
        .test(
            'fecha-valida',
            'La fecha de cierre debe ser válida.',
            (value) => {
                if (!value) return false;
                const fecha = new Date(value);
                return !isNaN(fecha.getTime());
            }
        )
        .test(
            'fecha-posterior',
            'La fecha de cierre debe ser posterior a la fecha de inicio.',
            function(value) {
                const { fecha_inicio } = this.parent;
                if (!fecha_inicio || !value) return true;

                const inicio = new Date(fecha_inicio);
                const cierre = new Date(value);
                return cierre > inicio;
            }
        ),
    ejercicio_fiscal: string()
        .required('El ejercicio fiscal es obligatorio.')
        .max(45, 'El ejercicio fiscal no debe exceder los 45 caracteres.'),
    esta_cerrado: boolean()
});

const localErrors = ref<Record<string, string>>({});

const validateField = async (field: string, value: any) => {
    try {
        await validationSchema.validateAt(field, { [field]: value, ...form });
        delete localErrors.value[field];
    } catch (error: any) {
        localErrors.value[field] = error.message;
    }
};

const validateAll = async () => {
    try {
        await validationSchema.validate(form, { abortEarly: false });
        localErrors.value = {};
        return true;
    } catch (error: any) {
        const errors: Record<string, string> = {};
        error.inner.forEach((err: any) => {
            errors[err.path] = err.message;
        });
        localErrors.value = errors;
        return false;
    }
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Períodos Fiscales',
        href: '/periodo-fiscal',
    },
    {
        title: 'Crear Período Fiscal',
        href: '/periodo-fiscal/create',
    },
];

const submit = async () => {
    console.log('Enviando datos:', form.data());
    const isValid = await validateAll();

    if (!isValid) {
        console.log('Errores de validación Yup:', localErrors.value);
        return;
    }
    form.transform((data) => ({
        ...data,
        esta_cerrado: data.esta_cerrado ? 1 : 0 // Convertir a 1/0 para Laravel
    })).post('/periodo-fiscal', {
        onSuccess: () => {
            console.log('¡Éxito!');
            form.reset();
        },
        onError: (errors) => {
            console.log('Errores del servidor:', errors);
        },
        preserveScroll: true,
    });
};

const today = new Date().toISOString().split('T')[0];
const minDate = '2000-01-01';
const generarEjercicioFiscal = () => {
    if (form.fecha_inicio && form.fecha_cierre) {
        const inicio = new Date(form.fecha_inicio);
        const cierre = new Date(form.fecha_cierre);
        const añoInicio = inicio.getFullYear();
        const añoCierre = cierre.getFullYear();

        if (añoInicio === añoCierre) {
            form.ejercicio_fiscal = `Ejercicio Fiscal ${añoInicio}`;
        } else {
            form.ejercicio_fiscal = `Ejercicio Fiscal ${añoInicio}-${añoCierre}`;
        }
    }
};
watch(() => [form.fecha_inicio, form.fecha_cierre], () => {
    if (form.fecha_inicio && form.fecha_cierre && !form.ejercicio_fiscal) {
        generarEjercicioFiscal();
    }
});

const formIsValid = computed(() => {
    return Object.keys(localErrors.value).length === 0 &&
           form.empresa_id &&
           form.fecha_inicio &&
           form.fecha_cierre &&
           form.ejercicio_fiscal;
});

const formatFecha = (fechaStr: string) => {
    if (!fechaStr) return '';
    const fecha = new Date(fechaStr);
    return fecha.toLocaleDateString('es-ES', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    });
};
</script>

<template>
    <Head title="Crear Período Fiscal" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Crear Nuevo Período Fiscal</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        Complete la información para registrar un nuevo período fiscal
                    </p>
                </div>

                <Link
                    href="/periodo-fiscal"
                    class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-md text-sm font-medium transition-colors"
                >
                    ← Volver a la lista
                </Link>
            </div>
            <div v-if="$page.props.flash?.success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ $page.props.flash.success }}
            </div>

            <div v-if="$page.props.flash?.error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ $page.props.flash.error }}
            </div>
            <div v-if="form.fecha_inicio && form.fecha_cierre"
                 class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="text-sm text-blue-600 dark:text-blue-400 font-medium mb-2">Resumen del Período</div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Inicio</div>
                        <div class="font-medium">{{ formatFecha(form.fecha_inicio) }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Fin</div>
                        <div class="font-medium">{{ formatFecha(form.fecha_cierre) }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Duración</div>
                        <div class="font-medium">
                            <span v-if="form.fecha_inicio && form.fecha_cierre">
                                {{
                                    Math.ceil(
                                        (new Date(form.fecha_cierre).getTime() - new Date(form.fecha_inicio).getTime())
                                        / (1000 * 3600 * 24)
                                    ) + 1
                                }} días
                            </span>
                            <span v-else>-</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="max-w-2xl mx-auto w-full">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-900 dark:text-white">
                                Empresa
                                <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="form.empresa_id"
                                @blur="validateField('empresa_id', form.empresa_id)"
                                class="w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                :class="{
                                    'border-gray-300 dark:border-gray-600': !localErrors.empresa_id && !form.errors.empresa_id,
                                    'border-red-500': localErrors.empresa_id || form.errors.empresa_id
                                }"
                            >
                                <option value="" disabled class="text-gray-500 dark:text-gray-400">
                                    Seleccione una empresa...
                                </option>
                                <option
                                    v-for="empresa in props.empresas"
                                    :key="empresa.id"
                                    :value="empresa.id"
                                    class="text-gray-900 dark:text-white"
                                >
                                    {{ empresa.nombre }}
                                </option>
                            </select>
                            <div v-if="localErrors.empresa_id" class="text-red-600 dark:text-red-400 text-sm">
                                {{ localErrors.empresa_id }}
                            </div>
                            <div v-if="form.errors.empresa_id && !localErrors.empresa_id" class="text-red-600 dark:text-red-400 text-sm">
                                {{ form.errors.empresa_id }}
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-900 dark:text-white">
                                Fecha de Inicio
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.fecha_inicio"
                                @blur="validateField('fecha_inicio', form.fecha_inicio)"
                                @change="generarEjercicioFiscal(); validateField('fecha_cierre', form.fecha_cierre)"
                                type="date"
                                :min="minDate"
                                :max="today"
                                class="w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                :class="{
                                    'border-gray-300 dark:border-gray-600': !localErrors.fecha_inicio && !form.errors.fecha_inicio,
                                    'border-red-500': localErrors.fecha_inicio || form.errors.fecha_inicio
                                }"
                            />
                            <div v-if="localErrors.fecha_inicio" class="text-red-600 dark:text-red-400 text-sm">
                                {{ localErrors.fecha_inicio }}
                            </div>
                            <div v-if="form.errors.fecha_inicio && !localErrors.fecha_inicio" class="text-red-600 dark:text-red-400 text-sm">
                                {{ form.errors.fecha_inicio }}
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-900 dark:text-white">
                                Fecha de Cierre
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.fecha_cierre"
                                @blur="validateField('fecha_cierre', form.fecha_cierre)"
                                @change="generarEjercicioFiscal()"
                                type="date"
                                :min="form.fecha_inicio || minDate"
                                :max="today"
                                class="w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                :class="{
                                    'border-gray-300 dark:border-gray-600': !localErrors.fecha_cierre && !form.errors.fecha_cierre,
                                    'border-red-500': localErrors.fecha_cierre || form.errors.fecha_cierre
                                }"
                            />
                            <div v-if="localErrors.fecha_cierre" class="text-red-600 dark:text-red-400 text-sm">
                                {{ localErrors.fecha_cierre }}
                            </div>
                            <div v-if="form.errors.fecha_cierre && !localErrors.fecha_cierre" class="text-red-600 dark:text-red-400 text-sm">
                                {{ form.errors.fecha_cierre }}
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-900 dark:text-white">
                                Ejercicio Fiscal
                                <span class="text-red-500">*</span>
                                <span class="text-xs text-gray-500 ml-2">(Se auto-genera o puedes editarlo)</span>
                            </label>
                            <input
                                v-model="form.ejercicio_fiscal"
                                @blur="validateField('ejercicio_fiscal', form.ejercicio_fiscal)"
                                type="text"
                                maxlength="45"
                                class="w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                :class="{
                                    'border-gray-300 dark:border-gray-600': !localErrors.ejercicio_fiscal && !form.errors.ejercicio_fiscal,
                                    'border-red-500': localErrors.ejercicio_fiscal || form.errors.ejercicio_fiscal
                                }"
                                placeholder="Ej: Ejercicio Fiscal 2024"
                            />
                            <div class="flex justify-between">
                                <div v-if="localErrors.ejercicio_fiscal" class="text-red-600 dark:text-red-400 text-sm">
                                    {{ localErrors.ejercicio_fiscal }}
                                </div>
                                <div v-if="form.errors.ejercicio_fiscal && !localErrors.ejercicio_fiscal" class="text-red-600 dark:text-red-400 text-sm">
                                    {{ form.errors.ejercicio_fiscal }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ form.ejercicio_fiscal?.length || 0 }}/45 caracteres
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-900 dark:text-white">
                                Estado del Período
                            </label>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <input
                                        v-model="form.esta_cerrado"
                                        :value="false"
                                        type="radio"
                                        id="abierto"
                                        class="w-4 h-4 text-blue-600 focus:ring-blue-500"
                                    />
                                    <label for="abierto" class="ml-2 text-sm text-gray-900 dark:text-white">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Abierto
                                        </span>
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input
                                        v-model="form.esta_cerrado"
                                        :value="true"
                                        type="radio"
                                        id="cerrado"
                                        class="w-4 h-4 text-blue-600 focus:ring-blue-500"
                                    />
                                    <label for="cerrado" class="ml-2 text-sm text-gray-900 dark:text-white">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                            Cerrado
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="text-xs text-gray-500">
                                Nota: Un período "Abierto" permite registrar movimientos. "Cerrado" bloquea nuevos registros.
                            </div>
                            <div v-if="form.errors.esta_cerrado" class="text-red-600 dark:text-red-400 text-sm">
                                {{ form.errors.esta_cerrado }}
                            </div>
                        </div>
                        <div class="flex gap-3 pt-6">
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="form.processing || !formIsValid"
                            >
                                <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ form.processing ? 'Guardando...' : 'Crear Período Fiscal' }}
                            </button>

                            <Link
                                href="/periodo-fiscal"
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
