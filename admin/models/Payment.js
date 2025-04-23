const mongoose = require('mongoose');

const paymentSchema = new mongoose.Schema({
    studentId: {
        type: String,
        required: true
    },
    studentName: {
        type: String,
        required: true
    },
    paymentDate: {
        type: Date,
        required: true
    },
    course: {
        type: String,
        required: true
    },
    email: String,
    phone: String,
    paymentType: {
        type: String,
        required: true,
        enum: ['tuition', 'exam', 'registration', 'library', 'other']
    },
    amount: {
        type: Number,
        required: true,
        min: 0
    },
    description: String,
    paymentMethod: {
        type: String,
        required: true,
        enum: ['cash', 'card', 'bank', 'online']
    },
    paymentDetails: {
        cash: {
            received: Number,
            change: Number
        },
        card: {
            name: String,
            number: String,
            expiry: String,
            cvv: String
        },
        bank: {
            bankName: String,
            transactionId: String,
            transferDate: Date
        },
        online: {
            platform: String,
            reference: String
        }
    },
    receiptNumber: {
        type: String,
        required: true,
        unique: true
    },
    status: {
        type: String,
        default: 'pending',
        enum: ['pending', 'completed', 'failed']
    },
    createdAt: {
        type: Date,
        default: Date.now
    }
});

module.exports = mongoose.model('Payment', paymentSchema); 