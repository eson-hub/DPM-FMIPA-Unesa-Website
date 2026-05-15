const router = require("express").Router();
const authMiddleware = require("../middleware/auth.middleware");
const {
  listMembers,
  createMember,
  updateMember,
  deleteMember,
} = require("../controllers/member.controller");

router.get("/", listMembers);
router.post("/", authMiddleware, createMember);
router.put("/:id", authMiddleware, updateMember);
router.delete("/:id", authMiddleware, deleteMember);

module.exports = router;
