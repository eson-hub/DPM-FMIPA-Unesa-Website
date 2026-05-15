const router = require("express").Router();
const authMiddleware = require("../middleware/auth.middleware");
const {
  listArticles,
  getArticleBySlug,
  createArticle,
  updateArticle,
  deleteArticle,
} = require("../controllers/article.controller");

router.get("/", listArticles);
router.get("/:slug", getArticleBySlug);
router.post("/", authMiddleware, createArticle);
router.put("/:id", authMiddleware, updateArticle);
router.delete("/:id", authMiddleware, deleteArticle);

module.exports = router;
