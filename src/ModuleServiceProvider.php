<?php
namespace JMsoft\Modules;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider {

    public function boot(){
        $this->publishes(
            [
                __DIR__ . '/../jm-modules' => base_path().'/jm-modules',
                __DIR__ . '/../../telegrambot/Telegrambot' => base_path().'/jm-modules/Telegrambot'
            ]
        );
        //получаем список модулей, которые надо подгрузить
        //Подключаем конфигурация
        if(file_exists(base_path('jm-modules/config.php'))){
            $this->mergeConfigFrom(base_path('jm-modules/config.php'), 'jm_modules');
        }
        $modules = config("jm_modules.modules");
        if($modules) {
            foreach ($modules as $module){
                //Подключаем конфигурация для модуля
                if(file_exists(base_path('jm-modules/'.$module.'/config/module.php'))){
                    $this->mergeConfigFrom(base_path('jm-modules/'.$module.'/config/module.php'), $module);
                }
                //Подключаем роуты для модуля
                if(file_exists(base_path('jm-modules/'.$module.'/routes/web.php'))){
                    $this->loadRoutesFrom(base_path('jm-modules/'.$module.'/routes/web.php'));
                }
                //Загружаем View
                if(is_dir(base_path('jm-modules/'.$module.'/views'))) {
                $this->loadViewsFrom(base_path('jm-modules/'.$module.'/views'), $module);
                }
                //Подгружаем миграции
                if(is_dir(base_path('jm-modules/'.$module.'/migration'))) {
                    $this->loadMigrationsFrom(base_path('jm-modules/'.$module.'/migration'));
                }
                //Подгружаем переводы
                if(is_dir(base_path('jm-modules/'.$module.'/lang'))) {
                    $this->loadTranslationsFrom(base_path('jm-modules/'.$module.'/lang'), $module);
                }
            }
        }
    }

    public function register(){

    }
}