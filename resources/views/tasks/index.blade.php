@extends('layouts.app')

@section('content')
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">📝 My To-Do List</h1>
        <p class="text-gray-600">Kelola tugas Anda dengan mudah</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md">
            {{ session('success') }}
        </div>
    @endif

    <!-- Daftar Task -->
    <div class="space-y-4">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">📋 Daftar Task</h2>

        @forelse($tasks as $task)
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition duration-300 {{ $task->is_completed ? 'opacity-75' : '' }}">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <!-- Toggle Complete -->
                            <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-2xl hover:scale-110 transition">
                                    {{ $task->is_completed ? '✅' : '⬜' }}
                                </button>
                            </form>

                            <h3 class="text-xl font-semibold {{ $task->is_completed ? 'line-through text-gray-500' : 'text-gray-800' }}">
                                {{ $task->title }}
                            </h3>

                            <!-- Priority Badge -->
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $task->priority === 'high' ? 'bg-red-200 text-red-800' : '' }}
                                {{ $task->priority === 'medium' ? 'bg-yellow-200 text-yellow-800' : '' }}
                                {{ $task->priority === 'low' ? 'bg-green-200 text-green-800' : '' }}">
                                {{ $task->priority === 'high' ? '🔴 Tinggi' : '' }}
                                {{ $task->priority === 'medium' ? '🟡 Sedang' : '' }}
                                {{ $task->priority === 'low' ? '🟢 Rendah' : '' }}
                            </span>
                        </div>

                        @if($task->description)
                            <p class="text-gray-600 mb-2 ml-11">{{ $task->description }}</p>
                        @endif

                        @if($task->due_date)
                            <p class="text-sm text-gray-500 ml-11">
                                📅 Deadline: {{ $task->due_date->format('d M Y') }}
                            </p>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        <button onclick="openEditModal({{ $task->id }}, '{{ addslashes($task->title) }}', '{{ addslashes($task->description ?? '') }}', '{{ $task->due_date?->format('Y-m-d') }}', '{{ $task->priority }}')"
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                            ✏️ Edit
                        </button>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus task ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                                🗑️ Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <p class="text-gray-500 text-lg">Belum ada task. Tambahkan task pertama Anda! 🚀</p>
            </div>
        @endforelse
    </div>

    <!-- Floating Add Button -->
    <button onclick="toggleDrawer()" aria-label="Tambah task" class="fixed right-6 bottom-6 bg-gradient-to-r from-purple-500 to-pink-500 text-white p-4 rounded-full shadow-lg hover:scale-105 transition z-40">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
    </button>

    <!-- Modal Edit -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center h-full">
            <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
            <h3 class="text-2xl font-semibold mb-4">Edit Task</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Judul Task</label>
                    <input type="text" name="title" id="editTitle" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Deskripsi</label>
                    <textarea name="description" id="editDescription" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Tanggal Deadline</label>
                    <input type="date" name="due_date" id="editDueDate" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Prioritas</label>
                    <select name="priority" id="editPriority" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="low">🟢 Rendah</option>
                        <option value="medium">🟡 Sedang</option>
                        <option value="high">🔴 Tinggi</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-purple-500 to-pink-500 text-white py-2 rounded-lg font-semibold hover:from-purple-600 hover:to-pink-600 transition">
                        Update
                    </button>
                    <button type="button" onclick="closeEditModal()" class="flex-1 bg-gray-300 text-gray-700 py-2 rounded-lg font-semibold hover:bg-gray-400 transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, title, description, dueDate, priority) {
            document.getElementById('editForm').action = `/tasks/${id}`;
            document.getElementById('editTitle').value = title;
            document.getElementById('editDescription').value = description || '';
            document.getElementById('editDueDate').value = dueDate || '';
            document.getElementById('editPriority').value = priority;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
@endsection
