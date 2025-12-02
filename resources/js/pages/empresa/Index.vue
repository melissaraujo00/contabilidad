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
    empresas: Empresa[],
    tiposEmpresa: string[]
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
                    </TableRow>
                </TableHeader>

                <TableBody>
                    <TableRow
                        v-for="empresa in props.empresas"
                        :key="empresa.id"
                    >
                        <TableCell>{{ empresa.id }}</TableCell>
                        <TableCell>{{ empresa.nombre }}</TableCell>
                        <TableCell>{{ empresa.tipo_empresa }}</TableCell>
                        <TableCell>
                            {{ new Date(empresa.created_at).toLocaleString() }}
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

        </div>
    </AppLayout>
</template>
