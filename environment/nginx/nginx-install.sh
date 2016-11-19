#!/bin/bash

cd /

VER_NGINX_DEVEL_KIT='0.2.19'
VER_LUA_NGINX_MODULE='0.9.16'
VER_NGINX='1.9.3'
VER_LUAJIT='2.0.4'

NGINX_DEVEL_KIT='ngx_devel_kit-0.2.19'
LUA_NGINX_MODULE='lua-nginx-module-$VER_LUA_NGINX_MODULE'
NGINX_ROOT='/nginx'

WEB_DIR='/var/www'

mkdir $WEB_DIR

apt-get -qq update
apt-get -qq -y install wget
apt-get -qq -y install make
apt-get -qq -y install libpcre3
apt-get -qq -y install libpcre3-dev
apt-get -qq -y install zlib1g-dev
apt-get -qq -y install libssl-dev
apt-get -qq -y install gcc

wget http://nginx.org/download/nginx-1.9.3.tar.gz
wget http://luajit.org/download/LuaJIT-2.0.4.tar.gz
wget --no-check-certificate https://github.com/simpl/ngx_devel_kit/archive/v0.2.19.tar.gz -O 0.2.19.tar.gz
wget --no-check-certificate https://github.com/openresty/lua-nginx-module/archive/v0.9.16.tar.gz -O 0.9.16.tar.gz

tar -xzvf nginx-1.9.3.tar.gz && rm nginx-1.9.3.tar.gz
tar -xzvf LuaJIT-2.0.4.tar.gz && rm LuaJIT-2.0.4.tar.gz
tar -xzvf 0.2.19.tar.gz && rm 0.2.19.tar.gz
tar -xzvf 0.9.16.tar.gz && rm 0.9.16.tar.gz

cd /LuaJIT-2.0.4
make
make install

cd /nginx-1.9.3

#./configure --prefix=/nginx --with-ld-opt="-Wl,-rpath,$/usr/local/lib" --add-module=/ngx_devel_kit-0.2.19 --add-module=/lua-nginx-module-0.9.16 --with-pcre --conf-path=/etc/nginx/nginx.conf --error-log-path=/var/log/nginx/error.log --http-client-body-temp-path=/var/lib/nginx/body --http-fastcgi-temp-path=/var/lib/nginx/fastcgi --http-log-path=/var/log/nginx/access.log --http-proxy-temp-path=/var/lib/nginx/proxy --with-http_gzip_static_module --lock-path=/var/lock/nginx.lock --pid-path=/run/nginx.pid --with-http_gunzip_module --with-http_realip_module --with-http_stub_status_module --with-http_spdy_module --with-http_ssl_module

./configure \
--prefix=$NGINX_ROOT \
--with-ld-opt="-Wl,-rpath,$LUAJIT_LIB" \
--add-module=/ngx_devel_kit-0.2.19 \
--add-module=/$LUA_NGINX_MODULE \
--with-pcre \
--conf-path=/etc/nginx/nginx.conf \
--error-log-path=/var/log/nginx/error.log \
--http-client-body-temp-path=/var/lib/nginx/body \
--http-fastcgi-temp-path=/var/lib/nginx/fastcgi \
--http-log-path=/var/log/nginx/access.log \
--http-proxy-temp-path=/var/lib/nginx/proxy \
--with-http_gzip_static_module \
--lock-path=/var/lock/nginx.lock \
--pid-path=/run/nginx.pid \
--with-http_gunzip_module \
--with-http_realip_module \
--with-http_stub_status_module \
--with-http_spdy_module \
--with-http_ssl_module

make -j2
make install
ln -s $NGINX_ROOT/sbin/nginx /usr/local/sbin/nginx

cd $WEB_DIR

mkdir -p /var/lib/nginx/body
mkdir -p /var/lib/nginx/proxy
mkdir -p /var/lib/nginx/fastcgi

rm -rf /nginx-$VER_NGINX
rm -rf /LuaJIT-$VER_LUAJIT
rm -rf /$NGINX_DEVEL_KIT
rm -rf /$LUA_NGINX_MODULE

# Export the Library Path
export LD_LIBRARY_PATH=/usr/local/lib/:/opt/drizzle/lib/:$LD_LIBRARY_PATH

#ssl
apt-get install openssl
mkdir /etc/nginx/ssl
cd /etc/nginx/ssl
openssl ciphers -V 'EECDH+ECDSA+AESGCM EECDH+aRSA+AESGCM EECDH+ECDSA+SHA256 EECDH+aRSA+SHA256 EECDH+aRSA+RC4 EDH+aRSA EECDH RC4 !aNULL !eNULL !LOW !3DES !MD5 !EXP !PSK !SRP !DSS'
openssl req -new -x509 -days 9999 -nodes -newkey rsa:2048 -out cert.pem -keyout cert.key