class BakeryVideoPlayer {
    constructor(container, options = {}) {
        this.container = typeof container === 'string' ? document.querySelector(container) : container;
        this.options = {
            autoplay: false,
            controls: true,
            loop: false,
            muted: false,
            preload: 'metadata',
            poster: '',
            quality: '720p',
            ...options
        };
        
        this.qualities = ['480p', '720p', '1080p'];
        this.currentQuality = this.options.quality;
        this.sources = {};
        
        this.initializePlayer();
    }
    
    initializePlayer() {
        // Create video element
        this.video = document.createElement('video');
        this.video.className = 'bakery-video-player';
        this.video.controls = this.options.controls;
        this.video.autoplay = this.options.autoplay;
        this.video.loop = this.options.loop;
        this.video.muted = this.options.muted;
        this.video.preload = this.options.preload;
        if (this.options.poster) {
            this.video.poster = this.options.poster;
        }
        
        // Create custom controls container
        this.controlsContainer = document.createElement('div');
        this.controlsContainer.className = 'bakery-video-controls';
        
        // Create quality selector
        this.qualitySelector = document.createElement('select');
        this.qualitySelector.className = 'bakery-video-quality';
        this.qualities.forEach(quality => {
            const option = document.createElement('option');
            option.value = quality;
            option.textContent = quality;
            option.selected = quality === this.currentQuality;
            this.qualitySelector.appendChild(option);
        });
        
        this.qualitySelector.addEventListener('change', (e) => {
            this.changeQuality(e.target.value);
        });
        
        // Create custom play button
        this.playButton = document.createElement('button');
        this.playButton.className = 'bakery-video-play';
        this.playButton.innerHTML = '▶';
        this.playButton.addEventListener('click', () => this.togglePlay());
        
        // Create progress bar
        this.progressBar = document.createElement('div');
        this.progressBar.className = 'bakery-video-progress';
        this.progressFill = document.createElement('div');
        this.progressFill.className = 'bakery-video-progress-fill';
        this.progressBar.appendChild(this.progressFill);
        
        this.progressBar.addEventListener('click', (e) => {
            const rect = this.progressBar.getBoundingClientRect();
            const pos = (e.clientX - rect.left) / rect.width;
            this.video.currentTime = pos * this.video.duration;
        });
        
        // Create volume control
        this.volumeControl = document.createElement('input');
        this.volumeControl.type = 'range';
        this.volumeControl.min = 0;
        this.volumeControl.max = 1;
        this.volumeControl.step = 0.1;
        this.volumeControl.value = this.video.volume;
        this.volumeControl.className = 'bakery-video-volume';
        
        this.volumeControl.addEventListener('input', (e) => {
            this.video.volume = e.target.value;
        });
        
        // Assemble controls
        this.controlsContainer.appendChild(this.playButton);
        this.controlsContainer.appendChild(this.progressBar);
        this.controlsContainer.appendChild(this.volumeControl);
        this.controlsContainer.appendChild(this.qualitySelector);
        
        // Add event listeners
        this.video.addEventListener('timeupdate', () => this.updateProgress());
        this.video.addEventListener('play', () => this.playButton.innerHTML = '❚❚');
        this.video.addEventListener('pause', () => this.playButton.innerHTML = '▶');
        
        // Add elements to container
        this.container.appendChild(this.video);
        this.container.appendChild(this.controlsContainer);
        
        // Add styles
        this.addStyles();
    }
    
    addStyles() {
        const style = document.createElement('style');
        style.textContent = `
            .bakery-video-player {
                width: 100%;
                max-width: 100%;
                border-radius: 8px;
            }
            
            .bakery-video-controls {
                display: flex;
                align-items: center;
                padding: 10px;
                background: rgba(0, 0, 0, 0.7);
                border-radius: 0 0 8px 8px;
                margin-top: -4px;
            }
            
            .bakery-video-play {
                background: none;
                border: none;
                color: white;
                cursor: pointer;
                padding: 0 10px;
                font-size: 20px;
            }
            
            .bakery-video-progress {
                flex-grow: 1;
                height: 5px;
                background: rgba(255, 255, 255, 0.3);
                margin: 0 10px;
                cursor: pointer;
                border-radius: 2.5px;
            }
            
            .bakery-video-progress-fill {
                height: 100%;
                background: #F67280;
                border-radius: 2.5px;
                width: 0;
            }
            
            .bakery-video-volume {
                width: 100px;
                margin: 0 10px;
            }
            
            .bakery-video-quality {
                background: rgba(255, 255, 255, 0.1);
                color: white;
                border: none;
                padding: 5px;
                border-radius: 4px;
                cursor: pointer;
            }
            
            .bakery-video-quality option {
                background: #2D3436;
                color: white;
            }
        `;
        document.head.appendChild(style);
    }
    
    setSources(sources) {
        this.sources = sources;
        this.updateSource();
    }
    
    updateSource() {
        if (this.sources[this.currentQuality]) {
            const currentTime = this.video.currentTime;
            const wasPlaying = !this.video.paused;
            
            this.video.src = this.sources[this.currentQuality];
            this.video.currentTime = currentTime;
            
            if (wasPlaying) {
                this.video.play();
            }
        }
    }
    
    changeQuality(quality) {
        if (this.qualities.includes(quality) && this.sources[quality]) {
            this.currentQuality = quality;
            this.updateSource();
        }
    }
    
    togglePlay() {
        if (this.video.paused) {
            this.video.play();
        } else {
            this.video.pause();
        }
    }
    
    updateProgress() {
        const progress = (this.video.currentTime / this.video.duration) * 100;
        this.progressFill.style.width = `${progress}%`;
    }
    
    destroy() {
        this.container.innerHTML = '';
    }
}

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = BakeryVideoPlayer;
}
