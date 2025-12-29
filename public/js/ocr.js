document.getElementById('icUpload').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const statusMsg = document.getElementById('statusMsg');
    const submitBtn = document.getElementById('submitBtn');

    if (!file) return;

    statusMsg.innerText = "Processing IC... please wait.";
    statusMsg.style.color = "blue";

    Tesseract.recognize(
        file,
        'eng', // Use 'msa' if scanning Malaysian IDs specifically
        { logger: m => console.log(m) }
    ).then(({ data: { text } }) => {
        console.log("OCR Result:", text);
        
        // 1. Extract IC Number (Looks for 12 digits, with or without dashes)
        // Matches: 950101-10-5544 or 950101105544
        const icRegex = /(\d{6}-?\d{2}-?\d{4})/;
        const icMatch = text.match(icRegex);
        
        if (icMatch) {
            document.getElementById('icNumber').value = icMatch[0];
        }

        // 2. Extract Name (Heuristic: usually the longest uppercase line)
        const lines = text.split('\n').filter(line => line.trim().length > 5);
        if (lines.length > 0) {
            // Pick the first long line that doesn't look like a number
            const nameLine = lines.find(l => !/\d/.test(l));
            if (nameLine) {
                document.getElementById('fullName').value = nameLine.trim();
            }
        }

        statusMsg.innerText = "Autofill complete! Please verify data.";
        statusMsg.style.color = "green";
        submitBtn.disabled = false; // Enable the button
    }).catch(err => {
        console.error(err);
        statusMsg.innerText = "OCR failed. Please enter details manually.";
        statusMsg.style.color = "red";
        submitBtn.disabled = false; // Enable anyway so they can type manually
    });
});