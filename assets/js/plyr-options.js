// Initialize Plyr - With proper state management and timing
document.addEventListener('DOMContentLoaded', function () {
  if (
    typeof Plyr !== 'undefined' &&
    document.querySelectorAll('.plyr-audio-player').length > 0
  ) {
    if (!window.plyrInitialized) {
      const players = Plyr.setup('.plyr-audio-player', {
        controls: [
          'play-large',
          'play',
          'progress',
          'current-time',
          'duration',
          'mute',
          'volume',
          'captions',
          'settings',
          'download',
        ],
      });
    }
  }
});
