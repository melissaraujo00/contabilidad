<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { ref } from 'vue';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import { Button } from '@/components/ui/button';

const props = defineProps<{
    filtros: {
        fechaInicio: string;
        fechaFin: string;
    };
    ingresos: any[];
    costos: any[];
    gastos: any[];
    totales: {
        totalIngresos: number;
        totalCostos: number;
        totalGastos: number;
        utilidadBruta: number;
        utilidadNeta: number;
    }
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Estado de Resultados',
        href: '/estado-resultados',
    },
];

const form = ref({
    fecha_inicio: props.filtros.fechaInicio,
    fecha_fin: props.filtros.fechaFin,
});

const filtrar = () => {
    router.get('/estado-resultados', form.value, { preserveState: true, replace: true });
};

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2
    }).format(value);
};
</script>

<template>
    <Head title="Estado de Resultados" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-6 max-w-5xl mx-auto">
            <!-- Encabezado y Filtros -->
            <div class="flex flex-col md:flex-row justify-between items-end gap-4 bg-white dark:bg-black p-4 rounded-lg shadow border border-gray-200 dark:border-gray-800">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Estado de Resultados</h1>
                    <p class="text-sm text-gray-500">Del {{ form.fecha_inicio }} al {{ form.fecha_fin }}</p>
                </div>
                <div class="flex flex-wrap items-end gap-2">
                    <div>
                        <Label class="p-1">Fecha Inicio</Label>
                        <Input type="date" v-model="form.fecha_inicio" />
                    </div>
                    <div>
                        <Label class="p-1">Fecha Fin</Label>
                        <Input type="date" v-model="form.fecha_fin" />
                    </div>
                    <Button @click="filtrar">Generar</Button>
                </div>
            </div>

            <!-- Cuerpo del Reporte -->
            <div class="bg-white dark:bg-black rounded-lg shadow border border-gray-200 dark:border-gray-800 overflow-hidden">
                <div class="p-6">

                    <!-- SECCIÓN: INGRESOS -->
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700 pb-2 mb-3">
                            INGRESOS DE OPERACIÓN
                        </h3>
                        <table class="w-full text-sm">
                            <tbody>
                                <tr v-for="cta in ingresos" :key="cta.id" class="border-b border-dashed border-gray-100 dark:border-gray-800">
                                    <td class="py-2 text-gray-500 w-24">{{ cta.codigo }}</td>
                                    <td class="py-2 text-gray-700 dark:text-gray-300">{{ cta.cuenta }}</td>
                                    <td class="py-2 text-right text-gray-700 dark:text-gray-300">{{ formatCurrency(cta.saldo_periodo) }}</td>
                                </tr>
                                <tr v-if="ingresos.length === 0">
                                    <td colspan="3" class="py-2 italic text-gray-400">Sin ingresos en este periodo</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="py-3 font-semibold text-right">Total Ingresos:</td>
                                    <td class="py-3 font-semibold text-right text-green-600 dark:text-green-400 border-t border-gray-300 dark:border-gray-600">
                                        {{ formatCurrency(totales.totalIngresos) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- SECCIÓN: COSTOS (Si existen) -->
                    <div v-if="costos.length > 0 || totales.totalCostos > 0" class="mb-6">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700 pb-2 mb-3">
                            (-) COSTOS DE VENTAS
                        </h3>
                        <table class="w-full text-sm">
                            <tbody>
                                <tr v-for="cta in costos" :key="cta.id" class="border-b border-dashed border-gray-100 dark:border-gray-800">
                                    <td class="py-2 text-gray-500 w-24">{{ cta.codigo }}</td>
                                    <td class="py-2 text-gray-700 dark:text-gray-300">{{ cta.cuenta }}</td>
                                    <td class="py-2 text-right text-gray-700 dark:text-gray-300">{{ formatCurrency(cta.saldo_periodo) }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="py-3 font-semibold text-right">Total Costos:</td>
                                    <td class="py-3 font-semibold text-right text-red-600 dark:text-red-400 border-t border-gray-300 dark:border-gray-600">
                                        ({{ formatCurrency(totales.totalCostos) }})
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- UTILIDAD BRUTA -->
                    <div class="bg-gray-100 dark:bg-gray-900 p-3 rounded mb-6 flex justify-between items-center font-bold text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700">
                        <span>UTILIDAD BRUTA</span>
                        <span>{{ formatCurrency(totales.utilidadBruta) }}</span>
                    </div>

                    <!-- SECCIÓN: GASTOS -->
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700 pb-2 mb-3">
                            (-) GASTOS DE OPERACIÓN
                        </h3>
                        <table class="w-full text-sm">
                            <tbody>
                                <tr v-for="cta in gastos" :key="cta.id" class="border-b border-dashed border-gray-100 dark:border-gray-800">
                                    <td class="py-2 text-gray-500 w-24">{{ cta.codigo }}</td>
                                    <td class="py-2 text-gray-700 dark:text-gray-300">{{ cta.cuenta }}</td>
                                    <td class="py-2 text-right text-gray-700 dark:text-gray-300">{{ formatCurrency(cta.saldo_periodo) }}</td>
                                </tr>
                                <tr v-if="gastos.length === 0">
                                    <td colspan="3" class="py-2 italic text-gray-400">Sin gastos en este periodo</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="py-3 font-semibold text-right">Total Gastos:</td>
                                    <td class="py-3 font-semibold text-right text-red-600 dark:text-red-400 border-t border-gray-300 dark:border-gray-600">
                                        ({{ formatCurrency(totales.totalGastos) }})
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- RESULTADO FINAL -->
                    <div class="mt-8 pt-4 border-t-2 border-gray-300 dark:border-gray-600">
                        <div class="flex justify-between items-center text-xl font-bold p-4 rounded-lg"
                            :class="totales.utilidadNeta >= 0
                                ? 'bg-green-100 text-green-800 border border-green-200 dark:bg-green-900/30 dark:border-green-800 dark:text-green-400'
                                : 'bg-red-100 text-red-800 border border-red-200 dark:bg-red-900/30 dark:border-red-800 dark:text-red-400'">
                            <span>{{ totales.utilidadNeta >= 0 ? 'UTILIDAD DEL EJERCICIO' : 'PÉRDIDA DEL EJERCICIO' }}</span>
                            <span>{{ formatCurrency(totales.utilidadNeta) }}</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>
