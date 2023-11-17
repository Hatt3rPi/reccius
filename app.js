const express = require('express');
const app = express();
const port = 3000;
const testRouter = require("./src/routers/router.test")
const dataRoutes = require('./src/routers/router.base'); // Asegúrate de que la ruta al archivo es correcta

// Middleware para procesar JSON y formularios
app.use(express.json());
app.use(express.urlencoded({ extended: true }));


//

const apitest = testRouter;
// Middleware para utilizar las rutas definidas en userRoutes.js
app.use('/api', apitest);



// Usar las rutas definidas en routes.js
app.use(dataRoutes);

// Ruta '/reccius/node.js'
app.get('/reccius/node.js', (req, res) => {
  res.send('Esta es la ruta para /reccius/node.js');
});

// Ruta '/reccius'
app.get('/reccius', (req, res) => {
  res.send('Hello World!');
});

// Iniciar el servidor Express
app.listen(port, () => {
  console.log(`Aplicación escuchando en el puerto ${port}`);
});
