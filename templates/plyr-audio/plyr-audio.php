<?php
$html = '<div class="plyrae-audio-element">';

if ( ! empty( $this->args['plyr_file_url'] ) ) {
	// Generate unique ID for each player instance
	$player_id = 'plyr-audio-player-' . uniqid();

	// Simple audio element without loading states
	$html .= '<audio id="' . $player_id . '" class="plyr-audio-player" style="width:100%; --plyr-audio-control-color: #000;" controls preload="metadata">';
	$html .= '<source src="' . $this->args['plyr_file_url'] . '" type="audio/mp3" />';
	$html .= 'Your browser does not support the audio element.';
	$html .= '</audio>';
}

$html .= '</div>';