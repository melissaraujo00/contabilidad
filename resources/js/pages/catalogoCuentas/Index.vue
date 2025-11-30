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
    id: number,
    codigo: string,
    cuenta: string,
    cuenta_padre_id: number|null,
    esta_activo: boolean
}

interface Cuentas {
    id: number,
    codigo: string,
    cuenta: string,
    cuenta_padre_id: number|null,
    esta_activo: boolean,
    cuenta_padre?: CuentaPadre
}

interface Props {
    cuentas: {
        data: Cuentas[];
        links: any[];
        meta: any[];
    }
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Catalogo Cuentas',
        href: '/catalogo-cuentas',
    },
];
</script>

<template>

    <Head title="Catalogo de Cuentas" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">

            <h1 class="font-bold">Listado de Catalogo de Cuentas</h1>

  <Table>
    <TableHeader>
      <TableRow>
        <TableHead class="w-[100px]">#</TableHead>
        <TableHead class="w-[100px]">Código</TableHead>
        <TableHead>Cuenta</TableHead>
        <TableHead>Cuenta Padre</TableHead>
        <TableHead>Cuenta Activa</TableHead>
      </TableRow>
    </TableHeader>
    <TableBody>
      <TableRow v-for="catalogo in props.cuentas.data" :key="catalogo.id">
                        <TableCell>{{catalogo.id}}</TableCell>
                        <TableCell>{{catalogo.codigo}}</TableCell>
                        <TableCell>{{catalogo.cuenta}}</TableCell>
                        <TableCell>{{catalogo.cuenta_padre?.cuenta ?? 'Sin cuenta padre'}}</TableCell>
                        <TableCell>{{catalogo.esta_activo ? 'SI' : 'NO'}}</TableCell>
      </TableRow>
    </TableBody>
  </Table>

            <!-- PAGINACIÓN -->
            <div class="mt-4 flex justify-center gap-2">
                <Link
                    v-for="link in props.cuentas.links"
                    :key="link.url ?? link.label"
                    :href="link.url ?? ''"
                    class="px-3 py-1 rounded border text-sm"
                    :class="{
                        'bg-blue-600 text-white': link.active,
                        'text-gray-600': !link.active,
                        'opacity-50 cursor-not-allowed': !link.url
                    }"
                    v-html="link.label"
                />
            </div>


        </div>
    </AppLayout>
</template>
