<x-app-layout>

    <div class="py-8" x-data="{ openModal: false, deleteUrl: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- CARD CONTENEDORA --}}
            <div class="bg-white dark:bg-gray-900 shadow-sm dark:shadow-gray-800 
                        rounded-lg p-6 border border-gray-200 dark:border-gray-700">

                {{-- HEADER --}}
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                        Categorías
                    </h2>

                    <a href="{{ route('categories.create') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm shadow
                               dark:bg-blue-500 dark:hover:bg-blue-400 dark:text-gray-100">
                        + Nueva Categoría
                    </a>
                </div>

                {{-- TABLA --}}
                <div class="overflow-x-auto">

                    <table id="categoriesTable" 
                           class="min-w-full text-left text-sm border border-gray-200 dark:border-gray-700 rounded-lg">

                        {{-- ENCABEZADO --}}
                        <thead class="bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <tr class="text-gray-700 dark:text-gray-300">
                                <th class="px-4 py-2">#</th>
                                <th class="px-4 py-2">Nombre</th>
                                <th class="px-4 py-2">Fecha creación</th>
                                <th class="px-4 py-2">Acciones</th>
                            </tr>
                        </thead>

                        {{-- CUERPO --}}
                        <tbody>
                            @foreach ($categories as $category)
                                <tr class="border-t border-gray-200 dark:border-gray-700 
                                           hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                    <td class="px-4 py-2 text-gray-700 dark:text-gray-200">{{ $category->id }}</td>
                                    <td class="px-4 py-2 text-gray-700 dark:text-gray-200">{{ $category->nombre }}</td>
                                    <td class="px-4 py-2 text-gray-700 dark:text-gray-200">
                                        {{ $category->created_at->format('d/m/Y') }}
                                    </td>

                                    {{-- ACCIONES --}}
                                    <td class="px-4 py-2 flex gap-2">

                                        {{-- EDITAR --}}
                                        <a href="{{ route('categories.edit', $category->id) }}"
                                            class="text-blue-600 dark:text-blue-300 hover:text-blue-800 
                                                   dark:hover:text-blue-200 font-semibold text-sm">
                                            Editar
                                        </a>

                                        {{-- ELIMINAR (ABRIR MODAL) --}}
                                        <button 
                                            @click="openModal = true; deleteUrl = '{{ route('categories.destroy', $category->id) }}';"
                                            class="text-red-600 dark:text-red-400 hover:text-red-800 
                                                   dark:hover:text-red-300 font-semibold text-sm">
                                            Eliminar
                                        </button>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>

                {{-- PAGINACIÓN DE LARAVEL --}}
                <div class="mt-6 dark:text-gray-200">
                    {{ $categories->links() }}
                </div>

            </div>

        </div>


        {{-- MODAL PERSONALIZADO --}}
        <div 
            x-show="openModal"
            x-transition.opacity
            class="fixed inset-0 bg-black/50 flex justify-center items-center z-50">

            <div x-show="openModal"
                x-transition.scale
                class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-lg 
                        w-11/12 sm:w-full max-w-md mx-auto 
                        border border-gray-300 dark:border-gray-700">

                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-3">
                    ¿Eliminar categoría?
                </h2>

                <p class="text-gray-600 dark:text-gray-300 mb-6">
                    Esta acción no se puede deshacer. ¿Deseas continuar?
                </p>

                <div class="flex justify-end gap-3">

                    <button
                        @click="openModal = false"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 
                            dark:text-gray-200 rounded-lg hover:bg-gray-300 
                            dark:hover:bg-gray-600 transition">
                        Cancelar
                    </button>

                    <form :action="deleteUrl" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg shadow">
                            Eliminar
                        </button>
                    </form>

                </div>

            </div>
        </div>


        </div>

    </div>

    {{-- SCRIPTS DATATABLES --}}
    @push('scripts')
        {{-- jQuery --}}
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        {{-- DataTables Core --}}
        <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.tailwindcss.min.css">

        {{-- Botones --}}
        <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>

        {{-- Export --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

        {{-- Responsive --}}
        <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.min.js"></script>

        <script>
            $(document).ready(function () {

                let table = $('#categoriesTable').DataTable({
                    responsive: true,
                    paging: false,
                    searching: true,
                    ordering: true,

                    layout: {
                        topStart: {
                            buttons: ['copy', 'excel', 'pdf', 'print']
                        }
                    },

                    language: {
                        search: "Buscar:",
                        zeroRecords: "No se encontraron resultados",
                        infoEmpty: "No hay registros",
                        infoFiltered: "(filtrado)"
                    }
                });

                // Ajuste tema oscuro DataTables
                const applyDarkMode = () => {
                    if (document.documentElement.classList.contains('dark')) {
                        $('.dataTables_filter input')
                            .addClass('bg-gray-800 text-gray-100 border-gray-600')
                            .removeClass('bg-white text-gray-900');

                        $('.dt-button')
                            .addClass('bg-gray-700 text-gray-200 hover:bg-gray-600')
                            .removeClass('bg-white text-gray-800');
                    } else {
                        $('.dataTables_filter input')
                            .removeClass('bg-gray-800 text-gray-100 border-gray-600')
                            .addClass('bg-white text-gray-900');

                        $('.dt-button')
                            .removeClass('bg-gray-700 text-gray-200 hover:bg-gray-600')
                            .addClass('bg-white text-gray-800');
                    }
                };

                applyDarkMode();

                const observer = new MutationObserver(applyDarkMode);
                observer.observe(document.documentElement, { attributes: true });

            });
        </script>
    @endpush

</x-app-layout>
