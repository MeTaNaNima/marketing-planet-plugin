<?php

namespace MP;

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

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
        $updateChecker = PucFactory::buildUpdateChecker(
            $this->repoUrl,
            $this->pluginFile,
            $this->pluginSlug
        );

        if ($token = getenv('GITHUB_TOKEN')) {
            $updateChecker->setAuthentication($token);
        }
        

        // $updateChecker->setBranch('main');
//        $updateChecker->getVcsApi()->enableReleaseAssets();
        $token = get_option('accessi_github_token');
        if (!empty($token)) {
            $updateChecker->setAuthentication($token);
        }

        // Debug:
        $updateChecker->addResultFilter(function($update) {
            error_log(print_r($update, true));
            return $update;
        });
    }
}
