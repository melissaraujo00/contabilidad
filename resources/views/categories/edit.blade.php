<x-app-layout>

    <div class="py-10">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-900 shadow dark:shadow-gray-800 sm:rounded-lg p-6
                        border border-gray-200 dark:border-gray-700">

                {{-- TÍTULO --}}
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-6">
                    Editar Categoría
                </h2>

                {{-- FORM --}}
                <form method="POST" action="{{ route('categories.update', $category->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Nombre --}}
                    <div>
                        <x-input-label class="dark:text-gray-200" for="nombre" value="Nombre de la categoría" />

                        <x-text-input id="nombre" name="nombre" type="text"
                            class="mt-1 block w-full dark:bg-gray-800 dark:text-gray-100 
                                   dark:border-gray-600 dark:focus:ring-indigo-500"
                            required value="{{ old('nombre', $category->nombre) }}"
                            placeholder="Ej: Alimentos, Ropa, Servicios…" />

                        <x-input-error :messages="$errors->get('nombre')" class="mt-2 dark:text-red-400" />
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end space-x-3 pt-4">

                        {{-- Cancelar --}}
                        <a href="{{ route('categories.index') }}"
                           class="px-4 py-2 bg-gray-200 dark:bg-gray-700 dark:text-gray-200
                                  text-gray-700 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                            Cancelar
                        </a>

                        {{-- Actualizar --}}
                        <x-primary-button class="dark:bg-indigo-600 dark:hover:bg-indigo-500">
                            Actualizar Categoría
                        </x-primary-button>

                    </div>
                </form>

            </div>

        </div>
    </div>

</x-app-layout>
