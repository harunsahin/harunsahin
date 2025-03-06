const express = require('express');
const router = express.Router();
const jwt = require('jsonwebtoken');

router.post('/login', async (req, res) => {
  const { email, password, remember } = req.body;

  try {
    // Kullanıcı doğrulama işlemleri burada yapılacak
    // Örnek:
    if (email === 'admin@example.com' && password === 'password123') {
      const token = jwt.sign(
        { userId: 1, email },
        process.env.JWT_SECRET,
        { expiresIn: remember ? '7d' : '24h' }
      );

      res.json({
        success: true,
        token,
        user: { id: 1, email }
      });
    } else {
      res.status(401).json({
        success: false,
        message: 'Geçersiz email veya şifre'
      });
    }
  } catch (error) {
    res.status(500).json({
      success: false,
      message: 'Sunucu hatası'
    });
  }
});

module.exports = router; 