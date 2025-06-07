<?php

namespace MP;

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;
use Dotenv\Dotenv;

class UpdateChecker {
    protected string $pluginFile;
    protected string $repoUrl;
    protected string $pluginSlug;

    public function __construct(string $pluginFile, string $repoUrl, string $pluginSlug) {
        $this->pluginFile = $pluginFile;
        $this->repoUrl = $repoUrl;
        $this->pluginSlug = $pluginSlug;
    }

    public function init(): void {
        // Load .env for GitHub token
        if (file_exists(dirname($this->pluginFile) . '/.env')) {
            $dotenv = Dotenv::createImmutable(dirname($this->pluginFile));
            $dotenv->load();
        }

        $updateChecker = PucFactory::buildUpdateChecker(
            $this->repoUrl,
            $this->pluginFile,
            $this->pluginSlug
        );

        if ($token = getenv('GITHUB_TOKEN')) {
            $updateChecker->setAuthentication($token);
        }

        $updateChecker->setBranch('main');

        // Debug:
        $updateChecker->addResultFilter(function($update) {
            error_log(print_r($update, true));
            return $update;
        });
    }
}
