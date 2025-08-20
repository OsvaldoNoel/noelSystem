# Documentación Técnica - Sistema de Autenticación Noel System

## 📋 Índice
1. [Arquitectura del Sistema](#arquitectura)
2. [Flujo de Autenticación](#flujo-autenticacion)
3. [Configuración Email (Brevo)](#configuracion-email)
4. [Despliegue en Producción (Railway)](#despliegue-railway)
5. [Variables de Entorno Producción](#variables-entorno)
6. [Solución de Problemas](#troubleshooting)

---

## 🏗️ Arquitectura del Sistema <a name="arquitectura"></a>

### Stack Tecnológico
- **Framework**: Laravel 11
- **Frontend**: Livewire 3 + Bootstrap
- **Autenticación**: Laravel Breeze (Adaptado)
- **Email**: Brevo (Sendinblue)
- **Base de datos**: MySQL
- **Cache**: Redis
- **Despliegue**: Railway

### Estructura de Verificación de Email
```
Usuario → Middleware Auth → Middleware Password → Middleware Email → Dashboard
```

---

## 🔄 Flujo de Autenticación <a name="flujo-autenticacion"></a>

### 1. Primer Acceso
```mermaid
graph TD
    A[Login] --> B{¿Password cambiada?}
    B -->|No| C[Formulario Cambio Password]
    B -->|Sí| D{¿Email verificado?}
    D -->|No| E[Verificación Email]
    D -->|Sí| F[Dashboard]
    
    C --> G[Actualiza Password]
    G --> E
    
    E --> H[Recibe Email Brevo]
    H --> I[Click Enlace Verificación]
    I --> J[Valida Token en DB]
    J --> F
```

### 2. Componentes Involucrados
- **PasswordChange**: Formulario cambio contraseña
- **EmailVerification**: Interfaz verificación email  
- **HomeTenant**: Dashboard principal
- **EmailVerificationService**: Lógica de verificación

### 3. Tablas de Base de Datos
```sql
-- users
id | email | password | password_changed_at | email_verified_at

-- email_verification_tokens  
id | user_id | token | expires_at | created_at
```

---

## 📧 Configuración Email con Brevo <a name="configuracion-email"></a>

### 1. Configuración SMTP
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=tu_smtp_usuario
MAIL_PASSWORD=tu_smtp_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@tudominio.com
MAIL_FROM_NAME="Noel System"
```

### 2. Servicio de Verificación
```php
// App\Services\EmailVerificationService
public function sendVerificationEmail(User $user)
{
    $token = Str::random(60);
    // Guarda token en DB
    $verificationUrl = url('/email/verify/'.$token);
    Mail::to($user->email)->send(new BrevoVerificationEmail($user, $verificationUrl));
}
```

### 3. Template Email
```blade
<!-- resources/views/emails/brevo-verification.blade.php -->
@component('mail::button', ['url' => $verificationUrl])
    Verificar Email
@endcomponent
```

---

## 🚀 Despliegue en Railway <a name="despliegue-railway"></a>

### 1. Preparación del Proyecto
```bash
# Instalar dependencias
composer install --optimize-autoloader --no-dev

# Compilar assets
npm run build

# Generar key
php artisan key:generate
```

### 2. Configuración Railway
```yaml
# railway.json
{
    "build": {
        "builder": "nixpacks",
        "buildCommand": "composer install --optimize-autoloader --no-dev && npm run build"
    },
    "deploy": {
        "startCommand": "php artisan serve --host=0.0.0.0 --port=${PORT}",
        "restartPolicyType": "ON_FAILURE"
    }
}
```

### 3. Comandos de Despliegue
```bash
# Conectar repositorio a Railway
railway link

# Desplegar
railway up

# Ver logs
railway logs
```

---

## 🔧 Variables de Entorno Producción <a name="variables-entorno"></a>

### Railway Environment Variables
```env
# Configuración Base
APP_NAME=Noel_System
APP_ENV=production
APP_KEY=base64:...  # Generar nuevo
APP_DEBUG=false
APP_URL=https://tudominio.railway.app

# Base de Datos (Railway proporciona)
DB_CONNECTION=mysql
DB_HOST=railway-proxy.railway.app
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=railway_password

# Email (Brevo Production)
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=production_smtp_user
MAIL_PASSWORD=production_smtp_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@tudominio.com
MAIL_FROM_NAME="Noel System"

# Cache y Sessions
CACHE_DRIVER=redis
SESSION_DRIVER=redis
REDIS_URL=redis://default:railway_redis_password@railway-redis.railway.app:6379
```

### Comandos de Producción
```bash
# Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migrar base de datos
php artisan migrate --force

# Limpiar cache
php artisan optimize:clear
```

---

## 🐛 Solución de Problemas <a name="troubleshooting"></a>

### Problemas Comunes y Soluciones

#### 1. Emails no se envían
```bash
# Verificar configuración Brevo
php artisan tinker
>>> Mail::raw('Test', fn($m) => $m->to('test@email.com')->subject('Test'))
```

#### 2. Error de verificación
```bash
# Verificar tokens en DB
SELECT * FROM email_verification_tokens WHERE expires_at > NOW();

# Limpiar tokens expirados
php artisan schedule:run
```

#### 3. Problemas de Redis en Railway
```env
# Usar variable proporcionada por Railway
REDIS_URL=${REDIS_URL}
```

#### 4. Error de dominios
```env
# Configurar correctamente para producción
APP_URL=https://tudominio.railway.app
SESSION_DOMAIN=.railway.app
```

### Monitoreo en Producción
```bash
# Verificar estado de la aplicación
railway status

# Ver logs en tiempo real
railway logs -f

# Ver variables de entorno
railway vars list
```

---

## 📞 Soporte y Mantenimiento

### Comandos Útiles
```bash
# Reinicar aplicación
railway restart

# Escalar recursos
railway scale

# Backup de base de datos
railway db backup
```

### Documentación Adicional
- [Railway Documentation](https://docs.railway.app)
- [Brevo SMTP Documentation](https://help.brevo.com/hc/en-us/articles/209467485)
- [Laravel Deployment](https://laravel.com/docs/deployment)

---

**Nota**: Esta documentación debe actualizarse con cada cambio significativo en el sistema de autenticación o configuración de producción.