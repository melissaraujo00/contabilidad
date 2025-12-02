<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { store } from '@/actions/App/Http/Controllers/PartidaController';
import { computed } from 'vue';
import { Trash2, Plus } from 'lucide-vue-next';

const { periodos, tiposPartida, cuentas } = usePage().props;

interface DetallePartida {
    id_cuenta: string;
    tipo_movimiento: 'DEBE' | 'HABER' | '';
    monto_debe: number | null;
    monto_haber: number | null;
    parcial: number | null;
    orden: number;
    observaciones: string;
}

const form = useForm({
    partida_numero: null,
    fecha_partida: null,
    periodo_fiscal_id: "",
    tipo_partida: "",
    description: null,
    estado: true,
    detalles: [] as DetallePartida[]
});

// Inicialización
form.detalles.push({
    id_cuenta: '',
    tipo_movimiento: '',
    monto_debe: null,
    monto_haber: null,
    parcial: null,
    orden: 1,
    observaciones: ''
});

const totalDebe = computed(() =>
    form.detalles.reduce((s, d) => s + (Number(d.monto_debe) || 0), 0)
);

const totalHaber = computed(() =>
    form.detalles.reduce((s, d) => s + (Number(d.monto_haber) || 0), 0)
);

const diferencia = computed(() => totalDebe.value - totalHaber.value);

const estaBalanceada = computed(() =>
    Math.abs(diferencia.value) < 0.01 && totalDebe.value > 0
);

const agregarDetalle = () => {
    form.detalles.push({
        id_cuenta: '',
        tipo_movimiento: '',
        monto_debe: null,
        monto_haber: null,
        parcial: null,
        orden: form.detalles.length + 1,
        observaciones: ''
    });
};

const eliminarDetalle = (i: number) => {
    if (form.detalles.length > 1) {
        form.detalles.splice(i, 1);
        form.detalles.forEach((d, idx) => d.orden = idx + 1);
    }
};

const handleSubmit = () => {
    if (!estaBalanceada.value) {
        alert('La partida debe estar balanceada (DEBE = HABER)');
        return;
    }

    form.submit(store());
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Partidas', href: '/partidas' },
    { title: 'Nueva Partida', href: '/partidas/create' }
];
</script>


<template>
    <Head title="Listado de Partidas" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4 bg-white dark:bg-black">

            <header class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nueva Partida</h1>
            </header>

            <form @submit.prevent="handleSubmit">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="w-full md:w-1/3 space-y-4">
                        <Label>Partida #</Label>
                        <Input v-model="form.partida_numero" type="number" placeholder="partida #" />
                    </div>

                    <div class="w-full md:w-1/3 space-y-4">
                        <Label>Fecha</Label>
                        <Input v-model="form.fecha_partida" type="date" />
                    </div>

                    <div class="w-full md:w-1/3 space-y-4">
                        <Label>Tipo de Partida</Label>
                        <select v-model="form.tipo_partida" class="w-full px-3 py-2 border rounded-md">
                            <option value="">Seleccione tipo de partida</option>
                            <option v-for="tipo in tiposPartida" :key="tipo.value" :value="tipo.value">
                                {{ tipo.label }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 mt-5">
                    <div class="w-full md:w-1/3 space-y-4">
                        <Label>Periodo Fiscal</Label>
                        <select v-model="form.periodo_fiscal_id" class="w-full px-3 py-2 border rounded-md">
                            <option value="">Seleccione un periodo fiscal</option>
                            <option v-for="p in periodos" :key="p.id" :value="p.id">
                                {{ p.fecha_inicio }} - {{ p.fecha_cierre }}
                            </option>
                        </select>
                    </div>

                    <div class="w-full md:w-1/3 space-y-4">
                        <Label>Descripción</Label>
                        <Input v-model="form.description" type="text" />
                    </div>

                    <div class="w-full md:w-1/3 space-y-4">
                        <Label>Estado</Label>
                        <Checkbox v-model="form.estado" :default-value="true" />
                    </div>
                </div>
                <div class="mt-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold">Detalles de la Partida</h2>
                        <Button type="button" @click="agregarDetalle" size="sm">
                            <Plus class="w-4 h-4 mr-2" /> Agregar Línea
                        </Button>
                    </div>

                    <div class="overflow-x-auto border rounded-lg">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-4 py-3 text-left">#</th>
                                    <th class="px-4 py-3 text-left">Cuenta</th>
                                    <th class="px-4 py-3 text-left">Parcial</th>
                                    <th class="px-4 py-3 text-right">Debe</th>
                                    <th class="px-4 py-3 text-right">Haber</th>
                                    <th class="px-4 py-3 text-right">Observaciones</th>
                                    <th class="px-4 py-3 text-right">Accion</th>
                                    <th class="px-4 py-3"></th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="(detalle, index) in form.detalles" :key="index" class="border-t">
                                    <td class="px-4 py-2">{{ detalle.orden }}</td>
                                    <td class="px-4 py-2">
                                        <select v-model="detalle.id_cuenta" class="w-full px-2 py-1 border rounded-md min-w-[300px]">
                                            <option value="">Seleccione cuenta</option>
                                            <option v-for="c in cuentas" :key="c.id" :value="c.id">
                                                {{ c.codigo }} - {{ c.cuenta }}
                                            </option>
                                        </select>
                                    </td>
                                    <td class="px-4 py-2">
                                        <Input v-model.number="detalle.parcial" type="number" step="0.01"
                                            class="w-24 text-right" />
                                    </td>

                                    <td class="px-4 py-2 text-right">
                                        <Input
                                            v-model.number="detalle.monto_debe"
                                            type="number"
                                            step="0.01"
                                            placeholder="0.00"
                                            class="w-24 text-right"
                                            @input="
                                                detalle.monto_haber = 0;
                                                detalle.tipo_movimiento = detalle.monto_debe > 0 ? 'DEBE' : '';
                                            "
                                        />
                                    </td>

                                    <td class="px-4 py-2 text-right">
                                        <Input
                                            v-model.number="detalle.monto_haber"
                                            type="number"
                                            step="0.01"
                                            placeholder="0.00"
                                            class="w-24 text-right"
                                            @input="
                                                detalle.monto_debe = 0;
                                                detalle.tipo_movimiento = detalle.monto_haber > 0 ? 'HABER' : '';
                                            "
                                        />
                                    </td>
                                    <td class="p-2">
                                        <Input
                                            v-model="detalle.observaciones"
                                            placeholder="Notas..."
                                            class="w-full"
                                        />
                                    </td>

                                    <td class="px-4 py-2 text-right">
                                        <button type="button" @click="eliminarDetalle(index)" class="text-red-600">
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </td>
                                </tr>
                            </tbody>

                            <tfoot class="bg-gray-100 dark:bg-gray-900 font-semibold">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-right">Totales:</td>
                                    <td class="px-4 py-3 text-right">{{ totalDebe.toFixed(2) }}</td>
                                    <td class="px-4 py-3 text-right">{{ totalHaber.toFixed(2) }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-right">Diferencia:</td>
                                    <td colspan="2" class="px-4 py-3 text-right"
                                        :class="diferencia === 0 ? 'text-green-600' : 'text-red-600'">
                                        {{ diferencia.toFixed(2) }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>
                <div class="mt-8 flex justify-end">
                    <Button type="submit">Guardar Partida</Button>
                </div>
            </form>
        </div>

    </AppLayout>
</template>
