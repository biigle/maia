# FROM biigle/build-dist AS intermediate

FROM pytorch/pytorch:1.11.0-cuda11.3-cudnn8-runtime
LABEL maintainer "Martin Zurowietz <martin@cebitec.uni-bielefeld.de>"

RUN LC_ALL=C.UTF-8 apt-get update \
    && apt-get install -y --no-install-recommends software-properties-common \
    && add-apt-repository -y ppa:ondrej/php \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
        php8.0-cli \
        php8.0-curl \
        php8.0-xml \
        php8.0-pgsql \
        php8.0-mbstring \
        php8.0-redis \
    && apt-get purge -y software-properties-common \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -r /var/lib/apt/lists/*

# Install MMCV dependencies
RUN LC_ALL=C.UTF-8 apt-get update \
    && apt-get install -y --no-install-recommends \
        build-essential \
        ffmpeg \
        libsm6 \
        libxext6 \
    && apt-get clean \
    && rm -r /var/lib/apt/lists/*

RUN pip3 install mmcv-full -f https://download.openmmlab.com/mmcv/dist/cu113/torch1.11.0/index.html

RUN pip3 install mmdet

WORKDIR /var/www

# This is required to run php artisan tinker in the worker container. Do this for
# debugging purposes.
RUN mkdir -p /.config/psysh && chmod o+w /.config/psysh

# COPY --from=intermediate /etc/localtime /etc/localtime
# COPY --from=intermediate /etc/timezone /etc/timezone
# COPY --from=intermediate /var/www /var/www
