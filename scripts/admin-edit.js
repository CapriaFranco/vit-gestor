// Admin team edit page JavaScript
// Handles dynamic position selects based on rotation system

let posicionesSeleccionadas = new Array(9).fill("")

function getBasePath() {
  const path = window.location.pathname
  const pathParts = path.split("/")
  const projectIndex = pathParts.findIndex((part) => part === "vit-gestor")

  if (projectIndex !== -1) {
    return "/" + pathParts.slice(1, projectIndex + 1).join("/")
  } else {
    return ""
  }
}

function obtenerPosicionesDisponibles(sistema, tipoCuatroDos, posicionesUsadas, ignorarPosicion = "") {
  let posicionesSistema = []

  if (sistema === "4:2") {
    if (tipoCuatroDos === "c") {
      // 4:2 con Centrales
      posicionesSistema = [
        { nombre: "Armador", max: 2 },
        { nombre: "Central", max: 2 },
        { nombre: "Punta", max: 2 },
        { nombre: "Libero", max: 1 },
      ]
    } else if (tipoCuatroDos === "o") {
      // 4:2 con Opuestos
      posicionesSistema = [
        { nombre: "Armador", max: 2 },
        { nombre: "Opuesto", max: 2 },
        { nombre: "Punta", max: 2 },
      ]
    }
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
    const usadas = posicionesUsadas.filter((p) => p && p.startsWith(pos.nombre)).length
    const ajusteIgnorada = ignorarPosicion && ignorarPosicion.startsWith(pos.nombre) ? 1 : 0
    const disponibles = pos.max - (usadas - ajusteIgnorada)

    if (pos.max === 1) {
      if (ignorarPosicion === pos.nombre || disponibles > 0) {
        posicionesDisponibles.push(pos.nombre)
      }
    } else {
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

function actualizarDivision() {
  const curso = document.getElementById("curso").value
  const divisionSelect = document.getElementById("division")
  const isBasico = ["1ro", "2do", "3ro"].includes(curso)

  divisionSelect.innerHTML = isBasico
    ? '<option value="A">A</option><option value="B">B</option><option value="C">C</option>'
    : '<option value="1ra">1ra</option><option value="2da">2da</option>'
}

function actualizarSistema() {
  const sistema = document.getElementById("sistema_juego").value
  const tipoDiv = document.getElementById("divTipoCuatroDos")

  if (sistema === "4:2") {
    if (!tipoDiv) {
      const newDiv = document.createElement("div")
      newDiv.id = "divTipoCuatroDos"
      newDiv.className = "flex-col"
      newDiv.innerHTML = `
                <label>
                    <img src="${getBasePath()}/assets/img/icons/iteration-cw.svg" alt="" class="label-icon" loading="lazy" decoding="async">
                    Tipo de 4:2
                    <abbr title="Campo obligatorio">*</abbr>
                </label>
                <select id="tipo_cuatro_dos" name="tipo_cuatro_dos" required onchange="regenerarPosiciones()">
                    <option value="c">4:2 con Centrales</option>
                    <option value="o">4:2 con Opuestos</option>
                </select>
            `
      document.getElementById("sistema_juego").closest(".flex-col").after(newDiv)
    }
  } else {
    if (tipoDiv) {
      tipoDiv.remove()
    }
  }

  regenerarPosiciones()
}

function regenerarPosiciones() {
  const sistema = document.getElementById("sistema_juego").value
  const tbody = document.getElementById("bodyIntegrantes")
  const rows = tbody.querySelectorAll("tr")

  let cantidadObligatoria
  if (sistema === "6:0") {
    cantidadObligatoria = 6
  } else if (sistema === "4:2") {
    const tipoCuatroDos = document.getElementById("tipo_cuatro_dos")?.value || "c"
    cantidadObligatoria = tipoCuatroDos === "o" ? 6 : 7
  } else {
    // 5:1
    cantidadObligatoria = 7
  }

  // Reset selected positions
  posicionesSeleccionadas = new Array(9).fill("")

  rows.forEach((row, index) => {
    const posCell = row.cells[2]
    const select = posCell.querySelector("select")
    if (select && select.value) {
      posicionesSeleccionadas[index + 1] = select.value
    }
  })

  rows.forEach((row, index) => {
    const posCell = row.cells[2]
    const numero = index + 1
    const nombreInput = row.cells[1].querySelector("input")
    const tieneNombre = nombreInput && nombreInput.value.trim() !== ""

    if (sistema === "6:0") {
      if (numero <= cantidadObligatoria) {
        posCell.innerHTML = `<div class="position-disabled"><img src="${getBasePath()}/assets/img/icons/circle-slash.svg" alt="Sin posición" title="6:0 no lleva posiciones" loading="lazy" decoding="async"></div>`
      } else {
        posCell.innerHTML = `<div class="position-disabled"><img src="${getBasePath()}/assets/img/icons/refresh-ccw.svg" alt="Suplente" title="Suplente" loading="lazy" decoding="async"></div>`
      }
    } else if (numero > cantidadObligatoria) {
      posCell.innerHTML = `<div class="position-disabled"><img src="${getBasePath()}/assets/img/icons/refresh-ccw.svg" alt="Suplente" title="Suplente" loading="lazy" decoding="async"></div>`
    } else {
      const tipoCuatroDos = sistema === "4:2" ? document.getElementById("tipo_cuatro_dos")?.value || "c" : null
      const posicionesUsadas = posicionesSeleccionadas.filter((p) => p !== "")
      const posicionActual = posicionesSeleccionadas[numero]

      const posicionesDisponibles = obtenerPosicionesDisponibles(
        sistema,
        tipoCuatroDos,
        posicionesUsadas,
        posicionActual,
      )

      const selectHTML = `
                <select name="posicion_${numero}" id="posicion_${numero}" required onchange="actualizarPosicionSeleccionada(${numero}, this.value)">
                    <option value="" ${!posicionActual ? "selected" : ""}>Seleccionar</option>
                    ${posicionesDisponibles
                      .map(
                        (pos) => `<option value="${pos}" ${posicionActual === pos ? "selected" : ""}>${pos}</option>`,
                      )
                      .join("")}
                </select>
            `
      posCell.innerHTML = selectHTML
    }
  })
}

function actualizarPosicionSeleccionada(numero, valor) {
  posicionesSeleccionadas[numero] = valor
  regenerarPosiciones()
}

// Initialize on page load
document.addEventListener("DOMContentLoaded", () => {
  const sistema = document.getElementById("sistema_juego").value
  const tbody = document.getElementById("bodyIntegrantes")
  const rows = tbody.querySelectorAll("tr")

  rows.forEach((row, index) => {
    const posCell = row.cells[2]
    const select = posCell.querySelector("select")
    if (select && select.value) {
      posicionesSeleccionadas[index + 1] = select.value
    }
  })

  // Apply initial position logic
  regenerarPosiciones()
})
