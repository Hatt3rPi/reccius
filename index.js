const  express = require('express')
const app = express()
const port = 3000

const http = require('http');

http.createServer(function (req, res) {
  res.writeHead(200, {'Content-Type': 'text/plain'});
  res.end('Hola Mundo');
}).listen(port);

console.log('Servidor ejecutándose en http://localhost:3000/');
