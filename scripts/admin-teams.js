const posicionesBase = ["Punta", "Opuesto", "Central", "Armador", "Libero"]

function obtenerPosicionesDisponibles(sistema, tipoCuatroDos, posicionesUsadas, ignorarPosicion = "") {
  let posicionesSistema = []

  if (sistema === "4:2") {
    if (tipoCuatroDos === "c") {
      // 4:2 con Centrales (armadores)
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
    // Contar cuántas de esta posición ya están usadas
    const usadas = posicionesUsadas.filter((p) => p === pos.nombre).length

    // Si es la posición que estamos ignorando, la contamos como no usada
    const ajusteIgnorada = ignorarPosicion === pos.nombre ? 1 : 0

    // Determinar cuántas posiciones de este tipo quedan disponibles
    const disponibles = pos.max - (usadas - ajusteIgnorada)

    // Si es la posición actual o hay disponibles, agregarla
    if (disponibles > 0 || ignorarPosicion === pos.nombre) {
      posicionesDisponibles.push(pos.nombre)
    }
  })

  return posicionesDisponibles
}

function actualizarSelectsPosiciones(form) {
  const sistema = form.querySelector('select[name="sistema_juego"]').value
  const tipoCuatroDos = form.querySelector('select[name="tipo_cuatro_dos"]')?.value || null

  if (!sistema || sistema === "6:0") return

  const rows = form.querySelectorAll(".edit-members-table tbody tr")

  let minIntegrantes = 6
  if (sistema === "4:2") {
    minIntegrantes = tipoCuatroDos === "o" ? 6 : 7
  } else if (sistema === "5:1") {
    minIntegrantes = 7
  }

  // Collect currently selected positions from required players only
  const posicionesUsadas = []
  rows.forEach((row, index) => {
    if (index < minIntegrantes) {
      const select = row.querySelector(".edit-posicion")
      if (select && select.value) {
        posicionesUsadas.push(select.value)
      }
    }
  })

  // Update each position select
  rows.forEach((row, index) => {
    const posicionCell = row.querySelector("td:nth-child(3)")
    if (!posicionCell) return

    if (index >= minIntegrantes) {
      posicionCell.innerHTML = '<span class="text-muted">Suplente</span>'
      return
    }

    const existingSelect = posicionCell.querySelector("select")
    if (!existingSelect) return

    const valorActual = existingSelect.value

    // Get available positions for this select
    const posicionesDisponibles = obtenerPosicionesDisponibles(sistema, tipoCuatroDos, posicionesUsadas, valorActual)

    // Rebuild select options
    existingSelect.innerHTML = `<option value="">Seleccionar</option>`

    posicionesDisponibles.forEach((pos) => {
      const opt = document.createElement("option")
      opt.value = pos
      opt.textContent = pos
      if (pos === valorActual) {
        opt.selected = true
      }
      existingSelect.appendChild(opt)
    })
  })
}

function toggleEditMode(teamId) {
  const teamCard = document.getElementById("team-" + teamId)
  const viewMode = teamCard.querySelector(".team-view-mode")
  const editMode = teamCard.querySelector(".team-edit-mode")

  viewMode.classList.add("dn")
  editMode.classList.remove("dn")
}

function cancelEdit(teamId) {
  const teamCard = document.getElementById("team-" + teamId)
  const viewMode = teamCard.querySelector(".team-view-mode")
  const editMode = teamCard.querySelector(".team-edit-mode")

  editMode.classList.add("dn")
  viewMode.classList.remove("dn")
}

function handleSistemaChange(select, teamId) {
  const form = select.closest("form")
  const sistema = select.value
  const tipoFieldContainer = form.querySelector(".tipo-cuatro-dos-field")
  const tbody = form.querySelector(".edit-members-table tbody")
  const rows = tbody.querySelectorAll("tr")

  if (sistema === "4:2") {
    if (!tipoFieldContainer) {
      const newField = document.createElement("div")
      newField.className = "flex-col tipo-cuatro-dos-field"
      newField.innerHTML = `
                <label>Tipo de 4:2</label>
                <select name="tipo_cuatro_dos" required onchange="handleTipoCuatroDosChange(this)">
                    <option value="">Seleccionar</option>
                    <option value="c">Con Centrales</option>
                    <option value="o">Con Opuestos</option>
                </select>
            `
      select.closest(".flex-col").after(newField)
    }
  } else {
    if (tipoFieldContainer) {
      tipoFieldContainer.remove()
    }
  }

  let minIntegrantes = 6
  if (sistema === "4:2") {
    const tipoCuatroDos = form.querySelector('select[name="tipo_cuatro_dos"]')?.value
    minIntegrantes = tipoCuatroDos === "o" ? 6 : 7
  } else if (sistema === "5:1") {
    minIntegrantes = 7
  }

  rows.forEach((row, index) => {
    const posicionCell = row.querySelector("td:nth-child(3)")
    if (!posicionCell) return

    if (sistema === "6:0") {
      if (index < 6) {
        posicionCell.innerHTML = '<span class="text-muted">N/A</span>'
      } else {
        posicionCell.innerHTML = '<span class="text-muted">Suplente</span>'
      }
    } else if (index >= minIntegrantes) {
      posicionCell.innerHTML = '<span class="text-muted">Suplente</span>'
    } else {
      const existingSelect = posicionCell.querySelector("select")
      if (!existingSelect) {
        const select = document.createElement("select")
        select.className = "edit-posicion"
        select.required = true
        select.innerHTML = `<option value="">Seleccionar</option>`
        select.addEventListener("change", () => actualizarSelectsPosiciones(form))
        posicionCell.innerHTML = ""
        posicionCell.appendChild(select)
      }
    }
  })

  // Update position options
  actualizarSelectsPosiciones(form)
}

function handleTipoCuatroDosChange(select) {
  const form = select.closest("form")
  actualizarSelectsPosiciones(form)
}

function addIntegranteRow(teamId) {
  const teamCard = document.getElementById("team-" + teamId)
  const tbody = teamCard.querySelector(".edit-members-table tbody")
  const currentRows = tbody.querySelectorAll("tr").length
  const form = teamCard.querySelector(".edit-team-form")
  const sistema = form.querySelector('select[name="sistema_juego"]').value
  const tipoCuatroDos = form.querySelector('select[name="tipo_cuatro_dos"]')?.value

  if (currentRows >= 8) {
    alert("Máximo 8 integrantes por equipo")
    return
  }

  let minIntegrantes = 6
  if (sistema === "4:2") {
    minIntegrantes = tipoCuatroDos === "o" ? 6 : 7
  } else if (sistema === "5:1") {
    minIntegrantes = 7
  }

  const newRow = document.createElement("tr")
  newRow.setAttribute("data-integrante-id", "new")

  let posicionHTML = ""
  if (sistema === "6:0") {
    posicionHTML = '<span class="text-muted">N/A</span>'
  } else if (currentRows >= minIntegrantes) {
    posicionHTML = '<span class="text-muted">Suplente</span>'
  } else {
    posicionHTML = `
            <select class="edit-posicion" required onchange="actualizarSelectsPosiciones(this.closest('form'))">
                <option value="">Seleccionar</option>
            </select>
        `
  }

  newRow.innerHTML = `
        <td>${currentRows + 1}</td>
        <td>
            <input type="text" value="" class="edit-nombre" placeholder="Nombre del integrante" required>
        </td>
        <td>${posicionHTML}</td>
        <td>
            <button type="button" class="btn-icon" onclick="this.closest('tr').remove(); renumberRows(${teamId})" title="Eliminar">
                🗑️
            </button>
        </td>
    `
  tbody.appendChild(newRow)

  // Update position options for all selects
  actualizarSelectsPosiciones(form)
}

function renumberRows(teamId) {
  const teamCard = document.getElementById("team-" + teamId)
  const tbody = teamCard.querySelector(".edit-members-table tbody")
  const rows = tbody.querySelectorAll("tr")
  rows.forEach((r, index) => {
    r.querySelector("td:first-child").textContent = index + 1
  })

  const form = teamCard.querySelector(".edit-team-form")
  actualizarSelectsPosiciones(form)
}

function removeIntegrante(integranteId, teamId) {
  if (!confirm("¿Está seguro de eliminar este integrante?")) {
    return
  }

  const teamCard = document.getElementById("team-" + teamId)
  const row = teamCard.querySelector(`tr[data-integrante-id="${integranteId}"]`)

  if (row) {
    row.remove()
    renumberRows(teamId)
  }
}

function saveTeamChanges(teamId) {
  const teamCard = document.getElementById("team-" + teamId)
  const form = teamCard.querySelector(".edit-team-form")

  // Collect form data
  const formData = new FormData(form)

  // Collect integrantes data
  const integrantes = []
  const rows = form.querySelectorAll(".edit-members-table tbody tr")

  const sistema = formData.get("sistema_juego")
  const tipoCuatroDos = formData.get("tipo_cuatro_dos")
  let minIntegrantes = 6

  if (sistema === "4:2") {
    minIntegrantes = tipoCuatroDos === "o" ? 6 : 7
  } else if (sistema === "5:1") {
    minIntegrantes = 7
  }

  rows.forEach((row, index) => {
    const nombreInput = row.querySelector(".edit-nombre")
    const posicionSelect = row.querySelector(".edit-posicion")

    if (!nombreInput || !nombreInput.value.trim()) {
      return // Skip empty rows
    }

    const nombre = nombreInput.value.trim()
    const posicion = sistema !== "6:0" && index < minIntegrantes && posicionSelect ? posicionSelect.value : null

    integrantes.push({
      nombre: nombre,
      posicion: posicion,
      orden: index + 1,
    })
  })

  if (integrantes.length < minIntegrantes) {
    alert(`El sistema ${sistema} requiere al menos ${minIntegrantes} integrantes`)
    return
  }

  if (sistema !== "6:0") {
    for (let i = 0; i < minIntegrantes; i++) {
      if (!integrantes[i].posicion) {
        alert("Todos los jugadores titulares deben tener una posición asignada")
        return
      }
    }
  }

  formData.append("integrantes", JSON.stringify(integrantes))
  formData.append("team_id", teamId)

  fetch("/php/update_team.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      const contentType = response.headers.get("content-type")
      if (!contentType || !contentType.includes("application/json")) {
        throw new Error("La respuesta del servidor no es JSON válido")
      }
      return response.json()
    })
    .then((data) => {
      if (data.success) {
        alert("Equipo actualizado correctamente")
        location.reload()
      } else {
        alert("Error al actualizar: " + data.message)
      }
    })
    .catch((error) => {
      console.error("[v0] Error saving team:", error)
      alert("Error al guardar los cambios: " + error.message)
    })
}
