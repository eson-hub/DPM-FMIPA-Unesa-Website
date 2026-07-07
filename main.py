graph TD
    %% Penyesuaian Gaya Visual %%
    classDef persiapan fill:#e1f5fe,stroke:#0288d1,stroke-width:2px,color:#000;
    classDef data fill:#fff3e0,stroke:#f57c00,stroke-width:2px,color:#000;
    classDef analisis fill:#e8f5e9,stroke:#388e3c,stroke-width:2px,color:#000;
    classDef solusi fill:#f3e5f5,stroke:#7b1fa2,stroke-width:2px,color:#000;
    classDef final fill:#ffebee,stroke:#d32f2f,stroke-width:2px,color:#000;

    %% Struktur Alur %%
    subgraph S1 [1. Tahap Persiapan]
        A[Studi Literatur Mutakhir<br>Guo 2021, Shao 2022, Noor 2026] --> B[Bedah Regulasi Lokal<br>RPJMD 2025-2029 & Arsitektur SPBE]
    end
    class A,B persiapan;

    subgraph S2 [2. Tahap Pengumpulan Data]
        B --> C[Koordinasi dengan Diskominfo<br>Data Teknis FO & Pusat Data]
        C --> D[Inventarisasi Kendala API<br>Interoperabilitas antar OPD]
    end
    class C,D data;

    subgraph S3 [3. Tahap Analisis Kajian]
        D --> E[Gap Analysis]
        E --> F[Analisis Teknis<br>Gap Jaringan FO & Integrasi SPLP]
        E --> G[Analisis Bisnis<br>Efisiensi Energi PUE & Anggaran DC]
    end
    class E,F,G analisis;

    subgraph S4 [4. Tahap Perumusan Solusi]
        F --> H[Penyusunan Model Bisnis &<br>Tata Kelola Infrastruktur Digital]
        G --> H
        H --> I[Pembuatan Roadmap Peningkatan<br>Jaringan FO & DC Berkelanjutan]
    end
    class H,I solusi;

    subgraph S5 [5. Tahap Finalisasi]
        I --> J[Penyusunan Laporan Akhir &<br>Rekomendasi Kebijakan BRIDA]
    end
    class J final;
