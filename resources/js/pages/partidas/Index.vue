<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'

const { partidas } = usePage().props

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
                               Periodo Fiscal
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
          <TableBody>
            <TableRow
              v-for="partida in partidas.data"
              :key="partida.id"
              class="hover:bg-gray-50/50 dark:hover:bg-gray-900/50 transition-colors duration-150 border-b border-gray-200 dark:border-gray-800"
            >
              <TableCell class="font-medium text-gray-600 dark:text-gray-400">
                {{ partida.id }}
              </TableCell>
              <TableCell class="font-mono text-sm text-blue-600 dark:text-blue-400">
                {{ partida.partida_numero }}
              </TableCell>
              <TableCell class="text-gray-800 dark:text-gray-200">
                {{ partida.fecha_partida }}
              </TableCell>
              <TableCell class="text-gray-600 dark:text-gray-400">
                {{ partida.periodoFiscal?.fecha_inicio ?? '' }} -
                {{ partida.periodoFiscal?.fecha_cierre ?? '' }}
              </TableCell>
              <TableCell class="text-gray-600 dark:text-gray-400">
                {{ partida.tipo_partida }}
              </TableCell>
              <TableCell class="text-gray-600 dark:text-gray-400">
                $ {{ partida.total_debe }}
              </TableCell>
              <TableCell class="text-gray-600 dark:text-gray-400">
                $ {{ partida.total_haber }}
              </TableCell>
              <TableCell>
                <span
                  :class="[
                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                    partida.estado
                      ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                      : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'
                  ]"
                >
                  {{ partida.estado ? 'Activa' : 'Inactiva' }}
                </span>
              </TableCell>
            </TableRow>
          </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>
