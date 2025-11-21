import Drawer from '@mui/material/Drawer';
import { useState, useEffect, useRef } from 'react';

export default function MobileMenu() {
	const [open, setOpen] = useState(false);
	const [content, setContent] = useState('');
	const htmlRef = useRef(null);

	useEffect(() => {
		const el = document.querySelector('#drawer-html');
		setContent(el?.innerHTML || '');
	}, []); // run once on mount (only in browser)

	/**
	 * add event listeners
	 */
	useEffect(()=>{
		
		if (!htmlRef.current || !content ) return;

		const root = htmlRef.current;

		const openButtons = root.querySelectorAll(".mobile-menu-nav .open-button");
		
		openButtons.forEach( btn=>{
			btn.addEventListener("click",()=>{
				const parent_li = btn.parentElement;	// get parent li element of submenu
				if( !parent_li ) return;
				parent_li.classList.add('selected');

				const closeBtn = parent_li.querySelector(":scope > .submenu > .submenu-label");
				console.log('closeBtn', closeBtn);
				if( !closeBtn ) return;
				closeBtn.addEventListener("click",()=>{ 
					parent_li.classList.remove("selected");
				});
			});
		});

	},[content])

	return (
		<div>
		<div className={"menu-toggler"+ (open ? " act" : "")}
			onClick={() => setOpen(!open)}
		>
			<div className="icon-directional"></div>
		</div>

		<Drawer 
			className={"mobile-menu-drawer"+(open ? " open" : "")}
			open={open} onClose={ ()=>{console.log('hello');setOpen(false)} }
			variant="temporary" 
			keepMounted
		>
			<div ref={htmlRef} dangerouslySetInnerHTML={{ __html: content }} />
		</Drawer>
		</div>
	);
}
