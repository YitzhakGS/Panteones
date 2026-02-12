<!-- TODO NUESTRO ESTILO VA AQUI  -->

<style>
 :root {
   
   --color-base: {{ config('SCITS.color-base') }};
   --color-primario: {{ config('SCITS.color-primario') }};
   --color-secundario: {{ config('SCITS.color-secundario') }};
   --color-complemento: {{ config('SCITS.color-complemento') }};
   --colorFuenteB: {{ config('SCITS.colorFuenteB') }};
   --colorFuenteN: {{ config('SCITS.colorFuenteN') }};
   --tamanio-texto: {{ config('SCITS.tamanio-texto') }};
   --fuente: {{ config('SCITS.fuente') }};
 }

        .bg-primario{
            background-color: var(--color-primario);
            color: var(--colorFuenteB);
            transition: background-color 0.3s ease, color 0.3s ease; /* Suaviza la transición */
        } 

        .bg-primario:hover {
            background-color: var(--color-base); /* Cambia el color de fondo al pasar el cursor */
            color: var(--colorFuenteB); /* Cambia el color del texto al pasar el cursor */
        }


        .bg-secundario{
            background-color: var(--color-secundario);
            color: var(--colorFuenteB);
            transition: background-color 0.3s ease, color 0.3s ease; /* Suaviza la transición */
        }

        .bg-secundario:hover {
            background-color: var(--color-complemento); /* Cambia el color de fondo al pasar el cursor */
            color: var(--colorFuenteN); /* Cambia el color del texto al pasar el cursor */
        }

        .bg-base{
            background-color: var(--color-base);
            color: var(--colorFuenteB);
            transition: background-color 0.3s ease, color 0.3s ease; /* Suaviza la transición */
        }

        .bg-base:hover {
            background-color: var(--color-primario); /* Cambia el color de fondo al pasar el cursor */
            color: var(--colorFuenteB); /* Cambia el color del texto al pasar el cursor */
        }


  .bg-secundario{
        background-color: var(--color-secundario);
        color: var(--colorFuenteB);
        transition: background-color 0.3s ease, color 0.3s ease; /* Suaviza la transición */

    }
    .bg-secundario:hover {
    background-color: var(--color-complemento); /* Cambia el color de fondo al pasar el cursor */
    color: var(--colorFuenteN); /* Cambia el color del texto al pasar el cursor */
}

.bg-base{
        background-color: var(--color-base);
        color: var(--colorFuenteB);
        transition: background-color 0.3s ease, color 0.3s ease; /* Suaviza la transición */

    }
    .bg-base:hover {
    background-color: var(--color-primario); /* Cambia el color de fondo al pasar el cursor */
    color: var(--colorFuenteB); /* Cambia el color del texto al pasar el cursor */
}

.bg-complemento-simple{
        background-color: var(--color-complemento);
        /* Color alterno para filas pares */
    } 
    
</style>
