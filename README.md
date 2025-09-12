# 💳 CG Sistema - Gestión de Créditos y Abonos

**CG Sistema** es una aplicación desarrollada en **Laravel + Livewire**, diseñada para la **gestión de créditos y abonos**, permitiendo a negocios y administradores llevar un control eficiente y seguro de clientes, carteras, cuotas y reportes financieros.

Optimizado para **usuarios administrativos y cobradores**, con un panel intuitivo, configuración adaptable y gestión de roles.

---

## 🚀 Características principales

- 👥 **Gestión de Clientes**: alta, edición y control de clientes activos.  
- 💼 **Carteras**: organización de créditos en diferentes carteras.  
- 💳 **Créditos y Abonos**: registro de créditos, pagos parciales, historial de abonos y cuotas pendientes.  
- 📊 **Reportes Financieros**: generados con **Chart.js** para estadísticas de créditos, abonos y clientes.  
- 📄 **Exportación a PDF**: reportes descargables gracias a **DomPDF**.  
- 🔐 **Gestión de Roles y Permisos**: implementado con **Laravel Permission**.  
- 📈 **Dashboard interactivo** con métricas clave:  
  - Créditos activos, próximos a vencer y en mora  
  - Flujo de abonos (7 días)  
  - Crecimiento de clientes (2025)  
  - Historial de pagos (12m, 30d, 7d)  

---

## 🛠️ Tecnologías utilizadas

- **Framework Backend:** Laravel 12 
- **Frontend:** Livewire + Tailwind CSS  
- **Gráficas:** Chart.js  
- **Reportes PDF:** DomPDF  
- **Roles y permisos:** Laravel Permission  
- **Base de datos:** MySQL (compatible con PostgreSQL y SQLite)  

---

## 📦 Instalación y uso

1. Clonar el repositorio:

   git clone https://github.com/usuario/cg-sistema.git](https://github.com/walner-prog/sis-credito-carlos.git)
   cd sis-credito-carlos
   composer install
   npm install && npm run build

   cp .env.example .env

# Edita el archivo .env con tus credenciales
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cg_sistema
DB_USERNAME=root
DB_PASSWORD=123456

php artisan key:generate
php artisan migrate --seed
php artisan serve


der the [MIT license](https://opensource.org/licenses/MIT).
