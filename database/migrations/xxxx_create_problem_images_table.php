<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk tabel problem_images
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('problem_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('problem_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->string('caption')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->index(['problem_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('problem_images');
    }
};

/**
 * Migration untuk tabel applications
 */
class CreateApplicationsTable extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('problem_id')->constrained()->onDelete('cascade');
            
            // status: pending, reviewed, accepted, rejected, withdrawn
            $table->enum('status', ['pending', 'reviewed', 'accepted', 'rejected', 'withdrawn'])->default('pending');
            
            // dokumen aplikasi
            $table->string('proposal_path')->nullable();
            $table->text('cover_letter')->nullable();
            $table->text('motivation');
            $table->json('skills')->nullable(); // skills yang dimiliki student
            
            // timestamps untuk tracking
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('withdrawn_at')->nullable();
            
            // feedback dari instansi
            $table->text('feedback')->nullable();
            $table->integer('rating')->nullable(); // rating dari instansi (1-5)
            
            $table->timestamps();
            $table->softDeletes();
            
            // indexes
            $table->index('status');
            $table->index(['student_id', 'status']);
            $table->index(['problem_id', 'status']);
            $table->unique(['student_id', 'problem_id']); // prevent duplicate applications
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
}

/**
 * Migration untuk tabel provinces
 */
class CreateProvincesTable extends Migration
{
    public function up(): void
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name');
            
            $table->index('code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provinces');
    }
}

/**
 * Migration untuk tabel regencies
 */
class CreateRegenciesTable extends Migration
{
    public function up(): void
    {
        Schema::create('regencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id')->constrained()->onDelete('cascade');
            $table->string('code', 10)->unique();
            $table->string('name');
            
            $table->index(['province_id', 'name']);
            $table->index('code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regencies');
    }
}

/**
 * Migration untuk tabel universities
 */
class CreateUniversitiesTable extends Migration
{
    public function up(): void
    {
        Schema::create('universities', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->foreignId('province_id')->constrained()->onDelete('cascade');
            $table->foreignId('regency_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['negeri', 'swasta'])->default('negeri');
            $table->enum('accreditation', ['A', 'B', 'C', 'Unggul', 'Baik Sekali', 'Baik'])->nullable();
            $table->string('website')->nullable();
            $table->timestamps();
            
            $table->index('name');
            $table->index(['province_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('universities');
    }
}

/**
 * Migration untuk tabel students
 */
class CreateStudentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->foreignId('university_id')->constrained()->onDelete('cascade');
            $table->string('major');
            $table->string('nim')->unique();
            $table->integer('semester');
            $table->string('phone');
            $table->string('profile_photo_path')->nullable();
            $table->text('bio')->nullable();
            $table->json('skills')->nullable(); // array of skills
            $table->json('interests')->nullable(); // array of interests
            $table->timestamps();
            
            $table->index('nim');
            $table->index('university_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
}

/**
 * Migration untuk tabel institutions
 */
class CreateInstitutionsTable extends Migration
{
    public function up(): void
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('type'); // pemerintah desa, dinas, ngo, puskesmas, dll
            $table->text('address');
            $table->foreignId('province_id')->constrained()->onDelete('cascade');
            $table->foreignId('regency_id')->constrained()->onDelete('cascade');
            $table->string('email');
            $table->string('phone');
            $table->string('logo_path')->nullable();
            $table->string('pic_name'); // person in charge
            $table->string('pic_position');
            $table->string('verification_document_path')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('is_verified');
            $table->index(['province_id', 'regency_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
}