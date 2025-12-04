<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import { Button } from '@/components/ui/button';

const props = defineProps({
    cuentas: Object,
    todasCuentas: Array,
    filtros: Object
});

const form = ref({
    fecha_inicio: props.filtros.fecha_inicio,
    fecha_fin: props.filtros.fecha_fin,
    cuenta_id: props.filtros.cuenta_id || ''
});

const filtrar = () => {
    router.get('/libro-mayor', form.value, { preserveState: true, replace: true });
};
</script>

<template>
    <Head title="Libro Mayor" />

    <AppLayout>
        <div class="p-6 bg-white dark:bg-black rounded-lg shadow">
            <h1 class="text-2xl font-bold mb-6">Libro Mayor</h1>

            <div class="flex flex-wrap gap-4 mb-8 bg-gray-50 p-4 rounded-lg">
                <div class="w-full md:w-auto">
                    <Label>Fecha Inicio</Label>
                    <Input type="date" v-model="form.fecha_inicio" />
                </div>
                <div class="w-full md:w-auto">
                    <Label>Fecha Fin</Label>
                    <Input type="date" v-model="form.fecha_fin" />
                </div>
                <div class="w-full md:w-64">
                    <Label>Cuenta (Opcional)</Label>
                    <select v-model="form.cuenta_id" class="w-full border rounded-md p-2">
                        <option value="">Todas las cuentas</option>
                        <option v-for="c in todasCuentas" :key="c.id" :value="c.id">
                            {{ c.codigo }} - {{ c.cuenta }}
                        </option>
                    </select>
                </div>
                <div class="flex items-end">
                    <Button @click="filtrar">Generar Reporte</Button>
                </div>
            </div>

            <div class="space-y-8">
                <div v-for="cuenta in cuentas.data" :key="cuenta.id" class="border rounded-lg overflow-hidden">
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 border-b flex justify-between items-center">
                        <h3 class="font-bold text-lg text-blue-800 dark:text-blue-300">
                            {{ cuenta.codigo }} - {{ cuenta.nombre }}
                        </h3>
                        <div class="text-sm font-mono">
                            Saldo: <span :class="cuenta.saldo_final >= 0 ? 'text-green-600' : 'text-red-600'">
                                $ {{ Number(cuenta.saldo_final).toFixed(2) }}
                            </span>
                        </div>
                    </div>

                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-800">
                            <tr>
                                <th class="p-2 text-left">Fecha</th>
                                <th class="p-2 text-left">Partida #</th>
                                <th class="p-2 text-left">Concepto</th>
                                <th class="p-2 text-right">Debe</th>
                                <th class="p-2 text-right">Haber</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(mov, idx) in cuenta.movimientos" :key="idx" class="border-t">
                                <td class="p-2">{{ mov.fecha }}</td>
                                <td class="p-2 font-mono text-blue-600">#{{ mov.partida_numero }}</td>
                                <td class="p-2">{{ mov.concepto }}</td>
                                <td class="p-2 text-right text-gray-600">{{ Number(mov.debe) > 0 ? '$ ' + Number(mov.debe).toFixed(2) : '-' }}</td>
                                <td class="p-2 text-right text-gray-600">{{ Number(mov.haber) > 0 ? '$ ' + Number(mov.haber).toFixed(2) : '-' }}</td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-gray-50 font-semibold border-t-2 border-gray-200">
                            <tr>
                                <td colspan="3" class="p-2 text-right">Totales del Periodo:</td>
                                <td class="p-2 text-right">$ {{ Number(cuenta.total_debe).toFixed(2) }}</td>
                                <td class="p-2 text-right">$ {{ Number(cuenta.total_haber).toFixed(2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div v-if="cuentas.data.length === 0" class="text-center py-8 text-gray-500">
                    No hay movimientos en este rango de fechas.
                </div>
            </div>
        </div>
    </AppLayout>
</template>
