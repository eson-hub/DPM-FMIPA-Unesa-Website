const httpError = require("../utils/httpError");

const requireRole = (...roles) => (req, res, next) => {
  if (!req.user || !roles.includes(req.user.role)) {
    return next(httpError(403, "Akses ditolak."));
  }

  next();
};

module.exports = requireRole;
