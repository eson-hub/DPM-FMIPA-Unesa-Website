require("dotenv").config();

const express = require("express");
const cors = require("cors");
const helmet = require("helmet");
const morgan = require("morgan");

const authRoutes = require("./routes/auth.routes");
const articleRoutes = require("./routes/article.routes");
const agendaRoutes = require("./routes/agenda.routes");
const regulationRoutes = require("./routes/regulation.routes");
const galleryRoutes = require("./routes/gallery.routes");
const commissionRoutes = require("./routes/commission.routes");
const memberRoutes = require("./routes/member.routes");
const { notFound, errorHandler } = require("./middleware/error.middleware");

const app = express();
const corsOrigin = process.env.CORS_ORIGIN
  ? process.env.CORS_ORIGIN.split(",").map((origin) => origin.trim())
  : "*";

app.use(helmet());
app.use(cors({ origin: corsOrigin }));
app.use(express.json({ limit: "2mb" }));
app.use(morgan("dev"));

app.get("/api/health", (req, res) => {
  res.json({ status: "ok", service: "dpm-fmipa-unesa-api" });
});

app.use("/api/auth", authRoutes);
app.use("/api/articles", articleRoutes);
app.use("/api/agendas", agendaRoutes);
app.use("/api/regulations", regulationRoutes);
app.use("/api/gallery", galleryRoutes);
app.use("/api/commissions", commissionRoutes);
app.use("/api/members", memberRoutes);

app.use(notFound);
app.use(errorHandler);

module.exports = app;
