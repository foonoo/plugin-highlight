<?php

namespace foonoo\plugins\contrib\highlight;

use foonoo\Plugin;
use foonoo\events\ContentOutputGenerated;
use foonoo\events\AssetPipelineReady;

class HighlightPlugin extends Plugin {

    public function getEvents() {
        return [
            ContentOutputGenerated::class => function(ContentOutputGenerated $event) { $this->injectCode($event); },
            AssetPipelineReady::class => function(AssetPipelineReady $event) { $this->injectAssets($event); }
        ];
    }

    private function injectAssets(AssetPipelineReady $event) {
        $event->getAssetPipeline()->addJavascript(__DIR__ . "/assets/highlight/highlight.pack.js");
        $event->getAssetPipeline()->addStylesheet(__DIR__ . "/assets/highlight/styles/agate.css");
    }

    private function injectCode(ContentOutputGenerated $event) {
        $dom = $event->getDOM();
        $xpath = new \DOMXPath($dom);
        $headTag = $xpath->query("//head")->item(0);
        $scriptTag = $dom->createElement("script", "hljs.initHighlightingOnLoad();");
        $headTag->appendChild($scriptTag);
    }
}