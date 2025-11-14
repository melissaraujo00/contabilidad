<x-app-layout>

    <div class="py-10">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-900 shadow dark:shadow-gray-800 sm:rounded-lg p-6
                        border border-gray-200 dark:border-gray-700">

                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-6">
                    Editar Producto
                </h2>

                <form method="POST" action="{{ route('products.update', $product->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Nombre --}}
                    <div>
                        <x-input-label for="nombre" class="dark:text-gray-200" value="Nombre" />

                        <x-text-input id="nombre" name="nombre" type="text"
                            class="mt-1 block w-full dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600"
                            required value="{{ old('nombre', $product->nombre) }}" />
                        <x-input-error :messages="$errors->get('nombre')" class="mt-2 dark:text-red-400" />
                    </div>

                    {{-- Precio --}}
                    <div>
                        <x-input-label for="precio" class="dark:text-gray-200" value="Precio" />

                        <x-text-input id="precio" name="precio" type="number" step="0.01"
                            class="mt-1 block w-full dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600"
                            required value="{{ old('precio', $product->precio) }}" />
                        <x-input-error :messages="$errors->get('precio')" class="mt-2 dark:text-red-400" />
                    </div>

                    {{-- Stock --}}
                    <div>
                        <x-input-label for="stock" class="dark:text-gray-200" value="Stock" />

                        <x-text-input id="stock" name="stock" type="number"
                            class="mt-1 block w-full dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600"
                            required value="{{ old('stock', $product->stock) }}" />
                        <x-input-error :messages="$errors->get('stock')" class="mt-2 dark:text-red-400" />
                    </div>

                    {{-- Categoría --}}
                    <div>
                        <x-input-label for="category_id" class="dark:text-gray-200" value="Categoría" />

                        <select id="category_id" name="category_id"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:text-gray-100 
                                   dark:border-gray-600 focus:ring-indigo-500">

                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" 
                                    {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nombre }}
                                </option>
                            @endforeach

                        </select>

                        <x-input-error :messages="$errors->get('category_id')" class="mt-2 dark:text-red-400" />
                    </div>

                    {{-- Stock mínimo --}}
                    <div>
                        <x-input-label for="stockMinimun" class="dark:text-gray-200" value="Stock mínimo" />

                        <x-text-input id="stockMinimun" name="stockMinimun" type="number"
                            class="mt-1 block w-full dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600"
                            required value="{{ old('stockMinimun', $product->stockMinimun) }}" />
                        <x-input-error :messages="$errors->get('stockMinimun')" class="mt-2 dark:text-red-400" />
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end gap-3 pt-4">

                        <a href="{{ route('products.index') }}"
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
