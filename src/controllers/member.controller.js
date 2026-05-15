const prisma = require("../lib/prisma");
const httpError = require("../utils/httpError");

const listMembers = async (req, res, next) => {
  try {
    const { isBph } = req.query;
    const members = await prisma.member.findMany({
      where: isBph !== undefined ? { isBph: isBph === "true" } : undefined,
      include: { commission: true },
      orderBy: { id: "asc" },
    });

    res.json({ data: members });
  } catch (error) {
    next(error);
  }
};

const createMember = async (req, res, next) => {
  try {
    const { name, position, photo, commissionId, isBph = false } = req.body;
    if (!name || !position) throw httpError(400, "Name dan position wajib diisi.");

    const member = await prisma.member.create({
      data: {
        name,
        position,
        photo,
        commissionId: commissionId ? Number(commissionId) : null,
        isBph,
      },
    });

    res.status(201).json({ data: member });
  } catch (error) {
    next(error);
  }
};

const updateMember = async (req, res, next) => {
  try {
    const { name, position, photo, commissionId, isBph } = req.body;
    const member = await prisma.member.update({
      where: { id: Number(req.params.id) },
      data: {
        name,
        position,
        photo,
        commissionId: commissionId === undefined ? undefined : Number(commissionId),
        isBph,
      },
    });

    res.json({ data: member });
  } catch (error) {
    next(error);
  }
};

const deleteMember = async (req, res, next) => {
  try {
    await prisma.member.delete({ where: { id: Number(req.params.id) } });
    res.status(204).send();
  } catch (error) {
    next(error);
  }
};

module.exports = { listMembers, createMember, updateMember, deleteMember };
