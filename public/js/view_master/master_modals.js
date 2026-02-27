/**
 * Master JavaScript Handler
 * Centraliza la lógica de interacción de UI para evitar redundancia en las vistas Blade.
 */
document.addEventListener('DOMContentLoaded', function () {
    console.log('JS MAESTRO CARGADO (Modales, Buscador, Paginación)');

    /* =========================================================
       CONFIGURACIÓN GENERAL
    ========================================================= */
    // Atributos de datos (data-*) que el script debe ignorar al inyectar valores.
    // Se omiten los atributos nativos de Bootstrap y la ruta del formulario.
    const DATA_BLACKLIST = ['bsTarget', 'bsToggle', 'action'];

    /* =========================================================
       1. MANEJADOR UNIVERSAL DE MODALES (SHOW / EDIT)
    ========================================================= */
    // Escucha el evento global de Bootstrap que se dispara justo antes de mostrar un modal.
    document.addEventListener('show.bs.modal', function (event) {

        // 'relatedTarget' es el elemento HTML (generalmente un botón) que disparó el modal.
        const button = event.relatedTarget;
        if (!button || !button.dataset) return; // Si no hay botón o atributos de datos, detiene la ejecución.

        // 'target' es el elemento HTML del modal que se está abriendo.
        const modal = event.target;

        /* -----------------------------------------
           A) ACTION DEL FORMULARIO (EDIT)
        ----------------------------------------- */
        // Si el botón tiene 'data-action', busca un formulario dentro del modal y actualiza su atributo 'action'.
        if (button.dataset.action) {
            const form = modal.querySelector('form');
            if (form) {
                form.action = button.dataset.action;
            }
        }

        /* -----------------------------------------
           B) PREPARAR BOTÓN "SALTAR A EDITAR" (TITULARES)
        ----------------------------------------- */
        // Busca si dentro del modal actual existe un botón para ir al modo edición.
        const btnJumpEdit = modal.querySelector('.btn-jump-edit');
        if (btnJumpEdit) {
            // Copia todos los atributos 'data-*' del botón original hacia este nuevo botón,
            // omitiendo los que están en la lista negra.
            Object.keys(button.dataset).forEach(key => {
                if (!DATA_BLACKLIST.includes(key)) {
                    btnJumpEdit.dataset[key] = button.dataset[key];
                }
            });
        }

        /* -----------------------------------------
           C) INYECCIÓN DE DATOS (INPUTS, TEXTOS, LISTAS)
        ----------------------------------------- */
        // Recorre cada atributo 'data-*' del botón original.
        Object.keys(button.dataset).forEach(key => {

            // Ignora las llaves en la lista negra para no sobrescribir configuraciones del DOM.
            if (DATA_BLACKLIST.includes(key)) return;

            let value = button.dataset[key];

            /* 1. INPUTS / SELECT / TEXTAREA (Modales de Edición) */
            // Busca un campo de formulario cuyo atributo 'name' coincida con la llave actual.
            const input = modal.querySelector(`[name="${key}"]`);
            if (input) {
                input.value = value ?? ''; // Asigna el valor o una cadena vacía si es nulo.
            }

            /* 2. TEXTO ESTÁTICO (Modales Show) */
            // Busca una etiqueta HTML que contenga la clase '.show-llave'.
            const textEl = modal.querySelector(`.show-${key}`);
            if (textEl) {
                textEl.textContent = value || '—'; // Inserta el texto o un guión si está vacío.
            }

            /* 3. LISTAS DESDE JSON (Relaciones complejas como Cuadrillas) */
            // Busca un contenedor (como <ul>) que tenga la clase '.list-llave'.
            const listEl = modal.querySelector(`.list-${key}`);
            if (listEl) {
                try {
                    // Intenta convertir el valor string en un arreglo JSON.
                    const array = JSON.parse(value);
                    // Construye dinámicamente elementos <li> o muestra un mensaje por defecto.
                    listEl.innerHTML = array.length
                        ? array.map(item => `<li class="list-group-item">${item.nombre}</li>`).join('')
                        : '<li class="list-group-item text-muted">Sin datos asignados</li>';
                } catch (error) {
                    // Evita que un JSON malformado rompa la ejecución del script.
                    console.warn(`Advertencia: JSON inválido para data-${key}`);
                }
            }
        });
    });

    /* =========================================================
       2. LÓGICA DEL BOTÓN "IR A EDITAR" (SHOW -> EDIT)
    ========================================================= */
    // Escucha clics en todo el documento para delegar la acción del botón interno de edición.
    document.addEventListener('click', function (e) {

        // Verifica si el clic ocurrió dentro o sobre un elemento '.btn-jump-edit'.
        const btn = e.target.closest('.btn-jump-edit');
        if (!btn) return;

        const id = btn.dataset.id;
        const editModalId = btn.dataset.editTarget; // Requiere definir data-edit-target="#idDelModal" en el HTML.

        if (!id || !editModalId) return;

        /* Cerrar el modal actual (Show) */
        const currentModalEl = btn.closest('.modal');
        if (currentModalEl) {
            // Obtiene la instancia de Bootstrap del modal actual y la oculta.
            const currentModal = bootstrap.Modal.getInstance(currentModalEl);
            currentModal?.hide();
        }

        /* Abrir el modal destino (Edit) */
        // Busca el modal de edición por su ID en el DOM y lo muestra usando Bootstrap API.
        const editModalEl = document.getElementById(editModalId);
        if (!editModalEl) return;

        new bootstrap.Modal(editModalEl).show();
    });

    /* =========================================================
       3. SCROLL DE PAGINACIÓN UNIVERSAL
    ========================================================= */
    // Detecta clics en los enlaces de paginación de Laravel/Bootstrap.
    document.addEventListener('click', function (e) {
        if (e.target.closest('.pagination a')) {
            // Busca el contenedor de las tarjetas y lo desplaza suavemente hacia arriba.
            const container = document.querySelector('.cards-scroll-container');
            container?.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    /* =========================================================
       4. BUSCADOR EN VIVO (GENÉRICO)
    ========================================================= */
    // Inicializa todos los inputs que tengan la clase '.global-search'.
    document.querySelectorAll('.global-search').forEach(input => {
        input.addEventListener('keyup', function () {
            // Captura el texto ingresado y lo normaliza a minúsculas.
            const value = this.value.toLowerCase();
            // Determina qué tarjetas filtrar basándose en 'data-target' o usa un valor por defecto.
            const targetClass = this.dataset.target || '.searchable-card';

            // Itera sobre todos los elementos objetivo y evalúa si contienen el texto de búsqueda.
            document.querySelectorAll(targetClass).forEach(card => {
                // Si el texto interno de la tarjeta incluye la búsqueda, remueve el display: none, si no, lo aplica.
                card.style.display = card.innerText.toLowerCase().includes(value)
                    ? ''
                    : 'none';
            });
        });
    });

});