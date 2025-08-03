-- Insert master kriteria
INSERT IGNORE INTO master_kriteria (kode_kriteria, nama_kriteria, deskripsi, tipe, is_active, created_at, updated_at) VALUES
('TPA', 'Tes Potensi Akademik (TPA)', 'Penilaian kemampuan akademik dasar siswa meliputi logika, matematika, dan bahasa', 'benefit', 1, NOW(), NOW()),
('PSI', 'Tes Psikologi', 'Penilaian aspek psikologis dan kepribadian siswa', 'benefit', 1, NOW(), NOW()),
('MNT', 'Minat dan Bakat', 'Penilaian minat dan bakat siswa terhadap bidang keahlian', 'benefit', 1, NOW(), NOW()),
('TKD', 'Kemampuan Teknik Dasar', 'Penilaian kemampuan teknik dasar dan pemahaman praktis', 'benefit', 1, NOW(), NOW()),
('MTK', 'Nilai Matematika', 'Nilai mata pelajaran matematika dari rapor', 'benefit', 1, NOW(), NOW()),
('IND', 'Nilai Bahasa Indonesia', 'Nilai mata pelajaran bahasa Indonesia dari rapor', 'benefit', 1, NOW(), NOW()),
('IPA', 'Nilai IPA', 'Nilai mata pelajaran IPA dari rapor', 'benefit', 1, NOW(), NOW());

-- Insert kriteria jurusan for all combinations
INSERT IGNORE INTO kriteria_jurusan (master_kriteria_id, jurusan_id, nilai_min, nilai_max, is_active, created_at, updated_at)
SELECT 
    mk.id as master_kriteria_id,
    j.id as jurusan_id,
    0 as nilai_min,
    100 as nilai_max,
    1 as is_active,
    NOW() as created_at,
    NOW() as updated_at
FROM master_kriteria mk
CROSS JOIN jurusan j
WHERE mk.is_active = 1 AND j.is_active = 1;
