<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'

interface Empresa {
    id: number;
    nombre: string;
}

interface PeriodoFiscal {
    id: number;
    empresa: Empresa;
    fecha_inicio: string;
    fecha_cierre: string;
    es_actual: boolean;
}

interface Props {
    periodosFiscales: {
        data: PeriodoFiscal[];
        links: any[];
        meta: any;
    };
    empresas: Empresa[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Períodos Fiscales',
        href: '/periodo-fiscal',
    },
];
</script>

<template>

    <Head title="Listado de Períodos Fiscales" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">

            <!-- Encabezado -->
            <div class="flex items-center justify-between mb-2">
                <h1 class="font-bold text-xl">Períodos Fiscales</h1>


            </div>

            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[60px]">#</TableHead>
                        <TableHead>Empresa</TableHead>
                        <TableHead>Fecha Inicio</TableHead>
                        <TableHead>Fecha Fin</TableHead>
                        <TableHead>Actual</TableHead>
                    </TableRow>
                </TableHeader>

                <TableBody>
                    <TableRow v-for="periodo in props.periodosFiscales.data" :key="periodo.id">
                        <TableCell>{{ periodo.id }}</TableCell>
                        <TableCell>{{ periodo.empresa?.nombre }}</TableCell>
                        <TableCell>{{ new Date(periodo.fecha_inicio).toLocaleDateString() }}</TableCell>
                        <TableCell>{{ new Date(periodo.fecha_cierre).toLocaleDateString() }}</TableCell>
                        <TableCell>
                            <span v-if="periodo.es_actual" class="text-green-600 font-semibold">
                                Sí
                            </span>
                            <span v-else class="text-gray-500">No</span>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <!-- PAGINACIÓN -->
            <div class="mt-4 flex justify-center gap-2">
                <Link v-for="link in props.periodosFiscales.links" :key="link.url ?? link.label" :href="link.url ?? ''"
                    class="px-3 py-1 rounded border text-sm" :class="{
                        'bg-blue-600 text-white': link.active,
                        'text-gray-600': !link.active,
                        'opacity-50 cursor-not-allowed': !link.url
                    }" v-html="link.label" />
            </div>

        </div>
    </AppLayout>
</template>
