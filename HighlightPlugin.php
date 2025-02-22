<?php

namespace foonoo\plugins\foonoo\highlight;

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

    private function injectAssets(AssetPipelineReady $event): void
    {
        $style = $this->getOption('style', 'mono-blue');
        $event->getAssetPipeline()->addItem(__DIR__ . "/assets/highlight/styles/{$style}.css", "css");
        $event->getAssetPipeline()->addItem(__DIR__ . "/assets/highlight/highlight.min.js", "js");
    }

    private function injectCode(ContentLayoutApplied $event): void
    {
        $dom = $event->getDocument();
        if($dom !== null) {
            $headTag = $dom->querySelector("head");
            //@var \Dom\Element
            $scriptTag = $dom->createElement("script");
            $scriptTag->textContent = "window.addEventListener('load', ()=>hljs.highlightAll())";
            $headTag->appendChild($scriptTag);            
        }
    }
}
