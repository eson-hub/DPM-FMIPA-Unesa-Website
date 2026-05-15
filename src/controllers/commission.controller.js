const prisma = require("../lib/prisma");
const httpError = require("../utils/httpError");

const listCommissions = async (req, res, next) => {
  try {
    const commissions = await prisma.commission.findMany({
      include: { members: true },
      orderBy: { id: "asc" },
    });

    res.json({ data: commissions });
  } catch (error) {
    next(error);
  }
};

const createCommission = async (req, res, next) => {
  try {
    const { name, description, tasks } = req.body;
    if (!name) throw httpError(400, "Name wajib diisi.");

    const commission = await prisma.commission.create({
      data: { name, description, tasks },
    });

    res.status(201).json({ data: commission });
  } catch (error) {
    next(error);
  }
};

const updateCommission = async (req, res, next) => {
  try {
    const { name, description, tasks } = req.body;
    const commission = await prisma.commission.update({
      where: { id: Number(req.params.id) },
      data: { name, description, tasks },
    });

    res.json({ data: commission });
  } catch (error) {
    next(error);
  }
};

const deleteCommission = async (req, res, next) => {
  try {
    await prisma.commission.delete({ where: { id: Number(req.params.id) } });
    res.status(204).send();
  } catch (error) {
    next(error);
  }
};

module.exports = {
  listCommissions,
  createCommission,
  updateCommission,
  deleteCommission,
};
