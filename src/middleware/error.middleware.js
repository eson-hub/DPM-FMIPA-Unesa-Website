const notFound = (req, res, next) => {
  const error = new Error(`Route ${req.originalUrl} tidak ditemukan.`);
  error.statusCode = 404;
  next(error);
};

const errorHandler = (error, req, res, next) => {
  const statusCode = error.statusCode || 500;

  res.status(statusCode).json({
    message: error.message || "Terjadi kesalahan server.",
  });
};

module.exports = { notFound, errorHandler };
