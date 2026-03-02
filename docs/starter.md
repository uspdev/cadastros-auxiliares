## Instalação inicial

Instalação do laravel e os pacotes USPDev seguindo os respectivos `readme.md`

    composer create-project laravel/laravel:^8.0 starter && cd starter

https://github.com/uspdev/laravel-usp-theme

    composer require uspdev/laravel-usp-theme

https://github.com/uspdev/senhaunica-socialite

    composer require uspdev/senhaunica-socialite

https://github.com/uspdev/replicado

    composer require uspdev/replicado

https://github.com/uspdev/laravel-tools

    composer require uspdev/laravel-tools

https://github.com/uspdev/wsfoto

Não instalado mas configurado no .env.exemple. Instalar com:

    composer require uspdev/wsfoto

Localization

    composer require --dev lucascudo/laravel-pt-br-localization

    "post-update-cmd": [
        "[ $COMPOSER_DEV_MODE -eq 0 ] || php artisan vendor:publish --tag=laravel-pt-br-localization"
    ]

config/app.php
* 'timezone' => 'America/Sao_Paulo',
* 'locale' => 'pt-BR',
* 'faker_locale' => 'pt-BR',

## Atualização do STARTER

    composer update
    php artisan vendor:publish --provider="Uspdev\UspTheme\ServiceProvider" --tag=config --force
    php artisan vendor:publish --provider="Uspdev\SenhaunicaSocialite\SenhaunicaServiceProvider" --tag="config" --force
    php artisan migrate
    
