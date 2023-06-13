<?php
$html = '<div ' . FusionBuilder::attributes('plyrae-audio') . '>';
$html .= '<div ' . FusionBuilder::attributes('plyrae-audio-wrapper') . '>';

if (!empty($args['plyr_file_url'])) {
    $html .= '<audio id="plyr-audio-player" class="plyr-audio-player" style="width:100%; --plyr-color-main: #da2128; --plyr-audio-control-color: #000;" controls>
      <source src="'.$args['plyr_file_url'].'" type="audio/mp3" />
    </audio>';
}

$html .= '</div>';
$html .= '</div>';
