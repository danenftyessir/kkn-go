{{-- 
    komponen grid view untuk menampilkan list problems
    digunakan di halaman browse problems
    
    props: $problems (collection)
--}}

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($problems as $problem)
        @include('student.browse-problems.components.problem-card', ['problem' => $problem])
    @empty
        <div class="col-span-full">
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Problem Ditemukan</h3>
                <p class="text-gray-600 mb-6">
                    Maaf, tidak ada problem yang sesuai dengan kriteria pencarian Anda.
                </p>
                <a href="{{ route('student.browse-problems') }}" 
                   class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Reset Filter
                </a>
            </div>
        </div>
    @endforelse
</div>

{{-- pagination --}}
@if($problems->hasPages())
<div class="mt-8">
    {{ $problems->links() }}
</div>
@endif