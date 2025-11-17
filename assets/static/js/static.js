(()=>{

	/** toggle mobile navigation */
	const navOpen = document.getElementById('nav-open');
	if( navOpen ){
		navOpen.addEventListener('click',()=>{
			document.getElementById('header').classList.add('open');
		});
	}

	const navClose = document.getElementById('nav-close');
	if( navClose ){
		navClose.addEventListener('click',()=>{
			document.getElementById('header').classList.remove('open');
		});
	}

  /**
   * toggle menu background color
   */
	const container = document.getElementById('container');
	const header = document.querySelector("#header");		

	if( header && container ){

		container.addEventListener("scroll",()=>{
	
			const scrollTop = container.scrollTop;

			if( scrollTop > 100 ){
				header.classList.add('scrolled');	
			}else{
				header.classList.remove('scrolled');	
			}
		}, { passive: true });

	}

})();