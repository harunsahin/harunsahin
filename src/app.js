const express = require('express');
const session = require('express-session');
const cookieParser = require('cookie-parser');
const authRoutes = require('./routes/auth');
const authMiddleware = require('./middleware/auth');

const app = express();

app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(cookieParser());
app.use(session({
  secret: process.env.SESSION_SECRET,
  resave: false,
  saveUninitialized: false,
  cookie: { secure: process.env.NODE_ENV === 'production' }
}));

// Statik dosyalar
app.use(express.static('public'));

// Auth routes
app.use('/api/auth', authRoutes);

// Protected routes
app.use('/dashboard', authMiddleware, (req, res) => {
  res.sendFile(path.join(__dirname, 'views', 'dashboard.html'));
});

// Login sayfası
app.get('/login', (req, res) => {
  res.sendFile(path.join(__dirname, 'views', 'login.html'));
}); 