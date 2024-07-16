class PDFViewer {
	static async preloadAndApplyWatermark(
		mainPdfUrl,
		watermarkImageUrl,
		anchor,
		userData
	) {
		try {
			const pdfContainer = document.getElementById(anchor);

			const pdfBytes = await fetch(mainPdfUrl).then((res) =>
				res.arrayBuffer()
			);
			const pdfDoc = await PDFLib.PDFDocument.load(pdfBytes);

			const watermarkBytes = await fetch(watermarkImageUrl).then((res) =>
				res.arrayBuffer()
			);
			const watermarkImage = await pdfDoc.embedPng(watermarkBytes);

			const helveticaFont = await pdfDoc.embedFont(
				PDFLib.StandardFonts.Helvetica
			);
			const helveticaBoldFont = await pdfDoc.embedFont(
				PDFLib.StandardFonts.HelveticaBold
			);

			const pages = pdfDoc.getPages();

			pages.forEach((page, index) => {
				const { width, height } = page.getSize();

				page.drawImage(watermarkImage, {
					x: width - watermarkImage.width * 0.5 - 10,
					y: 10,
					width: watermarkImage.width * 0.5,
					height: watermarkImage.height * 0.5,
					opacity: 0.5,
				});

				const fontSize = 12;
				let userDataSplit = userData.split("|");
				let userDataFirst = userDataSplit[0];
				let userDataSec = userDataSplit[1];

				for (let y = 0; y < height; y += 100) {
					for (let x = 0; x < width; x += width * 0.6) {
						page.drawText(userDataFirst, {
							x: x,
							y: y,
							size: fontSize,
							color: PDFLib.rgb(0, 0, 0),
							font: helveticaBoldFont,
							opacity: 0.3,
						});

						page.drawText(userDataSec, {
							x: x,
							y: (y += 14),
							size: fontSize,
							color: PDFLib.rgb(0, 0, 0),
							font: helveticaBoldFont,
							opacity: 0.3,
						});
					}
				}
			});

			const pdfBytesModified = await pdfDoc.save();
			const pdfBlob = new Blob([pdfBytesModified], {
				type: "application/pdf",
			});
			const pdfDataUri = URL.createObjectURL(pdfBlob);

			pdfContainer.innerHTML = `<embed src="${pdfDataUri}" type="application/pdf" width="100%" height="600px" />`;
		} catch (error) {
			console.error("Error in preloadAndApplyWatermark:", error);
		}
	}

	static downloadPDF(anchor, filename) {
		try {
			const pdfEmbed = document.querySelector(`#${anchor} embed`);
			const link = document.createElement("a");
			link.href = pdfEmbed.getAttribute("src");
			link.download = filename;
			document.body.appendChild(link);
			link.click();
			document.body.removeChild(link);
		} catch (error) {
			console.error("Error in downloadPDF:", error);
		}
	}
}
