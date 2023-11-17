const express = require('express');
const app = express();
const port = 3000;
const TestRuta = require("./src/routers/router.test")

// Middleware para procesar JSON y formularios
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Requerir el módulo de conexión a la base de datos que has creado
const pool = require('./src/database/database'); // Asegúrate de que la ruta es correcta

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

// Ruta original
app.get('/reccius', (req, res) => {
  res.send('Hello World!');
});

// Usar las rutas definidas en dataRoutes.js
app.use('/api', dataRoutes);



// Iniciar el servidor Express
app.listen(port, () => {
  console.log(`Aplicación de ejemplo escuchando en el puerto ${port}`);
});
