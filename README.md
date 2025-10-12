# KKN-Go: Revolusi Mahasiswa - Mengubah KKN Menjadi Solusi Nyata Bangsa

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-316192?style=for-the-badge&logo=postgresql&logoColor=white)](https://www.postgresql.org)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Vite](https://img.shields.io/badge/Vite-5.x-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev)

---

## 🎯 Tentang Project

**KKN-Go** adalah platform digital terintegrasi yang merevolusi pelaksanaan program Kuliah Kerja Nyata (KKN) di Indonesia. Platform ini menghubungkan mahasiswa dengan instansi pemerintah daerah dan lembaga sosial untuk menciptakan kolaborasi yang lebih efektif dan berdampak nyata terhadap pencapaian Sustainable Development Goals (SDGs).

### 🏆 Submission

- **Lomba**: SwitchFest 2025 - Web Development
- **Kategori**: Full-Stack Web Application
- **Tim**: AnakSoleh
- **Institusi**: Institut Teknologi Bandung (ITB)

---

## 🌟 Latar Belakang

Program KKN di Indonesia saat ini menghadapi beberapa tantangan:

- **Mismatch antara Kebutuhan dan Solusi**: Mahasiswa sering kesulitan menemukan proyek yang sesuai dengan keahlian dan minat mereka
- **Fragmentasi Data**: Hasil-hasil KKN tidak terdokumentasi dengan baik dan sulit diakses untuk pembelajaran
- **Kurangnya Transparansi**: Proses seleksi dan penempatan mahasiswa kurang transparan
- **Minimnya Kolaborasi**: Tidak ada platform terpusat untuk memfasilitasi komunikasi antara mahasiswa dan instansi
- **Pemborosan Anggaran**: Banyak proyek KKN yang redundan karena kurangnya koordinasi

**KKN-Go hadir sebagai solusi** dengan menyediakan marketplace digital yang transparan, repository pengetahuan terstruktur, dan sistem manajemen proyek yang terintegrasi.

### 📊 Dampak yang Diharapkan

- Pengurangan pemborosan anggaran hingga **Rp 540 miliar per tahun**
- Pembangunan database **100.000+ laporan KKN** terstruktur dalam 5 tahun pertama
- Peningkatan kualitas program KKN di seluruh Indonesia
- Akselerasi pencapaian SDGs melalui kolaborasi mahasiswa-pemerintah

---

## ✨ Fitur Utama

### 👨‍🎓 Untuk Mahasiswa

#### 1. **Browse Problems (Marketplace)**
- 🔍 Pencarian dan filter berdasarkan:
  - Lokasi (Provinsi, Kabupaten/Kota)
  - Kategori SDG (17 kategori)
  - Durasi proyek
  - Tingkat kesulitan
  - Status proyek
- 🗺️ Visualisasi peta interaktif dengan Leaflet.js
- 📊 Grid/List view toggle
- 🔖 Wishlist/Save functionality

#### 2. **Problem Detail**
- 📄 Deskripsi lengkap masalah dengan data pendukung
- 📸 Galeri foto/video dokumentasi lapangan
- 🎯 Skills yang dibutuhkan
- 📅 Timeline dan deadline
- 🏢 Informasi instansi penerbit
- 🤝 Expected outcomes & deliverables
- 🏨 Fasilitas yang disediakan

#### 3. **My Applications**
- 📋 List semua aplikasi yang diajukan
- 🔄 Status tracking (Pending, Reviewed, Accepted, Rejected)
- 💬 Feedback dari instansi
- ⏱️ Timeline progress

#### 4. **My Projects**
- 🚀 Active projects dengan progress tracking
- ✅ Completed projects
- 📝 Upload progress reports
- 💬 Communication tools dengan instansi
- 📤 Submit final report

#### 5. **Portfolio (Public Profile)**
- 👤 Profil publik yang dapat dibagikan
- 🏆 Showcase completed projects
- ⭐ Reviews dan ratings dari instansi
- 📊 Impact metrics & statistics
- 🎖️ Achievements dan certificates

### 🏛️ Untuk Instansi

#### 1. **Dashboard Instansi**
- 📊 Statistik overview (problems posted, applications received)
- 📈 Analytics proyek
- 🔔 Notifikasi real-time
- 📂 Manajemen dokumen

#### 2. **Problem Management**
- ➕ Publikasi masalah dengan form terstruktur
- 📝 Template berdasarkan kategori SDG
- ✏️ Editor visual untuk deskripsi
- 📸 Upload foto/video dengan preview
- 🔄 Update status proyek

#### 3. **Application Management**
- 📥 Review aplikasi mahasiswa
- ✅ Approval/Rejection workflow
- 🔔 Notifikasi otomatis ke mahasiswa
- 💬 Communication tools

#### 4. **Project Monitoring**
- 👀 Track progress proyek
- 📊 Lihat laporan berkala
- ⭐ Evaluasi dan review tim
- 🎖️ Issue sertifikasi digital

### 📚 Knowledge Repository

- 🔍 Pencarian full-text dengan filter advanced
- 📄 PDF viewer terintegrasi
- 📥 Download tracking
- 📚 Sistem sitasi otomatis (APA, MLA)
- 📷 Galeri foto dokumentasi
- 🔖 Highlight & bookmark
- 🔗 Visualisasi koneksi antar dokumen
- 📊 Usage statistics

### 🔐 Sistem Autentikasi & Authorization

- 👥 Multi-role system (Admin, Mahasiswa, Instansi)
- 📧 Email verification
- 🔑 Password reset functionality
- 🛡️ Middleware protection untuk setiap role
- 📝 Detailed audit logging

---

## 🛠️ Technology Stack

### Backend Core

| Komponen | Teknologi | Versi | Deskripsi |
|----------|-----------|-------|-----------|
| **Bahasa Pemrograman** | PHP | 8.2+ | Server-side scripting untuk logika bisnis |
| **Framework** | Laravel | 11.x | Framework PHP modern dengan arsitektur MVC |
| **Database** | PostgreSQL | 16+ | Database relasional untuk integritas data |
| **ORM** | Eloquent | - | Active Record pattern untuk interaksi database |

### Frontend

| Komponen | Teknologi | Versi | Deskripsi |
|----------|-----------|-------|-----------|
| **Template Engine** | Blade | - | Laravel's native templating engine |
| **CSS Framework** | Tailwind CSS | 3.x | Utility-first CSS framework |
| **Build Tool** | Vite | 5.x | Modern asset bundler & compiler |
| **JavaScript** | Vanilla JS + Axios | ES6+ | Client-side interactivity & HTTP client |
| **Icons** | Boxicons | 2.1.4 | Comprehensive icon library |

### Tools & Services

| Komponen | Teknologi | Deskripsi |
|----------|-----------|-----------|
| **Cloud Storage** | Supabase Storage | Penyimpanan file (dokumen, foto, video) |
| **Maps** | Leaflet.js | Visualisasi geografis interaktif |
| **Animation** | AOS (Animate On Scroll) | Smooth scroll animations |
| **Package Manager (PHP)** | Composer | Manajemen dependencies PHP |
| **Package Manager (JS)** | NPM | Manajemen dependencies JavaScript |
| **Email** | SMTP/Laravel Mail | Notifikasi email otomatis |
| **Version Control** | Git | Source code management |

---

## 🏗️ Arsitektur Sistem

### Pola Arsitektur: Monolitik MVC

KKN-Go dibangun menggunakan **arsitektur monolitik** dengan pola **Model-View-Controller (MVC)** yang diimplementasikan oleh Laravel:

```
┌─────────────────────────────────────────────┐
│         PRESENTATION LAYER                   │
├──────────────┬──────────────┬────────────────┤
│ Blade Views  │ Tailwind CSS │ Vite (Build)   │
│   (.blade)   │              │                │
└──────────────┴──────────────┴────────────────┘
                      ▲
                      │
                      ▼
┌─────────────────────────────────────────────┐
│         APPLICATION LAYER                    │
├─────────────────────────────────────────────┤
│  Route + Middleware Stack                   │
│  [auth, verified, student/institution]      │
├──────────────┬──────────────┬────────────────┤
│ Controllers  │  Requests    │   Services     │
│ (HTTP Layer) │ (Validation) │   (Logic)      │
└──────────────┴──────────────┴────────────────┘
                      ▲
                      │
                      ▼
┌─────────────────────────────────────────────┐
│              DATA LAYER                      │
├──────────────┬──────────────┬────────────────┤
│   Eloquent   │  PostgreSQL  │   Supabase     │
│    Models    │   Database   │    Storage     │
└──────────────┴──────────────┴────────────────┘
```

### Komponen Utama

1. **Presentation Layer**: Blade templates + Tailwind CSS untuk UI/UX
2. **Application Layer**: Controllers, Middleware, Services untuk logika aplikasi
3. **Data Layer**: Eloquent ORM + PostgreSQL + Supabase Storage

### Keunggulan Arsitektur Monolitik

- ✅ **Simplicity**: Lebih mudah untuk develop, test, dan deploy
- ✅ **Performance**: Tidak ada network latency antar services
- ✅ **Transaction**: ACID compliance untuk integritas data
- ✅ **Debugging**: Easier debugging dengan single codebase
- ✅ **Cost-Effective**: Lebih murah untuk maintain di awal

## 👥 Tim Pengembang

### Tim AnakSoleh

| Nama | Role | NIM | Kontak |
|------|------|-----|--------|
| **Danendra Shafi Athallah** | Full-Stack Developer | 13523136 |
| **Kenzie Raffa Ardhana** | Project Manager | 18223127 |
| **M. Abizzar Gamadrian** | Backend Developer | 13523155 |

### Institusi

**Institut Teknologi Bandung**  
Sekolah Teknik Elektro dan Informatika  
Jl. Ganesha 10, Bandung 40132

---

## 🙏 Acknowledgments

Terima kasih kepada:

- **SwitchFest 2025** untuk menyelenggarakan lomba ini
- **Institut Teknologi Bandung** untuk dukungan dan fasilitasnya
- **Laravel Community** untuk framework yang luar biasa
- **Open Source Contributors** untuk berbagai library yang digunakan
- **Kementerian Pendidikan** untuk program KKN di Indonesia

---

## 📚 Referensi

1. Badan Pusat Statistik. (2023). Statistik Pendidikan Tinggi Indonesia 2023.
2. Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi. (2024). Panduan Pelaksanaan Kampus Merdeka: Kuliah Kerja Nyata Tematik 2024.
3. United Nations Development Programme Indonesia. (2023). Accelerating the SDGs through Digital Transformation.

---

<div align="center">

**Dibuat dengan ❤️ oleh Tim AnakSoleh**

**Institut Teknologi Bandung - 2025**

[⬆ Kembali ke atas](#kkn-go-revolusi-mahasiswa---mengubah-kkn-menjadi-solusi-nyata-bangsa)

</div>