const prisma = require("../lib/prisma");
const httpError = require("../utils/httpError");
const toSlug = require("../utils/slug");

const articleSelect = {
  id: true,
  title: true,
  slug: true,
  category: true,
  thumbnail: true,
  content: true,
  isPublished: true,
  createdAt: true,
  updatedAt: true,
  author: { select: { id: true, name: true } },
};

const listArticles = async (req, res, next) => {
  try {
    const { category, published, limit } = req.query;
    const where = {};

    if (category) where.category = category.toUpperCase();
    if (published !== undefined) where.isPublished = published === "true";

    const articles = await prisma.article.findMany({
      where,
      select: articleSelect,
      orderBy: { createdAt: "desc" },
      take: limit ? Number(limit) : undefined,
    });

    res.json({ data: articles });
  } catch (error) {
    next(error);
  }
};

const getArticleBySlug = async (req, res, next) => {
  try {
    const article = await prisma.article.findUnique({
      where: { slug: req.params.slug },
      select: articleSelect,
    });

    if (!article) throw httpError(404, "Artikel tidak ditemukan.");
    res.json({ data: article });
  } catch (error) {
    next(error);
  }
};

const createArticle = async (req, res, next) => {
  try {
    const { title, slug, category, thumbnail, content, isPublished = false } = req.body;
    if (!title || !category || !content) {
      throw httpError(400, "Title, category, dan content wajib diisi.");
    }

    const article = await prisma.article.create({
      data: {
        title,
        slug: slug ? toSlug(slug) : toSlug(title),
        category: category.toUpperCase(),
        thumbnail,
        content,
        isPublished,
        authorId: req.user.id,
      },
      select: articleSelect,
    });

    res.status(201).json({ data: article });
  } catch (error) {
    next(error);
  }
};

const updateArticle = async (req, res, next) => {
  try {
    const { title, slug, category, thumbnail, content, isPublished } = req.body;
    const data = {};

    if (title !== undefined) data.title = title;
    if (slug !== undefined) data.slug = toSlug(slug);
    if (category !== undefined) data.category = category.toUpperCase();
    if (thumbnail !== undefined) data.thumbnail = thumbnail;
    if (content !== undefined) data.content = content;
    if (isPublished !== undefined) data.isPublished = isPublished;

    const article = await prisma.article.update({
      where: { id: Number(req.params.id) },
      data,
      select: articleSelect,
    });

    res.json({ data: article });
  } catch (error) {
    next(error);
  }
};

const deleteArticle = async (req, res, next) => {
  try {
    await prisma.article.delete({ where: { id: Number(req.params.id) } });
    res.status(204).send();
  } catch (error) {
    next(error);
  }
};

module.exports = {
  listArticles,
  getArticleBySlug,
  createArticle,
  updateArticle,
  deleteArticle,
};
