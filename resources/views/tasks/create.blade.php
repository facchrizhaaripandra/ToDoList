<div class="bg-white rounded-2xl shadow-xl p-4">
    <h2 class="text-xl font-semibold mb-3 text-gray-800">➕ Tambah Task Baru</h2>
    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 gap-3 mb-3">
            <div>
                <label class="block text-gray-700 font-medium mb-2">Judul Task</label>
                <input type="text" name="title" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                    placeholder="Masukkan judul task...">
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">Prioritas</label>
                <select name="priority" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="low">🟢 Rendah</option>
                    <option value="medium" selected>🟡 Sedang</option>
                    <option value="high">🔴 Tinggi</option>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label class="block text-gray-700 font-medium mb-2">Deskripsi</label>
            <textarea name="description" rows="2"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                placeholder="Deskripsi task (opsional)..."></textarea>
        </div>
        <div class="mb-3">
            <label class="block text-gray-700 font-medium mb-2">Tanggal Deadline</label>
            <input type="date" name="due_date"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        </div>
        <button type="submit" class="w-full bg-gradient-to-r from-purple-500 to-pink-500 text-white py-2 rounded-lg font-semibold hover:from-purple-600 hover:to-pink-600 transition duration-300">
            Tambah Task
        </button>
    </form>
</div>
