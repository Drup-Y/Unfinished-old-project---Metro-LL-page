$(document).ready(function() {

  // Validación del formulario de inicio de sesión
  const formulario = document.querySelector('form');
  if (formulario) { // Verificar si el formulario existe en la página actual
    formulario.addEventListener('submit', function(event) {
      event.preventDefault();

      const nombreUsuario = document.getElementById('nombre_usuario').value;
      const contrasena = document.getElementById('contrasena').value;

      if (nombreUsuario === '' || contrasena === '') {
        alert('Por favor, completa todos los campos.');
        return;
      }

      this.submit();
    });
  }

  // Cargar contenido al hacer clic en los enlaces de navegación (excepto "Inicio")
  $("nav a[href^!='#']").on("click", function(event) {
    event.preventDefault();
    var href = $(this).attr("href");
    var sectionId = "contenido-" + href.substring(0, href.indexOf(".html"));

    $.ajax({
      url: href,
      success: function(data) {
        $("#contenido-secciones").html($("#" + sectionId, data).html());
      },
      complete: function() {
        // ... (código del slider) ... 
      }
    });
  });

  // Inicializar el slider de imágenes
  $(".image-slider").each(function() {
    var $slider = $(this);
    var $images = $slider.find("img");
    var currentIndex = 0;

    function showImage(index) {
      $images.hide();
      $images.eq(index).fadeIn();
    }

    function nextImage() {
      currentIndex = (currentIndex + 1) % $images.length;
      showImage(currentIndex);
    }

    function prevImage() {
      currentIndex = (currentIndex - 1 + $images.length) % $images.length;
      showImage(currentIndex);
    }

    $slider.append('<button class="prev-btn"></button><button class="next-btn"></button>');

    $slider.find(".prev-btn").on("click", function() {
      prevImage();
    });

    $slider.find(".next-btn").on("click", function() {
      nextImage();
    });

    showImage(currentIndex);
  });
});