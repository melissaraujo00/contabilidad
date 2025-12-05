<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import { Button } from '@/components/ui/button';

const props = defineProps<{
    filtros: {
        fechaInicio: string;
        fechaFin: string;
    };
    cuentas: any[];
    totales: {
        debe: number;
        haber: number;
        deudor: number;
        acreedor: number;
    }
}>();

const form = ref({
    fecha_inicio: props.filtros.fechaInicio,
    fecha_fin: props.filtros.fechaFin,
});

const filtrar = () => {
    router.get('/balance-comprobacion', form.value, { preserveState: true, replace: true });
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
    <Head title="Balance de Comprobación" />

    <AppLayout>
        <div class="p-6 space-y-6">
            <!-- Encabezado y Filtros -->
            <div class="flex flex-col md:flex-row justify-between items-end gap-4 bg-white dark:bg-black p-4 rounded-lg shadow border border-gray-200 dark:border-gray-800">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Balance de Comprobación</h1>
                    <p class="text-sm text-gray-500">Sumas y Saldos del {{ form.fecha_inicio }} al {{ form.fecha_fin }}</p>
                </div>
                <div class="flex flex-wrap items-end gap-2">
                    <div>
                        <Label>Fecha Inicio</Label>
                        <Input type="date" v-model="form.fecha_inicio" />
                    </div>
                    <div>
                        <Label>Fecha Fin</Label>
                        <Input type="date" v-model="form.fecha_fin" />
                    </div>
                    <Button @click="filtrar">Generar</Button>
                </div>
            </div>

            <!-- Tabla de Comprobación -->
            <div class="bg-white dark:bg-black rounded-lg shadow border border-gray-200 dark:border-gray-800 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-200 uppercase text-xs font-bold">
                            <tr>
                                <th rowspan="2" class="px-4 py-3 text-left border-r border-gray-200 dark:border-gray-800">Código</th>
                                <th rowspan="2" class="px-4 py-3 text-left border-r border-gray-200 dark:border-gray-800 min-w-[200px]">Cuenta</th>
                                <th colspan="2" class="px-4 py-2 text-center border-b border-gray-200 dark:border-gray-800 bg-blue-50 dark:bg-blue-900/20">Movimientos (Sumas)</th>
                                <th colspan="2" class="px-4 py-2 text-center border-b border-gray-200 dark:border-gray-800 bg-green-50 dark:bg-green-900/20">Saldos</th>
                            </tr>
                            <tr>
                                <th class="px-4 py-2 text-right bg-blue-50 dark:bg-blue-900/20">Debe</th>
                                <th class="px-4 py-2 text-right border-r border-gray-200 dark:border-gray-800 bg-blue-50 dark:bg-blue-900/20">Haber</th>
                                <th class="px-4 py-2 text-right bg-green-50 dark:bg-green-900/20">Deudor</th>
                                <th class="px-4 py-2 text-right bg-green-50 dark:bg-green-900/20">Acreedor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            <tr v-for="cta in cuentas" :key="cta.id" class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400 font-mono">{{ cta.codigo }}</td>
                                <td class="px-4 py-2 font-medium text-gray-800 dark:text-gray-200">{{ cta.cuenta }}</td>

                                <!-- Sumas -->
                                <td class="px-4 py-2 text-right text-gray-600 dark:text-gray-400">
                                    {{ Number(cta.suma_debe) !== 0 ? formatCurrency(cta.suma_debe) : '-' }}
                                </td>
                                <td class="px-4 py-2 text-right text-gray-600 dark:text-gray-400 border-r border-gray-100 dark:border-gray-800">
                                    {{ Number(cta.suma_haber) !== 0 ? formatCurrency(cta.suma_haber) : '-' }}
                                </td>

                                <!-- Saldos -->
                                <td class="px-4 py-2 text-right" :class="{'text-gray-900 dark:text-gray-100 font-semibold': cta.saldo_deudor > 0, 'text-gray-400': cta.saldo_deudor == 0}">
                                    {{ Number(cta.saldo_deudor) !== 0 ? formatCurrency(cta.saldo_deudor) : '-' }}
                                </td>
                                <td class="px-4 py-2 text-right" :class="{'text-gray-900 dark:text-gray-100 font-semibold': cta.saldo_acreedor > 0, 'text-gray-400': cta.saldo_acreedor == 0}">
                                    {{ Number(cta.saldo_acreedor) !== 0 ? formatCurrency(cta.saldo_acreedor) : '-' }}
                                </td>
                            </tr>
                            <tr v-if="cuentas.length === 0">
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500 italic">
                                    No hay movimientos en el rango seleccionado.
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-gray-100 dark:bg-gray-900 font-bold border-t-2 border-gray-300 dark:border-gray-700">
                            <tr>
                                <td colspan="2" class="px-4 py-3 text-right uppercase">Totales Generales:</td>
                                <td class="px-4 py-3 text-right text-blue-700 dark:text-blue-400">{{ formatCurrency(totales.debe) }}</td>
                                <td class="px-4 py-3 text-right text-blue-700 dark:text-blue-400 border-r border-gray-300 dark:border-gray-700">{{ formatCurrency(totales.haber) }}</td>
                                <td class="px-4 py-3 text-right text-green-700 dark:text-green-400">{{ formatCurrency(totales.deudor) }}</td>
                                <td class="px-4 py-3 text-right text-green-700 dark:text-green-400">{{ formatCurrency(totales.acreedor) }}</td>
                            </tr>
                            <!-- Verificación de Cuadre -->
                            <tr class="text-center text-xs text-white">
                                <td colspan="2" class="bg-gray-500 dark:bg-gray-800"></td>
                                <td colspan="2" :class="Math.abs(totales.debe - totales.haber) < 0.01 ? 'bg-green-500' : 'bg-red-500'">
                                    {{ Math.abs(totales.debe - totales.haber) < 0.01 ? 'SUMAS IGUALES' : 'DESCUADRE: ' + formatCurrency(totales.debe - totales.haber) }}
                                </td>
                                <td colspan="2" :class="Math.abs(totales.deudor - totales.acreedor) < 0.01 ? 'bg-green-500' : 'bg-red-500'">
                                    {{ Math.abs(totales.deudor - totales.acreedor) < 0.01 ? 'SALDOS IGUALES' : 'DESCUADRE: ' + formatCurrency(totales.deudor - totales.acreedor) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
