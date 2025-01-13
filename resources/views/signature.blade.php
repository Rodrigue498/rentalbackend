<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signature Pad</title>
</head>
<body>
    <h1>Sign the Contract</h1>
    <canvas id="signature-pad" width="600" height="200" style="border:1px solid #000;"></canvas>
    <button id="clear">Clear</button>
    <button id="save">Save</button>

    <!-- Include the Signature Pad library -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.umd.min.js"></script>

    <!-- Signature Pad functionality -->
    <script>
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas);

        document.getElementById('clear').addEventListener('click', () => {
            signaturePad.clear();
        });

        document.getElementById('save').addEventListener('click', () => {
            if (signaturePad.isEmpty()) {
                alert('Please provide a signature first.');
                return;
            }

            const signature = signaturePad.toDataURL('image/png');
            fetch('/save-signature', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ signature }),
            })
                .then(response => response.json())
                .then(data => alert(data.message))
                .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>
