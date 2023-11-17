// controllers/dataController.js
const pool = require('../database/database');

exports.getData = (req, res) => {
  pool.query('SELECT * FROM nombre_de_tu_tabla', (error, results) => {
    if (error) {
      return res.status(500).json({ error });
    }
    res.json(results);
  });
};
