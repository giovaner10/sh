class ImageViewer {
	static preloadAndApplyWatermark(
		mainImageUrl,
		watermarkImageUrl,
		anchor,
		userData
	) {
		const imgElement = document.querySelector(`#${anchor} img`);
		imgElement.dataset.src = mainImageUrl;

		let canvas = document.getElementById("documentImageFactory");
		if (!canvas) {
			canvas = document.createElement("canvas");
			canvas.id = "documentImageFactory";
			document.body.appendChild(canvas);
		}
		const ctx = canvas.getContext("2d");

		const observer = new IntersectionObserver(
			(entries, observer) => {
				entries.forEach((entry) => {
					if (entry.isIntersecting) {
						const img = new Image();
						const watermark = new Image();

						img.onload = () => {
							ctx.clearRect(0, 0, canvas.width, canvas.height);

							const maxWidth =
								window.innerWidth ||
								document.documentElement.clientWidth;
							const scaleRatio =
								maxWidth < img.width ? maxWidth / img.width : 1;
							canvas.width = img.width * scaleRatio;
							canvas.height = img.height * scaleRatio;

							ctx.drawImage(
								img,
								0,
								0,
								canvas.width,
								canvas.height
							);

							watermark.onload = () => {
								// Scale the watermark size relative to the image size
								const watermarkScale = 2; // Watermark is 10% of image width
								const watermarkWidth =
									canvas.width * watermarkScale;
								const watermarkHeight =
									(watermark.width / watermark.height) *
									watermarkWidth;
								const posX = canvas.width - watermarkWidth - 10;
								const posY =
									canvas.height - watermarkHeight - 15;
								ctx.drawImage(
									watermark,
									posX,
									posY,
									watermarkWidth,
									watermarkHeight
								);

								const fontSize = canvas.width * 0.028;
								ctx.font = `bold ${fontSize}px Arial`;
								ctx.fillStyle = "rgba(255, 0, 0, 1)";
								ctx.shadowOffsetX = 3;
								ctx.shadowOffsetY = 3;
								ctx.shadowColor = "rgba(255,255,255,0.6)";
								ctx.shadowBlur = 3;
								const textPositionY =
									canvas.height - canvas.height * 0.02;

								ctx.fillText(userData, 10, textPositionY);

								imgElement.src = canvas.toDataURL("image/jpeg");
								document.querySelector(`#${anchor}`).href =
									imgElement.src;

								try {
									ImageViewer.initPhotoSwipe();
								} catch (RangeError) {
									
								}
							};

							watermark.src = watermarkImageUrl;
						};

						img.src = entry.target.dataset.src;
						observer.unobserve(entry.target);
					}
				});
			},
			{
				rootMargin: "0px",
				threshold: 0.1,
			}
		);

		observer.observe(imgElement);
	}

	static initPhotoSwipe() {
		const lightbox = new PhotoSwipeLightbox({
			gallery: "#documentImageGallery",
			children: "a",
			pswpModule: () => import("./dist/photoswipe.esm.js"),
			showAnimationDuration: 500,
			hideAnimationDuration: 500,
		});

		lightbox.init();

		lightbox.on("close", function () {
			const pswpElement = document.querySelector(".pswp");
			if (pswpElement) {
				pswpElement.style.opacity = "0";

				pswpElement.addEventListener(
					"transitionend",
					function hideOnTransitionEnd(e) {
						if (
							e.propertyName === "opacity" &&
							pswpElement.style.opacity === "0"
						) {
							pswpElement.style.visibility = "hidden";
							pswpElement.removeEventListener(
								"transitionend",
								hideOnTransitionEnd
							);
						}
					},
					{ once: true }
				);
			}
		});
	}

	static onElementAdded() {
		const photoSwipe = document.querySelector(".pswp");
		const modalLoadingMessageAct = document.querySelector(
			".modalLoadingMessageAct"
		);

		const img = document.querySelector(".pswp__item");

		let scrollTimeout;
		function onScroll() {
			img.classList.add("scrolling");
			clearTimeout(scrollTimeout);
			scrollTimeout = setTimeout(() => {
				img.classList.remove("scrolling");
			}, 250);
		}

		img.addEventListener("scroll", onScroll);

		if (photoSwipe && modalLoadingMessageAct) {
			modalLoadingMessageAct.style.display = "block";
			photoSwipe.style.display = "none";

			setTimeout(() => {
				modalLoadingMessageAct.style.display = "none";
				photoSwipe.style.display = "block";
				photoSwipe.style.opacity = "1";
				photoSwipe.style.visibility = "visible";
			}, 3000);
		}
	}

	static downloadImage(filename) {
		const canvas = document.getElementById("documentImageFactory");
		if (!canvas) {
			console.error("Canvas not found");
			return;
		}

		const image = canvas.toDataURL("image/jpeg");

		const downloadLink = document.createElement("a");
		downloadLink.href = image;
		downloadLink.download = filename;

		document.body.appendChild(downloadLink);
		downloadLink.click();
		document.body.removeChild(downloadLink);
	}
}

document.getElementById("div-img").addEventListener("click", () => {
	const observer = new MutationObserver((mutationsList, observer) => {
		for (const mutation of mutationsList) {
			if (mutation.type === "childList") {
				const addedNodes = Array.from(mutation.addedNodes);
				const hasPhotoSwipe = addedNodes.some(
					(node) => node.classList && node.classList.contains("pswp")
				);
				if (hasPhotoSwipe) {
					ImageViewer.onElementAdded();
					observer.disconnect();
					break;
				}
			}
		}
	});

	observer.observe(document.body, { childList: true, subtree: true });
});

document.addEventListener('focusin', function(event) {
    event.stopPropagation(); 
}, true);