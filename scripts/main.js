const posicionesBase = ["Punta", "Opuesto", "Central", "Armador", "Libero"]
let typingTimer
let posicionesSeleccionadas = new Array(9).fill("") // Almacena las posiciones seleccionadas

// Función para obtener la ruta base del proyecto
function getBasePath() {
  const path = window.location.pathname
  const pathParts = path.split("/")
  const projectIndex = pathParts.findIndex((part) => part === "vit-gestor")

  if (projectIndex !== -1) {
    // En local (con carpeta vit-gestor)
    return "/" + pathParts.slice(1, projectIndex + 1).join("/")
  } else {
    // En InfinityFree (archivos en raíz)
    return ""
  }
}

// Función para actualizar los colores
function actualizarColores() {
  const curso = document.getElementById("curso").value
  if (!curso) return

  fetch(getBasePath() + "/php/colores_ajax.php?curso=" + curso)
    .then((res) => res.json())
    .then((data) => {
      const coloresDiv = document.getElementById("coloresUsados")

      if (!data || data.length === 0) {
        coloresDiv.innerHTML = "Aún no se ha usado ningún color"
        coloresDiv.classList.add("empty")
      } else {
        coloresDiv.classList.remove("empty")
        const ul = document.createElement("ul")
        data.forEach((color) => {
          const li = document.createElement("li")
          li.textContent = color
          ul.appendChild(li)
        })
        coloresDiv.innerHTML = "<p>Colores usados:</p>"
        coloresDiv.appendChild(ul)
      }
    })
    .catch(() => {
      const coloresDiv = document.getElementById("coloresUsados")
      coloresDiv.innerHTML = "Aún no se ha usado ningún color"
      coloresDiv.classList.add("empty")
    })
}

// Actualizar colores cada 10 segundos
setInterval(actualizarColores, 10000)

document.getElementById("curso").addEventListener("change", actualizarColores)

function mostrarDivision() {
  const curso = document.getElementById("curso").value
  const div = document.getElementById("division")
  div.innerHTML = '<option value="" selected disabled>Seleccionar</option>'
  let opciones = []
  if (["1ro", "2do", "3ro"].includes(curso)) opciones = ["A", "B", "C"]
  else opciones = ["1ra", "2da"]
  opciones.forEach((o) => {
    const opt = document.createElement("option")
    opt.value = o
    opt.textContent = o
    div.appendChild(opt)
  })
  document.getElementById("div2").classList.remove("dn")
  actualizarColores()
}

function mostrarNombreEquipo() {
  document.getElementById("div3").classList.remove("dn")
}

document.getElementById("nombre_equipo").addEventListener("input", () => {
  clearTimeout(typingTimer)
  typingTimer = setTimeout(() => {
    document.getElementById("div4").classList.remove("dn")
  }, 400)
})

document.getElementById("color_remera").addEventListener("input", () => {
  clearTimeout(typingTimer)
  typingTimer = setTimeout(() => {
    const colorValue = document.getElementById("color_remera").value
    if (colorValue && colorValue.length >= 4) {
      document.getElementById("codigoDiv").classList.remove("dn")
    }
  }, 400)
})

function obtenerPosicionesDisponibles(sistema, posicionesUsadas, ignorarPosicion = "") {
  let posicionesSistema = []
  if (sistema === "4:2") {
    posicionesSistema = [
      { nombre: "Armador", max: 2 },
      { nombre: "Central", max: 2 },
      { nombre: "Punta", max: 2 },
      { nombre: "Libero", max: 1 },
    ]
  } else if (sistema === "5:1") {
    posicionesSistema = [
      { nombre: "Armador", max: 1 },
      { nombre: "Opuesto", max: 1 },
      { nombre: "Libero", max: 1 },
      { nombre: "Central", max: 2 },
      { nombre: "Punta", max: 2 },
    ]
  }

  const posicionesDisponibles = []

  posicionesSistema.forEach((pos) => {
    // Contar cuántas de esta posición ya están usadas
    const usadas = posicionesUsadas.filter((p) => p.startsWith(pos.nombre)).length

    // Si es la posición que estamos ignorando, la contamos como no usada
    const ajusteIgnorada = ignorarPosicion && ignorarPosicion.startsWith(pos.nombre) ? 1 : 0

    // Determinar cuántas posiciones de este tipo quedan disponibles
    const disponibles = pos.max - (usadas - ajusteIgnorada)

    // Si es la posición actual o hay disponibles, agregarla
    if (pos.max === 1) {
      if (ignorarPosicion === pos.nombre || disponibles > 0) {
        posicionesDisponibles.push(pos.nombre)
      }
    } else {
      // Para posiciones múltiples
      for (let i = 1; i <= pos.max; i++) {
        const posicionNumerada = `${pos.nombre}`
        if (ignorarPosicion === posicionNumerada || disponibles >= i) {
          posicionesDisponibles.push(posicionNumerada)
        }
      }
    }
  })

  return posicionesDisponibles
}

function mostrarIntegrantes() {
  const sistema = document.getElementById("sistema_juego").value
  const tbody = document.getElementById("bodyIntegrantes")
  tbody.innerHTML = ""
  const cantidad = sistema === "6:0" ? 6 : 7
  const total = 8

  // Resetear posiciones al cambiar sistema
  posicionesSeleccionadas = new Array(9).fill("")

  for (let i = 1; i <= total; i++) {
    const tr = document.createElement("tr")
    const th = document.createElement("th")

    if (i === 1) {
      th.textContent = "Cap"
    } else {
      th.textContent = `#${i}`
    }

    const tdNombre = document.createElement("td")

    const inputWrapper = document.createElement("div")
    inputWrapper.style.position = "relative"

    const inp = document.createElement("input")
    inp.type = "text"
    inp.name = "integrante_" + i
    inp.placeholder = i === 1 ? "Nombre del capitán" : `Nombre del integrante ${i}`
    inp.pattern = "[A-Za-záéíóúÁÉÍÓÚñÑ ]+"
    inp.title =
      i === 1
        ? "Ingrese el nombre del capitán. Solo se permiten letras A-Z o a-z"
        : `Ingrese el nombre del integrante ${i}. Solo se permiten letras A-Z o a-z`
    inp.maxLength = 100
    inp.minLength = 4
    inp.required = i <= cantidad

    if (i <= cantidad) {
      const iconImg = document.createElement("img")
      iconImg.src = getBasePath() + "/assets/img/icons/info.svg"
      iconImg.alt = "Campo obligatorio"
      iconImg.className = "required-icon-absolute"
      iconImg.loading = "lazy"
      iconImg.decoding = "async"
      inputWrapper.appendChild(iconImg)
    }

    inputWrapper.appendChild(inp)
    tdNombre.appendChild(inputWrapper)

    const tdPos = document.createElement("td")
    if (sistema === "6:0" && i <= cantidad) {
      const posDiv = document.createElement("div")
      posDiv.className = "position-disabled"
      const iconImg = document.createElement("img")
      iconImg.src = getBasePath() + "/assets/img/icons/circle-slash.svg"
      iconImg.alt = "6:0 no lleva posiciones"
      iconImg.title = "6:0 no lleva posiciones"
      iconImg.loading = "lazy"
      iconImg.decoding = "async"
      posDiv.appendChild(iconImg)
      tdPos.appendChild(posDiv)
    } else if (i > cantidad) {
      // Players beyond the required amount are substitutes
      const posDiv = document.createElement("div")
      posDiv.className = "position-disabled"
      const iconImg = document.createElement("img")
      iconImg.src = getBasePath() + "/assets/img/icons/refresh-ccw.svg"
      iconImg.alt = "Suplente"
      iconImg.title = "Suplente"
      iconImg.loading = "lazy"
      iconImg.decoding = "async"
      posDiv.appendChild(iconImg)
      tdPos.appendChild(posDiv)
    } else if (i <= cantidad) {
      const sel = document.createElement("select")
      sel.name = "posicion_" + i
      sel.required = true
      sel.id = "posicion_" + i
      const def = document.createElement("option")
      def.value = ""
      def.textContent = "Seleccionar Posición"
      def.selected = !posicionesSeleccionadas[i]
      def.disabled = true
      sel.appendChild(def)

      // Contar posiciones ya seleccionadas
      const posicionesUsadas = posicionesSeleccionadas.filter((p) => p !== "")

      // Obtener posiciones disponibles, ignorando la posición actual si está seleccionada
      const posicionesDisponibles = obtenerPosicionesDisponibles(sistema, posicionesUsadas, posicionesSeleccionadas[i])

      posicionesDisponibles.forEach((p) => {
        const opt = document.createElement("option")
        opt.value = p
        opt.textContent = p
        opt.selected = posicionesSeleccionadas[i] === p
        sel.appendChild(opt)
      })

      sel.addEventListener("change", (e) => {
        const valorSeleccionado = e.target.value
        posicionesSeleccionadas[i] = valorSeleccionado
        actualizarSelects()
      })
      tdPos.appendChild(sel)
    }
    tr.appendChild(th)
    tr.appendChild(tdNombre)
    tr.appendChild(tdPos)
    tbody.appendChild(tr)
  }
  document.getElementById("tablaIntegrantes").classList.remove("dn")
  document.getElementById("colorDiv").classList.remove("dn")
  actualizarColores()
  document.getElementById("btnSubmit").classList.remove("dn")
}

function actualizarSelects() {
  const sistema = document.getElementById("sistema_juego").value
  if (!sistema || sistema === "6:0") return

  const cantidad = sistema === "6:0" ? 6 : 7
  const posicionesUsadas = posicionesSeleccionadas.filter((p) => p !== "")

  for (let i = 1; i <= cantidad; i++) {
    const sel = document.getElementById("posicion_" + i)
    if (!sel) continue

    const valorActual = sel.value
    // Guardar todas las opciones actuales excepto la primera (que es el placeholder)
    const opcionesActuales = Array.from(sel.options)
      .slice(1)
      .map((opt) => opt.value)

    // Obtener posiciones disponibles considerando la posición actual
    // Obtener las posiciones disponibles
    const posicionesSistema = obtenerPosicionesDisponibles(sistema, posicionesUsadas, valorActual)

    // Solo actualizar si las opciones han cambiado
    const opcionesNuevas = posicionesSistema.sort()
    if (JSON.stringify(opcionesActuales.sort()) !== JSON.stringify(opcionesNuevas)) {
      // Mantener solo la opción por defecto
      sel.innerHTML = ""
      const def = document.createElement("option")
      def.value = ""
      def.textContent = "Seleccionar Posición"
      def.selected = !valorActual
      def.disabled = true
      sel.appendChild(def)

      // Agregar las nuevas opciones
      posicionesSistema.forEach((p) => {
        const opt = document.createElement("option")
        opt.value = p
        opt.textContent = p
        opt.selected = p === valorActual
        sel.appendChild(opt)
      })
    }

    // Actualizar opciones si han cambiado
    if (JSON.stringify(opcionesActuales) !== JSON.stringify(posicionesSistema)) {
      const valorPrevio = sel.value

      // Mantener solo la opción por defecto
      sel.innerHTML = ""
      const def = document.createElement("option")
      def.value = ""
      def.textContent = "Seleccionar Posición"
      def.selected = !valorPrevio
      def.disabled = true
      sel.appendChild(def)

      // Agregar nuevas opciones
      posicionesSistema.forEach((p) => {
        const opt = document.createElement("option")
        opt.value = p
        opt.textContent = p
        opt.selected = p === valorPrevio
        sel.appendChild(opt)
      })
    }
  }
}

document.getElementById("formEquipo").addEventListener("submit", (e) => {
  // Allow form to submit normally, but clear history after
  setTimeout(() => {
    // Clear form data from browser memory
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href)
    }
  }, 100)
})
