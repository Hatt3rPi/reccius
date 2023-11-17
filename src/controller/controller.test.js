// controllers/dataController.js
const pool = require('../database/database');
const mysql = require("mysql")

// Ahora 'getData' es una constante que almacena la función
const getData = (req, res) => {
  pool.query('SELECT * FROM usuarios', (error, results) => {
    if (error) {
      return res.status(500).json({ error });
    }
    res.json(results);
  });
};

// Exportar la constante 'getData'
module.exports = { getData };