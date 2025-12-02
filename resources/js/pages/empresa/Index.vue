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
    id: number,
    nombre: string,
    tipo_empresa: string,
    created_at: string
}

interface Props {
    empresas: {
        data: Empresa[];
        links: any[];
        meta: any;
    };
    tiposEmpresa: string[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Empresas',
        href: '/empresas',
    },
];
</script>

<template>

    <Head title="Listado de Empresas" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="flex items-center justify-between">
                <h1 class="font-bold text-xl">Empresas Registradas</h1>
                <Link
                    href="/empresa/create"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium transition-colors"
                >
                    Crear Empresa
                </Link>
            </div>

            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[80px]">#</TableHead>
                        <TableHead>Nombre</TableHead>
                        <TableHead>Tipo de Empresa</TableHead>
                        <TableHead>Fecha de Creación</TableHead>
                        <TableHead class="text-right w-[80px]">Acciones</TableHead>
                    </TableRow>
                </TableHeader>

                <TableBody>
                    <TableRow
                        v-for="empresa in props.empresas?.data || []"
                        :key="empresa.id"
                    >
                        <TableCell>{{ empresa.id }}</TableCell>
                        <TableCell>{{ empresa.nombre }}</TableCell>
                        <TableCell>{{ empresa.tipo_empresa }}</TableCell>
                        <TableCell>
                            {{ new Date(empresa.created_at).toLocaleString() }}
                        </TableCell>
                        <TableCell class="text-right">
                            <Link
                                :href="`/empresa/${empresa.id}/edit`"
                                class="inline-flex items-center justify-center p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded"
                                title="Editar"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </Link>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <!-- Paginación -->
            <div v-if="props.empresas?.data?.length > 0" class="flex justify-center gap-2 mt-4">
                <Link
                    v-for="link in props.empresas.links"
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

        </div>
    </AppLayout>
</template>
