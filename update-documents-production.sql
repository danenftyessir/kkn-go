-- SQL Script untuk Update Documents di Production Database
-- Mengganti semua dummy files dengan file PDF yang sebenarnya dari Supabase
--
-- Cara menggunakan:
-- 1. Login ke Railway Dashboard
-- 2. Buka Database > Query
-- 3. Copy paste script ini dan execute

-- Step 1: Tampilkan jumlah dummy files yang akan diupdate
SELECT
    COUNT(*) as total_dummy_files,
    'documents that need to be updated' as description
FROM documents
WHERE file_path LIKE '%dummy%';

-- Step 2: Update semua dummy files dengan file PDF yang sebenarnya
-- Setiap dokumen akan mendapat random file dari list yang tersedia

WITH real_files AS (
    SELECT unnest(ARRAY[
        'documents/reports/3341b-laporan_kkn_hasbi_mudzaki_fix-1-.pdf',
        'documents/reports/aaLAPORAN-PROGRAM-KERJA-KKN.pdf',
        'documents/reports/bc4f599c360deae829ef0952f9200a4f.pdf',
        'documents/reports/d5460592f2ee74a2f9f5910138d650e6.pdf',
        'documents/reports/f3f3ec539ee2d963e804d3a964b3290f.pdf',
        'documents/reports/KKN_III.D.3_REG.96_2022.pdf',
        'documents/reports/LAPORAN AKHIR KKN .pdf',
        'documents/reports/laporan akhir KKN PPM OK.pdf',
        'documents/reports/LAPORAN KELOMPOK KKN 1077fix.pdf',
        'documents/reports/LAPORAN KKN DEMAPESA.pdf',
        'documents/reports/LAPORAN KKN KELOMPOK 2250.pdf',
        'documents/reports/LAPORAN KKN_1.A.2_REG.119_2024.pdf',
        'documents/reports/LAPORAN KKN.pdf',
        'documents/reports/laporan_3460160906115724.pdf',
        'documents/reports/laporan_akhir_201_35_2.pdf',
        'documents/reports/laporan_akhir_3011_45_5.pdf',
        'documents/reports/laporan-kelompok.pdf',
        'documents/reports/Laporan-KKN-2019.pdf',
        'documents/reports/Laporan-Tugas-Akhir-KKN-156.pdf'
    ]) AS file_path
),
file_list AS (
    SELECT file_path, ROW_NUMBER() OVER () as rn
    FROM real_files
),
dummy_docs AS (
    SELECT
        id,
        ROW_NUMBER() OVER (ORDER BY id) as rn
    FROM documents
    WHERE file_path LIKE '%dummy%'
)
UPDATE documents d
SET file_path = f.file_path
FROM dummy_docs dd
CROSS JOIN LATERAL (
    SELECT file_path
    FROM file_list
    ORDER BY random()
    LIMIT 1
) f
WHERE d.id = dd.id;

-- Step 3: Update file paths yang belum punya prefix documents/reports/
UPDATE documents
SET file_path = 'documents/reports/' || file_path
WHERE file_path NOT LIKE 'documents/reports/%'
  AND file_path NOT LIKE '%dummy%';

-- Step 4: Verifikasi hasil - seharusnya tidak ada dummy files lagi
SELECT
    COUNT(*) as remaining_dummy_files,
    'should be 0' as expected
FROM documents
WHERE file_path LIKE '%dummy%';

-- Step 5: Tampilkan sample dokumen untuk verifikasi
SELECT
    id,
    title,
    file_path,
    'https://zgpykwjzmiqxhweifmrn.supabase.co/storage/v1/object/public/kkngo-storage/' || file_path as full_url
FROM documents
ORDER BY id
LIMIT 10;
