FROM debian:stretch-slim
MAINTAINER Aleksey Nikulin <masterweb@e1.ru>

ENV NGINX_VERSION 1.15.10

RUN \
  echo 'Europe/Moscow' > /etc/timezone && \
  cp -f /usr/share/zoneinfo/Europe/Moscow /etc/localtime && \
  date; \
  #
  echo 'deb http://httpredir.debian.org/debian/ stretch-backports main contrib non-free' | tee -a /etc/apt/sources.list && \
  echo 'deb-src http://httpredir.debian.org/debian/ stretch-backports main contrib non-free' | tee -a /etc/apt/sources.list && \
  echo 'debconf debconf/frontend select Noninteractive' | debconf-set-selections; \
  #
  apt-get update && \
  apt-get install -y \
    locales \
    wget \
    unzip \
    make \
    gcc \
    libzip-dev \
    libssl-dev \
    libpcre3-dev  && \
    # libldap2-dev && \
  apt-get install -y -t stretch-backports \
    openssl \
    ca-certificates \
    ssl-cert; \
  #
  make-ssl-cert generate-default-snakeoil && \
  usermod --append --groups ssl-cert www-data && \
  ls -l /etc/ssl/certs/ssl-cert-snakeoil.pem /etc/ssl/private/ssl-cert-snakeoil.key; \
  #
  groupadd -g 396 nginx && \
  useradd nginx -u 396 -g 396 -M -c 'Nginx web server' -s /sbin/nologin -d /var/lib/nginx; \
  #
  mkdir -p /var/log/nginx /var/lib/nginx /var/cache/nginx /usr/share/nginx && \
  chown -R nginx:nginx /var/log/nginx /var/lib/nginx /var/cache/nginx; \
  #
  ln -sf /dev/stdout /var/log/nginx/access.log && \
  ln -sf /dev/stderr /var/log/nginx/error.log; \
  #
  mkdir /tmp/build && cd /tmp/build && \
  # wget -O nginx-auth-ldap.zip https://codeload.github.com/kvspb/nginx-auth-ldap/zip/master && \
  # unzip nginx-auth-ldap.zip && \
  wget http://nginx.org/download/nginx-$NGINX_VERSION.tar.gz && \
  tar xf nginx-$NGINX_VERSION.tar.gz && cd nginx-$NGINX_VERSION; \
  #
  ./configure \
    --prefix=/etc/nginx \
    --sbin-path=/usr/sbin/nginx \
    --conf-path=/etc/nginx/nginx.conf \
    --error-log-path=/var/log/nginx/error.log \
    --http-log-path=/var/log/nginx/access.log \
    --pid-path=/var/run/nginx.pid \
    --lock-path=/var/run/nginx.lock \
    --http-client-body-temp-path=/var/cache/nginx/client_temp \
    --http-proxy-temp-path=/var/cache/nginx/proxy_temp \
    --http-fastcgi-temp-path=/var/cache/nginx/fastcgi_temp \
    --http-uwsgi-temp-path=/var/cache/nginx/uwsgi_temp \
    --http-scgi-temp-path=/var/cache/nginx/scgi_temp \
    --user=nginx \
    --group=nginx \
    --with-http_ssl_module \
    --with-http_realip_module \
    --with-http_addition_module \
    --with-http_sub_module \
    --with-http_dav_module \
    --with-http_flv_module \
    --with-http_mp4_module \
    --with-http_gunzip_module \
    --with-http_gzip_static_module \
    --with-http_random_index_module \
    --with-http_secure_link_module \
    --with-http_stub_status_module \
    --with-http_auth_request_module \
    --with-mail \
    --with-mail_ssl_module \
    --with-file-aio \
    # --with-http_spdy_module \
    --with-http_v2_module \
    --with-ipv6 && \
    # --add-module=../nginx-auth-ldap-master && \
  make && make install; \
  #
  echo "\ndaemon off;" >> /etc/nginx/nginx.conf; \
  #
  apt-get remove -y \
    gcc \
    wget \
    make \
    unzip; \
  rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*;

COPY config/ /etc/nginx/
VOLUME ["/var/www"]
WORKDIR /etc/nginx

EXPOSE 80

ENTRYPOINT ["nginx"]
CMD ["-g", "daemon off;"]