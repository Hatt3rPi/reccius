const express = require('express');
const router = express.Router();
const pool = require('../database/database'); // Asegúrate de que la ruta al archivo es correcta

console.log("Hola mundo")


// Definir una ruta GET que consulta la base de datos y muestra los resultados
router.get('/mostrar-datos', (req, res) => {
  // Reemplaza 'tu_tabla' con el nombre real de tu tabla
  pool.query('SELECT * FROM usuarios', (error, results) => {
    if (error) {
      // Si hay un error, enviar un mensaje al cliente
      return res.status(500).send('Error al realizar la consulta: ' + error.message);
    } 
    // Si todo va bien, enviar los resultados como JSON
    res.json(results);
  });
});

module.exports = router;
