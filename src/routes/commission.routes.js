const router = require("express").Router();
const authMiddleware = require("../middleware/auth.middleware");
const {
  listCommissions,
  createCommission,
  updateCommission,
  deleteCommission,
} = require("../controllers/commission.controller");

router.get("/", listCommissions);
router.post("/", authMiddleware, createCommission);
router.put("/:id", authMiddleware, updateCommission);
router.delete("/:id", authMiddleware, deleteCommission);

module.exports = router;
