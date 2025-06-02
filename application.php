<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mudra Loan Letter</title>
    <style>
        body {
            margin: 0;
            padding: 20px;
            background-color: #f5f5dc;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .canvas-container {
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        
        canvas {
            border: 1px solid #ccc;
            background: #f5deb3;
        }
        
        .controls {
            margin-top: 20px;
            text-align: center;
        }
        
        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 5px;
            cursor: pointer;
            border-radius: 4px;
        }
        
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="canvas-container">
        <canvas id="letterCanvas" width="794" height="1123"></canvas>
        <div class="controls">
            <button onclick="downloadImage()">Download as Image</button>
        </div>
    </div>

    <script>
        const canvas = document.getElementById('letterCanvas');
        const ctx = canvas.getContext('2d');
        
        // Static images - replace URLs with your actual image paths
        let leftLogoImg = new Image();
        let centerLogoImg = new Image();
        let watermarkImg = new Image();
        let signatureImg = new Image();
        let moharImg = new Image();
        let scannerImg = new Image();

        leftLogoImg.src = 'images/emifinance.png'; // Your left logo path
        centerLogoImg.src = 'images/mudra.png';    // Your center logo path
        watermarkImg.src = 'images/mudra.png';     // Default watermark image
        signatureImg.src = 'images/signature.png'; // Signature image path
        moharImg.src = 'images/mohar.png';        // Mohar image path
        scannerImg.src = 'images/scanner.png';    // Scanner image path

        // Wait for images to load before drawing
        let imagesLoaded = 0;
        const totalImages = 6; // Update this when adding new images
        
        function imageLoaded() {
            imagesLoaded++;
            if (imagesLoaded === totalImages) {
                drawCanvas(); // Draw canvas only after all images load
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
            
            // Save the current context state
            ctx.save();
            
            // Set opacity
            ctx.globalAlpha = 0.3;
            
            // Move to center of canvas
            ctx.translate(canvas.width / 2, canvas.height / 2);
            
            // Draw the image centered at the origin
            ctx.drawImage(watermarkImg, -200, -75, 400, 150);
            
            // Restore the context to its original state
            ctx.restore();
        }
        
        function drawHeaderImages() {
            // Left logo - fixed position and size
            if (leftLogoImg.complete) {
                ctx.drawImage(leftLogoImg, 50, 20, 120, 80);
            }
            
            // Center logo - fixed position and size  
            if (centerLogoImg.complete) {
                ctx.drawImage(centerLogoImg, 280, 10, 250, 100);
            }
        }
        
        function drawHeader() {
            // Blue underline
            ctx.fillStyle = 'blue';
            ctx.fillRect(50, 120, canvas.width - 100, 4);
            
            // Company info
            drawText('AN ISO 9001:2008 CERTIFICATES COMPANY', 50, 140, 14, 'black', 'bold');
            drawText('INCORPORATED UNDER THE NATIONAL FINANCIAL CORPORATION ACT 1956', 50, 160, 12);
            
            // Wrap address text
            ctx.font = '12px Arial';
            ctx.fillStyle = 'black';
            wrapText('ADDRESS:- 13RD FLOOR, OFFICE NO. - 216 & 219 & 222 GOKHALE PLAZA CONDOMINIUM SURVEY NO 160/2/1A, CST. 8167, CHINCHWAD – AKURDI LINK ROAD, NEAR RAMKRISHNA MORE, AUDITORIUM, PUNE, MAHARASHTRA', 
                    50, 180, canvas.width - 100, 18);
        }
        
        function drawApprovalLetter() {
            let yPos = 260;
            
            drawText('APPROVAL LETTER', canvas.width / 2, yPos, 18, 'black', 'bold', 'center');
            yPos += 40;
            
            drawText('Name : Mr/Mrs Burra Satish Kumar', 50, yPos, 14);
            yPos += 25;
            
            drawText('KYC Verification : MUDFNC/374/243/A069', 50, yPos, 14);
            yPos += 25;
            
            drawText('Subject : Approval For Personal Loan', 50, yPos, 14, 'red', 'bold');
            yPos += 25;
            
            drawText('Loan Amount : 1000000.00/-', 50, yPos, 14);
            yPos += 25;
            
            drawText('Date : 02-Jul-2020', canvas.width - 200, 330, 14, 'black', 'bold');
        }
        
        function drawLoanContent() {
            let yPos = 420;
            
            drawText('Dear Mr/Mrs Burra Satish Kumar', 50, yPos, 14, 'black', 'bold');
            yPos += 30;
            
            ctx.font = '14px Arial';
            ctx.fillStyle = 'black';
            const line1 = 'We are sending you this letter to confirm your loan has been approved by ';
            const line1Width = ctx.measureText(line1).width;
            ctx.fillText(line1, 50, yPos);
            
            ctx.fillStyle = 'green';
            ctx.font = 'bold 14px Arial';
            const mudraText = 'Pardhan Mantri Mudra Loan';
            const mudraWidth = ctx.measureText(mudraText).width;
            ctx.fillText(mudraText, 50 + line1Width, yPos);
            
            ctx.fillStyle = 'black';
            ctx.font = '14px Arial';
            ctx.fillText(' for 5 year.', 50 + line1Width + mudraWidth, yPos);
            yPos += 30;
            
            drawText('5 year Monthly EMI Rs 18872.00/- at 5% P.A Reducing Rate Of Interest', 50, yPos, 14, 'green', 'bold');
            yPos += 20;
            drawText('through N.R.I funding Scheme.', 50, yPos, 14, 'green', 'bold');
            yPos += 40;
            
            // Long paragraph with word wrapping
            ctx.font = '14px Arial';
            ctx.fillStyle = 'black';
            const longText = 'When You Submit your amount for Processing Charges Fee Rs 2500.00/- its company responsibility to handover your loan Amount Rs 1000000.00/- and our team will visit to your place shortly.';
            const wrapHeight = wrapText(longText, 50, yPos, canvas.width - 100, 20);
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
            
            // Draw a line above the footer
            ctx.fillStyle = 'blue';
            ctx.fillRect(50, yPos - 20, canvas.width - 100, 2);
            
            // Footer content with 4 sections
            const sectionWidth = (canvas.width - 100) / 4;
            
            // Section 1: Left section with text
            ctx.textAlign = 'left';
            drawText('Your Truly', 50, yPos, 14);
            drawText('Pankaj Singh', 50, yPos + 25, 14);
            drawText('Consumer Business', 50, yPos + 50, 14);
            drawText('Mudra Loan', 50, yPos + 75, 14);
            
            // Section 2: Scanner image
            if (scannerImg.complete) {
                ctx.drawImage(scannerImg, 50 + sectionWidth, yPos, 100, 80);
            }
            
            // Section 3: Mohar image
            if (moharImg.complete) {
                ctx.drawImage(moharImg, 50 + (sectionWidth * 2), yPos, 100, 80);
            }
            
            // Section 4: Signature with "Verified By" text
            ctx.textAlign = 'center';
            drawText('Verified By', 50 + (sectionWidth * 3) + (sectionWidth/2), yPos, 14);
            if (signatureImg.complete) {
                ctx.drawImage(signatureImg, 50 + (sectionWidth * 3) + (sectionWidth/2) - 50, yPos + 20, 100, 60);
            }
            
            // Footer bottom line
            ctx.fillStyle = 'blue';
            ctx.fillRect(50, yPos + 100, canvas.width - 100, 2);
        }
        
        function drawBarcodeArea() {
            ctx.fillStyle = 'black';
            for (let i = 0; i < 20; i++) {
                const width = Math.random() < 0.5 ? 2 : 4;
                ctx.fillRect(canvas.width - 200 + (i * 8), 370, width, 40);
            }
        }
        
        function drawCanvas() {
            // Clear canvas
            ctx.fillStyle = '#f5deb3';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            // Draw all elements
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
            const link = document.createElement('a');
            link.download = 'mudra_loan_letter.png';
            link.href = canvas.toDataURL();
            link.click();
        }
        
        // Initial canvas generation - only if images are already loaded
        setTimeout(() => {
            if (leftLogoImg.complete && centerLogoImg.complete && watermarkImg.complete && 
                signatureImg.complete && moharImg.complete && scannerImg.complete) {
                drawCanvas();
            }
        }, 100);
    </script>
</body>
</html>