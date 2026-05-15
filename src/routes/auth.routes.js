const router = require("express").Router();
const authMiddleware = require("../middleware/auth.middleware");
const requireRole = require("../middleware/role.middleware");
const { login, bootstrap, createAdmin, me } = require("../controllers/auth.controller");

router.post("/login", login);
router.post("/bootstrap", bootstrap);
router.get("/me", authMiddleware, me);
router.post("/admins", authMiddleware, requireRole("SUPER_ADMIN"), createAdmin);

module.exports = router;
