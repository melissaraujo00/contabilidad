<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
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
    {
        title: 'Nueva Partida',
        href: '/partidas/create',
    },
];
</script>

<template>

    <Head title="Listado de Partidas" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4 bg-white dark:bg-black">
            <header class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Nueva Partida
                </h1>
            </header>

            <form>
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="w-full md:w-1/3 space-y-4">
                      <Label for="Partida Numero">Partida #</Label>
                      <Input type="number" placeholder="partida #"/>
                    </div>

                    <div class="w-full md:w-1/3 space-y-4">
                      <Label for="Fecha Partida">Fecha</Label>
                      <Input type="date"/>
                    </div>

                    <div class="w-full md:w-1/3 space-y-4">
                      <Label for="Tipo Partida">Tipo de Partida</Label>
                      <Input type="text"/>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 mt-5">
                    <div class="w-full md:w-1/3 space-y-4">
                      <Label for="Partida Numero">Periodo Fiscal</Label>
                      <Input type="number" placeholder="partida #"/>
                    </div>

                    <div class="w-full md:w-1/3 space-y-4">
                      <Label for="Fecha Partida">Descripcion</Label>
                      <Input type="text"/>
                    </div>

                    <div class="w-full md:w-1/3 space-y-4">
                      <Label for="Tipo Partida">Estado</Label>
                      <Input type="text"/>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
