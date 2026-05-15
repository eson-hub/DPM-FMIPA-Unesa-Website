const router = require("express").Router();
const authMiddleware = require("../middleware/auth.middleware");
const {
  listRegulations,
  createRegulation,
  updateRegulation,
  deleteRegulation,
} = require("../controllers/regulation.controller");

router.get("/", listRegulations);
router.post("/", authMiddleware, createRegulation);
router.put("/:id", authMiddleware, updateRegulation);
router.delete("/:id", authMiddleware, deleteRegulation);

module.exports = router;
