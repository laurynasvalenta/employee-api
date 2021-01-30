FROM nfqlt/php74-fpm:latest

RUN cd /tmp \
    && phpenmod json phar mbstring \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/bin --filename=composer

WORKDIR /home/project/src

ENTRYPOINT phpenmod $ENABLE_PHP_MODULES && sudo -u project bash -c '/home/project/src/tools/init.sh'; /entrypoint.sh
