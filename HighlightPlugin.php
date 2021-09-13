<?php

namespace foonoo\plugins\contrib\highlight;

use foonoo\Plugin;
use foonoo\events\ContentLayoutApplied;
use foonoo\events\AssetPipelineReady;

class HighlightPlugin extends Plugin {

    public function getEvents() {
        return [
            ContentLayoutApplied::class => function(ContentLayoutApplied $event) { $this->injectCode($event); },
            AssetPipelineReady::class => function(AssetPipelineReady $event) { $this->injectAssets($event); }
        ];
    }

    private function injectAssets(AssetPipelineReady $event) {
        $style = $this->getOption('style', 'mono-blue');
        $event->getAssetPipeline()->addItem(__DIR__ . "/assets/highlight/styles/{$style}.css", "css");
        $event->getAssetPipeline()->addItem(__DIR__ . "/assets/highlight/highlight.min.js", "js");
    }

    private function injectCode(ContentLayoutApplied $event) {
        $dom = $event->getDOM();
        $xpath = new \DOMXPath($dom);
        $headTag = $xpath->query("//head")->item(0);
        $scriptTag = $dom->createElement("script", "window.addEventListener('load', ()=>hljs.highlightAll())");
        $headTag->appendChild($scriptTag);
    }
}