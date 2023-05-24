// Selección de elementos HTML
const empleadoLink = document.querySelector('.empleado-link');
const administradorLink = document.querySelector('.administrador-link');
const empleadoForm = document.querySelector('.empleado-form');
const administradorForm = document.querySelector('.administrador-form');

// Mostrar formulario de empleado por defecto
empleadoForm.style.display = 'block';
administradorForm.style.display = 'none';

// Cambiar entre formularios al hacer clic en los enlaces
empleadoLink.addEventListener('click', function () {
  empleadoForm.style.display = 'block';
  administradorForm.style.display = 'none';
  empleadoLink.classList.add('active');
  administradorLink.classList.remove('active');
});

administradorLink.addEventListener('click', function () {
  empleadoForm.style.display = 'none';
  administradorForm.style.display = 'block';
  administradorLink.classList.add('active');
  empleadoLink.classList.remove('active');
});


