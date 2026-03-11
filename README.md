# EcoShop - Laravel & Flutter Multi-Vendor Ecommerce Script

Bienvenido al repositorio del backend de **EcoShop**, un sistema completo de comercio electrónico diseñado para plataformas de un solo vendedor o multi-vendedor.

## 📖 Resumen del Proyecto

EcoShop está construido utilizando el framework **Laravel 9** para PHP, garantizando alta seguridad (protección contra inyecciones SQL, ataques XSS y CSRF) y un código fácil de navegar. Cuenta con un sistema de gestión de atributos completamente funcional que permite agregar variantes de productos ilimitadas y precios específicos por variante.

### ✨ Características Principales

*   **Framework:** Laravel 9
*   **Gestión de Catálogo:** Búsqueda avanzada por categoría, subcategoría, marca y variantes. Opciones de variantes de productos con precios dinámicos.
*   **Ventas y Promociones:** Gestión de cupones, ofertas relámpago (Flash Deals) y lista de deseos (Wishlist).
*   **Logística:** Módulo completo de envíos con configuración de tarifas por área de mensajería, inventario y seguimiento de pedidos.
*   **Multi-Vendedor:** Soporte completo para múltiples vendedores (configurable también para un solo vendedor) con paneles independientes para la gestión de productos, perfiles, reportes y retiros de dinero.
*   **Métodos de Pago Integrados:** PayPal, Stripe, Razorpay, Flutterwave, Mollie, Paystack, Instamojo, SslCommerz, Transferencia Bancaria y Contra Reembolso (COD).

---

## 🚀 Despliegue Continuo (CI/CD) a cPanel con GitHub Actions

Este proyecto cuenta con un flujo de despliegue continuo automatizado. Cada vez que se hace un `push` a la rama `main`, GitHub Actions se conecta automáticamente al servidor de producción por SSH y ejecuta el script de despliegue.

### ⚙️ 1. Configuración de Secrets en GitHub
Para que el entorno `production` en GitHub Actions funcione correctamente, dirígete a **Settings > Environments > production** y configura los siguientes **Secrets**:

*   `SSH_HOST`: La IP o dominio de tu servidor cPanel.
*   `SSH_USER`: El usuario SSH de tu cPanel.
*   `SSH_PRIVATE_KEY`: Tu clave privada SSH para acceder al servidor.
*   `DEPLOY_PATH`: La ruta absoluta de la carpeta de despliegue (Ej: `/home/keywordcv/test.keywordcv.com`).

### 🔑 2. Autenticación del Servidor (GitHub PAT)
Para evitar conflictos con contraseñas de claves SSH en la ejecución remota, el servidor descarga las actualizaciones de GitHub mediante solicitudes HTTPS y un **Personal Access Token (PAT)**.

En el servidor, el repositorio origen debe estar configurado así:
```bash
git remote set-url origin https://<TU_TOKEN_PAT>@github.com/Lesnier/marketplace-multivendor-backend.git
```

### 📜 3. Script de Despliegue (`deploy.sh`)
El proceso automatizado ejecuta de manera segura el script `deploy.sh` alojado en el servidor, el cual realiza las siguientes tareas:
1. Activa el modo mantenimiento de Laravel (`php artisan down`).
2. Obtiene los últimos cambios (`git pull origin main`).
3. Instala/Actualiza las dependencias utilizando la **ruta absoluta de Composer** (`php /opt/cpanel/ea-wappspector/composer.phar install --no-dev --optimize-autoloader --no-interaction`).
4. Limpia la caché antigua y genera nueva caché de configuraciones y vistas.
    *   *Nota:* El comando de caché de rutas (`route:cache`) está marcado como no-fatal (`|| true`) para no detener el despliegue en caso de colisiones de nombres de rutas.
5. Vincula el almacenamiento simbólico y ajusta los permisos.
6. Desactiva el modo mantenimiento (`php artisan up`).

---

## 💻 Requisitos del Sistema

*   PHP >= 8.0.2
*   Extensiones PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML.
