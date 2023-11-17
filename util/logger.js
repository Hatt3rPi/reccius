const fs = require('fs');
const path = require('path');
const logFilePath = path.join(__dirname, 'logs.csv'); // Define la ruta del archivo de logs

// Función para registrar logs en un archivo CSV
function logToFile(message) {
  // Obtener la fecha y hora actual
  const timestamp = new Date().toISOString();
  // Crear un registro en formato CSV
  const logEntry = `${timestamp},"${message.replace(/"/g, '""')}"\n`;
  // Escribir el log en el archivo, si no existe se crea
  fs.appendFileSync(logFilePath, logEntry);
}

// ... aquí va el resto de tu configuración de Express y conexión a la base de datos ...

// Definir una ruta GET para probar la conexión a la base de datos
app.get('/testdb', (req, res) => {
  pool.getConnection((err, connection) => {
    if (err) {
      const errorMessage = 'Error al conectar a la base de datos: ' + err.message;
      // Registrar el error en el archivo de logs
      logToFile(errorMessage);
      // Si hay un error, enviar un mensaje al cliente
      res.status(500).send(errorMessage);
    } else {
      const successMessage = 'Conexión a la base de datos establecida con éxito.';
      // Registrar el éxito en el archivo de logs
      logToFile(successMessage);
      // Si la conexión es exitosa, enviar un mensaje al cliente
      res.send(successMessage);
      connection.release(); // No olvides liberar la conexión
    }
  });
});
