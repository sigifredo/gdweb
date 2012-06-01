
ifneq ($(shell whoami), root)
    $(error "No es usuario root.")
endif

ifneq ($(shell ./db/sudo.dep), instalado)
    $(error "Es necesario tener 'sudo' instalado en el computador")
endif

por_defecto:apache postgres

all:apache postgres base_de_datos

apache:
	mkdir -p /tmp/gdweb/www/img/usr
	mkdir -p /tmp/gdweb/www/img/news
	mkdir -p /tmp/gdweb/www/img/proy
	mkdir -p /tmp/gdweb/www/img/inf
	chown -R www-data.www-data /tmp/gdweb/www

postgres:
	mkdir -p /tmp/gdweb/pg/img/news
	mkdir -p /tmp/gdweb/pg/img/proy
	mkdir -p /tmp/gdweb/pg/img/inf
	chown -R postgres.postgres /tmp/gdweb/pg
	ln -s /tmp/gdweb/pg public/pg

base_de_datos:
	sudo -u postgres createuser -s -P gdadmin
	sudo -u postgres createdb gfifdev --owner=gdadmin
	psql -U gdadmin gfifdev < db/gfifdev.sql 1> /dev/null

base_de_datos_copia:
	pg_dump -U gdadmin gfifdev > db/gfifdev.sql.bk

base_de_datos_leer:
	psql -U gdadmin gfifdev < db/gfifdev.sql 1> /dev/null

clean:
	rm -rf /tmp/gdweb
	rm -f public/pg

clean-all:clean
	pg_dump -U gdadmin gfifdev > db/gfifdev.sql.bk
	sudo -u postgres dropdb gfifdev
	sudo -u postgres dropuser gdadmin
