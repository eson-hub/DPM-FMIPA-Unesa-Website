const router = require("express").Router();
const authMiddleware = require("../middleware/auth.middleware");
const {
  listAgendas,
  createAgenda,
  updateAgenda,
  deleteAgenda,
} = require("../controllers/agenda.controller");

router.get("/", listAgendas);
router.post("/", authMiddleware, createAgenda);
router.put("/:id", authMiddleware, updateAgenda);
router.delete("/:id", authMiddleware, deleteAgenda);

module.exports = router;
