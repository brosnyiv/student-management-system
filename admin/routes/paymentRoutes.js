const express = require('express');
const router = express.Router();
const Payment = require('../models/Payment');

// Generate a unique receipt number
function generateReceiptNumber() {
    const date = new Date();
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
    return `MI-${year}${month}${day}-${random}`;
}

// Create a new payment
router.post('/', async (req, res) => {
    try {
        const paymentData = req.body;
        paymentData.receiptNumber = generateReceiptNumber();
        
        const payment = new Payment(paymentData);
        await payment.save();
        
        res.status(201).json({
            message: 'Payment processed successfully',
            payment: payment
        });
    } catch (error) {
        res.status(400).json({ message: error.message });
    }
});

// Get all payments
router.get('/', async (req, res) => {
    try {
        const payments = await Payment.find().sort({ createdAt: -1 });
        res.json(payments);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
});

// Get payment by receipt number
router.get('/:receiptNumber', async (req, res) => {
    try {
        const payment = await Payment.findOne({ receiptNumber: req.params.receiptNumber });
        if (!payment) {
            return res.status(404).json({ message: 'Payment not found' });
        }
        res.json(payment);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
});

// Get payments by student ID
router.get('/student/:studentId', async (req, res) => {
    try {
        const payments = await Payment.find({ studentId: req.params.studentId })
            .sort({ paymentDate: -1 });
        res.json(payments);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
});

// Update payment status
router.patch('/:receiptNumber/status', async (req, res) => {
    try {
        const payment = await Payment.findOne({ receiptNumber: req.params.receiptNumber });
        if (!payment) {
            return res.status(404).json({ message: 'Payment not found' });
        }
        
        payment.status = req.body.status;
        await payment.save();
        
        res.json({
            message: 'Payment status updated successfully',
            payment: payment
        });
    } catch (error) {
        res.status(400).json({ message: error.message });
    }
});

module.exports = router; 