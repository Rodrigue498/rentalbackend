<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Dashboard</title>
</head>

<body>
    <h1>Your Documents</h1>
    <ul id="documents"></ul>

    <script>
        fetch('/documents', {
                headers: {
                    'Authorization': 'Bearer {{ auth()->user()->createToken('dashboard_token')->plainTextToken }}',
                },
            })
            .then(response => response.json())
            .then(data => {
                const documentsList = document.getElementById('documents');
                data.forEach(doc => {
                    const listItem = document.createElement('li');
                    listItem.innerHTML = `
                    ${doc.document_name} -
                    <a href="/documents/${doc.id}/download">Download</a>
                `;
                    documentsList.appendChild(listItem);
                });
            });
    </script>
</body>

</html>
