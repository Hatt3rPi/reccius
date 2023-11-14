const express = require('express');
const mysql = require('mysql');

const app = express();
const PORT = process.env.PORT || 3001 ; 

app.listen(PORT, () =>{
    console.log(`El servidor está escuchando en el puerto ${PORT}`)
})