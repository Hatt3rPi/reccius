const express = require('express');
const app = express();
const port = 3000;

// Middleware para procesar JSON y formularios
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Requerir el módulo de conexión a la base de datos que has creado
// Asegúrate de que la ruta es correcta
const pool = require('./src/database/database'); 

// Rutas
const testRoute = require('./src/routers/router.test'); // Asegúrate de que este archivo exporte un router de Express

// Definir una ruta GET para probar la conexión a la base de datos
app.get('/testdb', (req, res) => {
  pool.getConnection((err, connection) => {
    if (err) {
      // Si hay un error, enviar un mensaje al cliente
      res.status(500).send('Error al conectar a la base de datos: ' + err.message);
    } else {
      // Si la conexión es exitosa, enviar un mensaje al cliente
      res.send('Conexión a la base de datos establecida con éxito.');
      connection.release(); // No olvides liberar la conexión
    }
  });
});

// Usar las rutas definidas en router.test.js
app.use('/api', testRoute);

// Iniciar el servidor Express
app.listen(port, () => {
  console.log(`Aplicación de ejemplo escuchando en el puerto ${port}`);
});
