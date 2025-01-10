<?php

namespace PioneerDynamics\LaraveHashid\Console;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Console\Command;

class GenerateHashIdKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hashid:key {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate hasid key for a model';

    public function handle()
    {
        $this->addConnection(
            $this->argument('model'),
            '',
            10,
            'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
        );

        $this->updateEnvFile(
            $this->argument('model'),
            Str::random(32)
        );
    }

    private function updateEnvFile($connectionName, $salt) 
    {
        $envFilePath = base_path('.env');
        $envContent = file_get_contents($envFilePath);

        $envVariable = 'HASH_ID_SALT_' . strtoupper($connectionName);
        $pattern = '/^' . $envVariable . '=.*/m';

        if (preg_match($pattern, $envContent)) {
            $replacement = $envVariable . '=' . $salt;
            $envContent = preg_replace($pattern, $replacement, $envContent);
        } else {
            $envContent .= "\n" . $envVariable . '=' . $salt;
        }

        file_put_contents($envFilePath, $envContent);
    }

    private function addConnection($connectionName, $salt, $length, $alphabet) 
    {
        $filePath = config_path('hashid.php');

        $config = file_get_contents($filePath);
        
        $newConnection = "
            '".ucfirst($connectionName)."' => [
                'salt' => env('HASH_ID_SALT_".strtoupper($connectionName)."','$salt'),
                'length' => $length,
                'alphabet' => '$alphabet'
            ],";
    
        $pattern = "/'".ucfirst($connectionName)."' => \[.*?\],/s";
        
        if (!preg_match($pattern, $config)) {
            $pattern = '/(\s*\'connections\'\s*=>\s*\[\s*)(.*?)(\s*\],)/s';
            $replacement = '$1$2' . $newConnection . '$3';
            $newConfig = preg_replace($pattern, $replacement, $config);
            file_put_contents($filePath, $newConfig);
        }
    }
}
