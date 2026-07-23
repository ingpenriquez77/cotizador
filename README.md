# 📊 Sistema de Cotizaciones y Gestión POS (Laravel 11)

Un módulo dinámico e intuitivo desarrollado en **Laravel 11** y **AdminLTE 4** para la generación, cálculo y gestión de cotizaciones comerciales con soporte para despliegue automatizado en **Render.com**.

---

## 🚀 Características Principales

* **Cálculo Dinámico y Bidireccional:**
    * Ajuste automático de **Precio Unitario** al cambiar margen de utilidad (%) o costo base.
    * Recálculo en tiempo real del **Margen de Utilidad (%)** al ingresar un precio personalizado.
    * Cálculo de subtotales, IVA (16%) e importes finales de forma inmediata.
* **Interfaz Fluidizada:** Prevención de envíos accidentales con la tecla *Enter* y controles responsivos.
* **Gestión de Catálogos:** Selección rápida de clientes y catálogo de productos.
* **Preparado para Producción (Docker Tier Free):**
    * Incluye configuración para despliegues en el plan gratuito de **Render.com**.
    * **Protección de Seeders:** Prevención de duplicación de datos mediante validaciones en `DatabaseSeeder.php` al reiniciar el servidor.

---

## 🛠️ Requisitos del Sistema (Entorno Local)

* **PHP:** >= 8.2
* **Composer**
* **Base de Datos:** MySQL / PostgreSQL
* **Node.js & NPM** (Opcional si compilas assets)

---

## ⚙️ Instalación Local

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/ingpenriquez77/cotizador.git
   cd cotizador
2. **Instalar dependencias de PHP:**
   ```bash
   composer install
3. **Configurar las Variables de Entorno:**
   *Copia el archivo **.env.example** y renómbralo a **.env**:*
   ```bash
    cp .env.example .env
4. **Configurar la Base de Datos:**
   *Abre el archivo **.env** en tu editor de código y ajusta los parámetros de conexión según tu entorno local:*
   ```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=cotizador_db
    DB_USERNAME=root
    DB_PASSWORD=
5. **Generar la clave de la aplicación:**
   ```bash
   php artisan key:generate
6. **Ejecutar migraciones y poblar la base de datos (Seeders):**
   ```bash
   php artisan migrate --seed
7. **Crear el enlace simbólico para el almacenamiento de archivos:**
   ```bash
   php artisan storage:link
8. **Iniciar el servidor local de desarrollo:**
   ```bash
   php artisan serve
