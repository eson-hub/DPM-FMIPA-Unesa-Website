require("dotenv").config();

const bcrypt = require("bcryptjs");
const { PrismaClient } = require("@prisma/client");

const prisma = new PrismaClient();

const main = async () => {
  const password = await bcrypt.hash("admin12345", 12);

  const admin = await prisma.user.upsert({
    where: { email: "admin@dpmfmipa.test" },
    update: {},
    create: {
      name: "Super Admin DPM",
      email: "admin@dpmfmipa.test",
      password,
      role: "SUPER_ADMIN",
    },
  });

  await prisma.article.upsert({
    where: { slug: "rapat-koordinasi-dpm-fmipa-unesa" },
    update: {},
    create: {
      title: "Rapat Koordinasi DPM FMIPA UNESA",
      slug: "rapat-koordinasi-dpm-fmipa-unesa",
      category: "BERITA",
      content:
        "DPM FMIPA UNESA melaksanakan rapat koordinasi untuk menyusun agenda kerja dan penguatan fungsi pengawasan.",
      thumbnail:
        "https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=1200&q=80",
      isPublished: true,
      authorId: admin.id,
    },
  });

  await prisma.article.upsert({
    where: { slug: "pengumuman-aspirasi-mahasiswa" },
    update: {},
    create: {
      title: "Pengumuman Kanal Aspirasi Mahasiswa",
      slug: "pengumuman-aspirasi-mahasiswa",
      category: "PENGUMUMAN",
      content:
        "Mahasiswa FMIPA dapat menyampaikan aspirasi melalui kanal resmi Halo DPM yang tersedia di website.",
      isPublished: true,
      authorId: admin.id,
    },
  });

  if ((await prisma.regulation.count()) === 0) {
    await prisma.regulation.createMany({
      data: [
        {
          title: "Tata Tertib Sidang DPM",
          description: "Dokumen pedoman pelaksanaan sidang DPM FMIPA UNESA.",
          externalUrl: "https://drive.google.com",
        },
        {
          title: "Mekanisme Penyaluran Aspirasi",
          description: "Panduan alur aspirasi dari mahasiswa hingga tindak lanjut.",
          externalUrl: "https://drive.google.com",
        },
      ],
    });
  }

  if ((await prisma.agenda.count()) === 0) {
    await prisma.agenda.createMany({
      data: [
        {
          title: "Pengembangan WEB DPM",
          description: "Koordinasi pengembangan website resmi DPM FMIPA UNESA.",
          eventDate: new Date("2026-05-17T08:00:00+07:00"),
          status: "UPCOMING",
          location: "Zoom Meeting",
        },
        {
          title: "Kunjungan Kerja",
          description: "Agenda kunjungan kerja organisasi.",
          eventDate: new Date("2026-06-16T09:00:00+07:00"),
          status: "UPCOMING",
          location: "Auditorium D1 FMIPA UNESA",
        },
        {
          title: "Dialog Kebangsaan",
          description: "Forum dialog terbuka bersama mahasiswa.",
          eventDate: new Date("2026-06-27T09:00:00+07:00"),
          status: "UPCOMING",
          location: "Zoom Meeting",
        },
      ],
    });
  }

  if ((await prisma.gallery.count()) === 0) {
    await prisma.gallery.createMany({
      data: [
        {
          title: "Rapat Kerja",
          imageUrl:
            "https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1200&q=80",
          altText: "Pengurus DPM berdiskusi dalam rapat kerja.",
        },
        {
          title: "Forum Aspirasi",
          imageUrl:
            "https://images.unsplash.com/photo-1523580494863-6f3031224c94?auto=format&fit=crop&w=1200&q=80",
          altText: "Mahasiswa mengikuti forum aspirasi.",
        },
      ],
    });
  }

  console.log("Seed selesai. Login awal: admin@dpmfmipa.test / admin12345");
};

main()
  .catch((error) => {
    console.error(error);
    process.exit(1);
  })
  .finally(async () => {
    await prisma.$disconnect();
  });
