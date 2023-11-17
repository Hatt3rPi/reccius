const mysql = require('mysql');

const pool = mysql.createPool({
  connectionLimit: 10,
  host: process.env.HOST,
  user: process.env.DB_USER,
  password: process.env.DB_PASSWORD,
  database: process.env.DATABASE,
  // ...otros parámetros de configuración...
});

// Evento emitido cuando una conexión es establecida
pool.on('connection', (connection) => {
  console.log('Nueva conexión establecida, ID:', connection.threadId);
});

// Evento emitido cuando una conexión es adquirida del pool
pool.on('acquire', (connection) => {
  console.log('Conexión %d adquirida del pool', connection.threadId);
});

// Evento emitido cuando ocurre un error de conexión
pool.on('error', (error) => {
  console.error('Error de conexión en el pool:', error);
});

module.exports = pool;
