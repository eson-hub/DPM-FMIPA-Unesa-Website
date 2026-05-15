const prisma = require("../lib/prisma");
const httpError = require("../utils/httpError");

const listRegulations = async (req, res, next) => {
  try {
    const regulations = await prisma.regulation.findMany({
      orderBy: { createdAt: "desc" },
      take: req.query.limit ? Number(req.query.limit) : undefined,
    });

    res.json({ data: regulations });
  } catch (error) {
    next(error);
  }
};

const createRegulation = async (req, res, next) => {
  try {
    const { title, description, filePath, externalUrl } = req.body;
    if (!title) throw httpError(400, "Title wajib diisi.");

    const regulation = await prisma.regulation.create({
      data: { title, description, filePath, externalUrl },
    });

    res.status(201).json({ data: regulation });
  } catch (error) {
    next(error);
  }
};

const updateRegulation = async (req, res, next) => {
  try {
    const { title, description, filePath, externalUrl } = req.body;
    const regulation = await prisma.regulation.update({
      where: { id: Number(req.params.id) },
      data: { title, description, filePath, externalUrl },
    });

    res.json({ data: regulation });
  } catch (error) {
    next(error);
  }
};

const deleteRegulation = async (req, res, next) => {
  try {
    await prisma.regulation.delete({ where: { id: Number(req.params.id) } });
    res.status(204).send();
  } catch (error) {
    next(error);
  }
};

module.exports = {
  listRegulations,
  createRegulation,
  updateRegulation,
  deleteRegulation,
};
