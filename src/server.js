const app = require("./app");
const prisma = require("./lib/prisma");

const port = process.env.PORT || 3000;

const server = app.listen(port, () => {
  console.log(`DPM FMIPA UNESA API running on http://localhost:${port}`);
});

const shutdown = async () => {
  await prisma.$disconnect();
  server.close(() => process.exit(0));
};

process.on("SIGINT", shutdown);
process.on("SIGTERM", shutdown);
