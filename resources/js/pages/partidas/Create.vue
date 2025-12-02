<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import { Head, Link, useForm, usePage, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button'
import { Checkbox } from '@/components/ui/checkbox'
import { store } from '@/actions/App/Http/Controllers/PartidaController'

const { periodos, tiposPartida } = usePage().props;

const form = useForm({
    partida_numero: null,
    fecha_partida: null,
    periodo_fiscal_id: "",
    tipo_partida: "",
    description: null,
    estado: null
});

const handleSubmit = () => {
    form.submit(store());
    console.log(form);
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

                <Button type="submit" class="mt-5" aria-label="Submit">
                    Guardar
                </Button>
            </form>
        </div>
    </AppLayout>
</template>
