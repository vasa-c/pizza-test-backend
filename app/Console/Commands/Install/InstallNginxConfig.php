<?php

declare(strict_types=1);

namespace App\Console\Commands\Install;

use App\Console\Commands\BaseCommand;
use Illuminate\Support\Facades\Storage;

class InstallNginxConfig extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'install:nginx';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Builds nginx.conf for the site';

    /**
     * {@inheritdoc}
     */
    public function handle()
    {
        $host = $this->getHost();
        if ($host === null) {
            return -1;
        }
        $config = config('pizza.nginx');
        $vars = [
            'listen' => $config['listen'],
            'host' => $host,
            'root' => public_path(),
            'fpm' => $config['fpm'],
            'nuxt' => $config['nuxt'],
        ];
        $template = file_get_contents(__DIR__.'/nginx.conf.tpl');
        $result = $this->replace($template, $vars);
        Storage::disk('local')->put('nginx.conf', $result);
    }

    /**
     * @return string|null
     */
    private function getHost(): ?string
    {
        $url = config('app.url');
        $params = parse_url($url);
        if (!isset($params['host'])) {
            $this->error('Cannot get server name from APP_URL');
            return null;
        }
        return $params['host'];
    }

    /**
     * @param string $template
     * @param array $vars
     * @return string
     */
    private function replace(string $template, array $vars): string
    {
        $search = [];
        $replace = [];
        foreach ($vars as $k => $v) {
            $search[] = '{'.$k.'}';
            $replace[] = $v;
        }
        return str_replace($search, $replace, $template);
    }
}
