const player = Plyr.setup('.plyr-audio-player', {
    controls: [
        'play-large', // The large play button in the center
        'play', // Play/pause playback
        'progress', // The progress bar and scrubber for playback and buffering
        'current-time', // The current time of playback
        'duration', // The full duration of the media
        'mute', // Toggle mute
        'volume', // Volume control
        'captions', // Toggle captions
        'settings', // Settings menu
        'download', // Show a download button with a link to either the current source or a custom URL you specify in your options
    ]
});

window.player = player;