<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import { Button } from '@/components/ui/button';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'

const page = usePage();
const partidas = computed(() => page.props.partidas);
const hayPartidas = computed(() => partidas.value && partidas.value.data && partidas.value.data.length > 0);

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Partidas',
    href: '/partidas',
  },
];
const props = defineProps({
  filtros: Object
});

const form = ref({
  fecha_inicio: props.filtros.fecha_inicio,
  fecha_fin: props.filtros.fecha_fin
});

const filtrar = () => {
  router.get('/partidas', form.value, { preserveState: true, replace: true });
};
const descargarPDF = () => {
  const params = new URLSearchParams();

  if (form.value.fecha_inicio) {
    params.append("fecha_inicio", form.value.fecha_inicio);
  }

  if (form.value.fecha_fin) {
    params.append("fecha_fin", form.value.fecha_fin);
  }

  const query = params.toString();
  window.location.href = `/partidas/reporte${query ? "?" + query : ""}`;
};
</script>

<template>

  <Head title="Listado de Partidas" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4  dark:bg-black">
      <header class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
          Listado de Partidas
        </h1>
      </header>

      <div class="flex flex-wrap gap-4 mb-8 items-end p-4 bg-gray-50 rounded-lg shadow">
        <Link href="/partidas/create"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium transition-colors">
        Crear Partida
        </Link>

        <div class="flex flex-wrap gap-4 items-end ml-auto">

          <div class="w-full md:w-auto">
            <Label class="block text-sm font-medium text-gray-700">Fecha Inicio</Label>
            <Input type="date" v-model="form.fecha_inicio"
              class="mt-1 block w-[150px] rounded-md border border-gray-300 bg-white px-3 py-2 text-sm q focus:ring-blue-500 focus:border-blue-500" />
          </div>

          <div class="w-full md:w-auto">
            <Label class="block text-sm font-medium text-gray-700">Fecha Fin</Label>
            <Input type="date" v-model="form.fecha_fin"
              class="mt-1 block w-[150px] rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500" />
          </div>

          <div class="flex gap-2">
            <Button @click="filtrar"
              class="bg-black hover:bg-gray-800 text-white px-4 py-2 rounded text-sm font-medium transition-colors">
              Filtrar
            </Button>

            <Button variant="outline" @click="descargarPDF"
              class="border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-4 py-2 rounded text-sm font-medium transition-colors">
              Descargar PDF
            </Button>
          </div>
        </div>
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
              <TableHead class="w-32 font-semibold text-gray-700 dark:text-gray-300">
                Detalles
              </TableHead>
            </TableRow>
          </TableHeader>
          <TableBody v-if="hayPartidas">
            <TableRow v-for="partida in partidas.data" :key="partida.id"
              class="hover:bg-gray-50/50 dark:hover:bg-gray-900/50 transition-colors duration-150 border-b border-gray-200 dark:border-gray-800">
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
                <span :class="[
                  'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                  partida.estado
                    ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                    : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'
                ]">
                  {{ partida.estado ? 'Activa' : 'Inactiva' }}
                </span>
              </TableCell>
              <TableCell class="text-left">
                <div class="flex justify-start gap-2">
                  <Link :href="`/partidas/${partida.id}`"
                    class="p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded" title="Ver detalles">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                  </Link>
                </div>
              </TableCell>
            </TableRow>
          </TableBody>
          <TableBody v-else>
            <TableRow>
              <TableCell :colspan="9" class="py-12 text-center text-gray-500">
                <p class="text-lg">
                  No se encuentran partidas en ese rango de fecha.
                </p>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </div>
    </div>
  </AppLayout>
</template>
