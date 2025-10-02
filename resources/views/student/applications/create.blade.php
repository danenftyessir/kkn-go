{{-- resources/views/student/applications/create.blade.php --}}
{{-- form untuk apply ke proyek --}}

@extends('layouts.app')

@section('title', 'Apply ke Proyek')

@push('styles')
<style>
/* form animations */
.form-container {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.6s ease-out forwards;
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* input focus effects */
.form-input, .form-textarea {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.form-input:focus, .form-textarea:focus {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* character counter */
.char-counter {
    transition: color 0.3s ease;
}

.char-counter.warning {
    color: #f59e0b;
}

.char-counter.danger {
    color: #ef4444;
}

/* file upload area */
.file-upload-area {
    transition: all 0.3s ease;
    border: 2px dashed #d1d5db;
}

.file-upload-area:hover {
    border-color: #3b82f6;
    background-color: #eff6ff;
}

.file-upload-area.dragging {
    border-color: #3b82f6;
    background-color: #dbeafe;
    transform: scale(1.02);
}

/* submit button animation */
.submit-btn {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.submit-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.submit-btn:active::before {
    width: 300px;
    height: 300px;
}

/* tips card */
.tips-card {
    transition: all 0.3s ease;
}

.tips-card:hover {
    transform: translateX(4px);
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- back button -->
        <div class="mb-6">
            <a href="{{ route('student.problems.show', $problem->id) }}" 
               class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Detail Proyek
            </a>
        </div>

        <!-- header -->
        <div class="form-container bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-start">
                @if($problem->institution->logo_path)
                <img src="{{ asset('storage/' . $problem->institution->logo_path) }}" 
                     alt="{{ $problem->institution->name }}"
                     class="w-16 h-16 rounded-lg object-cover mr-4">
                @else
                <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center mr-4">
                    <span class="text-white font-bold text-2xl">
                        {{ strtoupper(substr($problem->institution->name, 0, 1)) }}
                    </span>
                </div>
                @endif

                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900">Apply ke: {{ $problem->title }}</h1>
                    <p class="text-gray-600 mt-1">{{ $problem->institution->name }}</p>
                    <div class="flex flex-wrap gap-2 mt-3">
                        <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-md">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                            {{ $problem->regency->name }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-sm rounded-md">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $problem->duration_months }} bulan
                        </span>
                        <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 text-sm rounded-md">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Deadline: {{ $problem->application_deadline->format('d M Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- main form -->
            <div class="lg:col-span-2">
                <div class="form-container bg-white rounded-xl shadow-sm border border-gray-200 p-6" style="animation-delay: 0.1s;">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Form Aplikasi</h2>

                    <form action="{{ route('student.applications.store', $problem->id) }}" 
                          method="POST" 
                          enctype="multipart/form-data"
                          x-data="applicationForm()"
                          @submit="submitForm">
                        @csrf

                        <!-- motivation -->
                        <div class="mb-6">
                            <label for="motivation" class="block text-sm font-semibold text-gray-900 mb-2">
                                Motivasi <span class="text-red-500">*</span>
                            </label>
                            <p class="text-sm text-gray-600 mb-3">Jelaskan mengapa Anda tertarik dengan proyek ini dan apa yang bisa Anda kontribusikan (minimal 100 karakter)</p>
                            <textarea id="motivation" 
                                      name="motivation" 
                                      rows="8"
                                      x-model="motivation"
                                      @input="updateCharCount('motivation')"
                                      class="form-textarea w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      required>{{ old('motivation') }}</textarea>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-xs text-gray-500">Minimal 100 karakter</span>
                                <span class="char-counter text-sm font-medium" 
                                      :class="{
                                          'text-gray-600': motivationCount < 100,
                                          'text-green-600': motivationCount >= 100 && motivationCount <= 1800,
                                          'warning': motivationCount > 1800 && motivationCount <= 2000,
                                          'danger': motivationCount > 2000
                                      }"
                                      x-text="motivationCount + ' / 2000'"></span>
                            </div>
                            @error('motivation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- cover letter (optional) -->
                        <div class="mb-6">
                            <label for="cover_letter" class="block text-sm font-semibold text-gray-900 mb-2">
                                Cover Letter <span class="text-gray-400">(Opsional)</span>
                            </label>
                            <p class="text-sm text-gray-600 mb-3">Ceritakan tentang pengalaman, skill, atau pencapaian relevan Anda</p>
                            <textarea id="cover_letter" 
                                      name="cover_letter" 
                                      rows="6"
                                      x-model="coverLetter"
                                      @input="updateCharCount('coverLetter')"
                                      class="form-textarea w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('cover_letter') }}</textarea>
                            <div class="flex justify-end mt-2">
                                <span class="char-counter text-sm font-medium" 
                                      :class="{
                                          'text-gray-600': coverLetterCount <= 1800,
                                          'warning': coverLetterCount > 1800 && coverLetterCount <= 2000,
                                          'danger': coverLetterCount > 2000
                                      }"
                                      x-text="coverLetterCount + ' / 2000'"></span>
                            </div>
                            @error('cover_letter')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- proposal upload (optional) -->
                        <div class="mb-8">
                            <label for="proposal" class="block text-sm font-semibold text-gray-900 mb-2">
                                Upload Proposal <span class="text-gray-400">(Opsional)</span>
                            </label>
                            <p class="text-sm text-gray-600 mb-3">Format: PDF, DOC, DOCX. Maksimal 5MB</p>
                            
                            <div class="file-upload-area rounded-lg p-8 text-center cursor-pointer"
                                 @dragover.prevent="isDragging = true"
                                 @dragleave.prevent="isDragging = false"
                                 @drop.prevent="handleFileDrop"
                                 @click="$refs.fileInput.click()"
                                 :class="{'dragging': isDragging}">
                                <input type="file" 
                                       name="proposal" 
                                       id="proposal"
                                       accept=".pdf,.doc,.docx"
                                       class="hidden"
                                       x-ref="fileInput"
                                       @change="handleFileSelect">
                                
                                <div x-show="!fileName">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600">Klik atau drag file ke sini</p>
                                    <p class="mt-1 text-xs text-gray-500">PDF, DOC, DOCX (Max 5MB)</p>
                                </div>

                                <div x-show="fileName" class="flex items-center justify-center">
                                    <svg class="h-8 w-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="text-left">
                                        <p class="text-sm font-medium text-gray-900" x-text="fileName"></p>
                                        <p class="text-xs text-gray-500" x-text="fileSize"></p>
                                    </div>
                                    <button type="button" 
                                            @click.stop="clearFile"
                                            class="ml-4 text-red-600 hover:text-red-800">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @error('proposal')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- submit buttons -->
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="submit" 
                                    class="submit-btn flex-1 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                    :disabled="submitting"
                                    x-text="submitting ? 'Mengirim...' : 'Kirim Aplikasi'">
                            </button>
                            <a href="{{ route('student.problems.show', $problem->id) }}" 
                               class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 text-center transition-colors">
                                Batal
                            </a>
                        </div>

                        <p class="mt-4 text-sm text-gray-500 text-center">
                            Dengan mengirim aplikasi, Anda menyetujui bahwa informasi yang diberikan adalah benar dan dapat dipertanggungjawabkan.
                        </p>
                    </form>
                </div>
            </div>

            <!-- sidebar tips -->
            <div class="lg:col-span-1">
                <div class="form-container sticky top-8 space-y-6" style="animation-delay: 0.2s;">
                    <div class="bg-blue-50 rounded-xl border border-blue-200 p-6">
                        <h3 class="text-lg font-bold text-blue-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            Tips Aplikasi
                        </h3>
                        
                        <div class="space-y-3">
                            <div class="tips-card p-3 bg-white rounded-lg border border-blue-100">
                                <p class="text-sm text-blue-900 font-medium mb-1">📝 Jelas & Spesifik</p>
                                <p class="text-xs text-blue-700">Jelaskan secara detail mengapa Anda cocok untuk proyek ini</p>
                            </div>
                            
                            <div class="tips-card p-3 bg-white rounded-lg border border-blue-100">
                                <p class="text-sm text-blue-900 font-medium mb-1">🎯 Relevan</p>
                                <p class="text-xs text-blue-700">Hubungkan pengalaman dan skill Anda dengan kebutuhan proyek</p>
                            </div>
                            
                            <div class="tips-card p-3 bg-white rounded-lg border border-blue-100">
                                <p class="text-sm text-blue-900 font-medium mb-1">💡 Inovatif</p>
                                <p class="text-xs text-blue-700">Tunjukkan ide atau perspektif unik yang bisa Anda bawa</p>
                            </div>
                            
                            <div class="tips-card p-3 bg-white rounded-lg border border-blue-100">
                                <p class="text-sm text-blue-900 font-medium mb-1">📄 Proposal (Opsional)</p>
                                <p class="text-xs text-blue-700">Lampirkan proposal untuk meningkatkan peluang diterima</p>
                            </div>
                        </div>
                    </div>

                    <!-- project info -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Info Proyek</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm text-gray-600">Mahasiswa Dibutuhkan</dt>
                                <dd class="text-sm font-semibold text-gray-900 mt-1">{{ $problem->required_students }} orang</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-600">Sudah Melamar</dt>
                                <dd class="text-sm font-semibold text-gray-900 mt-1">{{ $problem->applications_count }} aplikasi</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-600">Periode</dt>
                                <dd class="text-sm font-semibold text-gray-900 mt-1">
                                    {{ $problem->start_date->format('M Y') }} - {{ $problem->end_date->format('M Y') }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-600">Deadline</dt>
                                <dd class="text-sm font-semibold text-red-600 mt-1">
                                    {{ $problem->application_deadline->format('d M Y') }}
                                    ({{ $problem->application_deadline->diffForHumans() }})
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// alpine.js component untuk application form
function applicationForm() {
    return {
        motivation: '',
        coverLetter: '',
        motivationCount: 0,
        coverLetterCount: 0,
        fileName: '',
        fileSize: '',
        isDragging: false,
        submitting: false,
        
        init() {
            // hitung karakter awal jika ada old input
            this.updateCharCount('motivation');
            this.updateCharCount('coverLetter');
        },
        
        updateCharCount(field) {
            if (field === 'motivation') {
                this.motivationCount = this.motivation.length;
            } else if (field === 'coverLetter') {
                this.coverLetterCount = this.coverLetter.length;
            }
        },
        
        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                this.setFileInfo(file);
            }
        },
        
        handleFileDrop(event) {
            this.isDragging = false;
            const file = event.dataTransfer.files[0];
            if (file) {
                // validasi tipe file
                const validTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                if (!validTypes.includes(file.type)) {
                    alert('Format file tidak valid. Gunakan PDF, DOC, atau DOCX');
                    return;
                }
                
                // validasi ukuran (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 5MB');
                    return;
                }
                
                this.$refs.fileInput.files = event.dataTransfer.files;
                this.setFileInfo(file);
            }
        },
        
        setFileInfo(file) {
            this.fileName = file.name;
            this.fileSize = this.formatFileSize(file.size);
        },
        
        clearFile() {
            this.$refs.fileInput.value = '';
            this.fileName = '';
            this.fileSize = '';
        },
        
        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        },
        
        submitForm(event) {
            // validasi motivasi
            if (this.motivationCount < 100) {
                alert('Motivasi minimal 100 karakter');
                event.preventDefault();
                return;
            }
            
            if (this.motivationCount > 2000) {
                alert('Motivasi maksimal 2000 karakter');
                event.preventDefault();
                return;
            }
            
            this.submitting = true;
        }
    };
}
</script>
@endpush