const bcrypt = require("bcryptjs");
const jwt = require("jsonwebtoken");
const prisma = require("../lib/prisma");
const httpError = require("../utils/httpError");

const signToken = (user) =>
  jwt.sign(
    { sub: user.id, email: user.email, role: user.role },
    process.env.JWT_SECRET,
    { expiresIn: process.env.JWT_EXPIRES_IN || "1d" },
  );

const login = async (req, res, next) => {
  try {
    const { email, password } = req.body;

    if (!email || !password) {
      throw httpError(400, "Email dan password wajib diisi.");
    }

    const user = await prisma.user.findUnique({ where: { email } });
    if (!user) {
      throw httpError(401, "Email atau password salah.");
    }

    const validPassword = await bcrypt.compare(password, user.password);
    if (!validPassword) {
      throw httpError(401, "Email atau password salah.");
    }

    const { password: _password, ...safeUser } = user;
    res.json({ token: signToken(user), user: safeUser });
  } catch (error) {
    next(error);
  }
};

const bootstrap = async (req, res, next) => {
  try {
    const userCount = await prisma.user.count();
    if (userCount > 0) {
      throw httpError(403, "Bootstrap hanya bisa dipakai saat user masih kosong.");
    }

    const { name, email, password } = req.body;
    if (!name || !email || !password) {
      throw httpError(400, "Name, email, dan password wajib diisi.");
    }

    const hashedPassword = await bcrypt.hash(password, 12);
    const user = await prisma.user.create({
      data: { name, email, password: hashedPassword, role: "SUPER_ADMIN" },
      select: { id: true, name: true, email: true, role: true, createdAt: true },
    });

    res.status(201).json({ user });
  } catch (error) {
    next(error);
  }
};

const createAdmin = async (req, res, next) => {
  try {
    const { name, email, password, role = "ADMIN" } = req.body;
    if (!name || !email || !password) {
      throw httpError(400, "Name, email, dan password wajib diisi.");
    }

    const hashedPassword = await bcrypt.hash(password, 12);
    const user = await prisma.user.create({
      data: { name, email, password: hashedPassword, role },
      select: { id: true, name: true, email: true, role: true, createdAt: true },
    });

    res.status(201).json({ user });
  } catch (error) {
    next(error);
  }
};

const me = async (req, res) => {
  res.json({ user: req.user });
};

module.exports = { login, bootstrap, createAdmin, me };
