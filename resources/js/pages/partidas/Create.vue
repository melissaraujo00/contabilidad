<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import { Head, Link, useForm, usePage, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { store } from '@/actions/App/Http/Controllers/PartidaController';
import { computed } from 'vue';
import { Trash2, Plus } from 'lucide-vue-next';

const { periodos, tiposPartida, cuentas } = usePage().props;

interface DetallePartida {
    id_cuenta: string;
    tipo_movimiento: 'DEBE' | 'HABER' | '';
    monto: number | null;
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

if (form.detalles.length === 0) {
    form.detalles.push({
        id_cuenta: '',
        tipo_movimiento: '',
        monto: null,
        parcial: null,
        orden: 1,
        observaciones: ''
    });
}

const totalDebe = computed(() => {
    return form.detalles
        .filter(d => d.tipo_movimiento === 'DEBE')
        .reduce((sum, d) => sum + (Number(d.monto) || 0), 0);
});

const totalHaber = computed(() => {
    return form.detalles
        .filter(d => d.tipo_movimiento === 'HABER')
        .reduce((sum, d) => sum + (Number(d.monto) || 0), 0);
});

const diferencia = computed(() => {
    return totalDebe.value - totalHaber.value;
});

const estaBalanceada = computed(() => {
    return Math.abs(diferencia.value) < 0.01 && totalDebe.value > 0;
});

const agregarDetalle = () => {
    form.detalles.push({
        id_cuenta: '',
        tipo_movimiento: '',
        monto: null,
        parcial: null,
        orden: form.detalles.length + 1,
        observaciones: ''
    });
};

const eliminarDetalle = (index: number) => {
    if (form.detalles.length > 1) {
        form.detalles.splice(index, 1);
        // Reordenar
        form.detalles.forEach((detalle, idx) => {
            detalle.orden = idx + 1;
        });
    }
};

const getNombreCuenta = (id: string) => {
    const cuenta = cuentas?.find((c: any) => c.id == id);
    return cuenta ? `${cuenta.codigo} - ${cuenta.cuenta}` : '';
};

const handleSubmit = () => {

    if (!estaBalanceada.value) {
        alert('La partida debe estar balanceada (DEBE = HABER)');
        return;
    }

    form.submit(store());
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Partidas',
        href: '/partidas',
    },
    {
        title: 'Nueva Partida',
        href: '/partidas/create',
    },
];
</script>

<template>

    <Head title="Listado de Partidas" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4 bg-white dark:bg-black">
            <header class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Nueva Partida
                </h1>
            </header>

            <form @submit.prevent="handleSubmit">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="w-full md:w-1/3 space-y-4">
                      <Label for="Partida Numero">Partida #</Label>
                      <Input v-model="form.partida_numero" type="number" placeholder="partida #"/>
                      <div v-if="form.errors.partida_numero" class="text-red-600 dark:text-red-400 text-sm">
                            {{ form.errors.partida_numero }}
                      </div>
                    </div>

                    <div class="w-full md:w-1/3 space-y-4">
                      <Label for="Fecha Partida">Fecha</Label>
                      <Input v-model="form.fecha_partida" type="date"/>
                      <div v-if="form.errors.fecha_partida" class="text-red-600 dark:text-red-400 text-sm">
                            {{ form.errors.fecha_partida }}
                      </div>
                    </div>

                    <div class="w-full md:w-1/3 space-y-4">
                      <Label for="Tipo Partida">Tipo de Partida</Label>
                      <select v-model="form.tipo_partida" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                        <option value="">Seleccione tipo de partida</option>
                        <option
                            v-for="tipo in tiposPartida"
                            :key="tipo.value"
                            :value="tipo.value"
                        >
                            {{ tipo.label }}
                        </option>
                    </select>
                      <div v-if="form.errors.tipo_partida" class="text-red-600 dark:text-red-400 text-sm">
                            {{ form.errors.tipo_partida }}
                      </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 mt-5">
                    <div class="w-full md:w-1/3 space-y-4">
                      <Label for="Periodo Fiscal">Periodo Fiscal</Label>
                    <select v-model="form.periodo_fiscal_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                    <option value="">Seleccione un periodo fiscal</option>
                    <option
                        v-for="periodo in periodos"
                        :key="periodo.id"
                        :value="periodo.id"
    >
                                {{ periodo.fecha_inicio }} - {{ periodo.fecha_cierre}}
                    </option>
                    </select>
                      <div v-if="form.errors.periodo_fiscal_id" class="text-red-600 dark:text-red-400 text-sm">
                            {{ form.errors.periodo_fiscal_id }}
                      </div>
                </div>

                    <div class="w-full md:w-1/3 space-y-4">
                      <Label for="Fecha Partida">Descripcion</Label>
                        <Input v-model="form.description" type="text"/>
                      <div v-if="form.errors.description" class="text-red-600 dark:text-red-400 text-sm">
                            {{ form.errors.description }}
                      </div>
                    </div>

                    <div class="w-full md:w-1/3 space-y-4">
                      <Label for="Tipo Partida">Estado</Label>
                     <Checkbox v-model="form.estado" :default-value="true" />
                      <div v-if="form.errors.estado" class="text-red-600 dark:text-red-400 text-sm">
                            {{ form.errors.estado }}
                      </div>
                    </div>
                </div>

                <!-- Tabla de Detalles -->
                <div class="mt-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Detalles de la Partida
                        </h2>
                        <Button type="button" @click="agregarDetalle" size="sm">
                            <Plus class="w-4 h-4 mr-2" />
                            Agregar Línea
                        </Button>
                    </div>

                    <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">#</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">Cuenta</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">Parcial</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">Tipo</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-700 dark:text-gray-300">Debe</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-700 dark:text-gray-300">Haber</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">Observaciones</th>
                                    <th class="px-4 py-3 text-center font-medium text-gray-700 dark:text-gray-300">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-for="(detalle, index) in form.detalles" :key="index" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ index + 1 }}</td>
                                    <td class="px-4 py-3">
                                        <select
                                            v-model="detalle.catalogo_cuenta_id"
                                            class="w-full min-w-[250px] px-2 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                        >
                                            <option value="">Seleccione cuenta...</option>
                                            <option
                                                v-for="cuenta in cuentas"
                                                :key="cuenta.id"
                                                :value="cuenta.id"
                                            >
                                                {{ cuenta.codigo }} - {{ cuenta.cuenta }}
                                            </option>
                                        </select>
                                        <div v-if="form.errors[`detalles.${index}.catalogo_cuenta_id`]" class="text-red-600 text-xs mt-1">
                                            {{ form.errors[`detalles.${index}.catalogo_cuenta_id`] }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <Input
                                            v-model.number="detalle.parcial"
                                            type="number"
                                            step="0.01"
                                            placeholder="0.00"
                                            class="w-24 text-right"
                                        />
                                    </td>
<td class="px-4 py-3">
    <select
        v-model="detalle.tipo_movimiento"
        class="w-20 px-2 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white mb-2"
    >
        <option value="">-</option>
        <option value="DEBE">DEBE</option>
        <option value="HABER">HABER</option>
    </select>
</td>
                                    <td class="px-4 py-3">
                                        <Input
                                            v-model.number="detalle.monto"
                                            type="number"
                                            step="0.01"
                                            placeholder="0.00"
                                            @focus="detalle.tipo_movimiento = 'DEBE'"
                                            class="w-28 text-right"
                                            :class="{'bg-gray-50 dark:bg-gray-800': detalle.tipo_movimiento === 'DEBE'}"
                                        />
                                    </td>
                                    <td class="px-4 py-3">
                                        <Input
                                            v-model.number="detalle.monto"
                                            type="number"
                                            step="0.01"
                                            placeholder="0.00"
                                            :disabled="detalle.tipo_movimiento !== 'HABER'"
                                            @focus="detalle.tipo_movimiento = 'HABER'"
                                            class="w-28 text-right"
                                            :class="{'bg-gray-50 dark:bg-gray-800': detalle.tipo_movimiento === 'HABER'}"
                                        />
                                    </td>
                                    <td class="px-4 py-3">
                                        <Input
                                            v-model="detalle.observaciones"
                                            type="text"
                                            placeholder="Observaciones..."
                                            class="w-full min-w-[150px]"
                                        />
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <Button
                                            type="button"
                                            @click="eliminarDetalle(index)"
                                            variant="ghost"
                                            size="sm"
                                            :disabled="form.detalles.length === 1"
                                            class="text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </Button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-800 font-semibold">
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-right text-gray-700 dark:text-gray-300">
                                        TOTALES:
                                    </td>
                                    <td class="px-4 py-3 text-right text-gray-900 dark:text-white">
                                        ${{ totalDebe.toFixed(2) }}
                                    </td>
                                    <td class="px-4 py-3 text-right text-gray-900 dark:text-white">
                                        ${{ totalHaber.toFixed(2) }}
                                    </td>
                                    <td colspan="2" class="px-4 py-3"></td>
                                </tr>
                                <tr v-if="!estaBalanceada && totalDebe > 0">
                                    <td colspan="3" class="px-4 py-3 text-right text-red-600 dark:text-red-400">
                                        DIFERENCIA:
                                    </td>
                                    <td colspan="2" class="px-4 py-3 text-right text-red-600 dark:text-red-400 font-bold">
                                        ${{ Math.abs(diferencia).toFixed(2) }}
                                    </td>
                                    <td colspan="2" class="px-4 py-3"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <Button type="submit" class="mt-5" aria-label="Submit">
                    Guardar
                </Button>
            </form>
        </div>
    </AppLayout>
</template>
