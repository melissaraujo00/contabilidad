<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import {
    LayoutDashboard,
    FolderTree,
    Building2,
    CalendarRange,
    FileText,
    BookOpen,
    Scale,
    Calculator,
    TrendingUp,
    Github,
    FileCode
} from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';


const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutDashboard,
    },
    {
        title: 'Catalogo Cuentas',
        href: '/catalogo-cuentas',
        icon: FolderTree,
    },
    {
        title: 'Empresa',
        href: '/empresa',
        icon: Building2,
    },
    {
        title: 'Periodo Fiscal',
        href: '/periodo-fiscal',
        icon: CalendarRange,
    },
    {
        title: 'Partidas',
        href: '/partidas',
        icon: FileText,
    },
    {
        title: 'Libro Mayor',
        href: '/libro-mayor',
        icon: BookOpen,
    },
    {
        title: 'Balance General',
        href: '/balance-general',
        icon: Scale,
    },
    {
        title: 'Balance Comprobacion',
        href: '/balance-comprobacion',
        icon: Calculator,
    },
    {
        title: 'Estado de Resultado',
        href: '/estado-resultado',
        icon: TrendingUp,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Github,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: FileCode,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
