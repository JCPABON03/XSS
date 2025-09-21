// Pequeña mejora: prevenir doble envío del formulario
document.addEventListener('DOMContentLoaded',()=> {
  const form = document.querySelector('.comment-form form');
  if (!form) return;
  form.addEventListener('submit', e => {
    const btn = form.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.textContent = 'Enviando...';
  });
});
