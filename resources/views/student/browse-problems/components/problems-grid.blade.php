{{-- resources/views/student/browse-problems/partials/problems-grid.blade.php --}}
{{-- partial untuk ajax loading problems grid --}}

@forelse($problems as $index => $problem)
    @include('student.browse-problems.components.problem-card', ['problem' => $problem, 'index' => $index])
@empty
    <div class="col-span-full">
        <div class="text-center py-16 bg-white rounded-xl border border-gray-200">
            <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Proyek Ditemukan</h3>
            <p class="text-gray-600 mb-6">Coba ubah filter atau kata kunci pencarian Anda</p>
            <button onclick="resetFilters()" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 hover:shadow-lg font-semibold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Reset Filter
            </button>
        </div>
    </div>
@endforelse