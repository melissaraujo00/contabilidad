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

interface CuentaPadre {
  id: number;
  codigo: string;
  cuenta: string;
  cuenta_padre_id: number | null;
  esta_activo: boolean;
}

interface Cuenta {
  id: number;
  codigo: string;
  cuenta: string;
  cuenta_padre_id: number | null;
  esta_activo: boolean;
  cuenta_padre?: CuentaPadre;
}

interface Props {
  cuentas: {
    data: Cuenta[];
    links: any[];
    meta: any[];
  };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Catálogo Cuentas',
    href: '/catalogo-cuentas',
  },
];
</script>

<template>
  <Head title="Catálogo de Cuentas" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4 bg-white dark:bg-black">
      <header class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
          Catálogo de Cuentas
        </h1>
      </header>

      <div class="bg-white dark:bg-black rounded-lg shadow-sm border border-gray-200 dark:border-gray-800">
        <Table>
          <TableHeader>
            <TableRow class="bg-gray-50/80 dark:bg-gray-900/80">
              <TableHead class="w-20 font-semibold text-gray-700 dark:text-gray-300">
                ID
              </TableHead>
              <TableHead class="w-32 font-semibold text-gray-700 dark:text-gray-300">
                Código
              </TableHead>
              <TableHead class="font-semibold text-gray-700 dark:text-gray-300">
                Cuenta
              </TableHead>
              <TableHead class="font-semibold text-gray-700 dark:text-gray-300">
                Cuenta Padre
              </TableHead>
              <TableHead class="w-32 font-semibold text-gray-700 dark:text-gray-300">
                Estado
              </TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow
              v-for="cuenta in props.cuentas.data"
              :key="cuenta.id"
              class="hover:bg-gray-50/50 dark:hover:bg-gray-900/50 transition-colors duration-150 border-b border-gray-200 dark:border-gray-800"
            >
              <TableCell class="font-medium text-gray-600 dark:text-gray-400">
                {{ cuenta.id }}
              </TableCell>
              <TableCell class="font-mono text-sm text-blue-600 dark:text-blue-400">
                {{ cuenta.codigo }}
              </TableCell>
              <TableCell class="text-gray-800 dark:text-gray-200">
                {{ cuenta.cuenta }}
              </TableCell>
              <TableCell class="text-gray-600 dark:text-gray-400">
                {{ cuenta.cuenta_padre?.cuenta ?? 'Sin cuenta padre' }}
              </TableCell>
              <TableCell>
                <span
                  :class="[
                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                    cuenta.esta_activo
                      ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                      : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'
                  ]"
                >
                  {{ cuenta.esta_activo ? 'Activa' : 'Inactiva' }}
                </span>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </div>
      <div class="flex justify-center items-center gap-1 mt-6">
        <Link
          v-for="link in props.cuentas.links"
          :key="link.url ?? link.label"
          :href="link.url ?? ''"
          class="min-w-8 px-3 py-2 rounded-md border text-sm font-medium transition-all duration-200"
          :class="{
            'bg-blue-600 text-white border-blue-600 shadow-sm': link.active,
            'text-gray-700 bg-white border-gray-300 hover:bg-gray-50 dark:text-gray-300 dark:bg-black dark:border-gray-800 dark:hover:bg-gray-900': !link.active,
            'text-gray-400 bg-gray-50 border-gray-200 cursor-not-allowed dark:text-gray-600 dark:bg-gray-900 dark:border-gray-800': !link.url,
            'rounded-l-md': link.label.includes('Previous'),
            'rounded-r-md': link.label.includes('Next')
          }"
          v-html="link.label"
          preserve-scroll
        />
      </div>
    </div>
  </AppLayout>
</template>
