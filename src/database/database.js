// database.js
const mysql = require('mysql');

const pool = mysql.createPool({
  connectionLimit: 10, // El límite de conexiones que deseas manejar
  host: process.env.HOST, // Sustituye con tu host real proporcionado por cPanel
  user: process.env.DB_USER, // Usa una variable de entorno personalizada para el usuario de la base de datos
  password: process.env.DB_PASSWORD, // Usa una variable de entorno personalizada para la contraseña
  database: process.env.DATABASE, // Sustituye con el nombre de tu base de datos
  // Asegúrate de agregar cualquier otro parámetro de configuración que necesites.
});

module.exports = pool;
