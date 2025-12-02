<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

interface Empresa { id: number; nombre: string; }
interface PeriodoFiscal {
    id: number;
    empresa: Empresa;
    fecha_inicio: string;
    fecha_cierre: string;
    ejercicio_fiscal: string;
    esta_cerrado: boolean;
}

interface Props {
    periodosFiscales: { data: PeriodoFiscal[]; links: any[]; meta: any; };
}

const props = defineProps<Props>();
const breadcrumbs: BreadcrumbItem[] = [{ title: 'Períodos Fiscales', href: '/periodo-fiscal' }];

const formatDate = (d: string) => new Date(d).toLocaleDateString('es-ES');
const duracion = (i: string, f: string) => Math.ceil((new Date(f).getTime() - new Date(i).getTime()) / 86400000) + 1;
const confirmDelete = (id: number) => confirm('¿Eliminar este período?') &&
    (window.location.href = `/periodo-fiscal/${id}/delete`);
</script>

<template>
    <Head title="Períodos Fiscales" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="font-bold text-xl">Períodos Fiscales</h1>
                <Link href="/periodo-fiscal/create" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nuevo Período
                </Link>
            </div>

            <div v-if="props.periodosFiscales.data.length > 0" class="grid grid-cols-3 gap-4">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="text-sm text-blue-600 dark:text-blue-400">Total Períodos</div>
                    <div class="text-2xl font-bold text-blue-700 dark:text-blue-300">
                        {{ props.periodosFiscales.meta?.total || props.periodosFiscales.data.length }}
                    </div>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="text-sm text-green-600 dark:text-green-400">Abiertos</div>
                    <div class="text-2xl font-bold text-green-700 dark:text-green-300">
                        {{ props.periodosFiscales.data.filter(p => !p.esta_cerrado).length }}
                    </div>
                </div>
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="text-sm text-red-600 dark:text-red-400">Cerrados</div>
                    <div class="text-2xl font-bold text-red-700 dark:text-red-300">
                        {{ props.periodosFiscales.data.filter(p => p.esta_cerrado).length }}
                    </div>
                </div>
            </div>

            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[60px]">#</TableHead>
                        <TableHead>Empresa</TableHead>
                        <TableHead>Ejercicio Fiscal</TableHead>
                        <TableHead>Fecha Inicio</TableHead>
                        <TableHead>Fecha Fin</TableHead>
                        <TableHead>Días</TableHead>
                        <TableHead>Estado</TableHead>
                        <TableHead class="text-right">Acciones</TableHead>
                    </TableRow>
                </TableHeader>

                <TableBody>
                    <TableRow v-for="p in props.periodosFiscales.data" :key="p.id">
                        <TableCell>{{ p.id }}</TableCell>
                        <TableCell class="font-medium">{{ p.empresa?.nombre }}</TableCell>
                        <TableCell>{{ p.ejercicio_fiscal || 'Sin definir' }}</TableCell>
                        <TableCell>{{ formatDate(p.fecha_inicio) }}</TableCell>
                        <TableCell>{{ formatDate(p.fecha_cierre) }}</TableCell>
                        <TableCell class="text-center">{{ duracion(p.fecha_inicio, p.fecha_cierre) }}</TableCell>
                        <TableCell>
                            <span :class="p.esta_cerrado ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                {{ p.esta_cerrado ? '✕ Cerrado' : '✓ Abierto' }}
                            </span>
                        </TableCell>
                        <TableCell class="text-right">
                            <div class="flex justify-end gap-2">
                                <Link :href="`/periodo-fiscal/${p.id}/edit`" class="p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded" title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </Link>
                                <button @click="confirmDelete(p.id)" class="p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded" title="Eliminar">
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
                            <Link href="/periodo-fiscal/create" class="text-blue-600 hover:text-blue-800 ml-1">¡Crea el primero!</Link>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <div v-if="props.periodosFiscales.data.length > 0" class="flex justify-center gap-2">
                <Link v-for="link in props.periodosFiscales.links" :key="link.url ?? link.label" :href="link.url ?? ''" class="px-3 py-1 rounded border text-sm" :class="{ 'bg-blue-600 text-white': link.active, 'text-gray-600 hover:bg-gray-50': !link.active && link.url, 'opacity-50 cursor-not-allowed': !link.url }" v-html="link.label" />
            </div>
        </div>
    </AppLayout>
</template>
