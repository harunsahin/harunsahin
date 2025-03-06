document.getElementById('loginForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  
  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;
  const remember = document.getElementById('remember').checked;

  try {
    const response = await fetch('/api/auth/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ email, password, remember })
    });

    const data = await response.json();

    if (response.ok) {
      // Başarılı giriş
      localStorage.setItem('token', data.token);
      window.location.href = '/dashboard';
    } else {
      // Hata durumu
      alert(data.message || 'Giriş başarısız');
    }
  } catch (error) {
    console.error('Giriş hatası:', error);
    alert('Bir hata oluştu, lütfen tekrar deneyin.');
  }
}); 