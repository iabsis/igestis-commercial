<?php
namespace Igestis\Modules\Commercial\Interfaces;
/**
 *
 * @author Gilles Hemmerlé
 */
interface HtmlRendererInterface {
    public function render(string $filename, array $replacements);
}