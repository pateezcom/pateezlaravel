<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\Settings\JsTranslationController;
use Illuminate\Http\Request;

class SyncJsTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:sync-js {--generate-file : Generate the JavaScript translations file} {--generate-view : Generate the JavaScript translations view}'; 

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize translations to JavaScript or generate translations file/view';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $controller = new JsTranslationController();
        $request = new Request();
        
        if ($this->option('generate-view')) {
            $this->info('Generating JavaScript translations view with comments...');
            $result = $controller->generateTranslationsView($request);
            
            // Parse response JSON
            $data = json_decode($result->getContent(), true);
            
            if (!empty($data['success'])) {
                $this->info("JavaScript translations view generated successfully with {$data['count']} translations!");
                $this->info("Saved to: {$data['view_path']}");
                $this->info("This view includes each translation key with its English value as a comment.");
            } else {
                $this->error('Error generating translations view: ' . ($data['error'] ?? 'Unknown error'));
            }
        } elseif ($this->option('generate-file')) {
            $this->info('Generating JavaScript translations file...');
            $controller->generateJsFile($request);
            $this->info('JavaScript translations file generated successfully: public/js/translations.js');
        } else {
            $this->info('Synchronizing translations to JavaScript cache...');
            $controller->syncTranslations($request);
            $this->info('Translations synchronized successfully!');
        }

        return Command::SUCCESS;
    }
}
