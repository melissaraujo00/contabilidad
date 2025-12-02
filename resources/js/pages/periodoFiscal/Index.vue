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
    ejercicio_fiscal: string; // Agregado
    esta_cerrado: boolean; // Cambiado de es_actual a esta_cerrado
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

// Función para formatear fechas
const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
};

// Función para calcular duración en días
const calcularDuracion = (inicio: string, fin: string) => {
    const fechaInicio = new Date(inicio);
    const fechaFin = new Date(fin);
    const diferencia = fechaFin.getTime() - fechaInicio.getTime();
    return Math.ceil(diferencia / (1000 * 3600 * 24)) + 1; // +1 para incluir el día inicial
};
</script>

<template>
    <Head title="Listado de Períodos Fiscales" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <!-- Encabezado -->
            <div class="flex items-center justify-between mb-2">
                <h1 class="font-bold text-xl">Períodos Fiscales</h1>
                <Link
                    href="/periodo-fiscal/create"
                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nuevo Período Fiscal
                </Link>
            </div>

            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[60px]">#</TableHead>
                        <TableHead>Empresa</TableHead>
                        <TableHead>Ejercicio Fiscal</TableHead>
                        <TableHead>Fecha Inicio</TableHead>
                        <TableHead>Fecha Fin</TableHead>
                        <TableHead>Duración (días)</TableHead>
                        <TableHead>Estado</TableHead>
                        <TableHead class="text-right">Acciones</TableHead>
                    </TableRow>
                </TableHeader>

                <TableBody>
                    <TableRow v-for="periodo in props.periodosFiscales.data" :key="periodo.id">
                        <TableCell>{{ periodo.id }}</TableCell>
                        <TableCell class="font-medium">{{ periodo.empresa?.nombre }}</TableCell>
                        <TableCell>{{ periodo.ejercicio_fiscal || 'Sin definir' }}</TableCell>
                        <TableCell>{{ formatDate(periodo.fecha_inicio) }}</TableCell>
                        <TableCell>{{ formatDate(periodo.fecha_cierre) }}</TableCell>
                        <TableCell class="text-center">
                            {{ calcularDuracion(periodo.fecha_inicio, periodo.fecha_cierre) }}
                        </TableCell>
                        <TableCell>
                            <span
                                v-if="periodo.esta_cerrado"
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200"
                            >
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                Cerrado
                            </span>
                            <span
                                v-else
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200"
                            >
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Abierto
                            </span>
                        </TableCell>
                        <TableCell class="text-right">
                            <div class="flex justify-end space-x-2">
                                <Link
                                    :href="`/periodo-fiscal/${periodo.id}/edit`"
                                    class="inline-flex items-center justify-center p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded"
                                    title="Editar"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </Link>
                                <button
                                    @click="confirmDelete(periodo.id)"
                                    class="inline-flex items-center justify-center p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded"
                                    title="Eliminar"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="props.periodosFiscales.data.length === 0">
                        <TableCell colspan="8" class="text-center py-8 text-gray-500">
                            No hay períodos fiscales registrados.
                            <Link href="/periodo-fiscal/create" class="text-blue-600 hover:text-blue-800 ml-1">
                                ¡Crea el primero!
                            </Link>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <!-- PAGINACIÓN -->
            <div v-if="props.periodosFiscales.data.length > 0" class="mt-4 flex justify-center gap-2">
                <Link
                    v-for="link in props.periodosFiscales.links"
                    :key="link.url ?? link.label"
                    :href="link.url ?? ''"
                    class="px-3 py-1 rounded border text-sm"
                    :class="{
                        'bg-blue-600 text-white': link.active,
                        'text-gray-600 hover:bg-gray-50': !link.active && link.url,
                        'opacity-50 cursor-not-allowed': !link.url
                    }"
                    v-html="link.label"
                />
            </div>

            <!-- Stats -->
            <div v-if="props.periodosFiscales.data.length > 0" class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="text-sm text-blue-600 dark:text-blue-400">Total Períodos</div>
                    <div class="text-2xl font-bold text-blue-700 dark:text-blue-300">
                        {{ props.periodosFiscales.meta?.total || props.periodosFiscales.data.length }}
                    </div>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="text-sm text-green-600 dark:text-green-400">Períodos Abiertos</div>
                    <div class="text-2xl font-bold text-green-700 dark:text-green-300">
                        {{ props.periodosFiscales.data.filter(p => !p.esta_cerrado).length }}
                    </div>
                </div>
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="text-sm text-red-600 dark:text-red-400">Períodos Cerrados</div>
                    <div class="text-2xl font-bold text-red-700 dark:text-red-300">
                        {{ props.periodosFiscales.data.filter(p => p.esta_cerrado).length }}
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
