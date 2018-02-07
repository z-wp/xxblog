<?php
/**
 * Created by PhpStorm.
 * User: lufficc
 * Date: 2016/9/21
 * Time: 23:00
 */

namespace Lufficc;

use League\HTMLToMarkdown\HtmlConverter;
use ParsedownExtra;
use DOMDocument;
use DOMXPath;

class MarkDownParser
{
    protected $parser;
    protected $htmlConverter;

    /**
     * MarkDownParser constructor.
     */
    public function __construct()
    {
        $this->parser = new ParsedownExtra();
        $this->htmlConverter = new HtmlConverter();
    }

    public function html2md($html)
    {
        return $this->htmlConverter->convert($html);
    }

    public function parse($markdown, $clean = true, $use_gallery = false)
    {
        $convertedHtml = $this->parser->setBreaksEnabled(true)->text($markdown);
        if ($clean) {
            $convertedHtml = clean($convertedHtml, 'user_comment_content');
        }
        if ($use_gallery) {
            $convertedHtml = $this->convertHtml($convertedHtml);
        }
        return $convertedHtml;
    }

    /**
     * convert
     * <div class="figure **" caption="caption">
     *     <p><img ..></p>
     *     <p><img ..></p>
     *     ...
     * </div>
     * to
     * <figure class="**">
     *     <div><img ..></div>
     *     <div><img ..></div>
     *     ...
     *     <figcaption>$caption</figcaption>
     * </figure>
     * @param DOMDocument $dom
     * @return bool
     */
    private function parseDiv(DOMDocument $dom)
    {
        $xpath = new DOMXpath($dom);
        $galleries = $xpath->query('//div[contains(@class, "figure")]');
        $changed = false;
        foreach ($galleries as $gallery) {
            $figure = $dom->createElement('figure');
            $figure->setAttribute('class', trim(str_replace('figure', '', $gallery->getAttribute('class'))));
            $frag = $dom->createDocumentFragment();
            $alt = '';
            foreach ($xpath->query('.//img', $gallery) as $image) {
                if (!$alt)
                    $alt = $image->getAttribute('alt');
                //wrapped with div
                $div = $dom->createElement('div');
                $div->appendChild($image);
                $frag->appendChild($div);
            }
            //empty string if no attribute with the given name is found.
            $caption = $gallery->getAttribute('caption');
            if (!$caption)
                $caption = $alt;
            $frag->appendXML("<figcaption>$caption</figcaption>");
            $figure->appendChild($frag);
            $gallery->parentNode->replaceChild($figure, $gallery);
            $changed = true;
        }
        return $changed;
    }

    /**
     * convert
     * <img .. alt='alt' class='figure'>
     * to
     * <figure>
     *     <img .. alt='alt'>
     *     <figcaption>$alt</figcaption>
     * </figure>
     * @param DOMDocument $dom
     * @return bool
     */
    private function parseImage(DOMDocument $dom)
    {
        $xpath = new DOMXpath($dom);
        $images = $xpath->query('//img[contains(@class, "figure")]');
        $changed = false;
        foreach ($images as $image) {
            $src = $image->getAttribute('src');
            $alt = $image->getAttribute('alt');
            $figure = $dom->createElement('figure');
            $frag = $dom->createDocumentFragment(); // create fragment
            $imgNode = $dom->createElement('img');
            $imgNode->setAttribute('src', $src);
            $imgNode->setAttribute('alt', $alt);
            $frag->appendChild($imgNode);
            $frag->appendXML("<figcaption>$alt</figcaption>");
            $figure->appendChild($frag);
            $image->parentNode->replaceChild($figure, $image);
            $changed = true;
        }
        return $changed;

    }

    private function convertHtml($html)
    {
        $dom = new DOMDocument();
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $changed = $this->parseImage($dom);
        $changed = $this->parseDiv($dom) || $changed;
        if ($changed) {
            $html = $dom->saveHTML();
        }
        return $html;
    }
}