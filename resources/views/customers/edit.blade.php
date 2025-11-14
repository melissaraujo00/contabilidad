<x-app-layout>

<div class="py-10">
    <div class="max-w-xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white dark:bg-gray-900 shadow dark:shadow-gray-800 sm:rounded-lg p-6
                    border border-gray-200 dark:border-gray-700">

            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-6">
                Editar Cliente
            </h2>

            <form method="POST" action="{{ route('customers.update', $customer->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Nombre --}}
                <div>
                    <x-input-label for="nombre" class="dark:text-gray-200" value="Nombre" />

                    <x-text-input id="nombre" name="nombre" type="text"
                        class="mt-1 block w-full dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600"
                        required value="{{ old('nombre', $customer->nombre) }}" />
                </div>

                {{-- Apellido --}}
                <div>
                    <x-input-label for="apellido" class="dark:text-gray-200" value="Apellido" />

                    <x-text-input id="apellido" name="apellido" type="text"
                        class="mt-1 block w-full dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600"
                        required value="{{ old('apellido', $customer->apellido) }}" />
                </div>

                {{-- Telefono --}}
                <div>
                    <x-input-label for="telefono" class="dark:text-gray-200" value="Teléfono" />

                    <x-text-input id="telefono" name="telefono" type="text"
                        class="mt-1 block w-full dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600"
                        value="{{ old('telefono', $customer->telefono) }}" />
                </div>

                {{-- Email --}}
                <div>
                    <x-input-label for="email" class="dark:text-gray-200" value="Email" />

                    <x-text-input id="email" name="email" type="email"
                        class="mt-1 block w-full dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600"
                        value="{{ old('email', $customer->email) }}" />
                </div>

                {{-- NIT --}}
                <div>
                    <x-input-label for="nit" class="dark:text-gray-200" value="NIT" />

                    <x-text-input id="nit" name="nit" type="text"
                        class="mt-1 block w-full dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600"
                        value="{{ old('nit', $customer->nit) }}" />
                </div>

                {{-- Dirección --}}
                <div>
                    <x-input-label for="direccion" class="dark:text-gray-200" value="Dirección" />

                    <textarea id="direccion" name="direccion"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 
                               dark:text-gray-100 dark:border-gray-600 focus:ring-indigo-500"
                        rows="3">{{ old('direccion', $customer->direccion) }}</textarea>
                </div>

                {{-- Tipo de cliente --}}
                <div>
                    <x-input-label for="tipo_cliente" class="dark:text-gray-200" value="Tipo de cliente" />

                    <select id="tipo_cliente" name="tipo_cliente"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:text-gray-100 
                               dark:border-gray-600 focus:ring-indigo-500">

                        @foreach ($tipos as $tipo)
                            <option value="{{ $tipo->value }}" 
                                {{ old('tipo_cliente', $customer->tipo_cliente) == $tipo->value ? 'selected' : '' }}>
                                {{ ucfirst($tipo->value) }}
                            </option>
                        @endforeach

                    </select>

                </div>

                {{-- Botones --}}
                <div class="flex justify-end gap-3 pt-4">

                    <a href="{{ route('customers.index') }}"
                       class="px-4 py-2 bg-gray-200 dark:bg-gray-700 dark:text-gray-200
                              rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Cancelar
                    </a>

                    <x-primary-button class="dark:bg-indigo-600 dark:hover:bg-indigo-500">
                        Actualizar
                    </x-primary-button>

                </div>

            </form>

        </div>
    </div>
</div>

</x-app-layout>
