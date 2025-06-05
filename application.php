<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mudra Loan Letter</title>
    <style>
        * {
            box-sizing: border-box;
        }
        
        body {
            margin: 0;
            padding: 10px;
            background-color: #f5f5dc;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }
        
        .canvas-container {
            background: white;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 900px;
            border-radius: 8px;
            overflow: hidden; /* Prevent scrollbars */
        }
        
        canvas {
            border: 1px solid #ccc;
            background: #f5deb3;
            width: 100%;
            height: auto;
            display: block;
            max-width: 100%;
        }
        
        .controls {
            margin-top: 15px;
            text-align: center;
        }
        
        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 12px 24px;
            margin: 5px;
            cursor: pointer;
            border-radius: 6px;
            font-size: 16px;
            width: 100%;
            max-width: 250px;
        }
        
        button:hover {
            background: #0056b3;
        }
        
        .info-text {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-top: 10px;
        }
        
        @media (max-width: 768px) {
            body {
                padding: 5px;
            }
            
            .canvas-container {
                padding: 10px;
            }
            
            button {
                padding: 15px 20px;
                font-size: 18px;
            }
        }
        
        @media (max-width: 480px) {
            .canvas-container {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="canvas-container">
        <canvas id="letterCanvas"></canvas>
        <div class="controls">
            <button onclick="downloadImage()">📄 Download as Image</button>
            <div class="info-text">
                Tap and hold the image above to save on mobile
            </div>
        </div>
    </div>

    <script>
        const canvas = document.getElementById('letterCanvas');
        const ctx = canvas.getContext('2d');
        
        // Fixed high-resolution dimensions for printing/downloading
        const printWidth = 794;  // A4 width in pixels at 96dpi
        const printHeight = 1123; // A4 height in pixels at 96dpi
        
        // Display dimensions
        let displayWidth, displayHeight, scaleFactor;
        
        function setCanvasDimensions() {
            const container = document.querySelector('.canvas-container');
            const containerWidth = container.clientWidth - 32; // Account for padding
            
            // Calculate scale factor based on container width
            scaleFactor = Math.min(containerWidth / printWidth, 1);
            
            displayWidth = printWidth * scaleFactor;
            displayHeight = printHeight * scaleFactor;
            
            // Set display dimensions
            canvas.style.width = displayWidth + 'px';
            canvas.style.height = displayHeight + 'px';
            
            // Set actual canvas dimensions (for drawing) to high resolution
            canvas.width = printWidth;
            canvas.height = printHeight;
        }
        
        // Responsive scaling function - uses print dimensions
        function scale(value) {
            return value; // No scaling for drawing - we're working at print resolution
        }
        
        // Static images - using placeholder colored rectangles for demo
        let leftLogoImg = new Image();
        let centerLogoImg = new Image();
        let watermarkImg = new Image();
        let signatureImg = new Image();
        let moharImg = new Image();
        let scannerImg = new Image();

        // Create placeholder images with colored rectangles
        function createPlaceholderImage(width, height, color) {
            const placeholderCanvas = document.createElement('canvas');
            placeholderCanvas.width = width;
            placeholderCanvas.height = height;
            const placeholderCtx = placeholderCanvas.getContext('2d');
            
            placeholderCtx.fillStyle = color;
            placeholderCtx.fillRect(0, 0, width, height);
            placeholderCtx.fillStyle = 'white';
            placeholderCtx.font = '14px Arial';
            placeholderCtx.textAlign = 'center';
            placeholderCtx.fillText('LOGO', width/2, height/2);
            
            return placeholderCanvas.toDataURL();
        }

        leftLogoImg.src = 'images/emifinance.png';
        centerLogoImg.src = 'images/mudra.png';
        watermarkImg.src = 'images/mudra.png';
        signatureImg.src = 'images/mohar.png';
        moharImg.src = 'images/scanner.png';
        scannerImg.src = 'images/barcode.png';

        // Wait for images to load before drawing
        let imagesLoaded = 0;
        const totalImages = 6;
        
        function imageLoaded() {
            imagesLoaded++;
            if (imagesLoaded === totalImages) {
                drawCanvas();
            }
        }

        leftLogoImg.onload = imageLoaded;
        centerLogoImg.onload = imageLoaded;
        watermarkImg.onload = imageLoaded;
        signatureImg.onload = imageLoaded;
        moharImg.onload = imageLoaded;
        scannerImg.onload = imageLoaded;
        
        function drawText(text, x, y, fontSize = 14, color = 'black', fontWeight = 'normal', textAlign = 'left') {
            ctx.fillStyle = color;
            ctx.font = `${fontWeight} ${fontSize}px Arial`;
            ctx.textAlign = textAlign;
            
            if (text.includes('\n')) {
                const lines = text.split('\n');
                lines.forEach((line, index) => {
                    ctx.fillText(line, x, y + (index * (fontSize + 4)));
                });
                return lines.length * (fontSize + 4);
            } else {
                ctx.fillText(text, x, y);
                return fontSize + 4;
            }
        }
        
        function wrapText(text, x, y, maxWidth, lineHeight) {
            const words = text.split(' ');
            let line = '';
            let currentY = y;
            
            ctx.font = `14px Arial`;
            
            for (let n = 0; n < words.length; n++) {
                const testLine = line + words[n] + ' ';
                const metrics = ctx.measureText(testLine);
                const testWidth = metrics.width;
                
                if (testWidth > maxWidth && n > 0) {
                    ctx.fillText(line, x, currentY);
                    line = words[n] + ' ';
                    currentY += lineHeight;
                } else {
                    line = testLine;
                }
            }
            ctx.fillText(line, x, currentY);
            return currentY + lineHeight - y;
        }
        
        function drawWatermark() {
            if (!watermarkImg.complete) return;
            
            ctx.save();
            ctx.globalAlpha = 0.3;
            
            ctx.translate(printWidth / 2, printHeight / 2);
            ctx.drawImage(watermarkImg, -200, -75, 400, 150);
            
            ctx.restore();
        }
        
        function drawHeaderImages() {
            if (leftLogoImg.complete) {
                ctx.drawImage(leftLogoImg, 50, 20, 120, 80);
            }
            
            if (centerLogoImg.complete) {
                ctx.drawImage(centerLogoImg, 280, 10, 250, 100);
            }
        }
        
        function drawHeader() {
            ctx.fillStyle = 'blue';
            ctx.fillRect(50, 120, printWidth - 100, 4);
            
            drawText('AN ISO 9001:2008 CERTIFICATES COMPANY', 50, 140, 14, 'black', 'bold');
            drawText('INCORPORATED UNDER THE NATIONAL FINANCIAL CORPORATION ACT 1956', 50, 160, 12);
            
            ctx.font = `12px Arial`;
            ctx.fillStyle = 'black';
            wrapText('ADDRESS:- 13RD FLOOR, OFFICE NO. - 216 & 219 & 222 GOKHALE PLAZA CONDOMINIUM SURVEY NO 160/2/1A, CST. 8167, CHINCHWAD – AKURDI LINK ROAD, NEAR RAMKRISHNA MORE, AUDITORIUM, PUNE, MAHARASHTRA', 
                    50, 180, printWidth - 100, 18);
        }
        
        function drawApprovalLetter() {
            let yPos = 260;
            
            drawText('APPROVAL LETTER', printWidth/2, yPos, 18, 'black', 'bold', 'center');
            yPos += 40;
            
            drawText('Name : Mr/Mrs Burra Satish Kumar', 50, yPos, 14);
            yPos += 25;
            
            drawText('KYC Verification : MUDFNC/374/243/A069', 50, yPos, 14);
            yPos += 25;
            
            drawText('Subject : Approval For Personal Loan', 50, yPos, 14, 'red', 'bold');
            yPos += 25;
            
            drawText('Loan Amount : 1000000.00/-', 50, yPos, 14);
            yPos += 25;
            
            drawText('Date : 02-Jul-2020', printWidth - 200, 330, 14, 'black', 'bold');
        }
        
        function drawLoanContent() {
            let yPos = 420;
            
            drawText('Dear Mr/Mrs Burra Satish Kumar', 50, yPos, 14, 'black', 'bold');
            yPos += 30;
            
            ctx.font = `14px Arial`;
            ctx.fillStyle = 'black';
            const line1 = 'We are sending you this letter to confirm your loan has been approved by ';
            const line1Width = ctx.measureText(line1).width;
            ctx.fillText(line1, 50, yPos);
            
            ctx.fillStyle = 'green';
            ctx.font = `bold 14px Arial`;
            const mudraText = 'Pardhan Mantri Mudra Loan';
            const mudraWidth = ctx.measureText(mudraText).width;
            ctx.fillText(mudraText, 50 + line1Width, yPos);
            
            ctx.fillStyle = 'black';
            ctx.font = `14px Arial`;
            ctx.fillText(' for 5 year.', 50 + line1Width + mudraWidth, yPos);
            yPos += 30;
            
            drawText('5 year Monthly EMI Rs 18872.00/- at 5% P.A Reducing Rate Of Interest', 50, yPos, 14, 'green', 'bold');
            yPos += 20;
            drawText('through N.R.I funding Scheme.', 50, yPos, 14, 'green', 'bold');
            yPos += 40;
            
            ctx.font = `14px Arial`;
            ctx.fillStyle = 'black';
            const longText = 'When You Submit your amount for Processing Charges Fee Rs 2500.00/- its company responsibility to handover your loan Amount Rs 1000000.00/- and our team will visit to your place shortly.';
            const wrapHeight = wrapText(longText, 50, yPos, printWidth - 100, 20);
            yPos += wrapHeight + 20;
            
            drawText('Please Continue Your Loan Process Without any Hesitation.', 50, yPos, 14, 'black', 'bold');
            yPos += 25;
            
            drawText('Loan Processing Charge :- 2500.00/-', 50, yPos, 14, 'red', 'bold');
        }
        
        function drawPaymentDetails() {
            let yPos = 710;
            
            drawText('Payment This Account :-', 50, yPos, 16, 'black', 'bold');
            yPos += 30;
            
            drawText('Name : Pardhan Mantri Mudra Loan', 50, yPos, 14, 'black', 'bold');
            yPos += 25;
            
            drawText('A/c : 31240330091', 50, yPos, 14, 'black', 'bold');
            yPos += 25;
            
            drawText('Ifsc Code : SBIN0011699', 50, yPos, 14, 'black', 'bold');
            yPos += 25;
            
            drawText('Branch : VIMANNAGAR, PUNE', 50, yPos, 14, 'black', 'bold');
            yPos += 25;
            
            drawText('Branch Address : GIGA,SPACE,COMPLEX,AMENITY,BUILDING,VIMAN,NAGAR,PUNE', 50, yPos, 14, 'black', 'bold');
        }
        
        function drawFooter() {
            let yPos = 900;
            
            ctx.fillStyle = 'blue';
            ctx.fillRect(50, yPos - 20, printWidth - 100, 2);
            
            const sectionWidth = (printWidth - 100) / 4;
            
            ctx.textAlign = 'left';
            drawText('Your Truly', 50, yPos, 14);
            drawText('Pankaj Singh', 50, yPos + 25, 14);
            drawText('Consumer Business', 50, yPos + 50, 14);
            drawText('Mudra Loan', 50, yPos + 75, 14);
            
            if (scannerImg.complete) {
                ctx.drawImage(scannerImg, 50 + sectionWidth, yPos, 100, 80);
            }
            
            if (moharImg.complete) {
                ctx.drawImage(moharImg, 50 + (sectionWidth * 2), yPos, 100, 80);
            }
            
            ctx.textAlign = 'center';
            drawText('Verified By', (50 + (printWidth - 100) * 3/4 + (printWidth - 100)/8), yPos, 14);
            if (signatureImg.complete) {
                ctx.drawImage(signatureImg, 50 + (sectionWidth * 3) + (sectionWidth/2) - 50, yPos + 20, 100, 60);
            }
            
            ctx.fillStyle = 'blue';
            ctx.fillRect(50, yPos + 100, printWidth - 100, 2);
        }
        
        function drawBarcodeArea() {
            ctx.fillStyle = 'black';
            for (let i = 0; i < 20; i++) {
                const width = Math.random() < 0.5 ? 2 : 4;
                ctx.fillRect(printWidth - 200 + (i * 8), 370, width, 40);
            }
        }
        
        function drawCanvas() {
            ctx.fillStyle = '#f5deb3';
            ctx.fillRect(0, 0, printWidth, printHeight);
            
            drawWatermark();
            drawHeaderImages();
            drawHeader();
            drawApprovalLetter();
            drawLoanContent();
            drawPaymentDetails();
            drawBarcodeArea();
            drawFooter();
        }
        
        function downloadImage() {
            // Create a temporary canvas for high-quality download
            const tempCanvas = document.createElement('canvas');
            tempCanvas.width = printWidth;
            tempCanvas.height = printHeight;
            const tempCtx = tempCanvas.getContext('2d');
            
            // Draw the content to the temporary canvas
            tempCtx.fillStyle = '#f5deb3';
            tempCtx.fillRect(0, 0, printWidth, printHeight);
            
            // Redraw all elements at full resolution
            tempCtx.drawImage(canvas, 0, 0);
            
            // Create download link
            const link = document.createElement('a');
            link.download = 'mudra_loan_letter.png';
            link.href = tempCanvas.toDataURL('image/png', 1.0);
            link.click();
        }
        
        // Initialize responsive canvas
        function init() {
            setCanvasDimensions();
            if (imagesLoaded === totalImages) {
                drawCanvas();
            }
        }
        
        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                setCanvasDimensions();
                if (imagesLoaded === totalImages) {
                    drawCanvas();
                }
            }, 250);
        });
        
        // Initialize when page loads
        window.addEventListener('load', init);
        
        // Also initialize immediately
        init();
    </script>
</body>
</html>