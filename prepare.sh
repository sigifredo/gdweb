#!/bin/bash

if [ "`whoami`" != "root" ]; then
    echo "No es usuario root." >&2
    exit
fi

if [ -d "/usr/bin/sudo" ]; then
    echo "Es necesario tener \"sudo\" instalado en el computador" >&2
    exit
fi

echo -e "\nConfiguración de apache:"
echo "Creando carpeta usr..."
mkdir -p /tmp/gdweb/www/img/usr
echo "Creando carpeta news..."
mkdir -p /tmp/gdweb/www/img/news
echo "Creando carpeta asignando permisos..."
chown -R www-data.www-data /tmp/gdweb/www

echo -e "\nConfiguración de postgres:"
echo "Creando usuario para la base de datos..."
sudo -u postgres createuser -s -P gdadmin
echo "Creando base de datos..."
sudo -u postgres createdb gfifdev --owner=gdadmin
echo "Importando base de datos..."
psql -U gdadmin gfifdev < db/gfifdev.sql 1> /dev/null
echo "Creando carpeta news..."
mkdir -p /tmp/gdweb/pg/img/news
echo "Creando carpeta asignando permisos..."
chown -R postgres.postgres /tmp/gdweb/pg
echo "Creando enlaces simbólicos"
ln -s /tmp/gdweb/pg public/pg

echo -e "\nTerminado"
