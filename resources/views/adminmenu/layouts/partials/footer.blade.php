<div class="container-fluid">
<footer class="pie-pagina">
    <div class="grupo-1">
        <div class="box">
            <figure>
				<img src="{{ asset('https://innovaciongubernamental.tulancingo.gob.mx/logo/logo2.png') }}" srcset="{{ asset('https://innovaciongubernamental.tulancingo.gob.mx/logos/logo2.png')}}" alt="Logo de SLee Dw">
            </figure>
        </div>
        <div class="box">
            <h2><a href="https://tulancingo.gob.mx/" target="_blank" class="link-blanco">Transformando Tulancingo</a></h2>
        </div>
        <div class="box">
            <h2>Dirección</h2>
            <p>Boulevard Nuevo San Nicolás, S/N, Fracc. Nuevo San Nicolás, 43640 Tulancingo, Hgo.</p>
        </div>
        <div class="box">
            <h2><a href="https://tulancingo.gob.mx/aviso-de-privacidad/" target="_blank" class="link-blanco">Aviso de Privacidad</a></h2>
        </div>
    </div>
    <div class="grupo-2">
        <small>&copy; 2024 <b>Transformando Tulancingo</b>. Todos los derechos reservados.</small><br>
        <small><i class="bi bi-code-slash"></i> <b>Desarrollado por: </b></small>
        <small>  Federico León Jiménez, </small>
        <small>  Jennifer Stephanie Huayatla Avendaño, </small>
        <small>  Adrian Santos Saavedra, </small>
        <small>  Daniel Garcia Loma.</small>
       
    </div>
</footer>
</div>

<style>
 @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Open Sans', sans-serif;
}

/*:::: Pie de Página */
.pie-pagina {
    width: 100%;
    background-color: var(--color-base); /* Color más neutro y profesional */
    color: var(--colorFuenteB); /* Texto en blanco */
}

.pie-pagina .grupo-1 {
    width: 100%;
    max-width: 1200px;
    margin: auto;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    padding: 30px 15px; /* Espaciado para un diseño más aireado */
}

.pie-pagina .grupo-1 .box {
    flex: 1;
    min-width: 200px;
    padding: 0 10px;
}

.pie-pagina .grupo-1 .box figure {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 15px;
}

.pie-pagina .grupo-1 .box figure img {
    width: 100px; /* Imagen más pequeña */
    height: auto;
}

.pie-pagina .grupo-1 .box h2 {
    color: var(--colorFuenteB);
    margin-bottom: 10px;
    font-size: 18px;
    text-align: center;
}

.pie-pagina .grupo-1 .box p {
    color: var(--colorFuenteB);
    font-size: 14px;
    line-height: 1.6;
    text-align: center;
}

.pie-pagina .grupo-2 {
    background-color:var(--color-secundario); /* Color más oscuro para contraste */
    padding: 15px;
    text-align: center;
    color: var(--colorFuenteB); 
    font-size: 13px;
}

.pie-pagina .grupo-2 small {
    font-size: 14px;
}

/* Enlaces */
.link-blanco {
    color: #ffffff;
    text-decoration: none;
    font-weight: 700;
    transition: color 0.3s ease;
}

.link-blanco:hover {
    color: #b79159; /* Color dorado al pasar el cursor */
}

/* Diseño Responsive */
@media screen and (max-width:800px) {
    .pie-pagina .grupo-1 {
        flex-direction: column;
        align-items: center;
        padding: 20px;
    }

    .pie-pagina .grupo-1 .box {
        margin-bottom: 20px;
    }
}


</style>