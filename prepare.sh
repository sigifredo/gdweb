#!/bin/bash

if [ "`whoami`" != "root" ]; then
    echo -e "No es usuario root."
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
echo "Creando carpeta news..."
mkdir -p /tmp/gdweb/pg/img/news
echo "Creando carpeta asignando permisos..."
chown -R postgres.postgres /tmp/gdweb/pg
echo "Creando enlaces simbólicos"
ln -s /tmp/gdweb/pg public/pg

echo -e "\nTerminado"
