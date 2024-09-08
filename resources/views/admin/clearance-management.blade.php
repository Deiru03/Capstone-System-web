<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Clearance Management') }}
        </h2>
    </x-slot>

    <style>
        /* Set a maximum height for the modal content and enable overflow scrolling */
        .modal-content {
            max-height: 80vh; /* Adjust the height as needed */
            overflow-y: auto;
        }
    </style>
    <!-- resources\views\admin\clearance-management.blade.php -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Add New Clearance Checklist Button -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <button onclick="openAddModal()" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Add New Clearance Checklist</button>
            </div>

            <!-- Existing Clearance Checklists -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900">Existing Clearance Checklists</h3>
                <table class="min-w-full bg-white mt-4">
                    <thead>
                        <tr>
                            <th class="py-2">ID</th>
                            <th class="py-2">Document Name</th>
                            <th class="py-2">Units</th>
                            <th class="py-2">Type</th>
                            <th class="py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clearanceChecklists as $checklist)
                        <tr>
                            <td class="border px-4 py-2">{{ $checklist->id }}</td>
                            <td class="border px-4 py-2">{{ $checklist->name }}</td>
                            <td class="border px-4 py-2">{{ $checklist->units }}</td>
                            <td class="border px-4 py-2">{{ $checklist->type }}</td>
                            <td class="border px-4 py-2">
                                <button onclick="openEditModal('{{ $checklist->table_name }}')" class="text-blue-500">Edit</button>
                                <form action="{{ route('admin.delete-clearance-checklist', $checklist->table_name) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden" style="z-index: 1050;">
        <div class="bg-white p-6 rounded-lg shadow-lg modal-content">
            <h3 class="text-lg font-medium">Add New Clearance Checklist</h3>
            <form id="addForm" action="{{ route('admin.add-clearance-checklist') }}" method="POST">
                @csrf
                <div class="mt-4">
                    <label for="type" class="block text-sm font-medium text-gray-700">Type:</label>
                    <select name="type" id="type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" required>
                        <option value="Permanent">Permanent</option>
                        <option value="Part-Timer">Part-Timer</option>
                        <option value="Temporary">Temporary</option>
                    </select>
                </div>
                <div class="mt-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Document Name:</label>
                    <input type="text" name="name" id="name" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" required>
                </div>
                <div class="mt-4">
                    <label for="units" class="block text-sm font-medium text-gray-700">Units:</label>
                    <input type="number" name="units" id="units" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" required>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" onclick="closeAddModal()" class="mr-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Cancel</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Create</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden" style="z-index: 1050;">
        <div class="bg-white p-6 rounded-lg shadow-lg modal-content w-3/4">
            <h3 class="text-lg font-medium">Edit Clearance Checklist</h3>
            <form id="editForm" action="{{ route('admin.update-clearance-checklist', '') }}" method="POST">
                @csrf
                <div id="editChecklistContent" class="mt-4">
                    <!-- Checklist content will be loaded here -->
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" onclick="closeEditModal()" class="mr-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Cancel</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function openEditModal(table) {
            fetch(`/admin/edit-clearance-checklist/${table}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('editChecklistContent').innerHTML = html;
                    document.getElementById('editForm').action = `/admin/update-clearance-checklist/${table}`;
                    document.getElementById('editModal').classList.remove('hidden');
                });
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function addRequirement() {
            const requirementHtml = `
                <div class="mt-4">
                    <label for="requirement_name" class="block text-sm font-medium text-gray-700">Requirement Name:</label>
                    <input type="text" name="requirement_name[]" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" required>
                    <button type="button" onclick="removeRequirement(this)" class="mt-2 bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-2 rounded">Remove</button>
                </div>
            `;
            document.getElementById('editChecklistContent').insertAdjacentHTML('beforeend', requirementHtml);
        }

        function removeRequirement(button) {
            button.parentElement.remove();
        }
    </script>
</x-admin-layout>