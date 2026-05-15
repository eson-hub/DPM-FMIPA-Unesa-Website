# Backend DPM FMIPA UNESA

Backend ini dibuat untuk melayani kebutuhan data dari `index3.html`: berita/artikel, regulasi, agenda, galeri, profil organisasi, dan autentikasi admin.

## Setup

```bash
npm install
copy .env.example .env
npm run prisma:generate
npm run prisma:migrate
npm run seed
npm run dev
```

## Endpoint Utama

- `GET /api/health`
- `POST /api/auth/login`
- `POST /api/auth/bootstrap` untuk membuat admin pertama jika database masih kosong
- `GET /api/articles?category=BERITA&published=true&limit=3`
- `GET /api/articles/:slug`
- `GET /api/regulations`
- `GET /api/agendas?status=UPCOMING&limit=3`
- `GET /api/gallery?limit=6`
- `GET /api/commissions`
- `GET /api/members`

Endpoint `POST`, `PUT`, dan `DELETE` untuk konten membutuhkan header:

```text
Authorization: Bearer <token>
```
