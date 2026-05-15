const prisma = require("../lib/prisma");
const httpError = require("../utils/httpError");

const listAgendas = async (req, res, next) => {
  try {
    const { status, limit } = req.query;
    const agendas = await prisma.agenda.findMany({
      where: status ? { status: status.toUpperCase() } : undefined,
      orderBy: { eventDate: "asc" },
      take: limit ? Number(limit) : undefined,
    });

    res.json({ data: agendas });
  } catch (error) {
    next(error);
  }
};

const createAgenda = async (req, res, next) => {
  try {
    const { title, description, eventDate, status = "UPCOMING", location } = req.body;
    if (!title || !eventDate) throw httpError(400, "Title dan eventDate wajib diisi.");

    const agenda = await prisma.agenda.create({
      data: {
        title,
        description,
        eventDate: new Date(eventDate),
        status: status.toUpperCase(),
        location,
      },
    });

    res.status(201).json({ data: agenda });
  } catch (error) {
    next(error);
  }
};

const updateAgenda = async (req, res, next) => {
  try {
    const { title, description, eventDate, status, location } = req.body;
    const data = {};

    if (title !== undefined) data.title = title;
    if (description !== undefined) data.description = description;
    if (eventDate !== undefined) data.eventDate = new Date(eventDate);
    if (status !== undefined) data.status = status.toUpperCase();
    if (location !== undefined) data.location = location;

    const agenda = await prisma.agenda.update({
      where: { id: Number(req.params.id) },
      data,
    });

    res.json({ data: agenda });
  } catch (error) {
    next(error);
  }
};

const deleteAgenda = async (req, res, next) => {
  try {
    await prisma.agenda.delete({ where: { id: Number(req.params.id) } });
    res.status(204).send();
  } catch (error) {
    next(error);
  }
};

module.exports = { listAgendas, createAgenda, updateAgenda, deleteAgenda };
