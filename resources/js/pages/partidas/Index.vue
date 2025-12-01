<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'

// interface CuentaPadre {
//   id: number;
//   codigo: string;
//   cuenta: string;
//   cuenta_padre_id: number | null;
//   esta_activo: boolean;
// }
//
// interface Cuenta {
//   id: number;
//   codigo: string;
//   cuenta: string;
//   cuenta_padre_id: number | null;
//   esta_activo: boolean;
//   cuenta_padre?: CuentaPadre;
// }
//
// interface Props {
//   cuentas: {
//     data: Cuenta[];
//     links: any[];
//     meta: any[];
//   };
// }

// const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Partidas',
        href: '/partidas',
    },
];
</script>

<template>

    <Head title="Listado de Partidas" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4 bg-white dark:bg-black">
            <header class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Listado de Partidas
                </h1>
            </header>

            <div class="flex items-center justify-between">
                <Link href="/partidas/create"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium transition-colors">
                Crear Partida
                </Link>
            </div>

            <div class="bg-white dark:bg-black rounded-lg shadow-sm border border-gray-200 dark:border-gray-800">
                <Table>
                    <TableHeader>
                        <TableRow class="bg-gray-50/80 dark:bg-gray-900/80">
                            <TableHead class="w-20 font-semibold text-gray-700 dark:text-gray-300">
                                ID
                            </TableHead>
                            <TableHead class="w-32 font-semibold text-gray-700 dark:text-gray-300">
                                Partida #
                            </TableHead>
                            <TableHead class="font-semibold text-gray-700 dark:text-gray-300">
                                Fecha Partida
                            </TableHead>
                            <TableHead class="font-semibold text-gray-700 dark:text-gray-300">
                                Tipo
                            </TableHead>
                            <TableHead class="w-32 font-semibold text-gray-700 dark:text-gray-300">
                                Total Debe
                            </TableHead>
                            <TableHead class="w-32 font-semibold text-gray-700 dark:text-gray-300">
                                Total Haber
                            </TableHead>
                            <TableHead class="w-32 font-semibold text-gray-700 dark:text-gray-300">
                                Estado
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>
