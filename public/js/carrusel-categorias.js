document.addEventListener("DOMContentLoaded", () => {
  const contenedorCirculos = document.getElementById("contenedorCirculosCategorias")
  const flechaIzquierda = document.getElementById("flechaIzquierdaCategorias")
  const flechaDerecha = document.getElementById("flechaDerechaCategorias")

  // Verificar que los elementos existen
  if (!contenedorCirculos || !flechaIzquierda || !flechaDerecha) {
    return
  }

  // Configuración del carrusel
  const desplazamientoPorClick = 300 // Píxeles a desplazar por click
  let posicionActual = 0

  // Función para actualizar el estado de las flechas
  function actualizarEstadoFlechas() {
    const scrollMaximo = contenedorCirculos.scrollWidth - contenedorCirculos.clientWidth

    // Flecha izquierda
    if (posicionActual <= 0) {
      flechaIzquierda.disabled = true
      flechaIzquierda.style.opacity = "0.5"
    } else {
      flechaIzquierda.disabled = false
      flechaIzquierda.style.opacity = "1"
    }

    // Flecha derecha
    if (posicionActual >= scrollMaximo) {
      flechaDerecha.disabled = true
      flechaDerecha.style.opacity = "0.5"
    } else {
      flechaDerecha.disabled = false
      flechaDerecha.style.opacity = "1"
    }
  }

  // Función para desplazar hacia la izquierda
  function desplazarIzquierda() {
    posicionActual = Math.max(0, posicionActual - desplazamientoPorClick)
    contenedorCirculos.scrollTo({
      left: posicionActual,
      behavior: "smooth",
    })
    setTimeout(actualizarEstadoFlechas, 300)
  }

  // Función para desplazar hacia la derecha
  function desplazarDerecha() {
    const scrollMaximo = contenedorCirculos.scrollWidth - contenedorCirculos.clientWidth
    posicionActual = Math.min(scrollMaximo, posicionActual + desplazamientoPorClick)
    contenedorCirculos.scrollTo({
      left: posicionActual,
      behavior: "smooth",
    })
    setTimeout(actualizarEstadoFlechas, 300)
  }

  // Event listeners para las flechas
  flechaIzquierda.addEventListener("click", desplazarIzquierda)
  flechaDerecha.addEventListener("click", desplazarDerecha)

  // Soporte para navegación con teclado (solo cuando el carrusel está en foco)
  contenedorCirculos.addEventListener("keydown", (e) => {
    if (e.key === "ArrowLeft") {
      e.preventDefault()
      desplazarIzquierda()
    } else if (e.key === "ArrowRight") {
      e.preventDefault()
      desplazarDerecha()
    }
  })

  // Soporte para touch/swipe en móviles
  let inicioTouchX = 0
  let finTouchX = 0

  contenedorCirculos.addEventListener("touchstart", (e) => {
    inicioTouchX = e.changedTouches[0].screenX
  })

  contenedorCirculos.addEventListener("touchend", (e) => {
    finTouchX = e.changedTouches[0].screenX
    manejarSwipe()
  })

  function manejarSwipe() {
    const diferenciaSwipe = inicioTouchX - finTouchX
    const umbralSwipe = 50 // Mínimo desplazamiento para activar swipe

    if (Math.abs(diferenciaSwipe) > umbralSwipe) {
      if (diferenciaSwipe > 0) {
        // Swipe hacia la izquierda (mostrar siguiente)
        desplazarDerecha()
      } else {
        // Swipe hacia la derecha (mostrar anterior)
        desplazarIzquierda()
      }
    }
  }

  // Actualizar estado inicial
  actualizarEstadoFlechas()

  // Actualizar estado cuando se redimensiona la ventana
  window.addEventListener("resize", () => {
    setTimeout(actualizarEstadoFlechas, 100)
  })

  // Detectar scroll manual y actualizar posición
  contenedorCirculos.addEventListener("scroll", () => {
    posicionActual = contenedorCirculos.scrollLeft
    actualizarEstadoFlechas()
  })
})
