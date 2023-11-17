// routes/dataRoutes.js
const express = require('express');
const router = express.Router();
const {getData} = require('../controller/controller.test');

router.get('/datos', getData);

module.exports = router;
