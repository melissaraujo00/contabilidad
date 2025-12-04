<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';

const props = defineProps({
  partida: Object
});

const breadcrumbs = [
  {
    title: 'Partidas',
    href: '/partidas',
  },
  {
    title: `Partida #${props.partida.partida_numero}`,
    href: '#',
  }
];

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('es-SV', {
    style: 'currency',
    currency: 'USD'
  }).format(amount || 0);
};
</script>

<template>
  <Head title="Detalles de Partida" />

    <AppLayout :breadcrumbs="breadcrumbs">

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-6 flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
            Partida #{{ partida.partida_numero }}
          </h2>
          <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Detalles completos de la partida contable
          </p>
        </div>
      </div>

      <!-- Partida Info Card -->
      <div class="bg-white dark:bg-black rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 mb-6">
        <div class="px-6 py-5">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            Información General
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
              <label class="text-sm font-medium text-gray-500 dark:text-gray-400">ID</label>
              <p class="mt-1 text-base text-gray-900 dark:text-gray-100">{{ partida.id }}</p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha Partida</label>
              <p class="mt-1 text-base text-gray-900 dark:text-gray-100">{{ partida.fecha_partida }}</p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo</label>
              <p class="mt-1 text-base text-gray-900 dark:text-gray-100">{{ partida.tipo_partida }}</p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Estado</label>
              <span
                :class="[
                  'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1',
                  partida.estado
                    ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                    : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'
                ]"
              >
                {{ partida.estado ? 'Activa' : 'Inactiva' }}
              </span>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Periodo Fiscal</label>
              <p class="mt-1 text-base text-gray-900 dark:text-gray-100">
                {{ partida.periodoFiscal?.fecha_inicio }} - {{ partida.periodoFiscal?.fecha_cierre }}
              </p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Debe</label>
              <p class="mt-1 text-base font-semibold text-blue-600 dark:text-blue-400">
                {{ formatCurrency(partida.total_debe) }}
              </p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Haber</label>
              <p class="mt-1 text-base font-semibold text-green-600 dark:text-green-400">
                {{ formatCurrency(partida.total_haber) }}
              </p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Diferencia</label>
              <p
                :class="[
                  'mt-1 text-base font-semibold',
                  Math.abs(partida.total_debe - partida.total_haber) < 0.01
                    ? 'text-green-600 dark:text-green-400'
                    : 'text-red-600 dark:text-red-400'
                ]"
              >
                {{ formatCurrency(Math.abs(partida.total_debe - partida.total_haber)) }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Details Table -->
      <div class="bg-white dark:bg-black rounded-lg shadow-sm border border-gray-200 dark:border-gray-800">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            Detalles de la Partida
            <span class="ml-2 text-sm font-normal text-gray-500 dark:text-gray-400">
              ({{ partida.detalles?.length || 0 }} movimientos)
            </span>
          </h3>
        </div>

        <div v-if="partida.detalles && partida.detalles.length > 0" class="overflow-x-auto">
          <Table>
            <TableHeader>
              <TableRow class="bg-gray-50/80 dark:bg-gray-900/80">
                <TableHead class="w-16 font-semibold text-gray-700 dark:text-gray-300">
                  Orden
                </TableHead>
                <TableHead class="font-semibold text-gray-700 dark:text-gray-300">
                  Cuenta
                </TableHead>
                <TableHead class="font-semibold text-gray-700 dark:text-gray-300">
                  Descripción
                </TableHead>
                <TableHead class="w-28 font-semibold text-gray-700 dark:text-gray-300">
                  Tipo
                </TableHead>
                <TableHead class="w-32 text-right font-semibold text-gray-700 dark:text-gray-300">
                  Debe
                </TableHead>
                <TableHead class="w-32 text-right font-semibold text-gray-700 dark:text-gray-300">
                  Haber
                </TableHead>
                <TableHead class="w-32 text-right font-semibold text-gray-700 dark:text-gray-300">
                  Parcial
                </TableHead>
                <TableHead class="font-semibold text-gray-700 dark:text-gray-300">
                  Observaciones
                </TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow
                v-for="detalle in partida.detalles"
                :key="detalle.id"
                class="hover:bg-gray-50/50 dark:hover:bg-gray-900/50 transition-colors duration-150"
              >
                <TableCell class="font-medium text-gray-600 dark:text-gray-400">
                  {{ detalle.orden }}
                </TableCell>
                <TableCell>
                  <div class="font-mono text-sm text-blue-600 dark:text-blue-400">
                    {{ detalle.catalogoCuenta?.codigo || detalle.catalogo_cuenta_id }}
                  </div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">
                    {{ detalle.catalogoCuenta?.nombre || '' }}
                  </div>
                </TableCell>
                <TableCell class="text-gray-800 dark:text-gray-200">
                  {{ detalle.description }}
                </TableCell>
                <TableCell>
                  <span
                    :class="[
                      'inline-flex items-center px-2 py-1 rounded text-xs font-medium',
                      detalle.tipo_movimiento === 'debe'
                        ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400'
                        : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                    ]"
                  >
                    {{ detalle.tipo_movimiento }}
                  </span>
                </TableCell>
                <TableCell class="text-right font-mono text-sm">
                  <span v-if="detalle.monto_debe" class="text-blue-600 dark:text-blue-400">
                    {{ formatCurrency(detalle.monto_debe) }}
                  </span>
                  <span v-else class="text-gray-400">-</span>
                </TableCell>
                <TableCell class="text-right font-mono text-sm">
                  <span v-if="detalle.monto_haber" class="text-green-600 dark:text-green-400">
                    {{ formatCurrency(detalle.monto_haber) }}
                  </span>
                  <span v-else class="text-gray-400">-</span>
                </TableCell>
                <TableCell class="text-right font-mono text-sm text-gray-600 dark:text-gray-400">
                  {{ detalle.parcial ? formatCurrency(detalle.parcial) : '-' }}
                </TableCell>
                <TableCell class="text-sm text-gray-600 dark:text-gray-400">
                  {{ detalle.observaciones || '-' }}
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </div>

        <div v-else class="px-6 py-12 text-center">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">
            Sin detalles
          </h3>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Esta partida no tiene movimientos registrados.
          </p>
        </div>
      </div>
    </div>
  </div>

    </AppLayout>
</template>
