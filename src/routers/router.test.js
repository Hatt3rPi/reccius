// routes/dataRoutes.js
const express = require('express');
const router = express.Router();
const dataController = require('../controller/controller.test');

router.get('/datos', dataController.getData);

module.exports = router;
