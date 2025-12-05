<script setup lang="ts">
import { login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canRegister: {
        type: Boolean,
        default: true
    }
});
</script>

<template>
    <Head title="Bienvenidos" />

    <div class="min-h-screen flex flex-col lg:flex-row bg-gray-100 text-gray-900 p-6 lg:p-10">
        <div class="flex-1 flex items-center justify-center mb-8 lg:mb-0">
            <div class="w-full max-w-md">
                <img
                    src="https://img.freepik.com/vector-premium/concepto-diseno-logotipo-contabilidad-financiera_761413-6935.jpg?semt=ais_hybrid&w=740&q=80"
                    alt="Contabilidad"
                    class="w-full rounded-xl shadow-lg object-cover"
                />
            </div>
        </div>

        <div class="flex-1 flex flex-col justify-center max-w-xl mx-auto">

            <h1 class="text-4xl font-bold mb-3">
                Bienvenido a tu plataforma contable
            </h1>

            <p class="text-gray-600 mb-8">
                Administra tus finanzas, controla tus registros y lleva tu contabilidad de forma simple y eficiente.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-10">

                <div class="bg-white p-5 rounded-lg shadow hover:shadow-md transition">
                    <h3 class="font-semibold text-lg">Reportes</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        Genera reportes financieros claros y profesionales.
                    </p>
                </div>

                <div class="bg-white p-5 rounded-lg shadow hover:shadow-md transition">
                    <h3 class="font-semibold text-lg">Gestión de cuentas</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        Controla ingresos, egresos y movimientos contables.
                    </p>
                </div>

                <div class="bg-white p-5 rounded-lg shadow hover:shadow-md transition">
                    <h3 class="font-semibold text-lg">Facturación</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        Administra facturas y comprobantes fácilmente.
                    </p>
                </div>

                <div class="bg-white p-5 rounded-lg shadow hover:shadow-md transition">
                    <h3 class="font-semibold text-lg">Registros organizados</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        Mantén organizada toda tu información contable.
                    </p>
                </div>
            </div>

            <div class="flex gap-3">
                <Link
                    :href="login()"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700"
                >
                    Iniciar sesión
                </Link>

                <Link
                    v-if="canRegister"
                    :href="register()"
                    class="px-6 py-2 bg-gray-900 text-white rounded-lg shadow hover:bg-black"
                >
                    Registrarse
                </Link>
            </div>
        </div>
    </div>
</template>
