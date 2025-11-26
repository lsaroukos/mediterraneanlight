// hooks/useIntersectionAnimation.js
import { useEffect } from "react";

export function useIntersectionAnimation(selector, className = "visible") {
  
    useEffect(() => {

		const elements = document.querySelectorAll(selector);
		if (!elements.length) return;

		const observer = new IntersectionObserver(entries => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					entry.target.classList.add(className);
					// observer.unobserve(entry.target); // stop observing once visible
				}else{
					entry.target.classList.remove(className);
				}
			});
		});

		elements.forEach(el => observer.observe(el));

		return () => observer.disconnect();
    }, [selector, className]);
}
