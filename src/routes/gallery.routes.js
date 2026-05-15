const router = require("express").Router();
const authMiddleware = require("../middleware/auth.middleware");
const {
  listGallery,
  createGalleryItem,
  updateGalleryItem,
  deleteGalleryItem,
} = require("../controllers/gallery.controller");

router.get("/", listGallery);
router.post("/", authMiddleware, createGalleryItem);
router.put("/:id", authMiddleware, updateGalleryItem);
router.delete("/:id", authMiddleware, deleteGalleryItem);

module.exports = router;
