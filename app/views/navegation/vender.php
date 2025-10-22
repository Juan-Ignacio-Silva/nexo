<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #E1E9EF;
            color: #334155;
            line-height: 1.6;
        }

        .seccion-venta {
            min-height: calc(100vh - 120px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
        }

        .contenedor-venta {
            text-align: center;
            max-width: 1000px;
            width: 100%;
            background: white;
            padding: 4rem 3rem;
            border-radius: 8px;
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .titulo-venta {
            font-size: 3.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .subtitulo-venta {
            font-size: 1.375rem;
            color: #64748b;
            margin-bottom: 3rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .boton-venta {
            background-color: #1976d2;
            color: white;
            border: none;
            padding: 15px 32px;
            border-radius: 8px;
            font-size: 20px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            overflow: hidden;
        }

        .modal.activo {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .contenido-modal {
            background-color: #2c5aa0;
            padding: 2rem;
            border-radius: 8px;
            max-width: 400px;
            width: 90%;
            position: relative;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .cerrar-modal {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #eef8ff;
        }

        .cerrar-modal:hover {
            color: #1e293b;
        }

        .titulo-modal {
            font-size: 1.5rem;
            font-weight: 700;
            color: #eef8ff;
            margin-bottom: 10px;
            text-align: center;
        }

        .texto-modal {
            color: #eef8ff;
            margin-bottom: 20px;
            text-align: center;
        }

        .botones-modal {
            display: flex;
            gap: 1rem;
            flex-direction: column;
        }

        .boton-modal {
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            text-decoration: none;
            text-align: center;
        }

        .boton-modal.primario {
            background-color: #1976d2;
            color: white;
        }

        .boton-modal.secundario {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .modalForm {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            overflow: hidden;
        }

        .modalForm.activo {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .contenido-modal-form{
            background-color: #2c5aa0;
            padding: 2rem;
            border-radius: 8px;
            width: 70%;
            height: auto;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            
        }

        .contenido-modal-form::-webkit-scrollbar {
            display: none;
        }

        .contenido-modal-form form {
            display: flex;
            flex-direction: column;
        }

        .contenido-modal-form form label {
            color: #eef8ff;
        }

        .contenido-modal-form form input {
            border-radius: 4px;
            padding: 5px;
            border: 1px solid #ccc;
            margin-bottom: 5px;
        }

        .contenido-modal-form form p {
            font-size: 15px;
            color: #eef8ff;
        }

        @media (max-width: 768px) {
            .seccion-venta {
                padding: 2rem 1rem;
            }

            .contenedor-venta {
                padding: 3rem 2rem;
            }

            .titulo-venta {
                font-size: 2.5rem;
            }

            .subtitulo-venta {
                font-size: 1.125rem;
            }
        }
    </style>
</head>

<body>
    <section class="seccion-venta">
        <div class="contenedor-venta">
            <h1 class="titulo-venta">¿Quieres vender?</h1>
            <p class="subtitulo-venta">Únete a nuestra plataforma y comienza a vender tus productos hoy mismo. Es fácil, rápido y seguro.</p>
            <button class="boton-venta" id="btn-venta">Vender Ahora</button>
        </div>
    </section>

    <div id="modalAuth" class="modal">
        <div class="contenido-modal">
            <button class="cerrar-modal" onclick="cerrarModal('modalAuth')">&times;</button>
            <h2 class="titulo-modal">Cuenta Requerida</h2>
            <p class="texto-modal">Para realizar esta acción necesitas tener una cuenta en nuestra plataforma. Puedes iniciar sesión o crear una nueva cuenta.</p>
            <div class="botones-modal">
                <a href="<?= BASE_URL ?>usuario/login" class="boton-modal primario">Iniciar Sesión</a>
                <a href="<?= BASE_URL ?>usuario/registro" class="boton-modal secundario">Crear Cuenta</a>
            </div>
        </div>
    </div>

    <div id="modalFormSeller" class="modalForm">
        <div class="contenido-modal-form">
            <button class="cerrar-modal" onclick="cerrarModal('modalFormSeller')">&times;</button>
            <h2 class="titulo-modal">Registrarme como vendedor</h2>
            <p class="texto-modal">Completa este formulario para poder registrarte como vendedor</p>
            <form action="">
                <label for="">Nombre de la tienda</label>
                <input type="text" id="nombreTienda">
                <label for="">RUT de la empresa (Opcional)</label>
                <input type="text" id="rutEmpresa">
                <label for="">Descripcipón</label>
                <input name="" id="descripcion">
            </form>
            <div class="botones-modal">
                <a href="<?= BASE_URL ?>usuario/registro" class="boton-modal primario">Registrarme</a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("btn-venta").addEventListener("click", () => {
            fetch("<?= BASE_URL ?>Auth/verificarSession", {
                    method: "POST"
            })
            .then(res => res.json())
            .then(data => {
                if (data.ok) {
                    document.getElementById('modalFormSeller').classList.add('activo');

                } else {
                    document.getElementById('modalAuth').classList.add('activo');
                }
            });
        });

        function cerrarModal(modalId) {
            document.getElementById(modalId).classList.remove('activo');
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modalAuth')) {
                event.target.classList.remove('activo');
            }
        }



        // Resgistro como vendedor:
        
    </script>

</body>

</html>