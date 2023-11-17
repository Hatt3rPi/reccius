const express = require('express');
const app = express();
const port = 3000;

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
