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
use function PHPSTORM_META\elementType;

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

    public function generateToc(DOMDocument $dom, $from = 1, $to = 4, $max_depth = 2, $list = 'ul')
    {
        assert($to - $from + 1 >= $max_depth, 'depth should smaller than to minus from.');
        $tags = '//*[';
        $xpath = new DOMXpath($dom);
        for ($i = $from; $i <= $to; $i++) {
            $tags .= 'self::h' . $i;
            if ($i != $to) {
                $tags .= ' or ';
            } else {
                $tags .= ']';
            }
        }
        $hs = $xpath->query($tags);
        $init_depth = 0;
        $depth = 0;
        $last_level = -1;
        $toc = '';
        $depth_map = [];
        foreach ($hs as $h) {
            sscanf($h->tagName, 'h%u', $level);
            if ($level > $last_level) {
                $toc .= "<$list>";
                $depth++;
                $depth_map[$level] = $depth;
            } elseif ($level == $last_level) {
                $toc .= '</li>';
            } elseif ($level < $last_level) {
                if (array_has($depth_map, $level)) {
                    $last_depth = $depth_map[$level];
                    $toc .= str_repeat("</li></$list>", $depth - $last_depth);
                    $toc .= "</li>";
                    $depth = $last_depth;
                }
            }
            $id = $h->textContent;
            $toc .= "<li><a href=#$id>$h->textContent</a>";
            $last_level = $level;
        }
        return $toc;
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