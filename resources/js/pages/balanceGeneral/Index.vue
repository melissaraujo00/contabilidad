<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { ref } from 'vue';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import { Button } from '@/components/ui/button';

const props = defineProps<{
    fechaCorte: string;
    activos: any[];
    pasivos: any[];
    patrimonio: any[];
    resultadoEjercicio: number;
    totales: {
        totalActivo: number;
        totalPasivo: number;
        totalPatrimonio: number;
    }
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Balance General',
        href: '/balance-general',
    },
];

const fecha = ref(props.fechaCorte);

const filtrar = () => {
    router.get('/balance-general', { fecha_corte: fecha.value }, { preserveState: true, replace: true });
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
    <Head title="Balance General" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-6">
            <!-- Encabezado y Filtro -->
            <div class="flex flex-col md:flex-row justify-between items-end gap-4 bg-white dark:bg-black p-4 rounded-lg shadow border border-gray-200 dark:border-gray-800">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Balance General</h1>
                    <p class="text-sm text-gray-500">Estado de Situación Financiera al {{ fecha }}</p>
                </div>
                <div class="flex items-end gap-2">
                    <div>
                        <Label class="p-1">Fecha de Corte</Label>
                        <Input type="date" v-model="fecha" />
                    </div>
                    <Button @click="filtrar">Actualizar</Button>
                </div>
            </div>

            <!-- Cuerpo del Balance (Doble Columna) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- COLUMNA IZQUIERDA: ACTIVOS -->
                <div class="bg-white dark:bg-black rounded-lg shadow border border-gray-200 dark:border-gray-800 flex flex-col h-full">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-800 bg-blue-50 dark:bg-blue-900/20">
                        <h2 class="font-bold text-lg text-blue-800 dark:text-blue-300 text-center">ACTIVO</h2>
                    </div>

                    <div class="p-4 flex-1 overflow-auto">
                        <table class="w-full text-sm">
                            <tbody>
                                <tr v-for="cta in activos" :key="cta.id" class="border-b border-dashed border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900">
                                    <td class="py-2 text-gray-600 dark:text-gray-400">{{ cta.codigo }}</td>
                                    <td class="py-2 pl-2 font-medium text-gray-800 dark:text-gray-200">{{ cta.cuenta }}</td>
                                    <td class="py-2 text-right text-gray-700 dark:text-gray-300">{{ formatCurrency(cta.saldo_actual) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 mt-auto">
                        <div class="flex justify-between items-center font-bold text-lg">
                            <span>TOTAL ACTIVO</span>
                            <span class="text-blue-600 dark:text-blue-400">{{ formatCurrency(totales.totalActivo) }}</span>
                        </div>
                    </div>
                </div>

                <!-- COLUMNA DERECHA: PASIVO + PATRIMONIO -->
                <div class="bg-white dark:bg-black rounded-lg shadow border border-gray-200 dark:border-gray-800 flex flex-col h-full">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-800 bg-red-50 dark:bg-red-900/20">
                        <h2 class="font-bold text-lg text-red-800 dark:text-red-300 text-center">PASIVO Y PATRIMONIO</h2>
                    </div>

                    <div class="p-4 flex-1 overflow-auto space-y-6">

                        <!-- Sección Pasivos -->
                        <div>
                            <h3 class="font-bold text-gray-500 mb-2 border-b">PASIVO</h3>
                            <table class="w-full text-sm">
                                <tbody>
                                    <tr v-for="cta in pasivos" :key="cta.id" class="border-b border-dashed border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900">
                                        <td class="py-2 text-gray-600 dark:text-gray-400">{{ cta.codigo }}</td>
                                        <td class="py-2 pl-2 font-medium text-gray-800 dark:text-gray-200">{{ cta.cuenta }}</td>
                                        <td class="py-2 text-right text-gray-700 dark:text-gray-300">{{ formatCurrency(cta.saldo_actual) }}</td>
                                    </tr>
                                    <tr v-if="pasivos.length === 0">
                                        <td colspan="3" class="py-2 text-center text-gray-400 italic">Sin pasivos registrados</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="py-2 text-right font-semibold text-xs text-gray-500">Total Pasivo:</td>
                                        <td class="py-2 text-right font-semibold text-gray-700 dark:text-gray-300">{{ formatCurrency(totales.totalPasivo) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Sección Patrimonio -->
                        <div>
                            <h3 class="font-bold text-gray-500 mb-2 border-b">PATRIMONIO</h3>
                            <table class="w-full text-sm">
                                <tbody>
                                    <tr v-for="cta in patrimonio" :key="cta.id" class="border-b border-dashed border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900">
                                        <td class="py-2 text-gray-600 dark:text-gray-400">{{ cta.codigo }}</td>
                                        <td class="py-2 pl-2 font-medium text-gray-800 dark:text-gray-200">{{ cta.cuenta }}</td>
                                        <td class="py-2 text-right text-gray-700 dark:text-gray-300">{{ formatCurrency(cta.saldo_actual) }}</td>
                                    </tr>

                                    <!-- RESULTADO DEL EJERCICIO (Calculado Dinámicamente) -->
                                    <tr class="bg-yellow-50 dark:bg-yellow-900/10">
                                        <td class="py-2 text-gray-600 dark:text-gray-400">R-EJER</td>
                                        <td class="py-2 pl-2 font-bold text-gray-800 dark:text-gray-200">
                                            {{ resultadoEjercicio >= 0 ? 'Utilidad del Ejercicio' : 'Pérdida del Ejercicio' }}
                                        </td>
                                        <td class="py-2 text-right font-bold" :class="resultadoEjercicio >= 0 ? 'text-green-600' : 'text-red-600'">
                                            {{ formatCurrency(resultadoEjercicio) }}
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="py-2 text-right font-semibold text-xs text-gray-500">Total Patrimonio:</td>
                                        <td class="py-2 text-right font-semibold text-gray-700 dark:text-gray-300">{{ formatCurrency(totales.totalPatrimonio) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 mt-auto">
                        <div class="flex justify-between items-center font-bold text-lg">
                            <span>TOTAL PASIVO + PATRIMONIO</span>
                            <span class="text-red-600 dark:text-red-400">
                                {{ formatCurrency(totales.totalPasivo + totales.totalPatrimonio) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ecuación Contable Status -->
            <div class="mt-6 p-4 rounded-lg text-center font-bold border"
                :class="Math.abs(totales.totalActivo - (totales.totalPasivo + totales.totalPatrimonio)) < 0.01
                    ? 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:border-green-800 dark:text-green-400'
                    : 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:border-red-800 dark:text-red-400'">

                <span v-if="Math.abs(totales.totalActivo - (totales.totalPasivo + totales.totalPatrimonio)) < 0.01">
                    ✓ EL BALANCE ESTÁ CUADRADO
                </span>
                <span v-else>
                    ⚠ EL BALANCE ESTÁ DESCUADRADO (Diferencia: {{ formatCurrency(Math.abs(totales.totalActivo - (totales.totalPasivo + totales.totalPatrimonio))) }})
                </span>
            </div>

        </div>
    </AppLayout>
</template>
