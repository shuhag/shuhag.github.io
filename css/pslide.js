




	
	
	
	/* $('a.highslide').each(function() {
    this.onclick = function() {
      return hs.expand(this, options);
    };
 }); */
	
	
		if (hs.addSlideshow) hs.addSlideshow({
			interval: 5000,
			repeat: false,
			useControls: true,
			fixedControls: false,
			overlayOptions: {
				opacity: 0.7,
				position: 'top right',
				hideOnMouseOut: true
			}
		});
	


	
		hs.transitions = ['expand', 'crossfade'];
	

hs.fadeInOut = false;
hs.dragByHeading = false;
hs.graphicsDir = 'nbdjs/jalbum/graphics/';
hs.outlineType = 'outer-glow';
hs.lang.loadingTitle = 'Click to cancel';
hs.lang.previousTitle = 'Previous (left arrow key)';
hs.lang.playTitle = 'Play slideshow (spacebar)';
hs.lang.pauseTitle = 'Pause slideshow (spacebar)';
hs.lang.nextTitle = 'Next (right arrow key)';
hs.lang.moveTitle = 'Click and drag to move';

hs.lang.fullExpandTitle = 'Expand to actual size (f)';
hs.lang.closeTitle = 'Close (ESC or Enter)';
hs.lang.loadingText = 'Loading...';
hs.lang.loadingTitle = 'Click to cancel';
hs.lang.restoreTitle = 'Click to close, use arrow keys for next and previous';
hs.lang.focusTitle = 'Click to bring to front';
hs.align = 'center';
hs.easingClose = 'easeInQuad';
hs.expandDuration = 500;
hs.restoreDuration = 500;
hs.showCredits = false;
hs.allowSizeReduction = false;
hs.dimmingOpacity = 0.7;
hs.dimmingDuration = 100;



		hs.easing = 'easeInBack';





		hs.allowSizeReduction = true;




		hs.onKeyDown = function(sender, e) {
		if (e.keyCode == 70) return false;
		}

