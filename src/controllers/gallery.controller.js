const prisma = require("../lib/prisma");
const httpError = require("../utils/httpError");

const listGallery = async (req, res, next) => {
  try {
    const gallery = await prisma.gallery.findMany({
      orderBy: { createdAt: "desc" },
      take: req.query.limit ? Number(req.query.limit) : undefined,
    });

    res.json({ data: gallery });
  } catch (error) {
    next(error);
  }
};

const createGalleryItem = async (req, res, next) => {
  try {
    const { title, imageUrl, altText } = req.body;
    if (!imageUrl) throw httpError(400, "ImageUrl wajib diisi.");

    const item = await prisma.gallery.create({ data: { title, imageUrl, altText } });
    res.status(201).json({ data: item });
  } catch (error) {
    next(error);
  }
};

const updateGalleryItem = async (req, res, next) => {
  try {
    const { title, imageUrl, altText } = req.body;
    const item = await prisma.gallery.update({
      where: { id: Number(req.params.id) },
      data: { title, imageUrl, altText },
    });

    res.json({ data: item });
  } catch (error) {
    next(error);
  }
};

const deleteGalleryItem = async (req, res, next) => {
  try {
    await prisma.gallery.delete({ where: { id: Number(req.params.id) } });
    res.status(204).send();
  } catch (error) {
    next(error);
  }
};

module.exports = {
  listGallery,
  createGalleryItem,
  updateGalleryItem,
  deleteGalleryItem,
};
