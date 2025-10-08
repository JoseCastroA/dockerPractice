#!/bin/bash

# Script de configuración inicial del proyecto
# Taller de Arquitectura Cloud - Docker

echo "🐳 Configurando Taller Docker - CloudTech Solutions"
echo "================================================="

# Verificar si Docker está instalado
if ! command -v docker &> /dev/null; then
    echo "❌ Error: Docker no está instalado."
    echo "   Instala Docker desde: https://docs.docker.com/get-docker/"
    exit 1
fi

# Verificar si Docker Compose está disponible
if ! command -v docker-compose &> /dev/null && ! docker compose version &> /dev/null; then
    echo "❌ Error: Docker Compose no está disponible."
    echo "   Instala Docker Compose desde: https://docs.docker.com/compose/install/"
    exit 1
fi

echo "✅ Docker está instalado correctamente"

# Crear archivo .env si no existe
if [ ! -f .env ]; then
    echo "📝 Creando archivo .env desde .env.example..."
    cp .env.example .env
    echo "✅ Archivo .env creado"
    echo "⚠️  IMPORTANTE: Revisa y personaliza las credenciales en .env"
else
    echo "✅ Archivo .env ya existe"
fi

# Generar contraseñas seguras automáticamente (opcional)
read -p "¿Quieres generar contraseñas seguras automáticamente? (y/N): " generate_passwords

if [[ $generate_passwords =~ ^[Yy]$ ]]; then
    echo "🔐 Generando contraseñas seguras..."
    
    # Función para generar contraseñas seguras
    generate_password() {
        openssl rand -base64 32 | tr -d "=+/" | cut -c1-16
    }
    
    ROOT_PASS=$(generate_password)
    USER_PASS=$(generate_password)
    
    # Actualizar archivo .env
    sed -i.bak "s/MYSQL_ROOT_PASSWORD=.*/MYSQL_ROOT_PASSWORD=${ROOT_PASS}/" .env
    sed -i.bak "s/MYSQL_PASSWORD=.*/MYSQL_PASSWORD=${USER_PASS}/" .env
    
    echo "✅ Contraseñas generadas y guardadas en .env"
    echo "📋 Nueva contraseña root: ${ROOT_PASS}"
    echo "📋 Nueva contraseña usuario: ${USER_PASS}"
    
    # Limpiar archivos backup
    rm -f .env.bak
fi

echo ""
echo "🚀 Configuración completada!"
echo ""
echo "Próximos pasos:"
echo "1. Revisar el archivo .env y personalizar si es necesario"
echo "2. Ejecutar: docker-compose up --build -d"
echo "3. Acceder a:"
echo "   - Aplicación: http://localhost:8080"
echo "   - phpMyAdmin: http://localhost:8081"
echo ""
echo "Para más información, consulta el README.md"
