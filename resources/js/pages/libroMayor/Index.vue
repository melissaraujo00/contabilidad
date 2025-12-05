<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import { Button } from '@/components/ui/button';

const props = defineProps({
    cuentas: Object,
    todasCuentas: Array,
    filtros: Object
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Libro Mayor',
        href: '/libro-mayor',
    },
];

const form = ref({
    fecha_inicio: props.filtros.fecha_inicio,
    fecha_fin: props.filtros.fecha_fin,
    cuenta_id: props.filtros.cuenta_id || ''
});

const filtrar = () => {
    router.get('/libro-mayor', form.value, { preserveState: true, replace: true });
};

const descargarPDF = () => {
    const params = new URLSearchParams();

    if (form.value.fecha_inicio) {
        params.append("fecha_inicio", form.value.fecha_inicio);
    }

    if (form.value.fecha_fin) {
        params.append("fecha_fin", form.value.fecha_fin);
    }

    if (form.value.cuenta_id) {
        params.append("cuenta_id", form.value.cuenta_id);
    }

    const query = params.toString();
    window.location.href = `/libro-mayor/reporte${query ? "?" + query : ""}`;
};

const formatNumber = (value: number | string) => {
    return new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(Number(value));
};
</script>

<template>
    <Head title="Libro Mayor" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 bg-white dark:bg-black rounded-lg shadow min-h-screen">
            <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Libro Mayor</h1>

            <!-- Filtros -->
            <div class="flex flex-wrap gap-4 mb-8 bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border border-gray-100 dark:border-gray-800">
                <div class="w-full md:w-auto">
                    <Label class="p-1 text-gray-700 dark:text-gray-300">Fecha Inicio</Label>
                    <Input type="date" v-model="form.fecha_inicio" class="bg-white dark:bg-gray-800 dark:text-white dark:border-gray-700" />
                </div>
                <div class="w-full md:w-auto">
                    <Label class="p-1 text-gray-700 dark:text-gray-300">Fecha Fin</Label>
                    <Input type="date" v-model="form.fecha_fin" class="bg-white dark:bg-gray-800 dark:text-white dark:border-gray-700" />
                </div>
                <div class="w-full md:w-64">
                    <Label class="p-1 text-gray-700 dark:text-gray-300">Cuenta (Opcional)</Label>
                    <select v-model="form.cuenta_id" class="w-full border rounded-md p-2 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todas las cuentas</option>
                        <option v-for="c in todasCuentas" :key="c.id" :value="c.id">
                            {{ c.codigo }} - {{ c.cuenta }}
                        </option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <Button @click="filtrar">Filtrar</Button>

                    <Button variant="outline" @click="descargarPDF" class="dark:border-gray-600 dark:text-gray-300 hover:bg-red-600 hover:text-white">
                        Descargar PDF
                    </Button>
                </div>
            </div>

            <!-- Listado de Cuentas -->
            <div class="space-y-8">
                <div v-for="cuenta in cuentas.data" :key="cuenta.id" class="border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden bg-white dark:bg-gray-900">
                    <!-- Cabecera de la Cuenta -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center">
                        <h3 class="font-bold text-lg text-blue-800 dark:text-blue-300">
                            {{ cuenta.codigo }} - {{ cuenta.nombre }}
                        </h3>
                        <div class="text-sm font-mono text-gray-700 dark:text-gray-300">
                            Saldo: <span :class="cuenta.saldo_final >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                $ {{ formatNumber(cuenta.saldo_final) }}
                            </span>
                        </div>
                    </div>

                    <!-- Tabla de Movimientos -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100 dark:bg-gray-800">
                                <tr>
                                    <th class="p-2 text-left text-gray-700 dark:text-gray-300 font-semibold">Fecha</th>
                                    <th class="p-2 text-left text-gray-700 dark:text-gray-300 font-semibold">Partida #</th>
                                    <th class="p-2 text-left text-gray-700 dark:text-gray-300 font-semibold">Concepto</th>
                                    <th class="p-2 text-right text-gray-700 dark:text-gray-300 font-semibold">Debe</th>
                                    <th class="p-2 text-right text-gray-700 dark:text-gray-300 font-semibold">Haber</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                <tr v-for="(mov, idx) in cuenta.movimientos" :key="idx" class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <td class="p-2 text-gray-600 dark:text-gray-400">{{ mov.fecha }}</td>
                                    <td class="p-2 font-mono text-blue-600 dark:text-blue-400">#{{ mov.partida_numero }}</td>
                                    <td class="p-2 text-gray-800 dark:text-gray-200">{{ mov.concepto }}</td>
                                    <td class="p-2 text-right text-gray-600 dark:text-gray-400">
                                        {{ Number(mov.debe) > 0 ? '$ ' + formatNumber(mov.debe) : '-' }}
                                    </td>
                                    <td class="p-2 text-right text-gray-600 dark:text-gray-400">
                                        {{ Number(mov.haber) > 0 ? '$ ' + formatNumber(mov.haber) : '-' }}
                                    </td>
                                </tr>
                            </tbody>

                            <tfoot class="bg-gray-50 dark:bg-gray-800 font-semibold border-t-2 border-gray-200 dark:border-gray-700">
                                <tr>
                                    <td colspan="3" class="p-2 text-right text-gray-600 dark:text-gray-400">Sumas del Periodo:</td>
                                    <td class="p-2 text-right border-t border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100">
                                        $ {{ formatNumber(cuenta.total_debe) }}
                                    </td>
                                    <td class="p-2 text-right border-t border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100">
                                        $ {{ formatNumber(cuenta.total_haber) }}
                                    </td>
                                </tr>

                                <tr class="bg-blue-50 dark:bg-blue-900/20 border-t border-blue-200 dark:border-blue-800">
                                    <td colspan="3" class="p-2 text-right font-bold text-gray-800 dark:text-gray-200">
                                        Saldo Actual:
                                    </td>
                                    <td colspan="2" class="p-2 text-center font-bold text-lg"
                                        :class="cuenta.saldo_final >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                        $ {{ formatNumber(Math.abs(cuenta.saldo_final)) }}
                                        <span class="text-xs font-normal text-gray-500 dark:text-gray-400 ml-1">
                                            ({{ cuenta.saldo_final >= 0 ? 'Deudor' : 'Acreedor' }})
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div v-if="cuentas.data.length === 0" class="text-center py-12 text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-lg">
                    No hay movimientos en este rango de fechas.
                </div>
            </div>
        </div>
    </AppLayout>
</template>
