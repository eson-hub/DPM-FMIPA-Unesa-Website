const jwt = require("jsonwebtoken");
const prisma = require("../lib/prisma");
const httpError = require("../utils/httpError");

const authMiddleware = async (req, res, next) => {
  try {
    const header = req.headers.authorization;
    const token = header && header.startsWith("Bearer ") ? header.slice(7) : null;

    if (!token) {
      throw httpError(401, "Token autentikasi wajib dikirim.");
    }

    const payload = jwt.verify(token, process.env.JWT_SECRET);
    const user = await prisma.user.findUnique({
      where: { id: payload.sub },
      select: { id: true, name: true, email: true, role: true },
    });

    if (!user) {
      throw httpError(401, "User tidak ditemukan.");
    }

    req.user = user;
    next();
  } catch (error) {
    next(error.statusCode ? error : httpError(401, "Token tidak valid."));
  }
};

module.exports = authMiddleware;
