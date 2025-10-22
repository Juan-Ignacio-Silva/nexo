<?php

?>
<link rel="stylesheet" href="/css/vist-ayuda/ayuda.css">
<head>
    <title>Centro de ayuda</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>¿Con qué podemos ayudarte?</h1>
        </div>

        <div class="section">
            <h2 class="section-title">Compras</h2>
            
            <div class="help-item" onclick="handleItemClick('administrar-compras')">
                <div class="help-item-content">
                    <div class="help-icon shopping-icon">🛒</div>
                    <div class="help-text">
                        <div class="help-title">Administrar y cancelar compras</div>
                        <div class="help-description">Pagar, seguir envíos, modificar, reclamar o cancelar compras.</div>
                    </div>
                </div>
                <div class="chevron">›</div>
            </div>

            <div class="help-item" onclick="handleItemClick('devoluciones')">
                <div class="help-item-content">
                    <div class="help-icon refund-icon">💲</div>
                    <div class="help-text">
                        <div class="help-title">Devoluciones y reembolsos</div>
                        <div class="help-description">Devolver un producto o consultar por reintegros de dinero de una compra.</div>
                    </div>
                </div>
                <div class="chevron">›</div>
            </div>

            <div class="help-item" onclick="handleItemClick('preguntas-compras')">
                <div class="help-item-content">
                    <div class="help-icon question-icon">❔</div>
                    <div class="help-text">
                        <div class="help-title">Preguntas frecuentes sobre compras</div>
                    </div>
                </div>
                <div class="chevron">›</div>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">Ventas</h2>
            
            <div class="help-item" onclick="handleItemClick('gestionar-ventas')">
                <div class="help-item-content">
                    <div class="help-icon sales-icon">🏷️</div>
                    <div class="help-text">
                        <div class="help-title">Gestionar ventas y publicaciones</div>
                        <div class="help-description">Ventas, cobros, envíos, reclamos, devoluciones, publicaciones y reputación.</div>
                    </div>
                </div>
                <div class="chevron">›</div>
            </div>

            <div class="help-item" onclick="handleItemClick('preguntas-ventas')">
                <div class="help-item-content">
                    <div class="help-icon question-icon">❔</div>
                    <div class="help-text">
                        <div class="help-title">Preguntas frecuentes sobre ventas</div>
                    </div>
                </div>
                <div class="chevron">›</div>
            </div>
        </div>

        <div class="section account-section">
            <h2 class="section-title">Ayuda sobre tu cuenta</h2>
            
            <div class="help-item" onclick="handleItemClick('configuracion-cuenta')">
                <div class="help-item-content">
                    <div class="help-text">
                        <div class="help-title">Configuración de mi cuenta</div>
                    </div>
                </div>
                <div class="chevron">›</div>
            </div>

            <div class="help-item" onclick="handleItemClick('seguridad-cuenta')">
                <div class="help-item-content">
                    <div class="help-text">
                        <div class="help-title">Seguridad y acceso a la cuenta</div>
                    </div>
                </div>
                <div class="chevron">›</div>
            </div>
        </div>
    </div>

      <!-- Modal -->
    <div id="helpModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle" class="modal-title">Título de Ayuda</h2>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <div id="modalBody" class="modal-body">
                <!-- Contenido dinámico se cargará aquí -->
            </div>
        </div>
    </div>

    <script>
        // Contenido de ayuda para cada sección
        const helpContent = {
            'administrar-compras': {
                title: 'Administrar y cancelar compras',
                content: `
                    <h3>¿Cómo administrar mis compras?</h3>
                    <p>En esta sección puedes gestionar todas tus compras de manera fácil y rápida:</p>
                    <ul>
                        <li><strong>Seguir envíos:</strong> Consulta el estado de tus pedidos en tiempo real</li>
                        <li><strong>Modificar compras:</strong> Cambia direcciones de entrega antes del envío</li>
                        <li><strong>Cancelar compras:</strong> Cancela pedidos que aún no han sido enviados</li>
                        <li><strong>Métodos de pago:</strong> Actualiza o cambia tu información de pago</li>
                    </ul>
                    
                    <h3>¿Cómo cancelar una compra?</h3>
                    <p>Para cancelar una compra:</p>
                    <ul>
                        <li>Ve a "Mis compras" en tu perfil</li>
                        <li>Busca el pedido que deseas cancelar</li>
                        <li>Haz clic en "Cancelar compra"</li>
                        <li>Selecciona el motivo de cancelación</li>
                        <li>Confirma la cancelación</li>
                    </ul>
                    
                    <p><strong>Nota:</strong> Solo puedes cancelar compras que no hayan sido despachadas aún.</p>
                `
            },
            'devoluciones': {
                title: 'Devoluciones y reembolsos',
                content: `
                    <h3>¿Cómo realizar una devolución?</h3>
                    <p>Puedes devolver productos dentro de los 30 días posteriores a la entrega:</p>
                    <ul>
                        <li>El producto debe estar en perfectas condiciones</li>
                        <li>Debe incluir el empaque original</li>
                        <li>Los productos personalizados no son elegibles para devolución</li>
                    </ul>
                    
                    <h3>Proceso de devolución:</h3>
                    <ul>
                        <li>Ve a "Mis compras" y selecciona el producto</li>
                        <li>Haz clic en "Devolver producto"</li>
                        <li>Selecciona el motivo de la devolución</li>
                        <li>Imprime la etiqueta de envío gratuito</li>
                        <li>Empaca el producto y envialo</li>
                    </ul>
                    
                    <h3>Reembolsos</h3>
                    <p>Una vez que recibamos tu devolución, procesaremos el reembolso en 3-5 días hábiles. El dinero será devuelto al método de pago original utilizado en la compra.</p>
                `
            },
            'preguntas-compras': {
                title: 'Preguntas frecuentes sobre compras',
                content: `
                    <h3>¿Cuánto tiempo tarda la entrega?</h3>
                    <p>Los tiempos de entrega varían según tu ubicación:</p>
                    <ul>
                        <li><strong>Entrega estándar:</strong> 3-7 días hábiles</li>
                        <li><strong>Entrega express:</strong> 1-3 días hábiles</li>
                        <li><strong>Entrega mismo día:</strong> Disponible en ciudades principales</li>
                    </ul>
                    
                    <h3>¿Qué métodos de pago aceptan?</h3>
                    <ul>
                        <li>Tarjetas de crédito y débito</li>
                        <li>PayPal</li>
                        <li>Transferencia bancaria</li>
                        <li>Pago contra entrega (disponible en algunas zonas)</li>
                    </ul>
                    
                    <h3>¿Puedo cambiar mi dirección de entrega?</h3>
                    <p>Sí, puedes cambiar tu dirección de entrega siempre que el pedido no haya sido despachado. Ve a "Mis compras" y selecciona "Modificar dirección".</p>
                    
                    <h3>¿Qué hago si mi pedido llega dañado?</h3>
                    <p>Si tu pedido llega dañado, contáctanos inmediatamente a través del chat de soporte o reporta el problema en "Mis compras". Te ayudaremos con un reemplazo o reembolso completo.</p>
                `
            },
            'gestionar-ventas': {
                title: 'Gestionar ventas y publicaciones',
                content: `
                    <h3>¿Cómo crear una publicación?</h3>
                    <p>Para crear una nueva publicación:</p>
                    <ul>
                        <li>Ve a "Vender" en el menú principal</li>
                        <li>Haz clic en "Publicar producto"</li>
                        <li>Completa toda la información del producto</li>
                        <li>Agrega fotos de alta calidad</li>
                        <li>Establece precio y condiciones de venta</li>
                        <li>Publica tu producto</li>
                    </ul>
                    
                    <h3>Gestión de ventas</h3>
                    <ul>
                        <li><strong>Procesar pedidos:</strong> Confirma y prepara los envíos</li>
                        <li><strong>Comunicación:</strong> Responde preguntas de compradores</li>
                        <li><strong>Envíos:</strong> Coordina la logística de entrega</li>
                        <li><strong>Cobros:</strong> Recibe pagos de manera segura</li>
                    </ul>
                    
                    <h3>Reputación del vendedor</h3>
                    <p>Mantén una buena reputación:</p>
                    <ul>
                        <li>Responde preguntas rápidamente</li>
                        <li>Envía productos en tiempo y forma</li>
                        <li>Proporciona descripciones precisas</li>
                        <li>Ofrece buen servicio al cliente</li>
                    </ul>
                `
            },
            'preguntas-ventas': {
                title: 'Preguntas frecuentes sobre ventas',
                content: `
                    <h3>¿Cuánto cobran de comisión?</h3>
                    <p>Nuestras comisiones son competitivas y transparentes:</p>
                    <ul>
                        <li>Productos hasta $50: 5% de comisión</li>
                        <li>Productos de $51-$200: 7% de comisión</li>
                        <li>Productos más de $200: 10% de comisión</li>
                    </ul>
                    
                    <h3>¿Cuándo recibo mi dinero?</h3>
                    <p>Los pagos se liberan:</p>
                    <ul>
                        <li>48 horas después de la entrega confirmada</li>
                        <li>O después de 21 días de realizada la venta</li>
                        <li>Los pagos se depositan en tu cuenta registrada</li>
                    </ul>
                    
                    <h3>¿Qué hago si un comprador no paga?</h3>
                    <p>Si un comprador no completa el pago en 48 horas:</p>
                    <ul>
                        <li>La venta se cancela automáticamente</li>
                        <li>El producto vuelve a estar disponible</li>
                        <li>Puedes contactar al comprador a través de mensajes</li>
                    </ul>
                    
                    <h3>¿Cómo mejoro mis ventas?</h3>
                    <ul>
                        <li>Usa fotos de alta calidad</li>
                        <li>Escribe descripciones detalladas</li>
                        <li>Ofrece envío gratuito</li>
                        <li>Mantén precios competitivos</li>
                        <li>Responde rápido a las preguntas</li>
                    </ul>
                `
            },
            'configuracion-cuenta': {
                title: 'Configuración de mi cuenta',
                content: `
                    <h3>Datos personales</h3>
                    <p>En esta sección puedes actualizar:</p>
                    <ul>
                        <li>Nombre y apellidos</li>
                        <li>Dirección de email</li>
                        <li>Número de teléfono</li>
                        <li>Direcciones de entrega</li>
                        <li>Foto de perfil</li>
                    </ul>
                    
                    <h3>Preferencias de notificaciones</h3>
                    <p>Configura qué notificaciones quieres recibir:</p>
                    <ul>
                        <li>Notificaciones por email</li>
                        <li>Notificaciones push</li>
                        <li>Ofertas y promociones</li>
                        <li>Actualizaciones de pedidos</li>
                    </ul>
                    
                    <h3>Métodos de pago</h3>
                    <p>Administra tus métodos de pago guardados de forma segura.</p>
                `
            },
            'seguridad-cuenta': {
                title: 'Seguridad y acceso a la cuenta',
                content: `
                    <h3>Cambiar contraseña</h3>
                    <p>Para mantener tu cuenta segura, cambia tu contraseña regularmente:</p>
                    <ul>
                        <li>Ve a Configuración > Seguridad</li>
                        <li>Haz clic en "Cambiar contraseña"</li>
                        <li>Ingresa tu contraseña actual</li>
                        <li>Crea una nueva contraseña segura</li>
                        <li>Confirma los cambios</li>
                    </ul>
                    
                    <h3>Autenticación en dos pasos</h3>
                    <p>Agrega una capa extra de seguridad a tu cuenta activando la verificación en dos pasos mediante SMS o aplicación autenticadora.</p>
                    
                    <h3>Actividad de la cuenta</h3>
                    <p>Revisa regularmente:</p>
                    <ul>
                        <li>Inicios de sesión recientes</li>
                        <li>Dispositivos conectados</li>
                        <li>Cambios en la cuenta</li>
                    </ul>
                    
                    <h3>¿Problemas para iniciar sesión?</h3>
                    <p>Si tienes problemas para acceder:</p>
                    <ul>
                        <li>Usa "Olvidé mi contraseña"</li>
                        <li>Verifica tu conexión a internet</li>
                        <li>Contacta soporte si persiste el problema</li>
                    </ul>
                `
            }
        };

        function handleItemClick(itemType) {
            const content = helpContent[itemType];
            if (content) {
                document.getElementById('modalTitle').textContent = content.title;
                document.getElementById('modalBody').innerHTML = content.content;
                openModal();
            } else {
                console.log('No content available for:', itemType);
            }
        }

        function openModal() {
            const modal = document.getElementById('helpModal');
            modal.classList.add('show');
            document.body.style.overflow = 'hidden'; // Prevenir scroll del body
        }

        function closeModal() {
            const modal = document.getElementById('helpModal');
            modal.classList.remove('show');
            document.body.style.overflow = 'auto'; // Restaurar scroll del body
        }

        // Cerrar modal al hacer clic fuera de él
        window.onclick = function(event) {
            const modal = document.getElementById('helpModal');
            if (event.target === modal) {
                closeModal();
            }
        }

        // Cerrar modal con tecla ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        // Agregar efectos de hover adicionales
        document.querySelectorAll('.help-item').forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.borderColor = '#007bff';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.borderColor = '#e9ecef';
            });
        });
    </script>
    
</body>