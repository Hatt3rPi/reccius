const express = require('express');
const app = express();
const port = 3000;

// Requerir el módulo de conexión a la base de datos que has creado
const pool = require('./src/database/database'); // Asegúrate de que la ruta es correcta

// Probar la conexión a la base de datos al iniciar la aplicación
pool.getConnection((err, connection) => {
  if (err) {
    console.error('Error conectando a la base de datos:', err);
    return;
  }
  console.log('Conexión a la base de datos establecida con éxito.');
  connection.release(); // No olvides liberar la conexión
});

// Definir una ruta GET para la raíz de tu aplicación
app.get('/reccius', (req, res) => {
  res.send('Hello World!');
});

// Iniciar el servidor Express
app.listen(port, () => {
  console.log(`Aplicación de ejemplo escuchando en el puerto ${port}`);
});
